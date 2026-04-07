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
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');

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

    Route::resource('shift_codes', ShiftCodeController::class)->except(['show','edit']);

    Route::post('/employees/import', [EmployeeController::class, 'import'])->name('employees.import');

    // assignment import
    Route::post('/employee_shift_assignments/import', [EmployeeShiftAssignmentController::class, 'import'])
        ->name('employee_shift_assignments.import');

    Route::patch('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/password', [UserController::class, 'changePassword'])->name('password.change');

    Route::prefix('fingerprint')->name('fingerprint.')->group(function () {
        Route::get('/',      [FingerprintLogController::class, 'index'])->name('index');
        Route::post('/sync', [FingerprintLogController::class, 'sync'])->name('sync');
    });
});