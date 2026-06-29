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
        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->string('lga')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::table('seller_profiles')
            ->whereNull('lga')
            ->update(['lga' => 'N/A']);

        Schema::table('seller_profiles', function (Blueprint $table) {
            $table->string('lga')->nullable(false)->change();
        });
    }
};
