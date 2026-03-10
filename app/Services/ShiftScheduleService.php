<?php

namespace App\Services;

use App\Models\ShiftSchedule;
use Illuminate\Database\Eloquent\Collection;

class ShiftScheduleService
{
    public function getAll(): Collection
    {
        return ShiftSchedule::with('shiftCode.shift')->get();
    }

    public function paginate($perPage = 10)
    {
        return ShiftSchedule::with('shiftCode.shift')->paginate($perPage);
    }

    public function create(array $data): ShiftSchedule
    {
        return ShiftSchedule::create($data);
    }

    public function update(ShiftSchedule $schedule, array $data): ShiftSchedule
    {
        $schedule->update($data);
        return $schedule->fresh();
    }

    public function delete(ShiftSchedule $schedule): bool
    {
        return $schedule->delete();
    }

    public function findById(int $id): ShiftSchedule
    {
        return ShiftSchedule::with('shiftCode.shift')->findOrFail($id);
    }
}