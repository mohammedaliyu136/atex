<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_id',
        'name',
        'type',
        'size',
        'lga',
        'ward',
        'lat',
        'lng',
        'status',
        'occupant_id',
        'qr_code_path'
    ];

    public function occupant()
    {
        return $this->belongsTo(Occupant::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
