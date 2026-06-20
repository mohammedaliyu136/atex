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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('categories')->onDelete('cascade')->after('id');
            // Change status from string to boolean. Since it's SQLite/MySQL, easiest is drop and add or change if doctrine/dbal installed.
            // Let's just drop the column and add it back as boolean.
            $table->dropColumn('status');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('status')->default(true)->after('slug');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'status', 'deleted_at', 'created_at', 'updated_at']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('status')->default('active')->after('slug');
        });
    }
};
