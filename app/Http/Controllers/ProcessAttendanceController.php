<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\FingerprintLog;
use App\Services\AttendanceProcessService;
use Illuminate\Http\Request;

class ProcessAttendanceController extends Controller
{
    public function __construct(protected AttendanceProcessService $service) {}

    public function index()
    {
        $stats = [
            'total_logs'  => FingerprintLog::count(),
            'unprocessed' => FingerprintLog::where('is_processed', false)->count(),
            'errors'      => FingerprintLog::whereNull('barcode')->count(),
            'ready'       => FingerprintLog::where('is_processed', true)->count(),
        ];

        $departments = Department::orderBy('name')->get();

        $preview = Attendance::with(['employee.department', 'shiftCode'])
            ->latest('attendance_date')
            ->limit(10)
            ->get();

        return view('process_attendances.index', compact('stats', 'departments', 'preview'));
    }


    public function process(Request $request)
    {
        $request->validate([
            'start_date'    => ['required', 'date'],
            'end_date'      => ['required', 'date', 'gte:start_date'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ]);

        $result  = ['processed' => 0, 'skipped' => 0, 'failed' => 0];
        $current = \Carbon\Carbon::parse($request->start_date);
        $end     = \Carbon\Carbon::parse($request->end_date);

        while ($current->lte($end)) {
            $daily = $this->service->processAll(
                $current->toDateString(),
                $request->department_id
            );
            $result['processed'] += $daily['processed'];
            $result['skipped']   += $daily['skipped'];
            $result['failed']    += $daily['failed'];
            $current->addDay();
        }

        return back()->with('process_result', $result);
    }
}