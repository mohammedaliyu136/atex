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
        Schema::table('exporter_profiles', function (Blueprint $table) {
            $table->string('nepc_certificate_path')->nullable()->after('export_markets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exporter_profiles', function (Blueprint $table) {
            $table->dropColumn('nepc_certificate_path');
        });
    }
};
