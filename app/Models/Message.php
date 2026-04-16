<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_id',
        'body',
        'read'
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    /**
     * A conversa à qual esta mensagem pertence.
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * O autor da mensagem.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}