@extends('layouts.app')

@section('title', 'Assignment Shift')

@php $active = 'assignment-shift'; @endphp

@section('content')

{{-- Feedback --}}
@if(session('success'))
    <div class="mb-4 p-4 rounded-xl flex items-start gap-3" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" style="flex-shrink:0;margin-top:2px"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        <p style="font-size:13px;color:#22c55e;font-weight:500">{{ session('success') }}</p>
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 rounded-xl flex items-start gap-3" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3)">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2" style="flex-shrink:0;margin-top:2px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p style="font-size:13px;color:#ef4444;font-weight:500">{{ session('error') }}</p>
    </div>
@endif
@if(session('import_success'))
    <div class="mb-4 p-4 rounded-xl" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3)">
        <p style="font-size:13px;color:#22c55e;font-weight:500">Berhasil import {{ session('import_success') }} baris.</p>
    </div>
@endif
@if(session('import_errors'))
    <div class="mb-4 p-4 rounded-xl" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3)">
        <ul class="list-disc pl-5" style="font-size:12px;color:#ef4444">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- PAGE HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Assignment Shift</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Assign employees to specific shifts and schedules efficiently.</p>
        @php
            $startOfWeek = \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::MONDAY);
            $endOfWeek   = \Carbon\Carbon::now()->endOfWeek(\Carbon\Carbon::SUNDAY);
        @endphp
        <div style="margin-top:8px;padding:6px 12px;border-radius:8px;background:rgba(124,58,237,.1);border:1px solid rgba(124,58,237,.2);display:inline-flex;align-items:center;gap:8px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            <span style="font-size:12px;font-weight:600;color:#a78bfa">Minggu Ini: {{ $startOfWeek->format('d M') }} – {{ $endOfWeek->format('d M Y') }}</span>
        </div>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <button onclick="openM('mAssignShift')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold"
                style="font-size:13.5px;background:rgba(124,58,237,.2);color:#a78bfa;border:1px solid rgba(124,58,237,.3);cursor:pointer;transition:all .2s"
                onmouseover="this.style.background='rgba(124,58,237,.3)';this.style.transform='translateY(-2px)'"
                onmouseout="this.style.background='rgba(124,58,237,.2)';this.style.transform='translateY(0)'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            Assign
        </button>
        <button onclick="openM('mImportAssignments')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold"
                style="font-size:13.5px;background:rgba(20,184,166,.2);color:#14b8a6;border:1px solid rgba(20,184,166,.3);cursor:pointer;transition:all .2s"
                onmouseover="this.style.background='rgba(20,184,166,.3)';this.style.transform='translateY(-2px)'"
                onmouseout="this.style.background='rgba(20,184,166,.2)';this.style.transform='translateY(0)'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Import
        </button>
    </div>
</div>

{{-- SEARCH + FILTER --}}
<x-search-filter
    searchId="assignSearch"
    searchPlaceholder="Search by employee name or ID..."
    :filters="[
        ['id'=>'department','label'=>'All Departments','options'=>$departments->pluck('name','id')->toArray()],
        ['id'=>'shift_code','label'=>'All Shift Code','options'=>$shiftCodes->pluck('code','id')->toArray()]
    ]" />

