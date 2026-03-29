<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('lab_staff', 'phone')) {
            Schema::table('lab_staff', function (Blueprint $table) {
                $table->string('phone')->after('name');
            });
        }
    }

public function down()
{
    Schema::table('lab_staff', function (Blueprint $table) {
        $table->dropColumn('phone');
    });
}

};
