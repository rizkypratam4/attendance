<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $today = today()->toDateString();
        
        // Count attendances for today
        $todayAttendance = Attendance::whereDate('attendance_date', $today)->count();
        $presentToday = Attendance::whereDate('attendance_date', $today)
            ->whereIn('status', ['present', 'late'])
            ->count();
        $absentToday = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'absent')
            ->count();
        $lateToday = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'late')
            ->count();
        
        // Total employees
        $totalEmployees = Employee::count();
        
        // Total departments
        $totalDepartments = Department::count();
        
        // Recent attendances
        $recentAttendances = Attendance::with(['employee.department', 'shiftCode.shift'])
            ->whereDate('attendance_date', $today)
            ->orderBy('clock_in', 'desc')
            ->limit(10)
            ->get();
        
        // Get departments with attendance stats for today
        $departments = Department::withCount(['employees'])->get()->map(function ($dept) use ($today) {
            $deptAttendances = Attendance::whereDate('attendance_date', $today)
                ->whereHas('employee', fn($q) => $q->where('department_id', $dept->id))
                ->get();
            
            $presentCount = $deptAttendances->whereIn('status', ['present', 'late'])->count();
            $lateCount = $deptAttendances->where('status', 'late')->count();
            $totalStaff = $dept->employees_count;
            $efficiency = $totalStaff > 0 ? round(($presentCount / $totalStaff) * 100) : 0;
            
            $dept->present_today = $presentCount;
            $dept->late_today = $lateCount;
            $dept->efficiency = $efficiency;
            
            return $dept;
        });
        
        return view('dashboard.index', [
            'todayAttendance' => $todayAttendance,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'lateToday' => $lateToday,
            'totalEmployees' => $totalEmployees,
            'totalDepartments' => $totalDepartments,
            'recentAttendances' => $recentAttendances,
            'departments' => $departments,
        ]);
    }
}
