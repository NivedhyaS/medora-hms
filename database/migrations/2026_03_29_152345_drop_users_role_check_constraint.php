<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Explicitly drop the CHECK constraint for PostgreSQL
        try {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        } catch (\Exception $e) {
            // Already dropped or different constraint name
        }

        // Also ensure the column is a generic string (varchar) to allow all roles
        DB::statement("ALTER TABLE users ALTER COLUMN role TYPE varchar(255)");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed to re-add constraint
    }
};
