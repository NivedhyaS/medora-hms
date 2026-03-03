<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'patient_id')) {
                $table->foreignId('patient_id')->nullable()->after('doctor_id')->constrained('patients')->onDelete('cascade');
            }
        });

        // Populate patient_id from user_id if possible
        $appointments = DB::table('appointments')->get();
        foreach ($appointments as $appointment) {
            $patient = DB::table('patients')->where('user_id', $appointment->user_id)->first();
            if ($patient) {
                DB::table('appointments')->where('id', $appointment->id)->update(['patient_id' => $patient->id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropColumn('patient_id');
        });
    }
};
