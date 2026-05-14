<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitoring extends Model
{
    protected $fillable = [
        'prescription_id',
        'medication_name',
        'interval_hours',
        'duration_days',
        'next_notification_at',
        'status' // 'pending', 'active', 'completed'
    ];

    protected $appends = [
        'duration_days',
    ];

    protected $casts = [
        'next_notification_at' => 'datetime',
    ];

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function getDurationDaysAttribute($value): ?int
    {
        if (! is_null($value)) {
            return (int) $value;
        }

        $prescription = $this->relationLoaded('prescription')
            ? $this->prescription
            : $this->prescription()->first();

        if ($prescription?->start_date && $prescription?->finish_date) {
            return max(1, $prescription->start_date->diffInDays($prescription->finish_date));
        }

        return null;
    }

    public function intakeLogs(): HasMany
    {
        return $this->hasMany(MedicationIntakeLog::class);
    }
}
