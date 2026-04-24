@extends('layouts.app')

@section('title', 'Attendance')

@php $active = 'attendance'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Attendance List</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">
            Monitoring kehadiran —
            <span style="color:var(--text-2);font-weight:600">
                {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
            </span>
        </p>
    </div>
    <a href="{{ route('attendances.export-pdf', request()->only(['date', 'shift_code', 'department', 'status'])) }}"
       class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold flex-shrink-0"
       style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-2);font-size:13.5px;cursor:pointer;text-decoration:none;transition:background .15s"
       title="Download attendance data as PDF"
       onmouseover="this.style.background='rgba(124,58,237,.1)'" 
       onmouseout="this.style.background='var(--bg-card)'">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <path d="M3 9h18M9 21V9"/>
        </svg>
        Download PDF
    </a>
</div>

{{-- ── FILTER BAR ── --}}
<div class="card rounded-2xl p-4 mb-5">
    <form method="GET" action="{{ route('attendances.index') }}">
        {{-- Pertahankan search param saat filter lain diubah --}}
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">

        {{-- Tanggal --}}
        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:8px">Tanggal</label>
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                style="background:var(--bg-input);border:1px solid var(--border-in);cursor:pointer"
                onclick="this.querySelector('input').showPicker()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0" style="pointer-events:none">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <input type="date" name="date"
                    value="{{ request('date', today()->toDateString()) }}"
                    style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:inherit;cursor:pointer;color-scheme:dark">
            </div>
        </div>

        {{-- Shift --}}
        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:8px">Shift</label>
            <div class="custom-select-wrapper" data-name="shift_code">
                <input type="hidden" name="shift_code" value="{{ request('shift_code') }}">
                <button type="button" class="custom-select-btn w-full px-4 py-2.5 rounded-xl flex items-center justify-between"
                        style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;cursor:pointer;font-family:inherit">
                    <span class="custom-select-label">
                        {{ request('shift_code') ? $shiftCodes->firstWhere('id', request('shift_code'))?->code : 'All Shifts' }}
                    </span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option" data-value="">All Shifts</div>
                    @foreach($shiftCodes as $sc)
                        <div class="custom-select-option {{ request('shift_code') == $sc->id ? 'selected' : '' }}"
                            data-value="{{ $sc->id }}">
                            {{ $sc->code }}
                            @if(!$sc->is_day_off && $sc->on_time)
                                <span style="font-size:11px;opacity:.6;margin-left:4px">
                                    {{ \Carbon\Carbon::parse($sc->on_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($sc->off_time)->format('H:i') }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Department --}}
        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:8px">Department</label>
            <div class="custom-select-wrapper" data-name="department">
                <input type="hidden" name="department" value="{{ request('department') }}">
                <button type="button" class="custom-select-btn w-full px-4 py-2.5 rounded-xl flex items-center justify-between"
                        style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;cursor:pointer;font-family:inherit">
                    <span class="custom-select-label">
                        {{ request('department') ? $departments->firstWhere('id', request('department'))?->name : 'All Departments' }}
                    </span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="custom-select-dropdown" style="display:none">
                    <div class="custom-select-option" data-value="">All Departments</div>
                    @foreach($departments as $dept)
                        <div class="custom-select-option {{ request('department') == $dept->id ? 'selected' : '' }}"
                            data-value="{{ $dept->id }}">
                            {{ $dept->name }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Status + Apply --}}
        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:8px">Status</label>
            <div class="flex items-center gap-2">
                <div class="custom-select-wrapper flex-1" data-name="status">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <button type="button" class="custom-select-btn w-full px-4 py-2.5 rounded-xl flex items-center justify-between"
                            style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;cursor:pointer;font-family:inherit">
                        <span class="custom-select-label">
                            {{ match(request('status')) {
                                'present' => 'Hadir',
                                'late'    => 'Terlambat',
                                'absent'  => 'Tidak Hadir',
                                default   => 'All Statuses'
                            } }}
                        </span>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div class="custom-select-dropdown" style="display:none">
                        <div class="custom-select-option" data-value="">All Statuses</div>
                        <div class="custom-select-option {{ request('status') === 'present' ? 'selected' : '' }}" data-value="present">Hadir</div>
                        <div class="custom-select-option {{ request('status') === 'late'    ? 'selected' : '' }}" data-value="late">Terlambat</div>
                        <div class="custom-select-option {{ request('status') === 'absent'  ? 'selected' : '' }}" data-value="absent">Tidak Hadir</div>
                    </div>
                </div>
                <button type="submit" class="purbtn px-4 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:13px">
                    Filter
                </button>
            </div>
        </div>

        </div>

        @if(request()->hasAny(['shift_code', 'department', 'status']) || request('date') !== today()->toDateString())
        <div class="mt-3">
            <a href="{{ route('attendances.index') }}" style="font-size:12px;font-weight:600;color:#a78bfa;text-decoration:none">
                ↺ Reset Filter
            </a>
        </div>
        @endif

        @if(request('search'))
        <div class="mt-3 flex items-center gap-2">
            <span style="font-size:12px;color:var(--text-3)">Menampilkan hasil untuk:</span>
            <span style="font-size:12px;font-weight:700;color:#a78bfa;background:rgba(124,58,237,.15);padding:2px 10px;border-radius:20px">
                "{{ request('search') }}"
            </span>
            <a href="{{ route('attendances.index', request()->except('search')) }}"
               style="font-size:12px;color:#f87171;text-decoration:none">✕ Hapus</a>
        </div>
        @endif
    </form>
