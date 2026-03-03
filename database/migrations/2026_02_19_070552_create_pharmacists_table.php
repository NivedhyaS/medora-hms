<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
      Schema::create('pharmacists', function (Blueprint $table) {
    $table->id();
    $table->string('pharm_id')->unique();
    $table->string('name');
    $table->string('email');
    $table->string('phone');
    $table->string('gender')->nullable();
    $table->date('dob')->nullable();
    $table->text('address')->nullable();
    $table->string('emergency_contact')->nullable();
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacists');
    }
};
