<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('legal_document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legal_document_id')->constrained()->cascadeOnDelete();
            $table->string('version'); // e.g. 1.0
            $table->longText('content');
            $table->string('content_hash'); // SHA256 of content
            $table->date('effective_date');
            $table->boolean('is_active')->default(false);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            // Ensure only one active version per document using unique constraint
            // SQLite supports partial indexes but Laravel uses unique constraint
            // In a real app we might handle this via business logic, but a unique constraint is good
            // Actually, we'll enforce this via business logic to support SQLite properly
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_document_versions');
    }
};
