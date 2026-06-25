<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExporterProfile extends Model
{
    protected $fillable = [
        'seller_profile_id',
        'nepc_number',
        'export_capacity',
        'years_of_experience',
        'export_markets',
        'nepc_certificate_path',
        'verification_status'
    ];

    public function sellerProfile()
    {
        return $this->belongsTo(SellerProfile::class);
    }

    public function getUserAttribute()
    {
        return $this->sellerProfile ? $this->sellerProfile->user : null;
    }
}
