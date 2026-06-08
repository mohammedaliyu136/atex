<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSplit extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'agency_id',
        'amount'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
