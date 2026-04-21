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

        // Build base query with all filters (except status)
        $baseQuery = Attendance::with(['employee.department', 'shiftCode.shift', 'newWorkingShift.shift'])
            ->whereDate('attendance_date', $date);

        if ($shiftId) {
            $baseQuery->where('shift_code_id', $shiftId);
        }

        if ($deptId) {
            $baseQuery->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        // Apply status filter only to the display query
        $query = clone $baseQuery;
        
        if ($status) {
            // Jika filter status = 'present', tampilkan karyawan yang hadir dan terlambat
            if ($status === 'present') {
                $query->whereIn('status', ['present', 'late']);
            } else {
                $query->where('status', $status);
            }
        }

        // Sort berdasarkan clock_in terbaru (descending)
        $attendances = $query->orderBy('clock_in', 'desc')->paginate(10)->withQueryString();

        // Calculate stats based on filtered query (with date, shift, department filters)
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

    /**
     * Export attendance data to PDF with applied filters
     */
    public function exportPdf(Request $request)
    {
        $date    = $request->input('date', today()->toDateString());
        $shiftId = $request->input('shift_code');
        $deptId  = $request->input('department');
        $status  = $request->input('status');

        // Build base query with all filters (except status) - same as index
        $baseQuery = Attendance::with(['employee.department', 'shiftCode.shift', 'newWorkingShift.shift'])
            ->whereDate('attendance_date', $date);

        if ($shiftId) {
            $baseQuery->where('shift_code_id', $shiftId);
        }

        if ($deptId) {
            $baseQuery->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }

        // Apply status filter only if provided
        $query = clone $baseQuery;
        
        if ($status) {
            if ($status === 'present') {
                $query->whereIn('status', ['present', 'late']);
            } else {
                $query->where('status', $status);
            }
        }

        // Get all data without pagination for PDF
        $attendances = $query->orderBy('clock_in', 'desc')->get();

        // Calculate stats based on filtered query
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

        // Prepare PDF data
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

        // Generate PDF using Dompdf
        $pdf = \PDF::loadView('attendances.pdf', $data);
        $filename = 'attendance_' . $date . '.pdf';
        
        return $pdf->download($filename);
    }
}