<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeShiftAssignmentController extends Controller
{
    public function index()
    {
            return view('employee_shift_assignments.index');
    }
}
