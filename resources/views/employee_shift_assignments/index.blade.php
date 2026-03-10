@extends('layouts.app')

@section('title', 'Assignment Shift')

@php $active = 'assignment-shift'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Assignment Shift</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Assign employees to specific shifts and schedules efficiently.</p>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        {{-- Bulk Assign --}}
        <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold ib-bg"
                style="font-size:13.5px;color:var(--text-2)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
            </svg>
            Bulk Assign
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
<div class="card rounded-2xl px-4 py-3 mb-5">
    <div class="flex flex-wrap items-center gap-3">

        {{-- Search --}}
        <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl flex-1"
             style="background:var(--bg-input);border:1px solid var(--border-in);min-width:200px">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <input type="text" id="assignSearch" placeholder="Search by employee name or ID..."
                   oninput="filterAssign()"
                   style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:inherit">
        </div>

        {{-- Department --}}
        <div class="relative flex-shrink-0">
            <select id="deptFilter" onchange="filterAssign()"
                    class="px-4 py-2.5 rounded-xl appearance-none pr-8 font-medium"
                    style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;outline:none;cursor:pointer;font-family:inherit">
                <option value="">All Departments</option>
                <option value="Engineering">Engineering</option>
                <option value="Operations">Operations</option>
                <option value="Customer Support">Customer Support</option>
                <option value="Human Resources">Human Resources</option>
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </div>

        {{-- Shift Group --}}
        <div class="relative flex-shrink-0">
            <select id="shiftFilter" onchange="filterAssign()"
                    class="px-4 py-2.5 rounded-xl appearance-none pr-8 font-medium"
                    style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;outline:none;cursor:pointer;font-family:inherit">
                <option value="">All Shift Groups</option>
                <option value="morning">Morning</option>
                <option value="afternoon">Afternoon</option>
                <option value="night">Night</option>
                <option value="unassigned">Unassigned</option>
            </select>
            <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </div>

        {{-- Reset --}}
        <button onclick="resetAssignFilters()"
                class="flex items-center gap-1.5 flex-shrink-0"
                style="font-size:13px;font-weight:600;color:#a78bfa;background:none;border:none;cursor:pointer">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="1 4 1 10 7 10"/>
                <path d="M3.51 15a9 9 0 102.13-9.36L1 10"/>
            </svg>
            Reset Filters
        </button>

    </div>
</div>

{{-- ── TABLE ── --}}
<div class="card rounded-2xl" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" id="assignTable" style="border-collapse:collapse;min-width:640px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Employee</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Department</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Current Shift</th>
                    <th class="text-left px-4 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Effective Date Range</th>
                    <th class="text-right px-5 py-3 font-semibold" style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Action</th>
                </tr>
            </thead>
            <tbody>

                {{-- Sarah Johnson - Morning --}}
                <tr class="assign-row" data-name="sarah johnson" data-id="sm-8842" data-dept="Engineering" data-shift="morning"
                    style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=1" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">Sarah Johnson</p>
                                <p style="font-size:11.5px;color:var(--text-3)">ID: SM-8842</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Engineering</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2 px-3 py-2 rounded-xl w-fit"
                             style="background:rgba(34,197,94,.15);border:1px solid rgba(34,197,94,.25)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2" class="flex-shrink-0">
                                <circle cx="12" cy="12" r="5"/>
                                <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                            </svg>
                            <span style="font-size:12.5px;font-weight:600;color:#4ade80;white-space:nowrap">Morning (08:00 - 17:00)</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Oct 01, 2023 - Dec 31,<br>2023</td>
                    <td class="px-5 py-4 text-right">
                        <button class="assign-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                                data-name="Sarah Johnson" data-id="SM-8842">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- Michael Chen - Afternoon --}}
                <tr class="assign-row" data-name="michael chen" data-id="sm-7123" data-dept="Operations" data-shift="afternoon"
                    style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=3" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">Michael Chen</p>
                                <p style="font-size:11.5px;color:var(--text-3)">ID: SM-7123</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Operations</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2 px-3 py-2 rounded-xl w-fit"
                             style="background:rgba(249,115,22,.15);border:1px solid rgba(249,115,22,.25)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="2" class="flex-shrink-0">
                                <circle cx="12" cy="12" r="5"/>
                                <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                                <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                            </svg>
                            <span style="font-size:12.5px;font-weight:600;color:#fdba74;white-space:nowrap">Afternoon (14:00 - 23:00)</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Sep 15, 2023 -<br>Permanent</td>
                    <td class="px-5 py-4 text-right">
                        <button class="assign-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                                data-name="Michael Chen" data-id="SM-7123">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- Alex Rivera - Night --}}
                <tr class="assign-row" data-name="alex rivera" data-id="sm-3390" data-dept="Customer Support" data-shift="night"
                    style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=9" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">Alex Rivera</p>
                                <p style="font-size:11.5px;color:var(--text-3)">ID: SM-3390</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Customer<br>Support</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2 px-3 py-2 rounded-xl w-fit"
                             style="background:rgba(99,102,241,.15);border:1px solid rgba(99,102,241,.25)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" class="flex-shrink-0">
                                <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                            </svg>
                            <span style="font-size:12.5px;font-weight:600;color:#a5b4fc;white-space:nowrap">Night (22:00 - 07:00)</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Oct 20, 2023 - Jan 20,<br>2024</td>
                    <td class="px-5 py-4 text-right">
                        <button class="assign-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                                data-name="Alex Rivera" data-id="SM-3390">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- Elena Rodriguez - Unassigned --}}
                <tr class="assign-row" data-name="elena rodriguez" data-id="sm-4412" data-dept="Human Resources" data-shift="unassigned"
                    style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=20" class="w-10 h-10 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">Elena Rodriguez</p>
                                <p style="font-size:11.5px;color:var(--text-3)">ID: SM-4412</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Human<br>Resources</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2 px-3 py-2 rounded-xl w-fit"
                             style="background:var(--bg-ghost);border:1px solid var(--border)">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span style="font-size:12.5px;font-weight:600;color:var(--text-3)">Unassigned</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:14px;color:var(--text-3)">--</td>
                    <td class="px-5 py-4 text-right">
                        <button class="assign-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                                data-name="Elena Rodriguez" data-id="SM-4412">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">
            Showing <strong style="color:var(--text-2)">1-4</strong> of
            <strong style="color:var(--text-2)">48</strong> employees
        </p>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button class="w-8 h-8 rounded-lg font-semibold purbtn" style="font-size:13px">1</button>
            <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">2</button>
            <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">3</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
            </button>
        </div>
    </div>
