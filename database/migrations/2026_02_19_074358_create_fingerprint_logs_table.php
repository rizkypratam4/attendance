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

            $table->string('barcode');

            $table->date('attendance_date');
            $table->time('attendance_time');

            $table->tinyInteger('attendance_type');

            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            $table->unique([
                'barcode',
                'attendance_date',
                'attendance_time',
            ]);
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
