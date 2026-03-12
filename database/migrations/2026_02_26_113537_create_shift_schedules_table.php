<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_code_id')->constrained('shift_codes')->cascadeOnDelete();
            $table->enum('day_type', ['senin_kamis', 'jumat', 'sabtu']);
            $table->timestamps();
            $table->unique(['shift_code_id', 'day_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_schedules');
    }
};
