<?php

namespace App\Helpers;

use App\Models\Attendance;
use App\Models\EmployeeShiftAssignment;
use Carbon\Carbon;

class AbsentCountHelper
{
    /**
     * Hitung jumlah absent yang akurat untuk tanggal tertentu.
     * Jika tanggal = hari ini, karyawan yang shift-nya belum mulai tidak dihitung absent.
     */
    public static function count(string $date, ?int $departmentId = null): int
    {
        $query = Attendance::whereDate('attendance_date', $date)
            ->where('status', 'absent');

        if ($departmentId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $departmentId));
        }

        // Jika bukan hari ini, kembalikan count langsung
        if ($date !== today()->toDateString()) {
            return $query->count();
        }

        // Hari ini: filter hanya karyawan yang shift-nya sudah mulai
        $now = Carbon::now();

        $absentIds = $query->with(['shiftCode', 'newWorkingShift'])->get()
            ->filter(function ($att) use ($now, $date) {
                $activeShift = $att->newWorkingShift ?? $att->shiftCode;

                // Tidak ada shift info → tetap hitung absent
                if (!$activeShift || !$activeShift->on_time) {
                    return true;
                }

                $scheduledIn = Carbon::parse($date . ' ' . $activeShift->on_time);

                // Hanya hitung absent jika jam sekarang sudah melewati jam masuk
                return $now->gte($scheduledIn);
            })
            ->count();

        return $absentIds;
    }
}
