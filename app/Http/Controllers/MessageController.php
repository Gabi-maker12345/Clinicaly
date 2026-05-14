<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        return view('pages.conversations', ['conversations' => $conversations]);
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->sender_id != Auth::id() && $conversation->receiver_id != Auth::id()) {
            abort(403, 'Você não tem permissão para ver esta conversa.');
        }

        $messages = $conversation->messages()->with('user')->get();

        $conversation->messages()
            ->where('user_id', '!=', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);

        $conversations = Conversation::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver', 'messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->latest('updated_at')
            ->get();

        return view('pages.conversations', compact('conversation', 'messages', 'conversations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'body' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::findOrFail($request->conversation_id);

        if ($conversation->sender_id !== Auth::id() && $conversation->receiver_id !== Auth::id()) {
            abort(403);
        }

        $message = $conversation->messages()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        $conversation->touch();

        if ($request->expectsJson()) {
            return response()->json($message->load('user'));
        }

        return back()->with('success', 'Mensagem enviada.');
    }

    public function update(Request $request, Message $message)
    {
        if ($message->user_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $message->update([
            'body' => $request->body,
        ]);

        $message->conversation?->touch();

        if ($request->expectsJson()) {
            return response()->json($message->fresh()->load('user'));
        }

        return back()->with('success', 'Mensagem atualizada.');
    }

    public function destroy(Request $request, Message $message)
    {
        if ($message->user_id != Auth::id()) {
            abort(403);
        }

        $conversation = $message->conversation;
        $message->delete();
        $conversation?->touch();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Mensagem excluída.']);
        }

        return back()->with('success', 'Mensagem excluída.');
    }

    public function destroyConversation(Conversation $conversation)
    {
        if ($conversation->sender_id != Auth::id() && $conversation->receiver_id != Auth::id()) {
            abort(403);
        }

        $conversation->messages()->delete();
        $conversation->delete();

        return redirect()->route('messages.index')->with('success', 'Conversa excluída.');
    }

    public function updateConversation(Request $request, Conversation $conversation)
    {
        if ($conversation->sender_id != Auth::id() && $conversation->receiver_id != Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'nullable|string|max:80',
        ]);

        $conversation->update([
            'title' => filled($data['title'] ?? null) ? trim($data['title']) : null,
        ]);

        return back()->with('success', 'Conversa atualizada.');
    }

    public function start(User $user)
    {
        $authId = Auth::id();

        if ($authId === $user->id) {
            return back()->with('error', 'Operação inválida.');
        }

        $conversation = Conversation::where(function($q) use ($authId, $user) {
            $q->where('sender_id', $authId)->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($authId, $user) {
            $q->where('sender_id', $user->id)->where('receiver_id', $authId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'sender_id' => $authId,
                'receiver_id' => $user->id,
            ]);
        }

        return redirect()->route('messages.show', $conversation->id);
    }

    public function search(Request $request)
    {
        $query = trim($request->input('q', ''));
        $authId = Auth::id();

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where('id', '!=', $authId)
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get(); 
            
        // Opcional: Transformar para garantir que o JSON contenha a URL da foto
        $users->each->append('profile_photo_url');

        return response()->json($users);
    }
}
