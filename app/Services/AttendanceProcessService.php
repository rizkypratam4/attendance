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

        // Hanya proses employee yang memiliki shift assignment di tanggal ini
        $employeeIdsWithShift = EmployeeShiftAssignment::where('date', $date)
            ->pluck('employee_id')
            ->unique();

        if ($employeeIdsWithShift->isEmpty()) {
            return $result; // Tidak ada shift assignment untuk tanggal ini
        }

        // Ambil barcode dari employee yang memiliki shift assignment
        $employeesQuery = Employee::whereIn('id', $employeeIdsWithShift)
            ->whereNotNull('machine_barcode');

        if ($departmentId) {
            $employeesQuery->where('department_id', $departmentId);
        }

        $validBarcodes = $employeesQuery->pluck('machine_barcode');

        if ($validBarcodes->isEmpty()) {
            return $result;
        }

        // Ambil fingerprint logs yang belum diproses untuk barcode yang valid
        $query = FingerprintLog::where('is_processed', false)
            ->whereDate('attendance_date', $date)
            ->whereIn('barcode', $validBarcodes);

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
            // Gunakan new_working_shift jika ada, karena itu adalah jadwal aktif karyawan
            $activeShiftCode = $assignment?->newWorkingShift ?? $assignment?->shiftCode;
            if ($activeShiftCode?->on_time && $clockInTime) {
                $scheduledIn = Carbon::parse($date . ' ' . $activeShiftCode->on_time);
                if ($clockInTime->gt($scheduledIn)) {
                    $lateMinutes = (int) $scheduledIn->diffInMinutes($clockInTime);
                }
            }

            $workDuration = 0;
            if ($clockInTime && $clockOutTime) {
                $workDuration = (int) $clockInTime->diffInMinutes($clockOutTime);
            }

            $status = $this->determineStatus($assignment, $clockInTime, $lateMinutes);

            // Tentukan shift_code_id yang akan disimpan
            $finalShiftCodeId  = $assignment?->new_working_shift_id ?? $assignment?->shift_code_id;
            $newWorkingShiftId = $assignment?->new_working_shift_id;

            Attendance::updateOrCreate(
                [
                    'employee_id'     => $employee->id,
                    'attendance_date' => $date,
                ],
                [
                    'shift_code_id'         => $finalShiftCodeId,
                    'new_working_shift_id'  => $newWorkingShiftId,
                    'clock_in'              => $clockInTime,
                    'clock_out'             => $clockOutTime,
                    'late_minutes'          => $lateMinutes,
                    'work_duration_minutes' => $workDuration,
                    'status'                => $status,
                    'has_idt'               => false,
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

        // Hanya ambil employee yang memiliki shift assignment di tanggal ini
        $employeeIdsWithShift = EmployeeShiftAssignment::where('date', $date)
            ->pluck('employee_id')
            ->unique();

        if ($employeeIdsWithShift->isEmpty()) {
            return 0; // Tidak ada shift assignment untuk tanggal ini
        }

        $query = Employee::whereIn('id', $employeeIdsWithShift)
            ->where('is_active', true)
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

            // Jika hari ini, skip karyawan yang shift-nya belum mulai
            if ($date === now()->toDateString() && !$this->isDayOff($assignment)) {
                $activeShift = $assignment->newWorkingShift ?? $assignment->shiftCode;
                if ($activeShift?->on_time) {
                    $scheduledIn = Carbon::parse($date . ' ' . $activeShift->on_time);
                    if (now()->lt($scheduledIn)) {
                        continue; // Belum waktunya masuk, skip
                    }
                }
            }

            $status = $this->isDayOff($assignment)
                ? Attendance::STATUS_DAY_OFF
                : Attendance::STATUS_ABSENT;

            // Tentukan shift_code_id yang akan disimpan
            // Jika new_working_shift tersedia, gunakan itu; jika tidak, gunakan shift_code
            $finalShiftCodeId = $assignment->new_working_shift_id ?? $assignment->shift_code_id;
            $newWorkingShiftId = $assignment->new_working_shift_id;

            Attendance::create([
                'employee_id'           => $employee->id,
                'shift_code_id'         => $finalShiftCodeId,
                'new_working_shift_id'  => $newWorkingShiftId,
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
            ->with(['shiftCode', 'newWorkingShift'])
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
            // Gunakan shift aktif (new_working_shift jika ada, fallback ke shiftCode)
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

    private function isDayOff(?EmployeeShiftAssignment $assignment): bool
    {
        // Cek shift aktif: new_working_shift jika ada, fallback ke shiftCode
        $activeShiftCode = $assignment?->newWorkingShift ?? $assignment?->shiftCode;
        return $activeShiftCode?->is_day_off === true;
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

    /**
     * Update attendance records yang incomplete (shift/status kosong)
     * berdasarkan shift assignment yang sudah ada.
     * Idempotent — aman dijalankan berulang kali.
     */
    public function updateIncomplete(string $startDate, string $endDate, ?int $departmentId = null): array
    {
        $result = ['updated' => 0, 'skipped' => 0, 'failed' => 0];

        // Ambil attendance yang memiliki kolom kosong/null ATAU perlu update karena shift assignment berubah
        $query = Attendance::with(['employee'])
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->where(function ($q) {
                $q->whereNull('shift_code_id')      // shift kosong
                  ->orWhereNull('status')            // status kosong
                  ->orWhere(function ($q2) {
                      // status absent tapi ada clock_in (data tidak konsisten)
                      $q2->where('status', Attendance::STATUS_ABSENT)
                         ->whereNotNull('clock_in');
                  })
                  ->orWhere(function ($q4) {
                      // shift assignment punya new_working_shift tapi attendance belum mencerminkannya
                      // Cek: new_working_shift_id di assignment berbeda dengan new_working_shift_id di attendance
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

        $attendances = $query->get();

        foreach ($attendances as $attendance) {
            try {
                DB::beginTransaction();

                $employee   = $attendance->employee;
                $date       = $attendance->attendance_date->toDateString();
                $assignment = $this->getAssignment($employee->id, $date);

                // Tidak ada assignment — skip
                if (!$assignment) {
                    $result['skipped']++;
                    DB::commit();
                    continue;
                }

                // Ambil ulang fingerprint logs untuk hitung clock in/out
                $logs = FingerprintLog::where('barcode', $employee->machine_barcode)
                    ->whereDate('attendance_date', $date)
                    ->orderBy('attendance_time')
                    ->get();

                $clockInLog  = $logs->where('attendance_type', FingerprintLog::TYPE_CLOCK_IN)->first();
                $clockOutLog = $logs->where('attendance_type', FingerprintLog::TYPE_CLOCK_OUT)->last();

                if (!$clockInLog && $logs->isNotEmpty()) {
                    $clockInLog = $logs->first();
                }
                if (!$clockOutLog || $clockOutLog->id === $clockInLog?->id) {
                    $clockOutLog = $logs->count() > 1 ? $logs->last() : null;
                }

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

                // Gunakan shift aktif (new_working_shift jika ada, fallback ke shiftCode) untuk hitung keterlambatan
                $shiftCodeForCalc = $assignment->newWorkingShift ?? $assignment->shiftCode;
                $lateMinutes = 0;
                if ($shiftCodeForCalc?->on_time && $clockInTime) {
                    $scheduledIn = Carbon::parse($date . ' ' . $shiftCodeForCalc->on_time);
                    if ($clockInTime->gt($scheduledIn)) {
                        $lateMinutes = (int) $scheduledIn->diffInMinutes($clockInTime);
                    }
                }

                $workDuration = 0;
                if ($clockInTime && $clockOutTime) {
                    $workDuration = (int) $clockInTime->diffInMinutes($clockOutTime);
                }

                $status = $this->determineStatus($assignment, $clockInTime, $lateMinutes);

                // Gunakan new_working_shift jika ada, fallback ke shift_code
                $finalShiftCodeId  = $assignment->new_working_shift_id ?? $assignment->shift_code_id;
                $newWorkingShiftId = $assignment->new_working_shift_id;

                $attendance->update([
                    'shift_code_id'         => $finalShiftCodeId,
                    'new_working_shift_id'  => $newWorkingShiftId,
                    'clock_in'              => $clockInTime  ?? $attendance->clock_in,
                    'clock_out'             => $clockOutTime ?? $attendance->clock_out,
                    'late_minutes'          => $lateMinutes,
                    'work_duration_minutes' => $workDuration,
                    'status'                => $status,
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
        }

        return $result;
    }
}