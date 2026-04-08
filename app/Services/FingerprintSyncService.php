<?php

namespace App\Services;

use App\Models\FingerprintLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FingerprintSyncService
{
    public function sync(?string $date = null): array
    {
        $date   = $date ?? now()->toDateString();
        $result = ['synced' => 0, 'skipped' => 0, 'failed' => 0];

        try {
            $logs = DB::connection('sqlsrv_finger')
                ->table('dbo.AttendanceMachinePolling')
                ->whereDate('AttendanceDate', $date)
                ->orderBy('AttendanceDate')
                ->orderBy('AttendanceTime')
                ->get();

            foreach ($logs as $log) {
                try {
                    $attendanceType = $this->mapAttendanceType($log->AttendanceType);

                    $inserted = FingerprintLog::insertOrIgnore([
                        'barcode'          => $log->Barcode,
                        'attendance_date'  => \Carbon\Carbon::parse($log->AttendanceDate)->toDateString(),
                        'attendance_time'  => $log->AttendanceTime,
                        'attendance_type'  => $attendanceType,
                        'is_processed'     => false,
                        'raw_created_date' => $log->CreatedDate,
                        'created_at'       => now(),
                    ]);

                    $inserted ? $result['synced']++ : $result['skipped']++;

                } catch (\Exception $e) {
                    $result['failed']++;
                    Log::error('FingerprintSync: gagal insert log', [
                        'barcode' => $log->Barcode,
                        'date'    => $log->AttendanceDate,
                        'time'    => $log->AttendanceTime,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('FingerprintSync: gagal koneksi ke SQL Server', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }

        Log::info('FingerprintSync: selesai', array_merge($result, ['date' => $date]));

        return $result;
    }

    private function mapAttendanceType(mixed $type): int
    {
        if (is_numeric($type)) {
            return (int) $type;
        }

        return match (strtolower(trim($type))) {
            'check in', 'in', 'masuk', '0' => FingerprintLog::TYPE_CLOCK_IN,
            default                         => FingerprintLog::TYPE_CLOCK_OUT,
        };
    }

    public function syncRange(string $startDate, string $endDate): array
    {
        $result  = ['synced' => 0, 'skipped' => 0, 'failed' => 0];
        $current = \Carbon\Carbon::parse($startDate);
        $end     = \Carbon\Carbon::parse($endDate);

        while ($current->lte($end)) {
            $daily = $this->sync($current->toDateString());

            $result['synced']  += $daily['synced'];
            $result['skipped'] += $daily['skipped'];
            $result['failed']  += $daily['failed'];

            $current->addDay();
        }

        return $result;
    }
}
