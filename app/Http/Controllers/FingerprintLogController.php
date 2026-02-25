<?php

namespace App\Http\Controllers;

class FingerprintLogController extends Controller
{
    public function index() {
        return view('fingerprint_logs.index');
    }
}
