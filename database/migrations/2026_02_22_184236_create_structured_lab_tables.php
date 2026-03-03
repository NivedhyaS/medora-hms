<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Lab Test Types
        Schema::create('lab_test_types', function (Blueprint $table) {
            $table->id();
            $table->string('department');
            $table->string('test_name');
            $table->timestamps();
        });

        // 2. Lab Test Parameters Master
        Schema::create('lab_test_parameters_master', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_type_id')->constrained('lab_test_types')->onDelete('cascade');
            $table->string('parameter_name');
            $table->decimal('normal_min', 10, 2)->nullable();
            $table->decimal('normal_max', 10, 2)->nullable();
            $table->string('unit')->nullable();
            $table->boolean('is_required')->default(true);
            $table->timestamps();
        });

        // 3. Update existing lab_tests table
        Schema::table('lab_tests', function (Blueprint $table) {
            if (!Schema::hasColumn('lab_tests', 'test_type_id')) {
                $table->foreignId('test_type_id')->nullable()->after('id')->constrained('lab_test_types')->onDelete('set null');
            }
            if (!Schema::hasColumn('lab_tests', 'remarks')) {
                $table->text('remarks')->nullable()->after('result');
            }
            if (!Schema::hasColumn('lab_tests', 'department')) {
                $table->string('department')->nullable()->after('test_type_id');
            }
        });

        // 4. Lab Report Values (individual results)
        Schema::create('lab_report_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('lab_tests')->onDelete('cascade');
            $table->foreignId('parameter_id')->constrained('lab_test_parameters_master')->onDelete('cascade');
            $table->string('value');
            $table->enum('status', ['low', 'normal', 'high'])->default('normal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_report_values');

        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropForeign(['test_type_id']);
            $table->dropColumn(['test_type_id', 'remarks', 'department']);
        });

        Schema::dropIfExists('lab_test_parameters_master');
        Schema::dropIfExists('lab_test_types');
    }
};
