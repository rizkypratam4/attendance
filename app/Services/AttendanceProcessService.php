<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\EmployeeShiftAssignment;
use App\Models\FingerprintLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttendanceProcessService
{
    //-- Orchestration (public) --//
    // Proses seluruh fingerprint log yang belum diproses untuk suatu tanggal, lalu generate absent.
    public function processAll(?string $date = null, ?int $departmentId = null): array
    {
        $date   = $date ?? now()->toDateString();
        $result = ['processed' => 0, 'skipped' => 0, 'failed' => 0, 'absent' => 0];

        $employeeIdsWithShift = $this->getEmployeeIdsWithShiftAssignment($date);

        if ($employeeIdsWithShift->isEmpty()) {
            return $result;
        }

        $validBarcodes = $this->getValidBarcodesForShiftedEmployees($employeeIdsWithShift, $departmentId);

        if ($validBarcodes->isEmpty()) {
            return $result;
        }

        $barcodes = $this->getUnprocessedBarcodes($date, $validBarcodes);

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

    // Proses fingerprint log satu karyawan (berdasarkan barcode) menjadi satu record attendance.
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
            $logs       = $this->fetchLogsForBarcode($barcode, $date);
            $computed   = $this->computeAttendanceData($date, $assignment, $logs);

            Attendance::updateOrCreate(
                [
                    'employee_id'     => $employee->id,
                    'attendance_date' => $date,
                ],
                array_merge($computed, ['has_idt' => false])
            );

            $this->markLogsProcessed($barcode, $date);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // Buat record attendance "absent"/"day off" untuk karyawan yang punya shift tapi belum ada attendance-nya.
    public function generateAbsent(string $date, ?int $departmentId = null): int
    {
        $employeeIdsWithShift = $this->getEmployeeIdsWithShiftAssignment($date);

        if ($employeeIdsWithShift->isEmpty()) {
            return 0;
        }

        $employees = $this->getActiveEmployeesWithShift($employeeIdsWithShift, $departmentId);

        $count = 0;

        foreach ($employees as $employee) {
            if ($this->hasAttendanceRecord($employee->id, $date)) {
                continue;
            }

            $assignment = $this->getAssignment($employee->id, $date);
            if (!$assignment) {
                continue;
            }

            if ($this->shouldSkipAbsentGeneration($date, $assignment)) {
                continue;
            }

            $this->createAbsentAttendance($employee, $assignment, $date);
            $count++;
        }

        return $count;
    }

    // Hitung ulang & update record attendance yang datanya belum lengkap/tidak konsisten dalam rentang tanggal.
    public function updateIncomplete(string $startDate, string $endDate, ?int $departmentId = null): array
    {
        $result = ['updated' => 0, 'skipped' => 0, 'failed' => 0];

        $attendances = $this->getIncompleteAttendances($startDate, $endDate, $departmentId);

        foreach ($attendances as $attendance) {
            $result = $this->updateSingleIncompleteAttendance($attendance, $result);
        }

        return $result;
    }

    // Hitung seluruh data attendance (clock in/out, telat, durasi kerja, status) dari log & assignment.
    private function computeAttendanceData(string $date, ?EmployeeShiftAssignment $assignment, Collection $logs): array
    {
        [$clockInLog, $clockOutLog]   = $this->resolveClockLogs($logs);
        [$clockInTime, $clockOutTime] = $this->resolveClockTimes($date, $clockInLog, $clockOutLog);

        $lateMinutes  = $this->calculateLateMinutes($date, $assignment, $clockInTime);
        $workDuration = $this->calculateWorkDuration($clockInTime, $clockOutTime);
        $status       = $this->determineStatus($assignment, $clockInTime, $lateMinutes);

        return [
            'shift_code_id'         => $assignment?->new_working_shift_id ?? $assignment?->shift_code_id,
            'new_working_shift_id'  => $assignment?->new_working_shift_id,
            'clock_in'              => $clockInTime,
            'clock_out'             => $clockOutTime,
            'late_minutes'          => $lateMinutes,
            'work_duration_minutes' => $workDuration,
            'status'                => $status,
        ];
    }

    // Tentukan log clock-in & clock-out dari kumpulan fingerprint log satu hari, dengan fallback jika tipe tak jelas.
    private function resolveClockLogs(Collection $logs): array
    {
        $clockInLog  = $logs->where('attendance_type', FingerprintLog::TYPE_CLOCK_IN)->first();
        $clockOutLog = $logs->where('attendance_type', FingerprintLog::TYPE_CLOCK_OUT)->last();

        if (!$clockInLog && $logs->isNotEmpty()) {
            $clockInLog = $logs->first();
        }

        if (!$clockOutLog || $clockOutLog->id === $clockInLog?->id) {
            $clockOutLog = $logs->count() > 1 ? $logs->last() : null;
        }

        return [$clockInLog, $clockOutLog];
    }

    // Konversi log clock-in/clock-out menjadi Carbon instance, menangani shift malam (clock-out di hari berikutnya).
    private function resolveClockTimes(string $date, $clockInLog, $clockOutLog): array
    {
        $clockInTime = $clockInLog
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

        return [$clockInTime, $clockOutTime];
    }

    // Hitung jumlah menit keterlambatan dibandingkan jam masuk shift aktif.
    private function calculateLateMinutes(string $date, ?EmployeeShiftAssignment $assignment, ?Carbon $clockInTime): int
    {
        $activeShiftCode = $assignment?->newWorkingShift ?? $assignment?->shiftCode;

        if (!$activeShiftCode?->on_time || !$clockInTime) {
            return 0;
        }

        $scheduledIn = Carbon::parse($date . ' ' . $activeShiftCode->on_time);

        if ($clockInTime->gt($scheduledIn)) {
            return (int) $scheduledIn->diffInMinutes($clockInTime);
        }

        return 0;
    }

    // Hitung durasi kerja (menit) antara clock-in dan clock-out.
    private function calculateWorkDuration(?Carbon $clockInTime, ?Carbon $clockOutTime): int
    {
        if ($clockInTime && $clockOutTime) {
            return (int) $clockInTime->diffInMinutes($clockOutTime);
        }

        return 0;
    }

    // Tentukan status attendance (day off/absent/late/present) berdasarkan assignment, clock-in, dan keterlambatan.
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
            $activeShiftCode = $assignment?->newWorkingShift ?? $assignment?->shiftCode;
            if (in_array($activeShiftCode?->code, ['1AA', '1AB'])) {
                $cutoffTime = Carbon::parse($clockIn->toDateString() . ' 10:00:00');
                if ($clockIn->gt($cutoffTime)) {
                    return Attendance::STATUS_ABSENT;
                }
            }
            return Attendance::STATUS_LATE;
        }

        return Attendance::STATUS_PRESENT;
    }

    // Cek apakah shift aktif dari suatu assignment adalah shift day-off.
    private function isDayOff(?EmployeeShiftAssignment $assignment): bool
    {
        $activeShiftCode = $assignment?->newWorkingShift ?? $assignment?->shiftCode;
        return $activeShiftCode?->is_day_off === true;
    }

    //-- ABSEN GENERATION HELPERS --//
    // Ambil karyawan aktif yang punya shift assignment di tanggal ini (opsional difilter department).
    private function getActiveEmployeesWithShift(Collection $employeeIds, ?int $departmentId)
    {
        $query = Employee::whereIn('id', $employeeIds)
            ->where('is_active', true)
            ->whereNotNull('machine_barcode');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->get();
    }

    // Cek apakah karyawan sudah punya record attendance di tanggal tertentu.
    private function hasAttendanceRecord(int $employeeId, string $date): bool
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereDate('attendance_date', $date)
            ->exists();
    }

    // Cek apakah generate absent harus dilewati (untuk hari ini, jika jam mulai shift belum lewat).
    private function shouldSkipAbsentGeneration(string $date, EmployeeShiftAssignment $assignment): bool
    {
        if ($date !== now()->toDateString() || $this->isDayOff($assignment)) {
            return false;
        }

        $activeShift = $assignment->newWorkingShift ?? $assignment->shiftCode;
        if (!$activeShift?->on_time) {
            return false;
        }

        $scheduledIn = Carbon::parse($date . ' ' . $activeShift->on_time);

        return now()->lt($scheduledIn);
    }

    // Buat record attendance baru dengan status absent atau day-off untuk seorang karyawan.
    private function createAbsentAttendance(Employee $employee, EmployeeShiftAssignment $assignment, string $date): void
    {
        $status = $this->isDayOff($assignment)
            ? Attendance::STATUS_DAY_OFF
            : Attendance::STATUS_ABSENT;

        Attendance::create([
            'employee_id'           => $employee->id,
            'shift_code_id'         => $assignment->new_working_shift_id ?? $assignment->shift_code_id,
            'new_working_shift_id'  => $assignment->new_working_shift_id,
            'attendance_date'       => $date,
            'status'                => $status,
            'late_minutes'          => 0,
            'work_duration_minutes' => 0,
        ]);
    }

    //-- Update-incomplete helpers  --//
    // Query attendance dalam rentang tanggal yang datanya belum lengkap/tidak konsisten dengan assignment terbaru.
    private function getIncompleteAttendances(string $startDate, string $endDate, ?int $departmentId)
    {
        $query = Attendance::with(['employee'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereNull('shift_code_id')
                    ->orWhereNull('status')
                    ->orWhere(function ($q2) {
                        $q2->where('status', Attendance::STATUS_ABSENT)
                            ->whereNotNull('clock_in');
                    })
                    ->orWhere(function ($q4) {
                        $q4->whereExists(function ($sub) {
                            $sub->select(DB::raw(1))
                                ->from('employee_shift_assignments')
                                ->whereColumn('employee_shift_assignments.employee_id', 'attendances.employee_id')
                                ->whereColumn('employee_shift_assignments.date', 'attendances.attendance_date')
                                ->whereNotNull('employee_shift_assignments.new_working_shift_id')
                                ->where(function ($sub2) {
                                    $sub2->whereColumn('employee_shift_assignments.new_working_shift_id', '!=', 'attendances.new_working_shift_id')
                                        ->orWhereNull('attendances.new_working_shift_id');
                                });
                        });
                    });
            });

        if ($departmentId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $departmentId));
        }

        return $query->get();
    }

    // Hitung ulang & update satu record attendance berdasarkan assignment & log fingerprint terbaru.
    private function updateSingleIncompleteAttendance(Attendance $attendance, array $result): array
    {
        $employee = null;
        $date     = null;

        try {
            DB::beginTransaction();

            $employee   = $attendance->employee;
            $date       = $attendance->attendance_date->toDateString();
            $assignment = $this->getAssignment($employee->id, $date);

            if (!$assignment) {
                $result['skipped']++;
                DB::commit();
                return $result;
            }

            $logs     = $this->fetchLogsForBarcode($employee->machine_barcode, $date);
            $computed = $this->computeAttendanceData($date, $assignment, $logs);

            $attendance->update([
                'shift_code_id'         => $computed['shift_code_id'],
                'new_working_shift_id'  => $computed['new_working_shift_id'],
                'clock_in'              => $computed['clock_in']  ?? $attendance->clock_in,
                'clock_out'             => $computed['clock_out'] ?? $attendance->clock_out,
                'late_minutes'          => $computed['late_minutes'],
                'work_duration_minutes' => $computed['work_duration_minutes'],
                'status'                => $computed['status'],
            ]);

            $result['updated']++;
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $result['failed']++;
            Log::error('UpdateIncomplete: gagal update attendance', [
                'attendance_id' => $attendance->id,
                'employee'      => $employee->name ?? 'unknown',
                'date'          => $date ?? 'unknown',
                'error'         => $e->getMessage(),
            ]);
        }

        return $result;
    }

    //-- SHARED QUERY HELPERS --//
    // Ambil daftar employee_id unik yang punya shift assignment pada tanggal tertentu.
    private function getEmployeeIdsWithShiftAssignment(string $date): Collection
    {
        return EmployeeShiftAssignment::where('date', $date)
            ->pluck('employee_id')
            ->unique();
    }

    // Ambil daftar machine_barcode karyawan yang punya shift assignment (opsional difilter department).
    private function getValidBarcodesForShiftedEmployees(Collection $employeeIds, ?int $departmentId): Collection
    {
        $query = Employee::whereIn('id', $employeeIds)
            ->whereNotNull('machine_barcode');

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        return $query->pluck('machine_barcode');
    }

    // Ambil daftar barcode unik yang punya fingerprint log belum diproses pada tanggal tertentu.
    private function getUnprocessedBarcodes(string $date, Collection $validBarcodes): Collection
    {
        return FingerprintLog::where('is_processed', false)
            ->whereDate('attendance_date', $date)
            ->whereIn('barcode', $validBarcodes)
            ->distinct()
            ->pluck('barcode');
    }

    // Ambil seluruh fingerprint log satu barcode pada tanggal tertentu, terurut berdasarkan jam.
    private function fetchLogsForBarcode(?string $barcode, string $date): Collection
    {
        return FingerprintLog::where('barcode', $barcode)
            ->whereDate('attendance_date', $date)
            ->orderBy('attendance_time')
            ->get();
    }

    // Ambil shift assignment seorang karyawan pada tanggal tertentu, lengkap dengan relasi shift code.
    private function getAssignment(int $employeeId, string $date): ?EmployeeShiftAssignment
    {
        return EmployeeShiftAssignment::where('employee_id', $employeeId)
            ->where('date', $date)
            ->with(['shiftCode', 'newWorkingShift'])
            ->first();
    }

    // Tandai seluruh fingerprint log suatu barcode/tanggal sebagai sudah diproses.
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
