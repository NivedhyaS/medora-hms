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
        if (!Schema::hasColumn('lab_staff', 'lab_id')) {
            Schema::table('lab_staff', function (Blueprint $table) {
                $table->string('lab_id')->after('id')->unique()->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_staff', function (Blueprint $table) {
            //
        });
    }
};
