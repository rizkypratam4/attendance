<?php

namespace App\Http\Controllers;

use App\Helpers\AbsentCountHelper;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\ShiftCode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Tampilkan daftar attendance hari tertentu (dengan filter, statistik, dan pagination) di halaman index.
    public function index(Request $request)
    {
        $date    = $request->input('date', today()->toDateString());
        $shiftId = $request->input('shift_code');
        $deptId  = $request->input('department');
        $status  = $request->input('status');
        $search  = $request->input('search');

        $baseQuery = $this->buildFilteredAttendanceQuery($date, $shiftId, $deptId, $search);

        $query = clone $baseQuery;
        $this->applyIndexStatusFilter($query, $status);

        $attendances = $query->orderBy('clock_in', 'desc')->paginate(10)->withQueryString();

        if ($date === today()->toDateString()) {
            $this->filterOutNotYetDueAbsences($attendances, $date);
        }

        $absentCount = $date === today()->toDateString()
            ? AbsentCountHelper::count($date, $deptId ?: null)
            : null;

        $stats = $this->buildStats($baseQuery, $absentCount);

        $departments = Department::orderBy('name')->get();
        $shiftCodes  = ShiftCode::orderBy('code')->get();

        return view('attendances.index', compact(
            'attendances',
            'stats',
            'departments',
            'shiftCodes',
            'date',
            'status'
        ));
    }

    // Update flag "has_idt" pada satu attendance, dengan penyesuaian otomatis status & late_minutes bila perlu.
    public function update(Request $request, Attendance $attendance)
    {
        $hasIdt = (bool) $request->input('has_idt', false);
        $data   = ['has_idt' => $hasIdt];

        if ($hasIdt && $attendance->status === Attendance::STATUS_LATE) {
            $data['status']       = Attendance::STATUS_PRESENT;
            $data['late_minutes'] = 0;
        }

        if (!$hasIdt && $attendance->status === Attendance::STATUS_PRESENT && $attendance->clock_in) {
            $data = array_merge($data, $this->recalculateLateStatus($attendance));
        }

        $attendance->update($data);

        return back()->with('success', 'Attendance berhasil diupdate.');
    }

    // Export daftar attendance (dengan filter & statistik yang sama seperti index) menjadi file PDF.
    public function exportPdf(Request $request)
    {
        $date    = $request->input('date', today()->toDateString());
        $shiftId = $request->input('shift_code');
        $deptId  = $request->input('department');
        $status  = $request->input('status');

        $baseQuery = $this->buildFilteredAttendanceQuery($date, $shiftId, $deptId);

        $query = clone $baseQuery;
        $this->applyExportStatusFilter($query, $status);

        $attendances = $query->orderBy('clock_in', 'desc')->get();

        $stats = $this->buildStats($baseQuery, null);

        $departments = Department::orderBy('name')->get();
        $shiftCodes  = ShiftCode::orderBy('code')->get();

        $data = [
            'attendances' => $attendances,
            'stats' => $stats,
            'date' => $date,
            'status' => $status,
            'deptId' => $deptId,
            'shiftId' => $shiftId,
            'departments' => $departments,
            'shiftCodes' => $shiftCodes,
        ];

        $pdf = \PDF::loadView('attendances.pdf', $data);
        $filename = 'attendance_' . $date . '.pdf';

        return $pdf->download($filename);
    }

    //-- QUERY & FILTER HELPER --//
    // Bangun query attendance dasar untuk satu tanggal, dengan filter shift/department/search opsional.
    private function buildFilteredAttendanceQuery(string $date, ?string $shiftId, ?string $deptId, ?string $search = null)
    {
        $query = Attendance::with(['employee.department', 'shiftCode.shift', 'newWorkingShift.shift'])
            ->whereDate('attendance_date', $date);

        if ($shiftId) {
            $query->where('shift_code_id', $shiftId);
        }

        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        if ($search) {
            $like = '%' . $search . '%';
            $query->whereHas('employee', fn($q) => $q->where('name', 'like', $like)->orWhere('nik', 'like', $like));
        }

        return $query;
    }

    // Terapkan filter status untuk halaman index (mendukung grup "present", "idt", atau status persis).
    private function applyIndexStatusFilter($query, ?string $status): void
    {
        if (!$status) {
            return;
        }

        if ($status === 'present') {
            $query->whereIn('status', ['present', 'late']);
        } elseif ($status === 'idt') {
            $query->where('has_idt', true);
        } else {
            $query->where('status', $status);
        }
    }

    // Terapkan filter status untuk export PDF (mendukung grup "present" atau status persis).
    private function applyExportStatusFilter($query, ?string $status): void
    {
        if (!$status) {
            return;
        }

        if ($status === 'present') {
            $query->whereIn('status', ['present', 'late']);
        } else {
            $query->where('status', $status);
        }
    }

    // Hitung ringkasan statistik (present, late, absent, day off, total) dari base query.
    private function buildStats($baseQuery, ?int $absentCount = null): array
    {
        $presentCount = (clone $baseQuery)->where('status', 'present')->count();
        $lateCount    = (clone $baseQuery)->where('status', 'late')->count();

        return [
            'present' => $presentCount,
            'late'    => $lateCount,
            'present_including_late' => $presentCount + $lateCount,
            'absent'  => $absentCount ?? (clone $baseQuery)->where('status', 'absent')->count(),
            'day_off' => (clone $baseQuery)->where('status', 'day_off')->count(),
            'total'   => (clone $baseQuery)->count(),
        ];
    }

    // Buang dari collection hasil paginate: record "absent" yang jam mulai shift-nya belum lewat (untuk hari ini).
    private function filterOutNotYetDueAbsences($paginator, string $date): void
    {
        $now = Carbon::now();

        $filtered = $paginator->getCollection()->reject(function ($attendance) use ($now, $date) {
            if ($attendance->status !== 'absent') {
                return false;
            }

            $activeShift = $attendance->newWorkingShift ?? $attendance->shiftCode;
            if (!$activeShift?->on_time) {
                return false;
            }

            $scheduledIn = Carbon::parse($date . ' ' . $activeShift->on_time);

            return $now->lt($scheduledIn);
        });

        $paginator->setCollection($filtered);
    }

    //-- UPDATE HELPERS --//
    // Hitung ulang status & late_minutes jika clock-in ternyata melewati jam mulai shift (kasus IDT dibatalkan).
    private function recalculateLateStatus(Attendance $attendance): array
    {
        $activeShift = $attendance->newWorkingShift ?? $attendance->shiftCode;

        if (!$activeShift?->on_time) {
            return [];
        }

        $scheduledIn = Carbon::parse($attendance->attendance_date->toDateString() . ' ' . $activeShift->on_time);

        if (!$attendance->clock_in->gt($scheduledIn)) {
            return [];
        }

        return [
            'late_minutes' => (int) $scheduledIn->diffInMinutes($attendance->clock_in),
            'status'       => Attendance::STATUS_LATE,
        ];
    }
}
