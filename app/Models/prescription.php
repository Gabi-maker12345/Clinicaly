<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    protected $fillable = ['diagnostico_id', 'recommendations', 'start_date', 'finish_date'];

    protected $casts = [
        'start_date' => 'date',
        'finish_date' => 'date',
    ];

    protected $with = ['diagnostico', 'monitorings.intakeLogs'];
    protected $appends = [];

    public function diagnostico(): BelongsTo
    {
        return $this->belongsTo(Diagnostico::class);
    }

    public function monitorings(): HasMany
    {
        return $this->hasMany(Monitoring::class);
    }
}
