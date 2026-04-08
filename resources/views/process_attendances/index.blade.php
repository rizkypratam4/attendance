@extends('layouts.app')

@section('title', 'Process Attendance')

@php $active = 'process-attendance'; @endphp

@section('content')

<div class="mb-6">
    <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Process Attendance</h1>
    <p style="font-size:13px;color:var(--text-3);margin-top:5px">Transform raw fingerprint logs into verified attendance records.</p>
</div>

{{-- ── RESULT FLASH ── --}}
@if(session('process_result'))
    @php $res = session('process_result'); @endphp
    <div class="mb-5 px-4 py-3 rounded-xl flex items-center gap-3"
         style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.25);color:#22c55e;font-size:13px">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        Selesai! <strong>{{ $res['processed'] }}</strong> diproses,
        <strong>{{ $res['skipped'] }}</strong> dilewati,
        <strong style="color:{{ $res['failed'] > 0 ? '#f87171' : '#22c55e' }}">{{ $res['failed'] }}</strong> gagal.
    </div>
@endif

{{-- ── FORM PROCESS ── --}}
<div class="card rounded-2xl p-5 mb-6">
    <form method="POST" action="{{ route('process_attendances.process') }}" id="processForm">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

            {{-- Start Date --}}
            <div>
                <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Start Date</label>
                <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                     style="background:var(--bg-input);border:1px solid var(--border-in)">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <input type="date" name="start_date"
                           value="{{ old('start_date', today()->toDateString()) }}"
                           style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit;cursor:pointer">
                </div>
                @error('start_date')
                    <p style="font-size:11px;color:#f87171;margin-top:4px">{{ $message }}</p>
                @enderror
            </div>

            {{-- End Date --}}
            <div>
                <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">End Date</label>
                <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                     style="background:var(--bg-input);border:1px solid var(--border-in)">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <input type="date" name="end_date"
                           value="{{ old('end_date', today()->toDateString()) }}"
                           style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit;cursor:pointer">
                </div>
                @error('end_date')
                    <p style="font-size:11px;color:#f87171;margin-top:4px">{{ $message }}</p>
                @enderror
            </div>

            {{-- Department (info only) --}}
            <div>
                <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Department</label>
                <div class="relative">
                    <select class="w-full px-4 py-2.5 rounded-xl appearance-none"
                            style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13.5px;cursor:pointer;outline:none;font-family:inherit"
                            disabled>
                        <option>All Departments</option>
                        @foreach($departments as $dept)
                            <option>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </div> 
            </div>

            {{-- Submit --}}
            <div>
                <button type="submit" id="processBtn"
                        class="w-full purbtn flex items-center justify-center gap-2 py-2.5 rounded-xl font-semibold"
                        style="font-size:14px">
                    <svg id="processIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="5 3 19 12 5 21 5 3"/>
                    </svg>
                    <span id="processLabel">Process</span>
                </button>
            </div>

        </div>
    </form>
</div>

{{-- ── STATS + ENGINE ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Engine Panel --}}
    <div class="lg:col-span-2 card rounded-2xl p-5">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2.5">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                </svg>
                <p style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.08em;text-transform:uppercase">Calculation Engine</p>
            </div>
            <span id="engineStatus" style="font-size:12.5px;font-weight:600;color:var(--text-3)">Idle</span>
        </div>

        <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <p style="font-size:13px;color:var(--text-2)">Batch processing status</p>
                <p id="progressPct" style="font-size:13px;font-weight:700;color:#a78bfa">0%</p>
            </div>
            <div class="h-2.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
                <div id="progressBar" class="h-full rounded-full"
                     style="width:0%;background:linear-gradient(90deg,#7c3aed,#a78bfa);transition:width .4s ease"></div>
            </div>
        </div>

        <div>
            <p style="font-size:11.5px;font-weight:600;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase;margin-bottom:10px">Action Log</p>
            <div class="space-y-3" id="actionLog">
                @if(session('process_result'))
                    @php $res = session('process_result'); @endphp
                    <div class="flex items-start gap-2.5">
                        <svg class="flex-shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <div>
                            <p style="font-size:13px;color:var(--text-2)">Proses selesai — {{ $res['processed'] }} records berhasil diproses.</p>
                            <p style="font-size:11.5px;color:var(--text-3);margin-top:2px">{{ now()->format('H:i:s') }}</p>
                        </div>
                    </div>
                    @if($res['failed'] > 0)
                    <div class="flex items-start gap-2.5">
                        <svg class="flex-shrink-0 mt-0.5" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <div>
                            <p style="font-size:13px;color:var(--text-2)">{{ $res['failed'] }} records gagal diproses. Cek log untuk detail.</p>
                            <p style="font-size:11.5px;color:var(--text-3);margin-top:2px">{{ now()->format('H:i:s') }}</p>
                        </div>
                    </div>
                    @endif
                @else
                    <p style="font-size:13px;color:var(--text-3)">Belum ada proses yang dijalankan. Pilih tanggal dan klik Process.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="flex flex-col gap-4">
        <div class="card rounded-2xl p-5 flex-1">
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Total Fingerprint Logs</p>
            <p style="font-size:38px;font-weight:800;color:var(--text-1);line-height:1">{{ number_format($stats['total_logs']) }}</p>
            <p style="font-size:12px;color:var(--text-3);margin-top:6px">{{ number_format($stats['unprocessed']) }} belum diproses</p>
        </div>

        <div class="card rounded-2xl p-5 flex-1">
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Flagged / Error</p>
            <p style="font-size:38px;font-weight:800;color:#f87171;line-height:1">{{ number_format($stats['errors']) }}</p>
            <p style="font-size:12px;color:var(--text-3);margin-top:6px">barcode tidak dikenal</p>
        </div>

        <div class="card rounded-2xl p-5 flex-1">
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">Sudah Diproses</p>
            <p style="font-size:38px;font-weight:800;color:#22c55e;line-height:1">{{ number_format($stats['ready']) }}</p>
            <p style="font-size:12px;color:var(--text-3);margin-top:6px">dari {{ number_format($stats['total_logs']) }} total logs</p>
        </div>
    </div>

