<?php

namespace Database\Seeders;

use App\Models\Shift;
use App\Models\ShiftCode;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
   public function run(): void
    {
        $shift1 = Shift::create(['name' => 'Shift1']);
        $shift2 = Shift::create(['name' => 'Shift2']);
        $shift3 = Shift::create(['name' => 'Shift3']);

        ShiftCode::create(['shift_id' => $shift1->id, 'code' => '1AA', 'has_idt' => true]);
        ShiftCode::create(['shift_id' => $shift1->id, 'code' => '1PR', 'has_idt' => false]);
        ShiftCode::create(['shift_id' => $shift1->id, 'code' => '1PQ', 'has_idt' => false]);
        ShiftCode::create(['shift_id' => $shift1->id, 'code' => '1ZA', 'has_idt' => false]);
 

        ShiftCode::create(['shift_id' => $shift2->id, 'code' => '2ZB', 'has_idt' => false]);
 
        ShiftCode::create(['shift_id' => $shift3->id, 'code' => '3ZZ', 'has_idt' => false]);
        ShiftCode::create(['shift_id' => $shift3->id, 'code' => '3ZC', 'has_idt' => false]);
 
        ShiftCode::create(['shift_id' => null, 'code' => 'Day Off', 'has_idt' => false]);
    }

}
