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
        Schema::table('seller_profile_kycs', function (Blueprint $table) {
            $table->string('cac_certificate_path')->nullable()->after('proof_of_address_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seller_profile_kycs', function (Blueprint $table) {
            $table->dropColumn('cac_certificate_path');
        });
    }
};
