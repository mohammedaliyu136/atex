<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuoteRequest extends Model
{
    protected $fillable = [
        'buyer_profile_id',
        'product_id',
        'quantity',
        'destination_country',
        'destination_port',
        'incoterm',
        'message',
        'response_amount',
        'response_message',
        'responded_at',
        'status',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    public function buyerProfile(): BelongsTo
    {
        return $this->belongsTo(BuyerProfile::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
