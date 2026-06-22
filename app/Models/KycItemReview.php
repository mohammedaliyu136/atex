<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KycItemReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_type',
        'owner_id',
        'item_key',
        'status',
        'comment',
        'reviewer_id',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
