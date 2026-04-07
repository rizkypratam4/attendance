<?php

namespace Database\Seeders;

use App\Models\ShiftCode;
use Illuminate\Database\Seeder;

class UpdateShiftCodeTimesSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Senin - Kamis
            '1AA'    => ['on_time' => '07:30', 'off_time' => '16:30', 'is_day_off' => false],
            '1PR'    => ['on_time' => '09:30', 'off_time' => '18:30', 'is_day_off' => false],
            '1PQ'    => ['on_time' => '09:30', 'off_time' => '17:30', 'is_day_off' => false],
            '1ZA'    => ['on_time' => '07:30', 'off_time' => '15:30', 'is_day_off' => false],
            '2ZB'    => ['on_time' => '15:30', 'off_time' => '23:30', 'is_day_off' => false],
            '3ZZ'    => ['on_time' => '22:30', 'off_time' => '06:30', 'is_day_off' => false],
            '3ZC'    => ['on_time' => '23:30', 'off_time' => '07:30', 'is_day_off' => false],

            // Jumat
            '1AB'    => ['on_time' => '07:30', 'off_time' => '17:00', 'is_day_off' => false],
            '1PRB'   => ['on_time' => '09:30', 'off_time' => '19:00', 'is_day_off' => false],
            '1PQB'   => ['on_time' => '09:30', 'off_time' => '18:00', 'is_day_off' => false],
            '1ZAB'   => ['on_time' => '07:30', 'off_time' => '16:00', 'is_day_off' => false],

            // Sabtu
            '1PQBN'  => ['on_time' => '09:30', 'off_time' => '15:00', 'is_day_off' => false],
            '1SSN'   => ['on_time' => '07:30', 'off_time' => '12:40', 'is_day_off' => false],
            '2SSN'   => ['on_time' => '12:40', 'off_time' => '17:50', 'is_day_off' => false],
            '3SSN'   => ['on_time' => '17:50', 'off_time' => '23:00', 'is_day_off' => false],

            // Day Off
            'Day Off' => ['on_time' => null, 'off_time' => null, 'is_day_off' => true],
        ];

        foreach ($data as $code => $values) {
            ShiftCode::where('code', $code)->update($values);
        }

        echo "Shift code times updated!\n";
    }
}