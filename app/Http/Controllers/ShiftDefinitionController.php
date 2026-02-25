<?php

namespace App\Http\Controllers;

class ShiftDefinitionController extends Controller
{
    public function index() {
        return view('shift_definitions.index');
    }
}
