<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ChatLog;
use App\Models\ChatSession;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatIA extends Component
{
    public $userInput = '';
    public $messages = [];
    public $suggestions = [];
    public $currentSessionId;

    public function mount()
    {
        $lastSession = ChatSession::where('user_id', Auth::id())->latest()->first();
        if (!$lastSession) {
            $this->createNewChat();
        } else {
            $this->currentSessionId = $lastSession->id;
            $this->loadMessages();
            $this->loadSuggestions();
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
        $this->loadStarterSuggestions();
        $this->dispatch('scroll-to-bottom');
    }

    public function switchSession($sessionId)
    {
        $this->currentSessionId = $sessionId;
        $this->loadMessages();
        $this->loadSuggestions();
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

    public function sendSuggestedPrompt($prompt)
    {
        $this->userInput = $prompt;
        $this->sendMessage();
    }

    public function getAiResponse($message)
    {
        $apiKey = config('services.groq.key');
        $baseUrl = rtrim(config('services.groq.base_url'), '/');
        $model = config('services.groq.model');
        $timeout = (int) config('services.groq.timeout', 30);
        $verifySsl = (bool) config('services.groq.verify_ssl', true);

        if (empty($apiKey)) {
            $botText = 'Erro de configuração: a chave GROQ_API_KEY não foi definida no arquivo .env.';

            ChatLog::create([
                'user_id' => auth()->id(),
                'chat_session_id' => $this->currentSessionId,
                'role' => 'assistant',
                'message' => $botText
            ]);

            $this->loadMessages();
            $this->dispatch('scroll-to-bottom');

            return;
        }

        $url = $baseUrl . '/chat/completions';
        $request = Http::timeout($timeout);

        if (!$verifySsl) {
            $request = $request->withoutVerifying();
        }

        try {
            $response = $request
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'model' => $model,
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um assistente médico. Responda sempre usando formatação Markdown rica (negrito, listas, tabelas e títulos) para facilitar a leitura.'],
                        ['role' => 'user', 'content' => $message]
                    ],
                    'temperature' => 0.4,
                    'max_completion_tokens' => 1024,
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
        $this->generateSuggestions($message, $botText);
        $this->dispatch('scroll-to-bottom');
    }

    public function loadSuggestions()
    {
        $latestAssistantMessage = collect($this->messages)
            ->where('role', 'assistant')
            ->last();

        if (!$latestAssistantMessage) {
            $this->loadStarterSuggestions();

            return;
        }

        $this->suggestions = Cache::get($this->suggestionsCacheKey($latestAssistantMessage['id'] ?? null), []);
    }

    private function generateSuggestions($userMessage, $assistantMessage)
    {
        $this->suggestions = $this->requestAiSuggestions([
            ['role' => 'system', 'content' => 'Você cria sugestões curtas para continuar uma conversa médica. Responda somente com um array JSON de 4 strings em português, sem Markdown. Cada sugestão deve ter no máximo 70 caracteres.'],
            ['role' => 'user', 'content' => "Mensagem do usuário: {$userMessage}\n\nResposta da IA: {$assistantMessage}"],
        ]);

        if (count($this->suggestions) > 0) {
            $latestAssistantMessage = collect($this->messages)
                ->where('role', 'assistant')
                ->last();

            if ($latestAssistantMessage) {
                Cache::put($this->suggestionsCacheKey($latestAssistantMessage['id'] ?? null), $this->suggestions, now()->addDay());
            }
        }
    }

    private function loadStarterSuggestions()
    {
        $this->suggestions = Cache::remember(
            'chat-ai-starter-suggestions:' . auth()->id(),
            now()->addDay(),
            fn () => $this->requestAiSuggestions([
                ['role' => 'system', 'content' => 'Você cria sugestões iniciais para um chat médico. Responda somente com um array JSON de 4 strings em português, sem Markdown. Cada sugestão deve ter no máximo 70 caracteres.'],
                ['role' => 'user', 'content' => 'Crie sugestões úteis para iniciar uma conversa com uma IA médica do Clinicaly.'],
            ])
        );
    }

    private function requestAiSuggestions(array $messages)
    {
        $apiKey = config('services.groq.key');

        if (empty($apiKey)) {
            return [];
        }

        $baseUrl = rtrim(config('services.groq.base_url'), '/');
        $model = config('services.groq.model');
        $timeout = (int) config('services.groq.timeout', 30);
        $verifySsl = (bool) config('services.groq.verify_ssl', true);
        $request = Http::timeout($timeout);

        if (!$verifySsl) {
            $request = $request->withoutVerifying();
        }

        try {
            $response = $request
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($baseUrl . '/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.6,
                    'max_completion_tokens' => 180,
                ]);

            if (!$response->successful()) {
                return [];
            }

            $content = $response->json()['choices'][0]['message']['content'] ?? '[]';
            $decoded = json_decode(trim($content), true);

            if (!is_array($decoded)) {
                preg_match('/\[[\s\S]*\]/', $content, $matches);
                $decoded = json_decode($matches[0] ?? '[]', true);
            }

            return collect($decoded)
                ->filter(fn ($suggestion) => is_string($suggestion) && trim($suggestion) !== '')
                ->map(fn ($suggestion) => mb_substr(trim($suggestion), 0, 90))
                ->take(4)
                ->values()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function suggestionsCacheKey($messageId)
    {
        return 'chat-ai-suggestions:' . auth()->id() . ':' . $this->currentSessionId . ':' . ($messageId ?? 'empty');
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
