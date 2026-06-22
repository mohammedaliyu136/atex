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
        Schema::create('kyc_item_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type', 50); // e.g. 'seller', 'buyer', 'logistics'
            $table->unsignedBigInteger('owner_id');
            $table->string('item_key', 100); // e.g. 'nin', 'business_name', 'id_front_path'
            $table->string('status')->default('pending'); // 'pending', 'approved', 'rejected'
            $table->text('comment')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['owner_type', 'owner_id', 'item_key'], 'kyc_item_reviews_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kyc_item_reviews');
    }
};
