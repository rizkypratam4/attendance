<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ROW 1: STATISTICS
        $totalActiveEmployees = Employee::where('is_active', true)->count();
        
        $today = today()->toDateString();
        $presentToday = Attendance::where('attendance_date', $today)
                            ->whereIn('status', ['present', 'late'])
                            ->count();
        
        $lateToday = Attendance::where('attendance_date', $today)
                        ->where('status', 'late')
                        ->count();
        
        $absentToday = Attendance::where('attendance_date', $today)
                         ->where('status', 'absent')
                         ->count();

        // ROW 2: LINE CHART - 7 DAYS ATTENDANCE
        $sevenDaysAgo = now()->subDays(6)->toDateString();
        $sevenDaysData = Attendance::selectRaw('DATE(attendance_date) as date')
            ->selectRaw('COUNT(CASE WHEN status IN ("present", "late") THEN 1 END) as present_count')
            ->selectRaw('COUNT(CASE WHEN status = "absent" THEN 1 END) as absent_count')
            ->whereBetween('attendance_date', [$sevenDaysAgo, $today])
            ->groupBy(DB::raw('DATE(attendance_date)'))
            ->orderBy(DB::raw('DATE(attendance_date)'))
            ->get();

        $attendanceChartLabels = [];
        $attendanceChartPresent = [];
        $attendanceChartAbsent = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dayName = Carbon::parse($date)->locale('id')->format('l');
            $attendanceChartLabels[] = ucfirst(substr($dayName, 0, 3));
            
            $data = $sevenDaysData->firstWhere('date', $date);
            $attendanceChartPresent[] = $data ? $data->present_count : 0;
            $attendanceChartAbsent[] = $data ? $data->absent_count : 0;
        }

        // ROW 2: PIE CHART - TODAY STATUS
        $todayStatus = Attendance::where('attendance_date', $today)
            ->selectRaw('COUNT(CASE WHEN status = "present" THEN 1 END) as present')
            ->selectRaw('COUNT(CASE WHEN status = "late" THEN 1 END) as late')
            ->selectRaw('COUNT(CASE WHEN status = "absent" THEN 1 END) as absent')
            ->selectRaw('COUNT(CASE WHEN status IN ("day_off", "holiday") THEN 1 END) as day_off')
            ->first();

        $statusChartData = [
            $todayStatus->present ?? 0,
            $todayStatus->late ?? 0,
            $todayStatus->absent ?? 0,
            $todayStatus->day_off ?? 0,
        ];

        // ROW 3: TOP 5 LATE EMPLOYEES THIS MONTH
        $topLateEmployees = Attendance::select('employee_id', DB::raw('COUNT(*) as late_count'))
            ->where('status', 'late')
            ->whereMonth('attendance_date', now()->month)
            ->whereYear('attendance_date', now()->year)
            ->groupBy('employee_id')
            ->orderByDesc('late_count')
            ->limit(5)
            ->with('employee.department')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'employee' => $item->employee,
                    'department' => $item->employee->department,
                    'late_count' => $item->late_count,
                ];
            });

        // ROW 3: TOP 5 LATE DEPARTMENTS
        // Calculate: percentage of working days where at least one person from the department was late
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Count total working days in the month (exclude day_off and holiday)
        $totalWorkingDays = DB::table('attendances')
            ->whereMonth('attendance_date', $currentMonth)
            ->whereYear('attendance_date', $currentYear)
            ->whereNotIn('status', ['day_off', 'holiday'])
            ->distinct('attendance_date')
            ->count('attendance_date');
        
        if ($totalWorkingDays == 0) $totalWorkingDays = 1; // Prevent division by zero
        
        $topLateDepartments = DB::table('departments')
            ->select('departments.id', 'departments.name')
            ->selectRaw('COUNT(DISTINCT attendances.attendance_date) as days_with_late')
            ->selectRaw('ROUND(COUNT(DISTINCT attendances.attendance_date) * 100.0 / ?, 2) as late_percentage', [$totalWorkingDays])
            ->leftJoin('employees', 'departments.id', '=', 'employees.department_id')
            ->leftJoin('attendances', function($join) use ($currentMonth, $currentYear) {
                $join->on('employees.id', '=', 'attendances.employee_id')
                    ->whereMonth('attendances.attendance_date', $currentMonth)
                    ->whereYear('attendances.attendance_date', $currentYear)
                    ->where('attendances.status', 'late');
            })
            ->where('employees.is_active', true)
            ->groupBy('departments.id', 'departments.name')
            ->orderByDesc('late_percentage')
            ->limit(5)
            ->get()
            ->map(function ($dept) {
                return (object)[
                    'name' => $dept->name,
                    'late_percentage' => $dept->late_percentage ?? 0,
                ];
            });

        // ROW 3: LAST IMPORT STATUS (mock data - dapat disesuaikan dengan implementasi actual)
        $lastImportStatus = [
            'success' => true,
            'last_import_time' => now()->subHours(2)->format('d M Y, H:i'),
            'records_imported' => 542,
            'message' => 'Import berhasil',
        ];

        return view('dashboard.index', compact(
            'totalActiveEmployees',
            'presentToday',
            'lateToday',
            'absentToday',
            'attendanceChartLabels',
            'attendanceChartPresent',
            'attendanceChartAbsent',
            'statusChartData',
            'topLateEmployees',
            'topLateDepartments',
            'lastImportStatus',
        ));
    }
}
