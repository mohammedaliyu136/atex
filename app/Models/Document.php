<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Document extends Model
{
    protected $fillable = [
        'owner_type',
        'owner_id',
        'document_type',
        'title',
        'path',
        'status',
        'expiry_date',
        'reviewed_by',
        'review_comment',
        'reviewed_at',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
