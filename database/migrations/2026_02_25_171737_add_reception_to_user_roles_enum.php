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
        // Use raw SQL for PostgreSQL to avoid 'enum' change syntax errors
        \Illuminate\Support\Facades\DB::statement("
            ALTER TABLE users 
            ALTER COLUMN role TYPE varchar(255),
            ALTER COLUMN role SET DEFAULT 'patient'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'doctor', 'patient', 'pharmacist', 'lab_staff'])
                ->default('patient')
                ->change();
        });
    }
};
