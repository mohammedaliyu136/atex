<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'title',
        'description',
    ];

    public function versions()
    {
        return $this->hasMany(LegalDocumentVersion::class);
    }

    public function activeVersion()
    {
        return $this->hasOne(LegalDocumentVersion::class)->where('is_active', true)->latest('effective_date');
    }
}
