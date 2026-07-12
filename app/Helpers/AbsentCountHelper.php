<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\EmployeeShiftAssignment;
use Carbon\Carbon;

class AbsentCountHelper
{
    public static function count(string $date, ?int $departmentId = null): int
    {
        $query = Attendance::whereDate('attendance_date', $date)
            ->where('status', 'absent');

        if ($departmentId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $departmentId));
        }

        if ($date !== today()->toDateString()) {
            return $query->count();
        }

        $now = Carbon::now();

        $absentIds = $query->with(['shiftCode', 'newWorkingShift'])->get()
            ->filter(function ($att) use ($now, $date) {
                $activeShift = $att->newWorkingShift ?? $att->shiftCode;
                if (!$activeShift || !$activeShift->on_time) {
                    return true;
                }

                $scheduledIn = Carbon::parse($date . ' ' . $activeShift->on_time);

                return $now->gte($scheduledIn);
            })
            ->count();

        return $absentIds;
    }
}
