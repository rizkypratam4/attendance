<?php

namespace App\Http\Controllers;

class EmployeeScheduleController extends Controller
{
    public function index() {
        return view('employee_schedules.index');
    }
}
