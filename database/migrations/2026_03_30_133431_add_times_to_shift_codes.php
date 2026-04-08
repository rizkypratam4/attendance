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
        Schema::table('shift_codes', function (Blueprint $table) {
            $table->time('on_time')->nullable()->after('code');
            $table->time('off_time')->nullable()->after('on_time');
            $table->boolean('is_day_off')->default(false)->after('off_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shift_codes', function (Blueprint $table) {
            $table->dropColumn(['on_time', 'off_time', 'is_day_off']);
        });
    }
};
