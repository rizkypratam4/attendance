<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('new_working_shift_id')
                ->nullable()
                ->after('shift_code_id')
                ->constrained('shift_codes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['new_working_shift_id']);
            $table->dropColumn('new_working_shift_id');
        });
    }
};
