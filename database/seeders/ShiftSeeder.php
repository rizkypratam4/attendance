<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\ShiftCode;
use App\Models\ShiftSchedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
   public function run(): void
    {
        // ==========================================
        // SHIFTS
        // ==========================================
        $shift1 = Shift::create(['name' => 'Shift1']);
        $shift2 = Shift::create(['name' => 'Shift2']);
        $shift3 = Shift::create(['name' => 'Shift3']);

        // ==========================================
        // SHIFT CODES + SCHEDULES
        // Format: [day_type, start_time, end_time, is_day_off, is_overnight]
        // ==========================================

        $this->createShiftCode($shift1->id, '1AA', true, [
            ['senin_kamis', '07:30', '16:30', false, false],
            ['jumat',       '07:30', '17:00', false, false],
            ['sabtu',        null,    null,   true,  false],
        ]);

        $this->createShiftCode($shift1->id, '1PR', false, [
            ['senin_kamis', '09:30', '18:30', false, false],
            ['jumat',       '09:30', '19:00', false, false],
            ['sabtu',        null,    null,   true,  false],
        ]);

        $this->createShiftCode($shift1->id, '1PQ', false, [
            ['senin_kamis', '09:30', '17:30', false, false],
            ['jumat',       '09:30', '18:00', false, false],
            ['sabtu',       '09:30', '15:00', false, false],
        ]);

        $this->createShiftCode($shift1->id, '1ZA', false, [
            ['senin_kamis', '07:30', '15:30', false, false],
            ['jumat',       '07:30', '16:00', false, false],
            ['sabtu',       '07:30', '12:40', false, false],
        ]);

        $this->createShiftCode($shift2->id, '2ZB', false, [
            ['senin_kamis', '15:30', '23:30', false, false],
            ['jumat',       '15:30', '23:30', false, false],
            ['sabtu',       '12:40', '17:50', false, false],
        ]);

        $this->createShiftCode($shift3->id, '3ZZ', false, [
            ['senin_kamis', '22:30', '06:30', false, true],  // overnight
            ['jumat',       '22:30', '06:30', false, true],  // overnight
            ['sabtu',       '17:50', '23:00', false, false],
        ]);

        $this->createShiftCode($shift3->id, '3ZC', false, [
            ['senin_kamis', '23:30', '07:30', false, true],  // overnight
            ['jumat',       '23:30', '07:30', false, true],  // overnight
            ['sabtu',       '17:50', '23:00', false, false],
        ]);
    }

    private function createShiftCode(int $shiftId, string $code, bool $hasIdt, array $schedules): void
    {
        $shiftCode = ShiftCode::create([
            'shift_id' => $shiftId,
            'code'     => $code,
            'has_idt'  => $hasIdt,
        ]);

        foreach ($schedules as [$dayType, $startTime, $endTime, $isDayOff, $isOvernight]) {
            ShiftSchedule::create([
                'shift_code_id' => $shiftCode->id,
                'day_type'      => $dayType,
                'start_time'    => $startTime,
                'end_time'      => $endTime,
                'is_day_off'    => $isDayOff,
                'is_overnight'  => $isOvernight,
            ]);
        }
    }
}
