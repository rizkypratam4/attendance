<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::create([
            'name' => 'Shift 1',
            'description' => 'Shift from 8 AM to 4 PM'
        ]);

        Shift::create([
            'name' => 'Shift 2',
            'description' => 'Shift from 2 PM to 10 PM'
        ]);

        Shift::create([
            'name' => 'Shift 3',
            'description' => 'Shift from 10 PM to 6 AM'
        ]);
    }
}
