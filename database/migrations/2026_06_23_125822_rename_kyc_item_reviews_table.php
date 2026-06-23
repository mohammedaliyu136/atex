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
        Schema::rename('kyc_item_reviews', 'seller_profile_kyc_item_reviews');
        
        // Also rename the unique index
        Schema::table('seller_profile_kyc_item_reviews', function (Blueprint $table) {
            $table->dropUnique('kyc_item_reviews_unique');
            $table->unique(['owner_type', 'owner_id', 'item_key'], 'seller_profile_kyc_item_reviews_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_profile_kyc_item_reviews', function (Blueprint $table) {
            $table->dropUnique('seller_profile_kyc_item_reviews_unique');
            $table->unique(['owner_type', 'owner_id', 'item_key'], 'kyc_item_reviews_unique');
        });
        
        Schema::rename('seller_profile_kyc_item_reviews', 'kyc_item_reviews');
    }
};
