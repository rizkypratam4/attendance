<?php

namespace App\Console\Commands;

use App\Services\FingerprintSyncService;
use Illuminate\Console\Command;

class SyncFingerprintCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finger:sync
                            {--date= : Tanggal spesifik (format: Y-m-d), default hari ini}
                            {--from= : Tanggal mulai untuk sync range}
                            {--to=   : Tanggal selesai untuk sync range}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data fingerprint dari SQL Server ke MySQL';

    /**
     * Execute the console command.
     */
    public function handle(FingerprintSyncService $service): void
    {
        if ($this->option('from') && $this->option('to')) {
            $from = $this->option('from');
            $to   = $this->option('to');

            $this->info("Sync range: {$from} s/d {$to}");
            $result = $service->syncRange($from, $to);

        } else {
            // Sync satu tanggal
            $date = $this->option('date') ?? now()->toDateString();

            $this->info("Sync tanggal: {$date}");
            $result = $service->sync($date);
        }

        $this->table(
            ['Synced', 'Skipped', 'Failed'],
            [[$result['synced'], $result['skipped'], $result['failed']]]
        );
    }
}
