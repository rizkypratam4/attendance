<?php

namespace App\Http\Controllers;


class ShiftGroupController extends Controller
{
    public function index() {
        return view('shift_groups.index');
    }
}
