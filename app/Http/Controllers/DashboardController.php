<?php

namespace App\Http\Controllers;


class DashboardController extends Controller
{
    public function index()
    {
        $recentActivity = [
            [
                'initials'      => 'JD',
                'name'          => 'John Doe',
                'department'    => 'Engineering Department',
                'status'        => 'IN',
                'status_color'  => '#22c55e',
                'time'          => '08:05 AM',
                'gradient_from' => '#60a5fa',
                'gradient_to'   => '#2563eb',
            ],
            [
                'initials'      => 'SM',
                'name'          => 'Sarah Miller',
                'department'    => 'Marketing Team',
                'status'        => 'IN',
                'status_color'  => '#22c55e',
                'time'          => '08:12 AM',
                'gradient_from' => '#f472b6',
                'gradient_to'   => '#e11d48',
            ],
            [
                'initials'      => 'RF',
                'name'          => 'Robert Fox',
                'department'    => 'Design Studio',
                'status'        => 'LATE',
                'status_color'  => '#f87171',
                'time'          => '09:30 AM',
                'gradient_from' => '#fbbf24',
                'gradient_to'   => '#f97316',
            ],
            [
                'initials'      => 'EC',
                'name'          => 'Emily Chen',
                'department'    => 'Product Management',
                'status'        => 'OUT',
                'status_color'  => 'var(--text-2)',
                'time'          => '12:15 PM',
                'gradient_from' => '#2dd4bf',
                'gradient_to'   => '#06b6d4',
            ],
            [
                'initials'      => 'DP',
                'name'          => 'David Park',
                'department'    => 'Sales Operations',
                'status'        => 'IN',
                'status_color'  => '#22c55e',
                'time'          => '08:45 AM',
                'gradient_from' => '#a78bfa',
                'gradient_to'   => '#7c3aed',
            ],
        ];

        $departments = [
            ['name' => 'Engineering',      'total' => 420, 'present' => 398, 'late' => 12, 'efficiency' => 95],
            ['name' => 'Marketing',        'total' => 180, 'present' => 162, 'late' =>  8, 'efficiency' => 90],
            ['name' => 'Design Studio',    'total' =>  95, 'present' =>  81, 'late' => 14, 'efficiency' => 75],
            ['name' => 'Sales Operations', 'total' => 310, 'present' => 295, 'late' =>  6, 'efficiency' => 95],
        ];

        return view('dashboard.index', compact('recentActivity', 'departments'));
    }
}