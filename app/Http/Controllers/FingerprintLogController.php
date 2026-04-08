<?php

namespace App\Http\Controllers;

use App\Models\FingerprintLog;
use App\Services\FingerprintSyncService;
use Illuminate\Http\Request;

class FingerprintLogController extends Controller
{
    public function index(Request $request)
    {
        $query = FingerprintLog::with('employee')
            ->orderByDesc('attendance_date')
            ->orderByDesc('attendance_time');

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('barcode')) {
            $query->where('barcode', 'like', '%' . $request->barcode . '%');
        }

        if ($request->filled('type')) {
            $query->where('attendance_type', $request->type);
        }

        if ($request->filled('processed')) {
            $query->where('is_processed', $request->processed);
        }

        $logs = $query->paginate(20)->withQueryString();

        $stats = [
            'total'       => FingerprintLog::count(),
            'unprocessed' => FingerprintLog::where('is_processed', false)->count(),
            'processed'   => FingerprintLog::where('is_processed', true)->count(),
            'today'       => FingerprintLog::whereDate('attendance_date', today())->count(),
        ];

        return view('fingerprint_logs.index', compact('logs', 'stats'));
    }

    public function sync(Request $request, FingerprintSyncService $service)
    {
        try {
            if ($request->filled('from') && $request->filled('to')) {
                $request->validate([
                    'from' => ['required', 'date'],
                    'to'   => ['required', 'date', 'gte:from'],
                ]);
                $result = $service->syncRange($request->from, $request->to);
                $msg = "Sync range {$request->from} s/d {$request->to} selesai: {$result['synced']} data baru, {$result['skipped']} duplikat, {$result['failed']} gagal.";
            } else {
                $date   = $request->input('date', now()->toDateString());
                $result = $service->sync($date);
                $msg = "Sync {$date} selesai: {$result['synced']} data baru, {$result['skipped']} duplikat, {$result['failed']} gagal.";
            }
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal sync: ' . $e->getMessage());
        }
    }

}