</div>

{{-- ── MODAL: NEW ASSIGNMENT ── --}}
<div class="mbk" id="mNewAssignment" onclick="closeOut(event,'mNewAssignment')">
    <div class="mbox" style="max-width:500px">
        <div class="mhdr">
            <span class="mtitle">New Assignment</span>
            <button class="mclose" onclick="closeM('mNewAssignment')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <div class="space-y-4">
                <div>
                    <label class="mlabel">Employee</label>
                    <select class="minput" style="cursor:pointer">
                        <option value="">-- Select Employee --</option>
                        <option>Sarah Johnson (SM-8842)</option>
                        <option>Michael Chen (SM-7123)</option>
                        <option>Alex Rivera (SM-3390)</option>
                        <option>Elena Rodriguez (SM-4412)</option>
                    </select>
                </div>
                <div>
                    <label class="mlabel">Department</label>
                    <select class="minput" style="cursor:pointer">
                        <option>Engineering</option>
                        <option>Operations</option>
                        <option>Customer Support</option>
                        <option>Human Resources</option>
                    </select>
                </div>
                <div>
                    <label class="mlabel">Shift</label>
                    <select class="minput" style="cursor:pointer">
                        <option>Morning (08:00 - 17:00)</option>
                        <option>Afternoon (14:00 - 23:00)</option>
                        <option>Night (22:00 - 07:00)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mlabel">Effective Date</label>
                        <input type="date" class="minput">
                    </div>
                    <div>
                        <label class="mlabel">End Date</label>
                        <div class="relative">
                            <input type="date" id="endDateInput" class="minput">
                            <div class="flex items-center gap-2 mt-2">
                                <input type="checkbox" id="permanentCheck" onchange="togglePermanent(this)" class="cursor-pointer">
                                <label for="permanentCheck" style="font-size:12px;color:var(--text-3);cursor:pointer">Permanent</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeM('mNewAssignment')" class="flex-1 py-2.5 rounded-xl font-medium"
                        style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                    Cancel
                </button>
                <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                    Save Assignment
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL: EDIT ASSIGNMENT ── --}}
<div class="mbk" id="mEditAssignment" onclick="closeOut(event,'mEditAssignment')">
    <div class="mbox" style="max-width:500px">
        <div class="mhdr">
            <span class="mtitle">Edit Assignment</span>
            <button class="mclose" onclick="closeM('mEditAssignment')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <div class="space-y-4">
                <div>
                    <label class="mlabel">Employee</label>
                    <input type="text" id="editAssignName" class="minput" readonly
                           style="opacity:.7;cursor:not-allowed">
                </div>
                <div>
                    <label class="mlabel">Shift</label>
                    <select id="editAssignShift" class="minput" style="cursor:pointer">
                        <option>Morning (08:00 - 17:00)</option>
                        <option>Afternoon (14:00 - 23:00)</option>
                        <option>Night (22:00 - 07:00)</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mlabel">Effective Date</label>
                        <input type="date" class="minput">
                    </div>
                    <div>
                        <label class="mlabel">End Date</label>
                        <input type="date" class="minput">
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeM('mEditAssignment')" class="flex-1 py-2.5 rounded-xl font-medium"
                        style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                    Cancel
                </button>
                <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

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

function togglePermanent(cb) {
    const input = document.getElementById('endDateInput');
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

    // Edit
    document.getElementById('assignEditBtn').addEventListener('click', () => {
        if (!_activeAssignTrigger) return;
        closeAssignDD();
        document.getElementById('editAssignName').value = _activeAssignTrigger.dataset.name;
        openM('mEditAssignment');
    });

    // Reassign
    document.getElementById('assignReassignBtn').addEventListener('click', () => {
        if (!_activeAssignTrigger) return;
        closeAssignDD();
        openM('mNewAssignment');
    });

    // Remove
    document.getElementById('assignRemoveBtn').addEventListener('click', () => {
        if (!_activeAssignTrigger) return;
        const name = _activeAssignTrigger.dataset.name;
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
                Swal.fire({
                    title: 'Removed!',
                    text: name + ' has been unassigned.',
                    icon: 'success',
                    background: '#1e1b2e',
                    color: '#e2e8f0',
                    confirmButtonColor: '#7c3aed',
                    timer: 2000,
                    timerProgressBar: true,
                });
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

function closeAssignDD() {
    document.getElementById('assignActDD')?.classList.remove('show');
}
</script>
@endpush