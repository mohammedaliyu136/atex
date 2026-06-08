<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LogisticsProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'coverage_regions',
        'transport_modes',
        'base_location',
        'fleet_capacity',
        'verification_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function documents()
    {
        return $this->morphMany(Document::class, 'owner');
    }
}
