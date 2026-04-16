<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monitoring extends Model
{
    protected $fillable = [
        'prescription_id',
        'medication_name',
        'interval_hours',
        'next_notification_at',
        'status' // 'pending', 'active', 'completed'
    ];

    protected $casts = [
        'next_notification_at' => 'datetime',
    ];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }
}