</div>

{{-- ── STAT CARDS ── --}}
@php $filterParams = request()->only(['date', 'shift_code', 'department']); @endphp

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">

    {{-- Hadir --}}
    <a href="{{ route('attendances.index', array_merge($filterParams, ['status' => 'present'])) }}"
       class="rounded-2xl p-5 block"
       style="background:rgba(34,197,94,.12);border:2px solid {{ request('status') === 'present' ? '#22c55e' : 'rgba(34,197,94,.2)' }};text-decoration:none;transition:transform .15s"
       onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(34,197,94,.25)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(34,197,94,.8);letter-spacing:.07em;text-transform:uppercase">Hadir</p>
            @if(request('status') === 'present')
                <span style="margin-left:auto;font-size:10px;background:rgba(34,197,94,.2);color:#22c55e;padding:2px 8px;border-radius:20px;white-space:nowrap">Aktif ✓</span>
            @endif
        </div>
        <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">{{ $stats['present_including_late'] }}</p>
        <p style="font-size:12px;color:#22c55e;margin-top:6px">{{ $stats['present'] }} hadir + {{ $stats['late'] }} terlambat</p>
    </a>

    {{-- Terlambat --}}
    <a href="{{ route('attendances.index', array_merge($filterParams, ['status' => 'late'])) }}"
       class="rounded-2xl p-5 block"
       style="background:rgba(251,146,60,.12);border:2px solid {{ request('status') === 'late' ? '#fb923c' : 'rgba(251,146,60,.2)' }};text-decoration:none;transition:transform .15s"
       onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(251,146,60,.25)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(251,146,60,.8);letter-spacing:.07em;text-transform:uppercase">Terlambat</p>
            @if(request('status') === 'late')
                <span style="margin-left:auto;font-size:10px;background:rgba(251,146,60,.2);color:#fb923c;padding:2px 8px;border-radius:20px;white-space:nowrap">Aktif ✓</span>
            @endif
        </div>
        <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">{{ $stats['late'] }}</p>
    </a>

    {{-- Tidak Hadir --}}
    <a href="{{ route('attendances.index', array_merge($filterParams, ['status' => 'absent'])) }}"
       class="rounded-2xl p-5 block"
       style="background:rgba(239,68,68,.12);border:2px solid {{ request('status') === 'absent' ? '#f87171' : 'rgba(239,68,68,.2)' }};text-decoration:none;transition:transform .15s"
       onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(239,68,68,.25)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(239,68,68,.8);letter-spacing:.07em;text-transform:uppercase">Tidak Hadir</p>
            @if(request('status') === 'absent')
                <span style="margin-left:auto;font-size:10px;background:rgba(239,68,68,.2);color:#f87171;padding:2px 8px;border-radius:20px;white-space:nowrap">Aktif ✓</span>
            @endif
        </div>
        <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">{{ $stats['absent'] }}</p>
        <p style="font-size:12px;color:#f87171;margin-top:6px">karyawan tidak hadir</p>
    </a>

    {{-- Total --}}
    <a href="{{ route('attendances.index', $filterParams) }}"
       class="rounded-2xl p-5 block"
       style="background:rgba(124,58,237,.15);border:2px solid {{ !request('status') ? '#7c3aed' : 'rgba(124,58,237,.25)' }};text-decoration:none;transition:transform .15s"
       onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background:rgba(124,58,237,.3)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(167,139,250,.8);letter-spacing:.07em;text-transform:uppercase">Total</p>
            @if(!request('status'))
                <span style="margin-left:auto;font-size:10px;background:rgba(124,58,237,.2);color:#a78bfa;padding:2px 8px;border-radius:20px;white-space:nowrap">Aktif ✓</span>
            @endif
        </div>
        <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">{{ $stats['total'] }}</p>
        <p style="font-size:12px;color:#a78bfa;margin-top:6px">total records hari ini</p>
    </a>

