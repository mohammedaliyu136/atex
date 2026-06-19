<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FulfillmentInventory extends Model
{
    protected $table = 'fulfillment_inventory';

    protected $fillable = [
        'seller_profile_id',
        'product_id',
        'brand_name',
        'seller_sku',
        'quantity_received',
        'quantity_available',
        'quantity_reserved',
        'quantity_fulfilled',
        'unit_label',
        'storage_location',
        'receipt_status',
        'notes',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
        'quantity_received' => 'integer',
        'quantity_available' => 'integer',
        'quantity_reserved' => 'integer',
        'quantity_fulfilled' => 'integer',
    ];

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
