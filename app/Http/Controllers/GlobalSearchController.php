<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ShiftCode;
use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $q = trim($request->get('q', ''));

        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $like    = "%{$q}%";
        $results = [];

        Employee::where('name', 'like', $like)
            ->orWhere('nik', 'like', $like)
            ->orWhere('position', 'like', $like)
            ->limit(5)
            ->get()
            ->each(function ($e) use (&$results) {
                $results[] = [
                    'group' => 'Karyawan',
                    'icon'  => 'user',
                    'label' => $e->name,
                    'sub'   => $e->nik . ($e->position ? ' · ' . $e->position : ''),
                    'url'   => route('employees.index', ['search' => $e->nik]),
                ];
            });

        Department::where('name', 'like', $like)
            ->limit(3)
            ->get()
            ->each(function ($d) use (&$results) {
                $results[] = [
                    'group' => 'Department',
                    'icon'  => 'building',
                    'label' => $d->name,
                    'sub'   => 'Department',
                    'url'   => route('departments.index', ['search' => $d->name]),
                ];
            });

        ShiftCode::where('code', 'like', $like)
            ->limit(3)
            ->get()
            ->each(function ($s) use (&$results) {
                $results[] = [
                    'group' => 'Shift Code',
                    'icon'  => 'clock',
                    'label' => $s->code,
                    'sub'   => ($s->on_time ? $s->on_time . ' – ' . $s->off_time : '') . ($s->is_day_off ? ' · Day Off' : ''),
                    'url'   => route('shift_codes.index', ['search' => $s->code]),
                ];
            });

        Attendance::with('employee')
            ->whereHas('employee', fn($q2) => $q2->where('name', 'like', $like)->orWhere('nik', 'like', $like))
            ->latest('attendance_date')
            ->limit(3)
            ->get()
            ->each(function ($a) use (&$results) {
                $results[] = [
                    'group' => 'Attendance',
                    'icon'  => 'calendar',
                    'label' => $a->employee->name ?? '—',
                    'sub'   => $a->attendance_date->format('d M Y') . ' · ' . ucfirst($a->status),
                    'url'   => route('attendances.index', [
                        'search' => $a->employee->name ?? '',
                        'date'   => $a->attendance_date->toDateString(),
                    ]),
                ];
            });

        User::whereRaw("CONCAT(first_name,' ',last_name) LIKE ?", [$like])
            ->orWhere('email', 'like', $like)
            ->limit(3)
            ->get()
            ->each(function ($u) use (&$results) {
                $results[] = [
                    'group' => 'User',
                    'icon'  => 'shield',
                    'label' => trim($u->first_name . ' ' . $u->last_name),
                    'sub'   => $u->email . ' · ' . ucfirst($u->role),
                    'url'   => route('users.index', ['search' => $u->email]),
                ];
            });

        return response()->json($results);
    }
}
