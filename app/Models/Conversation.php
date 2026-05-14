<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'receiver_id', 'title'];

    /**
     * Obtém todas as mensagens desta conversa.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * O usuário que iniciou a conversa.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * O usuário que recebeu o contacto inicial.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Helper para obter o outro participante da conversa (quem não é o utilizador atual).
     */
   public function getParticipantAttribute()
    {
        if (!Auth::check()) {
            return null;
        }

        $authId = Auth::user()?->id;

        if ($this->sender_id == $authId) {
            return $this->receiver;
        }

        return $this->sender;
    }
}
