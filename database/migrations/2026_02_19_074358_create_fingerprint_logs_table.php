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
        Schema::create('fingerprint_logs', function (Blueprint $table) {
            $table->id();
            $table->string('barcode', 20);
            $table->date('attendance_date');
            $table->time('attendance_time');
            $table->tinyInteger('attendance_type');
            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('raw_created_date')->nullable();
            $table->timestamp('created_at')->nullable();
            

            $table->index(['barcode', 'attendance_date']);
            $table->index('is_processed');

            $table->unique(['barcode', 'attendance_date', 'attendance_time', 'attendance_type'], 'finger_logs_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fingerprint_logs');
    }
};
