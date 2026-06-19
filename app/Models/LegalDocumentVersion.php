<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'legal_document_id',
        'version',
        'content',
        'content_hash',
        'effective_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($version) {
            if (empty($version->content_hash)) {
                $version->content_hash = hash('sha256', $version->content);
            }
        });

        static::updating(function ($version) {
            if ($version->isDirty('content')) {
                $version->content_hash = hash('sha256', $version->content);
            }
        });
    }

    public function document()
    {
        return $this->belongsTo(LegalDocument::class, 'legal_document_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function acceptances()
    {
        return $this->hasMany(UserDocumentAcceptance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLatestVersion($query)
    {
        return $query->orderByDesc('effective_date')->orderByDesc('id');
    }
}
