<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'amount',
        'status',
        'reference',
        'gateway'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function splits()
    {
        return $this->hasMany(PaymentSplit::class);
    }
}
