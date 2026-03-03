<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->enum('gender', ['Male', 'Female', 'Other'])
                  ->nullable()
                  ->after('name');

            $table->date('dob')
                  ->nullable()
                  ->after('gender');

            $table->enum('blood_group', [
                'A+','A-',
                'B+','B-',
                'AB+','AB-',
                'O+','O-'
            ])->nullable()
              ->after('dob');

            $table->string('mobile', 15)
                  ->nullable()
                  ->after('blood_group');

            $table->text('address')
                  ->nullable()
                  ->after('mobile');

            $table->string('emergency_contact', 15)
                  ->nullable()
                  ->after('address');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'gender',
                'dob',
                'blood_group',
                'mobile',
                'address',
                'emergency_contact',
            ]);

        });
    }
};
