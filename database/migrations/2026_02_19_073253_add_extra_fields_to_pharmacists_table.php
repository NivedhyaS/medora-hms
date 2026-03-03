<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pharmacists', function (Blueprint $table) {
            $table->string('pharm_id')->unique()->after('id');
            $table->string('gender')->nullable()->after('phone');
            $table->date('dob')->nullable()->after('gender');
            $table->text('address')->nullable()->after('dob');
            $table->string('emergency_contact')->nullable()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('pharmacists', function (Blueprint $table) {
            $table->dropColumn([
                'pharm_id',
                'gender',
                'dob',
                'address',
                'emergency_contact'
            ]);
        });
    }
};
