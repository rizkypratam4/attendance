<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_shift_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('shift_code_id')->constrained('shift_codes')->cascadeOnDelete();
            $table->date('date');
            $table->date('duty_on_date')->nullable();
            $table->time('duty_on_time')->nullable();
            $table->date('duty_off_date')->nullable();
            $table->time('duty_off_time')->nullable();
            $table->string('new_working_shift', 30)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_shift_assignments');
    }
};
