<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatLog;
use App\Models\ChatSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatIA extends Component
{
    public $userInput = '';
    public $messages = [];
    public $currentSessionId;

    public function mount()
    {
        $lastSession = ChatSession::where('user_id', Auth::id())->latest()->first();
        if (!$lastSession) {
            $this->createNewChat();
        } else {
            $this->currentSessionId = $lastSession->id;
            $this->loadMessages();
        }

        $prompt = request()->query('prompt');

        if ($prompt) {
            $this->userInput = $prompt;
            
            $this->sendMessage(); 
        }
    }

    public function createNewChat()
    {
        $session = ChatSession::create([
            'user_id' => Auth::id(),
            'title' => 'Nova Conversa ' . now()->format('H:i')
        ]);
        $this->currentSessionId = $session->id;
        $this->messages = [];
        $this->dispatch('scroll-to-bottom');
    }

    public function switchSession($sessionId)
    {
        $this->currentSessionId = $sessionId;
        $this->loadMessages();
        $this->dispatch('scroll-to-bottom');
    }

    public function deleteMessage($messageId)
    {
        ChatLog::where('id', $messageId)->where('user_id', Auth::id())->delete();
        $this->loadMessages();
    }

    public function sendMessage()
    {
        if (empty(trim($this->userInput))) return;

        ChatLog::create([
            'user_id' => Auth::id(),
            'chat_session_id' => $this->currentSessionId,
            'role' => 'user',
            'message' => $this->userInput
        ]);

        $input = $this->userInput;
        $this->userInput = '';
        $this->loadMessages();
        $this->dispatch('scroll-to-bottom');

        $this->getAiResponse($input);
    }

    public function getAiResponse($message)
    {
        $apiKey = 'sk-8rWJ9SKIHH0ms4Hp7UgBm6ElOux2nEDa2X6s44smmG04Ky56'; 
       
        $url = "https://api.chatanywhere.tech/v1/chat/completions";

        try {
            $response = Http::withoutVerifying()
                ->withOptions([
                    'proxy' => 'http://127.0.0.1:8080', 
                    'timeout' => 30,
                ])
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'model' => 'gpt-3.5-turbo', 
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um assistente médico. Responda sempre usando formatação Markdown rica (negrito, listas, tabelas e títulos) para facilitar a leitura.'],
                        ['role' => 'user', 'content' => $message]
                    ]
                ]);

            if ($response->successful()) {
                $botText = $response->json()['choices'][0]['message']['content'];
            } else {
                $error = $response->json();
                $botText = "Erro do Servidor Externo: " . ($error['error']['message'] ?? 'Chave inválida ou limite atingido.');
            }

        } catch (\Exception $e) {
            $botText = "Erro de conexão: " . $e->getMessage();
        }

        \App\Models\ChatLog::create([
            'user_id' => auth()->id(),
            'chat_session_id' => $this->currentSessionId,
            'role' => 'assistant',
            'message' => $botText
        ]);

        $this->loadMessages();
        $this->dispatch('scroll-to-bottom');
    }
    public function loadMessages()
    {
        $this->messages = ChatLog::where('chat_session_id', $this->currentSessionId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->toArray();
    }

    public function renameSession($sessionId, $newTitle)
    {
        $session = \App\Models\ChatSession::where('id', $sessionId)
                    ->where('user_id', auth()->id())
                    ->first();

        if ($session) {
            $session->update(['title' => $newTitle]);
        }
    }

    public function deleteSession($sessionId)
    {
        \App\Models\ChatSession::where('id', $sessionId)
            ->where('user_id', auth()->id())
            ->delete();

        if ($this->currentSessionId == $sessionId) {
            $this->createNewChat();
        }
    }

    public function render()
    {
        return view('livewire.chat-i-a');
    }
}