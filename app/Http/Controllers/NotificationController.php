<?php

namespace App\Http\Controllers;

use App\Models\Message;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $systemNotifications = $user->notifications()
            ->latest()
            ->limit(80)
            ->get()
            ->toBase()
            ->map(function ($notification) {
                $type = $notification->data['type'] ?? 'presc';

                return [
                    'id' => $notification->id,
                    'source' => 'notification',
                    'type' => $type,
                    'read' => (bool) $notification->read_at,
                    'title' => $notification->data['title'] ?? 'Notificação',
                    'message' => $notification->data['message'] ?? 'Você tem uma nova notificação.',
                    'time' => $notification->created_at,
                    'url' => $notification->data['url'] ?? $notification->data['complete_url'] ?? route('profile.show') . '#prescricoes',
                    'read_url' => $notification->read_at ? null : route('notifications.read', $notification->id),
                    'complete_url' => $notification->data['complete_url'] ?? null,
                    'color' => $type === 'appt' ? 'var(--in)' : 'var(--gr)',
                ];
            });

        $messageNotifications = Message::query()
            ->with(['user', 'conversation'])
            ->where('user_id', '!=', $user->id)
            ->where('read', false)
            ->whereHas('conversation', function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->latest()
            ->limit(80)
            ->get()
            ->toBase()
            ->map(function (Message $message) {
                return [
                    'id' => $message->id,
                    'source' => 'message',
                    'type' => 'msg',
                    'read' => false,
                    'title' => 'Nova mensagem de ' . ($message->user?->name ?? 'Utilizador'),
                    'message' => str($message->body)->limit(120)->toString(),
                    'time' => $message->created_at,
                    'url' => route('messages.show', $message->conversation_id),
                    'read_url' => null,
                    'complete_url' => null,
                    'color' => 'var(--wn)',
                ];
            });

        $notifications = $systemNotifications
            ->merge($messageNotifications)
            ->sortByDesc('time')
            ->values();

        return view('notifications.index', [
            'notifications' => $notifications,
            'unreadCount' => $notifications->where('read', false)->count(),
            'counts' => [
                'all' => $notifications->count(),
                'unread' => $notifications->where('read', false)->count(),
                'diag' => $notifications->where('type', 'diag')->count(),
                'msg' => $notifications->where('type', 'msg')->count(),
                'presc' => $notifications->where('type', 'presc')->count(),
                'appt' => $notifications->where('type', 'appt')->count(),
            ],
        ]);
    }

    public function markAsRead(string $id)
    {
        auth()->user()->notifications()->where('id', $id)->firstOrFail()->markAsRead();

        if (request()->expectsJson()) {
            return response()->noContent();
        }

        return back();
    }

    public function markAllAsRead()
    {
        $user = auth()->user();

        $user->unreadNotifications->markAsRead();

        Message::query()
            ->where('user_id', '!=', $user->id)
            ->where('read', false)
            ->whereHas('conversation', function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            })
            ->update(['read' => true]);

        return back();
    }
}
