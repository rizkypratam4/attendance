<?php

namespace App\Http\Controllers;

class CalenderViewController extends Controller
{
    public function index() 
    {
        return view('calender_views.index');
    }
}
