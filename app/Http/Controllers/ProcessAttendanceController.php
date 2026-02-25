<?php

namespace App\Http\Controllers;

class ProcessAttendanceController extends Controller
{
    public function index() {
        return view('process_attendances.index');
    }
}
