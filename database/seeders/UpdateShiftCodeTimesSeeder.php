<?php

namespace Database\Seeders;

use App\Models\ShiftCode;
use App\Models\Shift;
use Illuminate\Database\Seeder;

class UpdateShiftCodeTimesSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil shift_id yang sudah ada
        $shift1Id = Shift::where('name', 'Shift1')->value('id');
        $shift2Id = Shift::where('name', 'Shift2')->value('id');
        $shift3Id = Shift::where('name', 'Shift3')->value('id');

        $data = [
            // Senin - Kamis
            '1AA'       => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '16:30', 'is_day_off' => false],
            '1PR'       => ['shift_id' => $shift1Id, 'on_time' => '09:30', 'off_time' => '18:30', 'is_day_off' => false],
            '1PQ'       => ['shift_id' => $shift1Id, 'on_time' => '09:30', 'off_time' => '17:30', 'is_day_off' => false],
            '1ZA'       => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '15:30', 'is_day_off' => false],
            '2ZB'       => ['shift_id' => $shift2Id, 'on_time' => '15:30', 'off_time' => '23:30', 'is_day_off' => false],
            '3ZZ'       => ['shift_id' => $shift3Id, 'on_time' => '22:30', 'off_time' => '06:30', 'is_day_off' => false],
            '3ZC'       => ['shift_id' => $shift3Id, 'on_time' => '23:30', 'off_time' => '07:30', 'is_day_off' => false],
            '1ZA Puasa' => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '16:00', 'is_day_off' => false],

            // Jumat
            '1AB'        => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '17:00', 'is_day_off' => false],
            '1PRB'       => ['shift_id' => $shift1Id, 'on_time' => '09:30', 'off_time' => '19:00', 'is_day_off' => false],
            '1PQB'       => ['shift_id' => $shift1Id, 'on_time' => '09:30', 'off_time' => '18:00', 'is_day_off' => false],
            '1ZAB'       => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '16:00', 'is_day_off' => false],
            '1ZAB Puasa' => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '16:30', 'is_day_off' => false],

            // Sabtu
            '1PQBN'      => ['shift_id' => $shift1Id, 'on_time' => '09:30', 'off_time' => '15:00', 'is_day_off' => false],
            '1SSN'       => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '12:40', 'is_day_off' => false],
            '2SSN'       => ['shift_id' => $shift2Id, 'on_time' => '12:40', 'off_time' => '17:50', 'is_day_off' => false],
            '3SSN'       => ['shift_id' => $shift3Id, 'on_time' => '17:50', 'off_time' => '23:00', 'is_day_off' => false],
            '1SSN Puasa' => ['shift_id' => $shift1Id, 'on_time' => '07:30', 'off_time' => '12:00', 'is_day_off' => false],

            // Day Off
            'Day Off'    => ['shift_id' => null, 'on_time' => null, 'off_time' => null, 'is_day_off' => true],
        ];

        foreach ($data as $code => $values) {
            ShiftCode::updateOrCreate(['code' => $code], $values);
        }

        $this->command->info('Shift codes updated/created: ' . count($data));
    }
}