{{-- TABLE --}}
<div class="card rounded-2xl" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:900px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Karyawan</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Shift Code</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">New Shift</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Periode</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Jam Masuk</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Jam Pulang</th>
                    <th class="text-center px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Hari</th>
                    <th class="text-center px-5 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $group)
                    @php
                        $emp     = $group->employee;
                        $minDate = \Carbon\Carbon::parse($group->min_date);
                        $maxDate = \Carbon\Carbon::parse($group->max_date);
                        $periode = $minDate->format('d') . '–' . $maxDate->format('d M Y');
                        $onTimes  = $group->on_times->filter()->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))->unique()->sort()->values();
                        $offTimes = $group->off_times->filter()->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))->unique()->sort()->values();
                    @endphp
                    <tr class="assign-row" style="border-bottom:1px solid var(--border)">

                        {{-- Karyawan --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                     style="background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:11px">
                                    {{ strtoupper(substr($emp->name,0,1)) }}{{ strtoupper(substr(strrchr($emp->name,' ') ?: ' ',1,1)) }}
                                </div>
                                <div>
                                    <p style="font-size:13.5px;font-weight:700;color:var(--text-1)">{{ $emp->name }}</p>
                                    <p style="font-size:11px;color:var(--text-3)">{{ $emp->nik }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Shift Codes (default) --}}
                        <td class="px-4 py-3.5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($group->shift_codes as $code)
                                    @php $isOff = strtolower($code) === 'day off'; @endphp
                                    <span class="px-2 py-0.5 rounded-md"
                                          style="font-size:11px;font-weight:700;white-space:nowrap;
                                                 background:{{ $isOff ? 'rgba(100,116,139,.15)' : 'rgba(124,58,237,.15)' }};
                                                 border:1px solid {{ $isOff ? 'rgba(100,116,139,.25)' : 'rgba(124,58,237,.25)' }};
                                                 color:{{ $isOff ? '#94a3b8' : '#a78bfa' }}">
                                        {{ $code }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        {{-- New Shifts --}}
                        <td class="px-4 py-3.5">
                            @if($group->new_shifts->isNotEmpty())
                                <div class="flex flex-wrap gap-1">
                                    @foreach($group->new_shifts as $ns)
                                        @php $nsOff = strtolower($ns) === 'day off'; @endphp
                                        <span class="px-2 py-0.5 rounded-md"
                                              style="font-size:11px;font-weight:700;white-space:nowrap;
                                                     background:{{ $nsOff ? 'rgba(100,116,139,.15)' : 'rgba(251,146,60,.15)' }};
                                                     border:1px solid {{ $nsOff ? 'rgba(100,116,139,.25)' : 'rgba(251,146,60,.3)' }};
                                                     color:{{ $nsOff ? '#94a3b8' : '#fb923c' }}">
                                            {{ $ns }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:var(--text-3);font-size:12px">—</span>
                            @endif
                        </td>

                        {{-- Periode --}}
                        <td class="px-4 py-3.5">
                            <p style="font-size:13px;font-weight:600;color:var(--text-2)">{{ $periode }}</p>
                            <p style="font-size:11px;color:var(--text-3)">{{ $minDate->translatedFormat('D') }} – {{ $maxDate->translatedFormat('D') }}</p>
                        </td>

                        {{-- Jam Masuk --}}
                        <td class="px-4 py-3.5">
                            @if($onTimes->isNotEmpty())
                                <div class="flex flex-col gap-0.5">
                                    @foreach($onTimes as $t)
                                        <span style="font-size:13px;font-weight:700;color:#22c55e;font-family:monospace">↓ {{ $t }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:var(--text-3);font-size:12px">—</span>
                            @endif
                        </td>

                        {{-- Jam Pulang --}}
                        <td class="px-4 py-3.5">
                            @if($offTimes->isNotEmpty())
                                <div class="flex flex-col gap-0.5">
                                    @foreach($offTimes as $t)
                                        <span style="font-size:13px;font-weight:700;color:#60a5fa;font-family:monospace">↑ {{ $t }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:var(--text-3);font-size:12px">—</span>
                            @endif
                        </td>

                        {{-- Total Hari --}}
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-lg font-bold"
                                  style="font-size:12px;background:rgba(124,58,237,.15);color:#a78bfa">
                                {{ $group->total_days }}d
                            </span>
                        </td>

                        {{-- Action --}}
                        <td class="px-5 py-3.5 text-center">
                            <button class="expand-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center mx-auto"
                                    data-target="detail-{{ $loop->index }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Detail rows per tanggal --}}
                    <tr id="detail-{{ $loop->index }}" style="display:none">
                        <td colspan="8" style="padding:0;background:var(--bg-ghost)">
                            <table class="w-full" style="border-collapse:collapse">
                                <thead>
                                    <tr style="border-bottom:1px solid var(--border)">
                                        <th class="text-left px-8 py-2 font-semibold" style="font-size:10px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase;width:180px">Tanggal</th>
                                        <th class="text-left px-4 py-2 font-semibold" style="font-size:10px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Shift Code</th>
                                        <th class="text-left px-4 py-2 font-semibold" style="font-size:10px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">New Shift</th>
                                        <th class="text-left px-4 py-2 font-semibold" style="font-size:10px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Jam Masuk</th>
                                        <th class="text-left px-4 py-2 font-semibold" style="font-size:10px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Jam Pulang</th>
                                        <th class="text-center px-4 py-2 font-semibold" style="font-size:10px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($group->rows->sortBy('date') as $row)
                                        @php
                                            $sc          = $row->shiftCode;
                                            $ns          = $row->newWorkingShift;
                                            $activeShift = $ns ?? $sc;
                                            $isOff       = $activeShift?->is_day_off ?? false;
                                            $scIsOff     = $sc?->is_day_off ?? false;
                                            $nsIsOff     = $ns?->is_day_off ?? false;
                                        @endphp
                                        <tr style="border-bottom:1px solid var(--border)">
                                            <td class="px-8 py-2.5">
                                                <p style="font-size:12.5px;font-weight:600;color:var(--text-2)">{{ \Carbon\Carbon::parse($row->date)->format('d M Y') }}</p>
                                                <p style="font-size:11px;color:var(--text-3)">{{ \Carbon\Carbon::parse($row->date)->translatedFormat('l') }}</p>
                                            </td>
                                            <td class="px-4 py-2.5">
                                                @if($sc)
                                                    <span class="px-2 py-0.5 rounded-md"
                                                          style="font-size:11px;font-weight:700;
                                                                 background:{{ $scIsOff ? 'rgba(100,116,139,.15)' : 'rgba(124,58,237,.15)' }};
                                                                 border:1px solid {{ $scIsOff ? 'rgba(100,116,139,.25)' : 'rgba(124,58,237,.25)' }};
                                                                 color:{{ $scIsOff ? '#94a3b8' : '#a78bfa' }}">
                                                        {{ $sc->code }}
                                                    </span>
                                                @else
                                                    <span style="color:var(--text-3);font-size:12px">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5">
                                                @if($ns)
                                                    <span class="px-2 py-0.5 rounded-md"
                                                          style="font-size:11px;font-weight:700;
                                                                 background:{{ $nsIsOff ? 'rgba(100,116,139,.15)' : 'rgba(251,146,60,.15)' }};
                                                                 border:1px solid {{ $nsIsOff ? 'rgba(100,116,139,.25)' : 'rgba(251,146,60,.3)' }};
                                                                 color:{{ $nsIsOff ? '#94a3b8' : '#fb923c' }}">
                                                        {{ $ns->code }}
                                                    </span>
                                                @else
                                                    <span style="color:var(--text-3);font-size:12px">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5">
                                                @if(!$isOff && $activeShift?->on_time)
                                                    <span style="font-size:12px;font-weight:700;color:#22c55e;font-family:monospace">↓ {{ \Carbon\Carbon::parse($activeShift->on_time)->format('H:i') }}</span>
                                                @else
                                                    <span style="color:var(--text-3);font-size:12px">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5">
                                                @if(!$isOff && $activeShift?->off_time)
                                                    <span style="font-size:12px;font-weight:700;color:#60a5fa;font-family:monospace">↑ {{ \Carbon\Carbon::parse($activeShift->off_time)->format('H:i') }}</span>
                                                @else
                                                    <span style="color:var(--text-3);font-size:12px">—</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-2.5 text-center">
                                                <button class="edit-row-btn ib-bg w-7 h-7 rounded-lg flex items-center justify-center mx-auto"
                                                        data-id="{{ $row->id }}"
                                                        data-employee-id="{{ $row->employee_id }}"
                                                        data-name="{{ $group->employee->name }}"
                                                        data-date="{{ \Carbon\Carbon::parse($row->date)->format('Y-m-d') }}"
                                                        data-shift-code-id="{{ $row->shift_code_id }}"
                                                        data-shift-code-text="{{ $sc?->code ?? '-' }}"
                                                        data-new-shift-id="{{ $row->new_working_shift_id }}"
                                                        title="Edit">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 12px;opacity:.2;color:var(--text-3)">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <p style="font-size:14px;font-weight:600;color:var(--text-3)">Tidak ada data minggu ini</p>
                            <p style="font-size:12px;color:var(--text-3);margin-top:4px;opacity:.6">Gunakan tombol Assign atau Import untuk menambahkan jadwal</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if($assignments->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4" style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">
            Showing <strong style="color:var(--text-2)">{{ $assignments->firstItem() }}–{{ $assignments->lastItem() }}</strong>
            of <strong style="color:var(--text-2)">{{ number_format($assignments->total()) }}</strong> employees
        </p>
        <div class="flex items-center gap-1">
            @if($assignments->onFirstPage())
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" disabled style="opacity:.4">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
            @else
                <a href="{{ $assignments->previousPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </a>
            @endif
            @foreach($assignments->getUrlRange(max(1,$assignments->currentPage()-2), min($assignments->lastPage(),$assignments->currentPage()+2)) as $page => $url)
                @if($page == $assignments->currentPage())
                    <button class="w-8 h-8 rounded-lg font-semibold purbtn" style="font-size:13px">{{ $page }}</button>
                @else
                    <a href="{{ $url }}" class="w-8 h-8 rounded-lg ib-bg flex items-center justify-center" style="font-size:13px;color:var(--text-2)">{{ $page }}</a>
                @endif
            @endforeach
            @if($assignments->hasMorePages())
                <a href="{{ $assignments->nextPageUrl() }}" class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
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

@include('employee_shift_assignments.partials.import-modal')
@include('employee_shift_assignments.partials.edit-modal')
@include('employee_shift_assignments.partials.assign-modal')

@if ($errors->has('file') || session('import_errors'))
    <script>document.addEventListener('DOMContentLoaded', () => openM('mImportAssignments'));</script>
@endif

@endsection

@push('styles')
<style>
.assign-row { transition: background .15s; }
.assign-row:hover { background: var(--bg-hover); }
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.expand-trigger').forEach(btn => {
    btn.addEventListener('click', function () {
        const detail = document.getElementById(this.dataset.target);
        const icon   = this.querySelector('svg polyline');
        const isOpen = detail.style.display !== 'none';
        detail.style.display = isOpen ? 'none' : 'table-row';
        icon.setAttribute('points', isOpen ? '6 9 12 15 18 9' : '6 15 12 9 18 15');
    });
});

document.querySelectorAll('.edit-row-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        const baseUrl = '{{ url("employee_shift_assignments") }}';
        document.getElementById('editAssignForm').action   = `${baseUrl}/${this.dataset.id}`;
        document.getElementById('editAssignName').value    = this.dataset.name;
        document.getElementById('editAssignEmployeeId').value = this.dataset.employeeId;
        document.getElementById('editAssignDate').value    = this.dataset.date;
        document.getElementById('editAssignShiftDisplay').value = this.dataset.shiftCodeText || '-';
        document.getElementById('editAssignNewShift').value = this.dataset.newShiftId || '';
        openM('mEditAssignment');
    });
});
</script>
@endpush
