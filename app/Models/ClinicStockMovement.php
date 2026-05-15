<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicStockMovement extends Model
{
    protected $fillable = [
        'clinic_id',
        'clinic_stock_item_id',
        'user_id',
        'prescription_id',
        'type',
        'quantity',
        'balance_after',
        'notes',
    ];

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'clinic_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(ClinicStockItem::class, 'clinic_stock_item_id');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }
}
