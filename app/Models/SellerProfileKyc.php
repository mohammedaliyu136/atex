<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerProfileKyc extends Model
{
    protected $fillable = [
        'seller_profile_id',
        'full_name',
        'date_of_birth',
        'nationality',
        'residential_address',
        'id_type',
        'id_number',
        'id_front_path',
        'id_back_path',
        'selfie_path',
        'proof_of_address_path',
        'cac_certificate_path',
    ];

    public function sellerProfile()
    {
        return $this->belongsTo(SellerProfile::class);
    }
}
