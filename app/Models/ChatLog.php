<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatLog extends Model
{
    protected $fillable = ['user_id', 'chat_session_id', 'role', 'message'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(ChatSession::class, 'chat_session_id');
    }
}
