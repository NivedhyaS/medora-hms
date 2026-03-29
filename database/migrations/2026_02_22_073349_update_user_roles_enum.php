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
            ALTER COLUMN role SET NOT NULL,
            ALTER COLUMN role SET DEFAULT 'patient'
        ");
        
        // Optionally add/update the check constraint if you want strict roles at DB level
        // But for migration reliability, converting to string is enough.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
