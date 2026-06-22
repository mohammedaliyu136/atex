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
        Schema::create('seller_profile_kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_profile_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->text('residential_address');
            $table->string('id_type'); // nin, passport, drivers, voter
            $table->string('id_number');
            $table->string('id_front_path')->nullable();
            $table->string('id_back_path')->nullable();
            $table->string('selfie_path')->nullable();
            $table->string('proof_of_address_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_profile_kycs');
    }
};