</div>

{{-- ── TABLE ── --}}
<div class="card rounded-2xl mb-5" style="overflow:hidden">

    @if(request('status'))
    <div class="px-5 py-3 flex items-center gap-2" style="border-bottom:1px solid var(--border);background:rgba(124,58,237,.05)">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
        </svg>
        <span style="font-size:12px;color:#a78bfa;font-weight:600">
            Filter status:
            {{ match(request('status')) {
                'present' => 'Hadir (' . $stats['present'] . ')',
                'late'    => 'Terlambat (' . $stats['late'] . ')',
                'absent'  => 'Tidak Hadir (' . $stats['absent'] . ')',
                default   => request('status'),
            } }}
        </span>
        <a href="{{ route('attendances.index', $filterParams) }}"
           style="font-size:11px;color:var(--text-3);margin-left:8px;text-decoration:none">✕ Hapus</a>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:720px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Karyawan</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Shift</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Jadwal</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Clock In</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Clock Out</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Status</th>
                    <th class="text-right px-5 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                    @php
                        $emp        = $att->employee;
                        $lateMin    = $att->late_minutes ?? 0;

                        // Gunakan new_working_shift jika tersedia, jika tidak gunakan shift_code
                        $shiftCode  = $att->newWorkingShift ?? $att->shiftCode;

                        $assignment = $emp->shiftAssignments->first();

                        [$statusBg, $statusColor, $statusLabel] = match($att->status) {
                            'present' => ['rgba(34,197,94,.18)',   '#22c55e', 'HADIR'],
                            'late'    => ['rgba(251,146,60,.18)',  '#fb923c', 'TERLAMBAT ' . $lateMin . 'M'],
                            'absent'  => ['rgba(239,68,68,.18)',   '#f87171', 'TIDAK HADIR'],
                            'day_off' => ['rgba(100,116,139,.18)', '#94a3b8', 'DAY OFF'],
                            'permit'  => ['rgba(96,165,250,.18)',  '#60a5fa', 'IZIN'],
                            'sick'    => ['rgba(167,139,250,.18)', '#a78bfa', 'SAKIT'],
                            default   => ['rgba(100,116,139,.18)', '#94a3b8', strtoupper($att->status)],
                        };
                    @endphp
                    <tr class="att-row" style="border-bottom:1px solid var(--border)">

                        {{-- Karyawan --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                     style="background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:11px">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}{{ strtoupper(substr(strrchr($emp->name, ' ') ?: ' ', 1, 1)) }}
                                </div>
                                <div>
                                    <p style="font-size:14px;font-weight:600;color:var(--text-1)">{{ $emp->name }}</p>
                                    <p style="font-size:12px;color:var(--text-3)">{{ $emp->department->name ?? '—' }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Shift — dari att->shiftCode (langsung) ✅ --}}
                        <td class="px-4 py-3.5">
                            @if($shiftCode)
                                <span style="font-size:12px;font-weight:700;color:#a78bfa">
                                    {{ $shiftCode->code }}
                                </span>
                                <p style="font-size:11px;color:var(--text-3)">
                                    {{ $shiftCode->shift->name ?? '' }}
                                </p>
                            @else
                                <span style="color:var(--text-3);font-size:12px">—</span>
                            @endif
                        </td>

                        {{-- Jadwal — dari shiftCode.on_time / off_time --}}
                        <td class="px-4 py-3.5">
                            @if($shiftCode && !$shiftCode->is_day_off && $shiftCode->on_time)
                                <span style="font-size:13px;font-family:monospace;color:var(--text-2)">
                                    {{ \Carbon\Carbon::parse($shiftCode->on_time)->format('H:i') }}
                                    –
                                    {{ \Carbon\Carbon::parse($shiftCode->off_time)->format('H:i') }}
                                </span>
                            @else
                                <span style="color:var(--text-3);font-size:12px">—</span>
                            @endif
                        </td>

                        {{-- Clock In --}}
                        <td class="px-4 py-3.5">
                            @if($att->clock_in)
                                <span style="font-size:13.5px;font-weight:600;color:{{ $lateMin > 0 ? '#fb923c' : 'var(--text-1)' }}">
                                    {{ $att->clock_in->format('H:i') }}
                                </span>
                                @if($lateMin > 0)
                                    <p style="font-size:11px;color:#fb923c">+{{ $lateMin }}m</p>
                                @endif
                            @else
                                <span style="font-size:13.5px;color:var(--text-3)">—</span>
                            @endif
                        </td>

                        {{-- Clock Out --}}
                        <td class="px-4 py-3.5">
                            @if($att->clock_out)
                                <span style="font-size:13.5px;color:var(--text-2)">
                                    {{ $att->clock_out->format('H:i') }}
                                </span>
                            @else
                                <span style="font-size:13.5px;color:var(--text-3)">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3.5">
                            <span class="px-3 py-1 rounded-full font-bold"
                                  style="font-size:10px;letter-spacing:.06em;background:{{ $statusBg }};color:{{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Action --}}
                        <td class="px-5 py-3.5 text-right">
                            <button class="att-trigger w-8 h-8 rounded-lg flex items-center justify-center ml-auto ib-bg"
                                    data-id="{{ $att->id }}"
                                    data-name="{{ $emp->name }}">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                 style="margin:0 auto 12px;opacity:.2;color:var(--text-3)">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <p style="font-size:14px;font-weight:600;color:var(--text-3)">Tidak ada data attendance</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($attendances->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">
            Showing
            <strong style="color:var(--text-2)">{{ $attendances->firstItem() }}–{{ $attendances->lastItem() }}</strong>
            of <strong style="color:var(--text-2)">{{ number_format($attendances->total()) }}</strong>
        </p>
        <div class="flex items-center gap-1">
            @if($attendances->onFirstPage())
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" disabled style="opacity:.4">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
            @else
                <a href="{{ $attendances->previousPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
            @endif

            @foreach($attendances->getUrlRange(max(1, $attendances->currentPage()-2), min($attendances->lastPage(), $attendances->currentPage()+2)) as $page => $url)
                @if($page == $attendances->currentPage())
                    <button class="w-8 h-8 rounded-lg font-semibold purbtn" style="font-size:13px">{{ $page }}</button>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 rounded-lg ib-bg flex items-center justify-center" style="font-size:13px;color:var(--text-2)">{{ $page }}</a>
                @endif
            @endforeach

            @if($attendances->hasMorePages())
                <a href="{{ $attendances->nextPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </a>
            @else
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" disabled style="opacity:.4">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection

@push('styles')
<style>
.att-row { transition: background .15s; }
.att-row:hover { background: var(--bg-hover); }
.custom-select-wrapper {
    position: relative;
}

.custom-select-btn {
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
}

.custom-select-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    z-index: 999;
    background: #1a1625;
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 5px;
    box-shadow: 0 10px 40px rgba(0,0,0,.5);
    max-height: 220px;
    overflow-y: auto;
}