</div>

{{-- ── RESULTS PREVIEW ── --}}
<div class="card rounded-2xl" style="overflow:hidden">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-bottom:1px solid var(--border)">
        <p style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.08em;text-transform:uppercase">
            Results Preview
            <span style="font-weight:400;color:var(--text-3)">(10 terbaru)</span>
        </p>
        <a href="{{ route('attendances.index') }}"
           class="purbtn px-4 py-2 rounded-xl font-semibold flex items-center gap-2"
           style="font-size:13px;text-decoration:none">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:560px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Karyawan</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Department</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Shift</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Tanggal</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Clock In</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($preview as $att)
                    @php
                        [$statusBg, $statusColor, $statusLabel] = match($att->status) {
                            'present' => ['rgba(34,197,94,.15)',   '#22c55e', 'HADIR'],
                            'late'    => ['rgba(251,146,60,.15)',  '#fb923c', 'TERLAMBAT'],
                            'absent'  => ['rgba(239,68,68,.15)',   '#f87171', 'TIDAK HADIR'],
                            'day_off' => ['rgba(100,116,139,.15)', '#94a3b8', 'DAY OFF'],
                            'permit'  => ['rgba(96,165,250,.15)',  '#60a5fa', 'IZIN'],
                            'sick'    => ['rgba(167,139,250,.15)', '#a78bfa', 'SAKIT'],
                            default   => ['rgba(100,116,139,.15)', '#94a3b8', strtoupper($att->status)],
                        };
                    @endphp
                    <tr class="proc-row" style="border-bottom:1px solid var(--border)">

                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                     style="background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:11px">
                                    {{ strtoupper(substr($att->employee->name, 0, 1)) }}{{ strtoupper(substr(strrchr($att->employee->name, ' ') ?: ' ', 1, 1)) }}
                                </div>
                                <div>
                                    <p style="font-size:13.5px;font-weight:600;color:var(--text-1)">{{ $att->employee->name }}</p>
                                    <p style="font-size:11.5px;color:var(--text-3)">{{ $att->employee->nik }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">
                            {{ $att->employee->department->name ?? '—' }}
                        </td>

                        <td class="px-4 py-3.5">
                            <span style="font-size:12px;font-weight:700;color:#a78bfa">
                                {{ $att->shiftCode->code ?? '—' }}
                            </span>
                        </td>

                        <td class="px-4 py-3.5" style="font-size:13px;color:var(--text-2)">
                            {{ $att->attendance_date->format('d M Y') }}
                        </td>

                        <td class="px-4 py-3.5" style="font-size:13.5px;font-weight:600;color:var(--text-1)">
                            {{ $att->clock_in?->format('H:i') ?? '—' }}
                        </td>

                        <td class="px-4 py-3.5">
                            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                                  style="font-size:10.5px;background:{{ $statusBg }};color:{{ $statusColor }};letter-spacing:.05em">
                                <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:{{ $statusColor }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <p style="font-size:14px;font-weight:600;color:var(--text-3)">Belum ada data attendance</p>
                            <p style="font-size:12px;color:var(--text-3);margin-top:4px;opacity:.6">Pilih tanggal dan klik Process untuk mulai</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-between px-5 py-4" style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">Menampilkan 10 records terbaru</p>
        <a href="{{ route('attendances.index') }}"
           class="purbtn px-5 py-2 rounded-xl font-semibold"
           style="font-size:13.5px;text-decoration:none">
            Lihat Semua Records →
        </a>
    </div>

</div>

@endsection

@push('styles')
<style>
.proc-row { transition: background .15s; }
.proc-row:hover { background: var(--bg-hover); }
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.spin { animation: spin 1s linear infinite; transform-origin: center; }
</style>
@endpush

@push('scripts')
<script>
document.getElementById('processForm').addEventListener('submit', function () {
    const btn   = document.getElementById('processBtn');
    const icon  = document.getElementById('processIcon');
    const label = document.getElementById('processLabel');
    const bar   = document.getElementById('progressBar');
    const pct   = document.getElementById('progressPct');
    const status = document.getElementById('engineStatus');

    btn.disabled = true;
    icon.classList.add('spin');
    label.textContent = 'Processing...';
    status.textContent = 'Processing...';
    status.style.color = '#fb923c';

    // Simulasi progress bar
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress >= 95) { progress = 95; clearInterval(interval); }
        bar.style.width = progress.toFixed(0) + '%';
        pct.textContent = progress.toFixed(0) + '%';
    }, 400);
});

// Jika ada result, tampilkan progress 100%
@if(session('process_result'))
    document.getElementById('progressBar').style.width = '100%';
    document.getElementById('progressPct').textContent = '100%';
    document.getElementById('engineStatus').textContent = 'Completed';
    document.getElementById('engineStatus').style.color = '#22c55e';
@endif
</script>
@endpush