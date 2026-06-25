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
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_seller_profiles_insert;
            DROP TRIGGER IF EXISTS trg_seller_profiles_update;
            DROP TRIGGER IF EXISTS trg_seller_profiles_delete;
        ");

        if (Schema::hasColumn('seller_profile_hist', 'trade_capacity')) {
            Schema::table('seller_profile_hist', function (Blueprint $table) {
                $table->dropColumn(['trade_capacity', 'years_of_experience', 'export_markets']);
            });
        }

        $sellerProfileCols = ["id","user_id","business_name","business_description","registration_number","tax_number","bvn","nin","business_type","business_category","lga","address","seller_program_status","seller_brand_name","fulfillment_model","verification_status","seller_tier","readiness_score","approved_at","created_at","updated_at","bank_name","account_number","account_name","rejection_reason","regulatory_reviews","country","state","city","phone"];
        $this->createTriggers('seller_profiles', 'seller_profile_hist', $sellerProfileCols);
    }

    private function createTriggers($table, $histTable, $cols) {
        $colsList = implode(', ', array_map(fn($c) => "`$c`", $cols));
        $newVals = implode(', ', array_map(fn($c) => "NEW.`$c`", $cols));
        $oldVals = implode(', ', array_map(fn($c) => "OLD.`$c`", $cols));

        DB::unprepared("
            CREATE TRIGGER trg_{$table}_insert AFTER INSERT ON {$table} FOR EACH ROW
            BEGIN
                INSERT INTO {$histTable} (operation_type, {$colsList}) VALUES ('INSERT', {$newVals});
            END;

            CREATE TRIGGER trg_{$table}_update AFTER UPDATE ON {$table} FOR EACH ROW
            BEGIN
                INSERT INTO {$histTable} (operation_type, {$colsList}) VALUES ('UPDATE', {$oldVals});
            END;

            CREATE TRIGGER trg_{$table}_delete AFTER DELETE ON {$table} FOR EACH ROW
            BEGIN
                INSERT INTO {$histTable} (operation_type, {$colsList}) VALUES ('DELETE', {$oldVals});
            END;
        ");
    }

    public function down(): void
    {
        // Not implemented for rollback as this is a fix
    }
};
