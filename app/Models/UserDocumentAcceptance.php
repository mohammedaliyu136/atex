<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocumentAcceptance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'legal_document_version_id',
        'accepted_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function version()
    {
        return $this->belongsTo(LegalDocumentVersion::class, 'legal_document_version_id');
    }

    public function scopeAcceptedByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
