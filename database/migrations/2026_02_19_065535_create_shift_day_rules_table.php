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
        Schema::create('shift_day_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_definition_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('day_of_week');
            $table->text('notes')->nullable();   
            $table->timestamps();
            $table->unique(['shift_definition_id', 'day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_day_rules');
    }
};
