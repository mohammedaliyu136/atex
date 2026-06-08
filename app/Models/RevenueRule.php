<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_id',
        'name',
        'amount',
        'frequency'
    ];

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
