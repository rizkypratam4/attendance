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
        Schema::create('attendancesss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('shift_schedule_id')
                ->constrained('shift_schedules')
                ->cascadeOnDelete();
            $table->date('attendance_date');

            $table->dateTime('clock_in')->nullable();
            $table->dateTime('idt_time')->nullable();
            $table->dateTime('clock_out')->nullable();

            $table->unsignedInteger('work_duration_minutes')->default(0);
            $table->unsignedInteger('late_minutes')->default(0);
            $table->unsignedInteger('early_leave_minutes')->default(0);
            $table->unsignedInteger('overtime_minutes')->default(0);

            $table->enum('status', [
                'present',
                'absent',
                'late',
                'day_off',
                'permit',
                'sick',
                'holiday',
                'incomplete',
                'no_clockin',
            ])->default('absent');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'attendance_date']);
            $table->index('attendance_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
