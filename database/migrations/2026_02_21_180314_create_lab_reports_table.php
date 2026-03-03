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
        Schema::create('lab_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_staff_id')->constrained('lab_staff')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('test_name');
            $table->text('result')->nullable();
            $table->string('file_path')->nullable(); // For uploaded reports
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_reports');
    }
};
