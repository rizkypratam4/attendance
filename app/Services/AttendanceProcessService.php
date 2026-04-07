<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeShiftAssignment;
use App\Models\FingerprintLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceProcessService
{
    public function processAll(?string $date = null, ?int $departmentId = null): array
    {
        $date   = $date ?? now()->toDateString();
        $result = ['processed' => 0, 'skipped' => 0, 'failed' => 0, 'absent' => 0];

        $query = FingerprintLog::where('is_processed', false)
            ->whereDate('attendance_date', $date);

        // Filter department jika ada
        if ($departmentId) {
            $query->whereIn('barcode', function ($q) use ($departmentId) {
                $q->select('machine_barcode')
                ->from('employees')
                ->where('department_id', $departmentId)
                ->whereNotNull('machine_barcode');
            });
        }

        $barcodes = $query->distinct()->pluck('barcode');

        foreach ($barcodes as $barcode) {
            try {
                $this->processBarcode($barcode, $date);
                $result['processed']++;
            } catch (\Throwable $e) {
                $result['failed']++;
                Log::error('AttendanceProcess: gagal proses barcode', [
                    'barcode' => $barcode,
                    'date'    => $date,
                    'error'   => $e->getMessage(),
                    'trace'   => $e->getTraceAsString(),
                ]);
            }
        }

        $result['absent'] = $this->generateAbsent($date, $departmentId);

        return $result;
    }

    public function processBarcode(string $barcode, string $date): void
    {
        DB::beginTransaction();

        try {
            $employee = Employee::where('machine_barcode', $barcode)->first();

            if (!$employee) {
                $this->markLogsProcessed($barcode, $date);
                DB::commit();
                return;
            }

            $assignment = $this->getAssignment($employee->id, $date);

            $logs = FingerprintLog::where('barcode', $barcode)
                ->whereDate('attendance_date', $date)
                ->orderBy('attendance_time')
                ->get();

            $clockInLog  = $logs->where('attendance_type', FingerprintLog::TYPE_CLOCK_IN)->first();
            $clockOutLog = $logs->where('attendance_type', FingerprintLog::TYPE_CLOCK_OUT)->last();

            // Fallback: kalau tidak ada tipe, ambil first/last
            if (!$clockInLog && $logs->isNotEmpty()) {
                $clockInLog = $logs->first();
            }
            if (!$clockOutLog || $clockOutLog->id === $clockInLog?->id) {
                $clockOutLog = $logs->count() > 1 ? $logs->last() : null;
            }

            $clockInTime  = $clockInLog
                ? Carbon::parse($date . ' ' . $clockInLog->attendance_time)
                : null;

            $clockOutTime = null;
            if ($clockOutLog) {
                $clockOutDate = $date;
                if ($clockInTime && Carbon::parse($date . ' ' . $clockOutLog->attendance_time)->lt($clockInTime)) {
                    $clockOutDate = Carbon::parse($date)->addDay()->toDateString();
                }
                $clockOutTime = Carbon::parse($clockOutDate . ' ' . $clockOutLog->attendance_time);
            }

            $lateMinutes = 0;
            if ($assignment && $assignment->shiftCode?->on_time && $clockInTime) {
                $scheduledIn = Carbon::parse($date . ' ' . $assignment->shiftCode->on_time);
                if ($clockInTime->gt($scheduledIn)) {
                    $lateMinutes = (int) $scheduledIn->diffInMinutes($clockInTime);
                }
            }

            $workDuration = 0;
            if ($clockInTime && $clockOutTime) {
                $workDuration = (int) $clockInTime->diffInMinutes($clockOutTime);
            }

            $status = $this->determineStatus($assignment, $clockInTime, $lateMinutes);

            Attendance::updateOrCreate(
                [
                    'employee_id'     => $employee->id,
                    'attendance_date' => $date,
                ],
                [
                    'shift_code_id'         => $assignment?->shift_code_id,
                    'clock_in'              => $clockInTime,
                    'clock_out'             => $clockOutTime,
                    'late_minutes'          => $lateMinutes,
                    'work_duration_minutes' => $workDuration,
                    'status'                => $status,
                ]
            );

            $this->markLogsProcessed($barcode, $date);
            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function generateAbsent(string $date, ?int $departmentId = null): int
    {
        $count = 0;

        $query = Employee::where('is_active', true)
            ->whereNotNull('machine_barcode');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->get();

        foreach ($employees as $employee) {
            $exists = Attendance::where('employee_id', $employee->id)
                ->whereDate('attendance_date', $date)
                ->exists();

            if ($exists) continue;

            $assignment = $this->getAssignment($employee->id, $date);
            if (!$assignment) continue;

            $status = $this->isDayOff($assignment)
                ? Attendance::STATUS_DAY_OFF
                : Attendance::STATUS_ABSENT;

            Attendance::create([
                'employee_id'           => $employee->id,
                'shift_code_id'         => $assignment->shift_code_id,
                'attendance_date'       => $date,
                'status'                => $status,
                'late_minutes'          => 0,
                'work_duration_minutes' => 0,
            ]);

            $count++;
        }

        return $count;
    }

    private function getAssignment(int $employeeId, string $date): ?EmployeeShiftAssignment
    {
        return EmployeeShiftAssignment::where('employee_id', $employeeId)
            ->where('date', $date)
            ->with('shiftCode')
            ->first();
    }

    private function determineStatus(
        ?EmployeeShiftAssignment $assignment,
        ?Carbon $clockIn,
        int $lateMinutes
    ): string {
        if ($assignment && $this->isDayOff($assignment)) {
            return Attendance::STATUS_DAY_OFF;
        }

        if (!$clockIn) {
            return Attendance::STATUS_ABSENT;
        }

        if ($lateMinutes > 0) {
            if ($this->isWithinLateTolerance($assignment, $clockIn)) {
                return Attendance::STATUS_PRESENT;
            }
            return Attendance::STATUS_LATE;
        }

        return Attendance::STATUS_PRESENT;
    }

    private function isWithinLateTolerance(
        ?EmployeeShiftAssignment $assignment,
        Carbon $clockIn
    ): bool {
        if (!$assignment?->shiftCode) return false;

        $code    = $assignment->shiftCode->code;
        $date    = $clockIn->toDateString();
        $onTime  = $assignment->shiftCode->on_time;

        if (!$onTime) return false;

        if ($code === '1AA') {
            $deadline = Carbon::parse($date . ' 10:00:00');
        } else {
            $deadline = Carbon::parse($date . ' ' . $onTime)->addHour();
        }

        return $clockIn->lte($deadline);
    }

    private function isDayOff(?EmployeeShiftAssignment $assignment): bool
    {
        return $assignment?->shiftCode?->is_day_off === true;
    }

    private function markLogsProcessed(string $barcode, string $date): void
    {
        FingerprintLog::where('barcode', $barcode)
            ->whereDate('attendance_date', $date)
            ->update([
                'is_processed' => true,
                'processed_at' => now(),
            ]);
    }
}