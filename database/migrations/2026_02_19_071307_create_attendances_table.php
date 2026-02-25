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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            $table->date('work_date');

            $table->foreignId('shift_definition_id')->constrained()->restrictOnDelete();

            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();

            $table->integer('late_minutes')->default(0);
            $table->integer('early_leave_minutes')->default(0);

            $table->boolean('idt_used')->default(false);
            $table->string('idt_reason')->nullable();

            $table->text('remark')->nullable();

            $table->timestamps();

            $table->unique(['employee_id', 'work_date']);
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
