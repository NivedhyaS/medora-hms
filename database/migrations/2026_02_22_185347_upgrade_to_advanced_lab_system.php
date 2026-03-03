<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Update Lab Test Parameters Master
        Schema::table('lab_test_parameters_master', function (Blueprint $table) {
            $table->renameColumn('normal_min', 'min_value');
            $table->renameColumn('normal_max', 'max_value');
            $table->enum('gender', ['male', 'female', 'all'])->default('all')->after('parameter_name');
            $table->integer('min_age')->default(0)->after('gender');
            $table->integer('max_age')->default(120)->after('min_age');
            $table->decimal('critical_low', 10, 2)->nullable()->after('max_value');
            $table->decimal('critical_high', 10, 2)->nullable()->after('critical_low');
        });

        // Update Lab Tests for AI Summary and Health Score
        Schema::table('lab_tests', function (Blueprint $table) {
            $table->text('ai_summary')->nullable()->after('remarks');
            $table->integer('health_score')->nullable()->after('ai_summary');
            $table->boolean('is_critical')->default(false)->after('status');
            $table->boolean('is_reviewed')->default(false)->after('is_critical');
        });

        // Update Lab Report Values for Critical status
        Schema::table('lab_report_values', function (Blueprint $table) {
            // Re-creating the enum because SQLite/MySQL handling of enum updates varies
            $table->string('status', 20)->default('normal')->change();
        });
    }

    public function down(): void
    {
        Schema::table('lab_report_values', function (Blueprint $table) {
            $table->string('status', 20)->default('normal')->change();
        });

        Schema::table('lab_tests', function (Blueprint $table) {
            $table->dropColumn(['ai_summary', 'health_score', 'is_critical', 'is_reviewed']);
        });

        Schema::table('lab_test_parameters_master', function (Blueprint $table) {
            $table->renameColumn('min_value', 'normal_min');
            $table->renameColumn('max_value', 'normal_max');
            $table->dropColumn(['gender', 'min_age', 'max_age', 'critical_low', 'critical_high']);
        });
    }
};
