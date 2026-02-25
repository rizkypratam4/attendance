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
        Schema::create('shift_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('shift_code')->unique();

            $table->foreignId('shift_group_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();

            $table->integer('break_minutes')->default(0);
            
            $table->boolean('is_off')->default(false);

            $table->boolean('idt_allowed')->default(false);
            $table->time('idt_cutoff_time')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_definitions');
    }
};
