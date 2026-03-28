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
        // Sync sequences for lab_report_values
        DB::statement("SELECT setval(pg_get_serial_sequence('lab_report_values', 'id'), COALESCE(MAX(id), 1)) FROM lab_report_values");
        
        // Sync sequences for other key tables just in case
        DB::statement("SELECT setval(pg_get_serial_sequence('lab_tests', 'id'), COALESCE(MAX(id), 1)) FROM lab_tests");
        DB::statement("SELECT setval(pg_get_serial_sequence('prescriptions', 'id'), COALESCE(MAX(id), 1)) FROM prescriptions");
        DB::statement("SELECT setval(pg_get_serial_sequence('patients', 'id'), COALESCE(MAX(id), 1)) FROM patients");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for sequence sync
    }
};
