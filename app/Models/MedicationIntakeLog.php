<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicationIntakeLog extends Model
{
    protected $fillable = [
        'monitoring_id',
        'scheduled_at',
        'notified_at',
        'due_until',
        'completed_at',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'notified_at' => 'datetime',
        'due_until' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function monitoring(): BelongsTo
    {
        return $this->belongsTo(Monitoring::class);
    }
}