.custom-select-option {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    color: #cbd5e1;
    cursor: pointer;
    transition: background .12s;
    font-family: inherit;
}

.custom-select-option:hover {
    background: rgba(124,58,237,.2);
    color: #e2e8f0;
}

.custom-select-option.selected {
    background: rgba(124,58,237,.25);
    color: #a78bfa;
    font-weight: 600;
}
</style>

@endpush

@push('scripts')
<script>
    // Custom Select
document.querySelectorAll('.custom-select-wrapper').forEach(wrapper => {
    const btn      = wrapper.querySelector('.custom-select-btn');
    const dropdown = wrapper.querySelector('.custom-select-dropdown');
    const input    = wrapper.querySelector('input[type=hidden]');
    const label    = wrapper.querySelector('.custom-select-label');

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        // Tutup semua dropdown lain
        document.querySelectorAll('.custom-select-dropdown').forEach(d => {
            if (d !== dropdown) d.style.display = 'none';
        });
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    });

    wrapper.querySelectorAll('.custom-select-option').forEach(opt => {
        opt.addEventListener('click', () => {
            // Update hidden input
            input.value = opt.dataset.value;

            // Update label
            label.textContent = opt.textContent.trim();

            // Update selected style
            wrapper.querySelectorAll('.custom-select-option').forEach(o => o.classList.remove('selected'));
            opt.classList.add('selected');

            // Tutup dropdown
            dropdown.style.display = 'none';
        });
    });
});

// Tutup semua dropdown kalau klik di luar
document.addEventListener('click', () => {
    document.querySelectorAll('.custom-select-dropdown').forEach(d => {
        d.style.display = 'none';
    });
});
</script>
@endpush