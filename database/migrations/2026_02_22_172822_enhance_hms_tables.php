<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Update appointments table
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'diagnosis')) {
                $table->text('diagnosis')->after('status')->nullable();
            }
            if (!Schema::hasColumn('appointments', 'clinical_notes')) {
                $table->text('clinical_notes')->after('diagnosis')->nullable();
            }
        });

        // Update prescriptions table
        Schema::table('prescriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('prescriptions', 'diagnosis')) {
                $table->text('diagnosis')->after('patient_id')->nullable();
            }
            if (!Schema::hasColumn('prescriptions', 'medicines')) {
                $table->text('medicines')->after('diagnosis')->nullable();
            }
            if (!Schema::hasColumn('prescriptions', 'dosage')) {
                $table->text('dosage')->after('medicines')->nullable();
            }
            if (!Schema::hasColumn('prescriptions', 'dispensed_at')) {
                $table->timestamp('dispensed_at')->nullable();
            }
        });

        // Rename lab_reports to lab_tests and update
        if (Schema::hasTable('lab_reports')) {
            Schema::rename('lab_reports', 'lab_tests');
        } else {
            Schema::create('lab_tests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
                $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
                $table->foreignId('lab_staff_id')->nullable()->constrained('lab_staff')->onDelete('cascade');
                $table->string('test_name');
                $table->text('instructions')->nullable();
                $table->text('result')->nullable();
                $table->string('file_path')->nullable();
                $table->enum('status', ['pending', 'completed'])->default('pending');
                $table->timestamp('requested_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
            return;
        }

        Schema::table('lab_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('lab_tests', 'doctor_id')) {
                $table->foreignId('doctor_id')->nullable()->after('id')->constrained('doctors')->onDelete('cascade');
            }
            if (!Schema::hasColumn('lab_tests', 'instructions')) {
                $table->text('instructions')->after('test_name')->nullable();
            }
            if (!Schema::hasColumn('lab_tests', 'requested_at')) {
                $table->timestamp('requested_at')->nullable();
            }
            if (Schema::hasColumn('lab_tests', 'lab_staff_id')) {
                $table->unsignedBigInteger('lab_staff_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn(['doctor_id', 'instructions', 'requested_at']);
        });

        if (Schema::hasTable('lab_tests')) {
            Schema::rename('lab_tests', 'lab_reports');
        }

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['diagnosis', 'medicines', 'dosage', 'dispensed_at']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['diagnosis', 'clinical_notes']);
        });
    }
};
