<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settlement extends Model
{
    protected $fillable = [
        'order_id',
        'exporter_profile_id',
        'gross_amount',
        'commission_amount',
        'tax_amount',
        'net_payout_amount',
        'status',
        'notes',
        'credited_at',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_payout_amount' => 'decimal:2',
        'credited_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function exporterProfile(): BelongsTo
    {
        return $this->belongsTo(ExporterProfile::class);
    }
}
