@extends('layouts.app')

@section('title', 'Assignment Shift')

@php $active = 'assignment-shift'; @endphp

@section('content')

{{-- feedback messages --}}
@if(session('import_success'))
    <div class="mb-4 text-green-500">Imported {{ session('import_success') }} rows successfully.</div>
@endif
@if(session('import_errors'))
    <div class="mb-4 text-red-500">
        <ul class="list-disc pl-5">
            @foreach(session('import_errors') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Assignment Shift</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Assign employees to specific shifts and schedules efficiently.</p>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        {{-- Import assignments --}}
        <button onclick="openM('mImportAssignments')"
                class="purbtn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold"
                style="font-size:13.5px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 5v14M5 12h14" stroke="currentColor"/>
                <path d="M4 4l16 16" stroke="currentColor" opacity="0"/><!-- invisible to keep size -->
            </svg>
            Import
        </button>
        {{-- New Assignment --}}
        <button onclick="openM('mNewAssignment')"
                class="purbtn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold"
                style="font-size:13.5px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            New Assignment
        </button>
    </div>
</div>

{{-- ── SEARCH + FILTER BAR ── --}}
<x-search-filter 
    searchId="assignSearch"
    searchPlaceholder="Search by employee name or ID..."
    :filters="[
        [
            'id' => 'department',
            'label' => 'All Departments',
            'options' => $departments->pluck('name', 'id')->toArray()
        ],
        [
            'id' => 'shift_code',
            'label' => 'All Shift Code',
            'options' => $shiftCodes->pluck('code', 'id')->toArray()
        ]
    ]" />

