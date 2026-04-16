<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\ChatLog; // Usando sua model existente
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function index(Request $request)
    {
        $prefillPrompt = $request->query('prompt');

        return view('pages.estudo', [
            'prefillPrompt' => $prefillPrompt
        ]);
    }

   public function askGemini($message)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

        $response = Http::withoutVerifying()->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $message]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $botResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'Sem resposta.';
        } else {
            $botResponse = "Erro: " . $response->status() . " - " . ($response->json()['error']['message'] ?? 'Erro desconhecido');
        }

        \App\Models\ChatLog::create([
            'user_id' => Auth::id(),
            'role' => 'assistant',
            'message' => $botResponse
        ]);

        return $botResponse;
    }
}