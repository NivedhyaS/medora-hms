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
        try {
            // Sync sequences for lab_report_values
            \Illuminate\Support\Facades\DB::statement("SELECT setval(pg_get_serial_sequence('lab_report_values', 'id'), COALESCE(MAX(id), 1)) FROM lab_report_values");
            
            // Sync sequences for other key tables just in case
            \Illuminate\Support\Facades\DB::statement("SELECT setval(pg_get_serial_sequence('lab_tests', 'id'), COALESCE(MAX(id), 1)) FROM lab_tests");
            \Illuminate\Support\Facades\DB::statement("SELECT setval(pg_get_serial_sequence('prescriptions', 'id'), COALESCE(MAX(id), 1)) FROM prescriptions");
            \Illuminate\Support\Facades\DB::statement("SELECT setval(pg_get_serial_sequence('patients', 'id'), COALESCE(MAX(id), 1)) FROM patients");
        } catch (\Exception $e) {
            // Sequence not found or other issue - safe to skip on some environments
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for sequence sync
    }
};
