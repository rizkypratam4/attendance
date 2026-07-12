<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CalenderViewController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\EmployeeShiftAssignmentController;
use App\Http\Controllers\FingerprintLogController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProcessAttendanceController;
use App\Http\Controllers\ShiftCodeController;
use App\Http\Controllers\ShiftDayRuleController;
use App\Http\Controllers\ShiftDefinitionController;
use App\Http\Controllers\ShiftGroupController;
use App\Http\Controllers\ShiftScheduleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Attendance routes - define before resources
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/export-pdf', [AttendanceController::class, 'exportPdf'])->name('attendances.export-pdf');
    Route::patch('/attendances/{attendance}', [AttendanceController::class, 'update'])->name('attendances.update');

    // Custom routes — harus sebelum Route::resources agar tidak ditangkap sebagai {id}
    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');
    Route::get('/employees/template', [EmployeeController::class, 'downloadTemplate'])->name('employees.template');

    Route::post('/employee_shift_assignments/import', [EmployeeShiftAssignmentController::class, 'import'])
        ->name('employee_shift_assignments.import');
    Route::get('/employee_shift_assignments/template', [EmployeeShiftAssignmentController::class, 'downloadTemplate'])
        ->name('employee_shift_assignments.template');
    Route::post('/employee_shift_assignments/bulk-assign', [EmployeeShiftAssignmentController::class, 'bulkAssign'])
        ->name('employee_shift_assignments.bulk_assign');

    Route::resources([
        'locations' => LocationController::class,
        'departments' => DepartmentController::class,
        'branches' => BranchController::class,
        'users' => UserController::class,
        'employees' => EmployeeController::class,
        'shift_groups' => ShiftGroupController::class,
        'shift_definitions' => ShiftDefinitionController::class,
        'shift_day_rules' => ShiftDayRuleController::class,
        'process_attendances' => ProcessAttendanceController::class,
        'employee_schedules' => EmployeeScheduleController::class,
        'calender_views' => CalenderViewController::class,
        'employee_shift_assignments' => EmployeeShiftAssignmentController::class,
    ]);

    Route::post('/process_attendances/process', [ProcessAttendanceController::class, 'process'])->name('process_attendances.process');
    Route::post('/process_attendances/reprocess', [ProcessAttendanceController::class, 'updateAttendance'])->name('process_attendances.reprocess');

    Route::resource('shift_codes', ShiftCodeController::class)->except(['show','edit']);

    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/password', [UserController::class, 'changePassword'])->name('password.change');

    Route::get('/search', \App\Http\Controllers\GlobalSearchController::class)->name('search.global');

    Route::prefix('fingerprint')->name('fingerprint.')->group(function () {
        Route::get('/',      [FingerprintLogController::class, 'index'])->name('index');
        Route::post('/sync', [FingerprintLogController::class, 'sync'])->name('sync');
    });
});