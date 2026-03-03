<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->unsignedBigInteger('doctor_id')->nullable()->change();
            $table->foreignId('appointment_id')->nullable()->after('patient_id')->constrained('appointments')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropForeign(['appointment_id']);
            $table->dropColumn('appointment_id');
            $table->unsignedBigInteger('doctor_id')->nullable(false)->change();
        });
    }
};