{{-- ── TABLE ── --}}
<div class="card rounded-2xl" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:700px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Karyawan</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Shift Code</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Tanggal</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Jam Masuk</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Jam Pulang</th>
                    <th class="text-right px-5 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    @php
                        $emp        = $assignment->employee;
                        $shiftCode  = $assignment->shiftCode;
                        $code       = $shiftCode->code ?? '-';
                        $shift      = $shiftCode->shift->name ?? '';
                        $isDayOff   = $shiftCode->is_day_off ?? ($code === 'Day Off');
                        $shiftLower = strtolower($shift);
                        [$badgeBg, $badgeBorder, $badgeColor] = match(true) {
                            $isDayOff                            => ['rgba(100,116,139,.15)', 'rgba(100,116,139,.2)',  '#94a3b8'],
                            str_contains($shiftLower, 'shift1') => ['rgba(34,197,94,.15)',   'rgba(34,197,94,.25)',   '#4ade80'],
                            str_contains($shiftLower, 'shift2') => ['rgba(249,115,22,.15)',  'rgba(249,115,22,.25)',  '#fdba74'],
                            str_contains($shiftLower, 'shift3') => ['rgba(99,102,241,.15)',  'rgba(99,102,241,.25)',  '#a5b4fc'],
                            default                              => ['rgba(124,58,237,.15)',  'rgba(124,58,237,.2)',   '#a78bfa'],
                        };
                    @endphp
                    <tr class="assign-row"
                        data-assignment-id="{{ $assignment->id }}"
                        data-employee-id="{{ $emp->id }}"
                        data-shift-code-id="{{ $assignment->shift_code_id }}"
                        data-date="{{ \Carbon\Carbon::parse($assignment->date)->format('Y-m-d') }}"
                        style="border-bottom:1px solid var(--border)">

                        {{-- Karyawan --}}
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                     style="background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:11px">
                                    {{ strtoupper(substr($emp->name, 0, 1)) }}{{ strtoupper(substr(strrchr($emp->name, ' ') ?: ' ', 1, 1)) }}
                                </div>
                                <div>
                                    <p style="font-size:13.5px;font-weight:700;color:var(--text-1)">{{ $emp->name }}</p>
                                    <p style="font-size:11px;color:var(--text-3)">{{ $emp->nik }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Shift Code --}}
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg w-fit"
                                 style="background:{{ $badgeBg }};border:1px solid {{ $badgeBorder }}">
                                <span style="font-size:12px;font-weight:700;color:{{ $badgeColor }};white-space:nowrap">
                                    {{ $code }}
                                </span>
                                @if($shift)
                                    <span style="font-size:11px;color:{{ $badgeColor }};opacity:.7">({{ $shift }})</span>
                                @endif
                            </div>
                        </td>

                        {{-- Tanggal --}}
                        <td class="px-4 py-3.5">
                            <p style="font-size:13px;color:var(--text-2)">
                                {{ \Carbon\Carbon::parse($assignment->date)->format('d M Y') }}
                            </p>
                            <p style="font-size:11px;color:var(--text-3)">
                                {{ \Carbon\Carbon::parse($assignment->date)->translatedFormat('l') }}
                            </p>
                        </td>

                        {{-- Jam Masuk — dari shift_codes --}}
                        <td class="px-4 py-3.5">
                            @if(!$isDayOff && $shiftCode?->on_time)
                                <span style="font-size:13px;font-weight:700;color:#22c55e;font-family:monospace">
                                    ↓ {{ \Carbon\Carbon::parse($shiftCode->on_time)->format('H:i') }}
                                </span>
                            @else
                                <span style="color:var(--text-3);font-size:12px">—</span>
                            @endif
                        </td>

                        {{-- Jam Pulang — dari shift_codes --}}
                        <td class="px-4 py-3.5">
                            @if(!$isDayOff && $shiftCode?->off_time)
                                <span style="font-size:13px;font-weight:700;color:#60a5fa;font-family:monospace">
                                    ↑ {{ \Carbon\Carbon::parse($shiftCode->off_time)->format('H:i') }}
                                </span>
                            @else
                                <span style="color:var(--text-3);font-size:12px">—</span>
                            @endif
                        </td>

                        {{-- Action --}}
                        <td class="px-5 py-3.5 text-right">
                            <button class="assign-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                                    data-name="{{ $emp->name }}">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 12px;opacity:.2;color:var(--text-3)">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <p style="font-size:14px;font-weight:600;color:var(--text-3)">Tidak ada data</p>
                            <p style="font-size:12px;color:var(--text-3);margin-top:4px;opacity:.6">Coba ubah filter atau import data shift dari HRD</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    @if($assignments->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">
            Showing
            <strong style="color:var(--text-2)">{{ $assignments->firstItem() }}–{{ $assignments->lastItem() }}</strong>
            of
            <strong style="color:var(--text-2)">{{ number_format($assignments->total()) }}</strong>
            records
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

            @foreach($assignments->getUrlRange(max(1, $assignments->currentPage()-2), min($assignments->lastPage(), $assignments->currentPage()+2)) as $page => $url)
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

@include('employee_shift_assignments.partials.create-modal')
@include('employee_shift_assignments.partials.edit-modal')
@include('employee_shift_assignments.partials.import-modal')

@if ($errors->has('file') || session('import_errors'))
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mImportAssignments'));
    </script>
@endif

@endsection

@push('styles')
<style>
.assign-row { transition: background .15s; }
.assign-row:hover { background: var(--bg-hover); }

#assignActDD {
    position: fixed;
    z-index: 9999;
    min-width: 160px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 5px;
    box-shadow: 0 10px 40px rgba(0,0,0,.5);
    display: none;
}
#assignActDD.show {
    display: block;
    animation: ddFade .14s ease;
}
@keyframes ddFade {
    from { opacity:0; transform:translateY(-5px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
.assign-dd-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: var(--text-2);
    background: transparent;
    border: none;
    cursor: pointer;
    text-align: left;
    transition: background .12s;
    font-family: inherit;
    white-space: nowrap;
}
.assign-dd-item:hover { background: var(--bg-hover); color: var(--text-1); }
.assign-dd-danger { color: #f87171 !important; }
.assign-dd-danger:hover { background: rgba(239,68,68,.12) !important; }
</style>
@endpush

@push('scripts')
    <script>
    // ── Filter ──
    function filterAssign() {
        const search = document.getElementById('assignSearch').value.toLowerCase();
        const dept   = document.getElementById('deptFilter').value;
        const shift  = document.getElementById('shiftFilter').value;

        document.querySelectorAll('.assign-row').forEach(row => {
            const matchSearch = !search || row.dataset.name.includes(search) || row.dataset.id.includes(search);
            const matchDept   = !dept   || row.dataset.dept  === dept;
            const matchShift  = !shift  || row.dataset.shift === shift;
            row.style.display = (matchSearch && matchDept && matchShift) ? '' : 'none';
        });
    }

    function resetAssignFilters() {
        document.getElementById('assignSearch').value = '';
        document.getElementById('deptFilter').value   = '';
        document.getElementById('shiftFilter').value  = '';
        filterAssign();
    }

    function togglePermanent(cb, targetId) {
        const inputId = targetId || 'endDateInput';
        const input = document.getElementById(inputId);
        if (!input) return;
        input.disabled = cb.checked;
        input.style.opacity = cb.checked ? '.4' : '1';
        if (cb.checked) input.value = '';
    }

    // ── Global Dropdown ──
    let _activeAssignTrigger = null;

    document.addEventListener('DOMContentLoaded', function () {
        const dd = document.createElement('div');
        dd.id = 'assignActDD';
        dd.innerHTML = `
            <button class="assign-dd-item" id="assignEditBtn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Edit Assignment
            </button>
            <button class="assign-dd-item" id="assignReassignBtn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="17 1 21 5 17 9"/>
                    <path d="M3 11V9a4 4 0 014-4h14M7 23l-4-4 4-4"/>
                    <path d="M21 13v2a4 4 0 01-4 4H3"/>
                </svg>
                Reassign Shift
            </button>
            <div style="height:1px;background:var(--border);margin:4px 8px"></div>
            <button class="assign-dd-item assign-dd-danger" id="assignRemoveBtn">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                </svg>
                Remove Assignment
            </button>
        `;
        document.body.appendChild(dd);

        // Bind triggers
        document.querySelectorAll('.assign-trigger').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                const same = _activeAssignTrigger === btn && dd.classList.contains('show');
                closeAssignDD();
                if (same) return;

                _activeAssignTrigger = btn;
                const rect    = btn.getBoundingClientRect();
                const ddWidth = 164;
                let left = rect.right - ddWidth;
                let top  = rect.bottom + 6;
                if (left < 8) left = 8;
                if (top + 140 > window.innerHeight) top = rect.top - 144;

                dd.style.left = left + 'px';
                dd.style.top  = top  + 'px';
                dd.classList.add('show');
            });
        });

        // auto-fill department when employee selected in new modal
        document.getElementById('newEmployee')?.addEventListener('change', e => {
            const dept = e.target.selectedOptions[0]?.dataset.dept || '';
            if (dept) document.getElementById('newDept').value = dept;
        });

        // Edit
        document.getElementById('assignEditBtn').addEventListener('click', () => {
            if (!_activeAssignTrigger) return;
            closeAssignDD();
            const row = _activeAssignTrigger.closest('.assign-row');
            const id = row.dataset.assignmentId;
            const name = _activeAssignTrigger.dataset.name;
            const form = document.getElementById('editAssignForm');
            const baseUrl = '{{ url('employee_shift_assignments') }}';
            form.action = `${baseUrl}/${id}`;
            document.getElementById('editAssignName').value = name;
            document.getElementById('editAssignShift').value = row.dataset.shiftCodeId || '';
            document.getElementById('editAssignDate').value = row.dataset.date || '';
            openM('mEditAssignment');
        });

        // Reassign
        document.getElementById('assignReassignBtn').addEventListener('click', () => {
            if (!_activeAssignTrigger) return;
            closeAssignDD();
            const row = _activeAssignTrigger.closest('.assign-row');
            const empId = row.dataset.employeeId;
            const dept = row.dataset.dept;
            const selEmp = document.getElementById('newEmployee');
            const selDept = document.getElementById('newDept');
            if (selEmp && empId) selEmp.value = empId;
            if (selDept && dept) selDept.value = dept;
            openM('mNewAssignment');
        });

        // Remove
        document.getElementById('assignRemoveBtn').addEventListener('click', () => {
            if (!_activeAssignTrigger) return;
            const row = _activeAssignTrigger.closest('.assign-row');
            const name = _activeAssignTrigger.dataset.name;
            const id   = row.dataset.assignmentId;
            closeAssignDD();
            Swal.fire({
                title: 'Remove Assignment?',
                text: name + "'s shift assignment will be removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#374151',
                confirmButtonText: 'Yes, Remove',
                cancelButtonText: 'Cancel',
                background: '#1e1b2e',
                color: '#e2e8f0',
            }).then(result => {
                if (result.isConfirmed) {
                    performDelete(id);
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#assignActDD') && !e.target.closest('.assign-trigger')) {
                closeAssignDD();
            }
        });
        window.addEventListener('scroll', closeAssignDD, true);
    });

    function performDelete(id) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const form = document.createElement('form');
        form.method = 'POST';
        const baseUrl = '{{ url('employee_shift_assignments') }}';
        form.action = `${baseUrl}/${id}`;
        form.style.display = 'none';
        form.innerHTML = `<input type="hidden" name="_token" value="${token}"><input type="hidden" name="_method" value="DELETE">`;
        document.body.appendChild(form);
        form.submit();
    }

    function closeAssignDD() {
        document.getElementById('assignActDD')?.classList.remove('show');
    }
    </script>
@endpush
