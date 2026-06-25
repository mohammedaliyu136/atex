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
        Schema::dropIfExists('exporter_profile_kyc_item_reviews');
        Schema::create('exporter_profile_kyc_item_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('owner_type', 50);
            $table->unsignedBigInteger('owner_id');
            $table->string('item_key', 100);
            $table->string('status'); // approved, rejected
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('reviewer_id');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['owner_type', 'owner_id', 'item_key'], 'exp_prof_kyc_item_reviews_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exporter_profile_kyc_item_reviews');
    }
};
