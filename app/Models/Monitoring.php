<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monitoring extends Model
{
    protected $fillable = [
        'user_id', 
        'disease_id', 
        'medication_id', 
        'start_date', 
        'finish_date', 
        'interval_hours', 
        'next_notification_at', 
        'status', 
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'finish_date' => 'date',
        'next_notification_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function disease(): BelongsTo
    {
        return $this->belongsTo(Disease::class);
    }

    public function medication(): BelongsTo
    {
        return $this->belongsTo(Medication::class);
    }
}
