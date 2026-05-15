<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicStockItem extends Model
{
    protected $fillable = [
        'clinic_id',
        'name',
        'category',
        'unit',
        'quantity',
        'minimum_quantity',
        'description',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'clinic_id');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(ClinicStockMovement::class);
    }
}
