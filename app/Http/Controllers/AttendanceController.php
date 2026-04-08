<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\ShiftCode;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date    = $request->input('date', today()->toDateString());
        $shiftId = $request->input('shift_code');
        $deptId  = $request->input('department');
        $status  = $request->input('status');

        $query = Attendance::with(['employee.department', 'shiftCode.shift'])
            ->whereDate('attendance_date', $date);

        if ($shiftId) {
            $query->where('shift_code_id', $shiftId);
        }

        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        if ($status) {
            // Jika filter status = 'present', tampilkan karyawan yang hadir dan terlambat
            if ($status === 'present') {
                $query->whereIn('status', ['present', 'late']);
            } else {
                $query->where('status', $status);
            }
        }

        // Sort berdasarkan clock_in terbaru (descending)
        $attendances = $query->orderBy('clock_in', 'desc')->paginate(25)->withQueryString();

        $baseQuery = Attendance::whereDate('attendance_date', $date);

        $presentCount = (clone $baseQuery)->where('status', 'present')->count();
        $lateCount    = (clone $baseQuery)->where('status', 'late')->count();

        $stats = [
            'present' => $presentCount,
            'late'    => $lateCount,
            'present_including_late' => $presentCount + $lateCount,
            'absent'  => (clone $baseQuery)->where('status', 'absent')->count(),
            'day_off' => (clone $baseQuery)->where('status', 'day_off')->count(),
            'total'   => (clone $baseQuery)->count(),
        ];

        $departments = Department::orderBy('name')->get();
        $shiftCodes  = ShiftCode::orderBy('code')->get();

        return view('attendances.index', compact(
            'attendances', 'stats', 'departments', 'shiftCodes', 'date', 'status'
        ));
    }
}