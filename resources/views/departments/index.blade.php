@extends('layouts.app')

@section('title', 'Master Data – Department')

@php $active = 'department'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Master Data</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Configure and manage organizational departments.</p>
    </div>
    <button onclick="openM('mAddDept')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="font-size:14px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/>
            <line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add Department
    </button>
</div>

{{-- ── TABLE CARD ── --}}
<div class="card rounded-2xl mb-5" style="overflow:hidden">

    {{-- Search + Filter bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4"
         style="border-bottom:1px solid var(--border)">

        {{-- Search --}}
        <div class="flex items-center gap-3 flex-1 rounded-xl px-4 py-2.5 min-w-0"
             style="background:var(--bg-input);border:1px solid var(--border-in)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Search departments..."
                   style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:14px;width:100%"
                   id="deptSearch" oninput="filterDepts()">
        </div>

        {{-- Right controls --}}
        <div class="flex items-center gap-3 flex-shrink-0">
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold"
                    style="background:#7c3aed;color:#fff;border:none;cursor:pointer;font-size:13px">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="4" y1="6" x2="20" y2="6"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                    <line x1="11" y1="18" x2="13" y2="18"/>
                </svg>
                Filters
            </button>
            <span style="font-size:13px;color:var(--text-3);white-space:nowrap">Showing 3 of 15 Departments</span>
            <button class="ib-bg w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/>
                    <line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:520px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-6 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Department Name
                    </th>
                    <th class="text-left px-6 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Head of Dept
                    </th>
                    <th class="text-left px-6 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Total Employees
                    </th>
                    <th class="text-right px-6 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody id="deptTableBody">

                {{-- Row 1: Engineering --}}
                <tr class="dept-row" style="border-bottom:1px solid var(--border)" data-name="engineering tech infrastructure">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            {{-- Icon --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(99,179,237,.15)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#63b3ed" stroke-width="2">
                                    <polyline points="16 18 22 12 16 6"/>
                                    <polyline points="8 6 2 12 8 18"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:15px;font-weight:700;color:var(--text-1)">Engineering</p>
                                <p style="font-size:12px;color:var(--text-3)">Tech &amp; Infrastructure</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=5"
                                 class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                                 alt="Jane Smith">
                            <span style="font-size:14px;font-weight:500;color:var(--text-1)">Jane Smith</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-4 py-1.5 rounded-full font-semibold"
                              style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">
                            45 Employees
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center"
                                    title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer"
                                    title="Delete">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Row 2: Human Resources --}}
                <tr class="dept-row" style="border-bottom:1px solid var(--border)" data-name="human resources people culture">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(72,187,120,.15)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#48bb78" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:15px;font-weight:700;color:var(--text-1)">Human Resources</p>
                                <p style="font-size:12px;color:var(--text-3)">People &amp; Culture</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=12"
                                 class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                                 alt="Michael Scott">
                            <span style="font-size:14px;font-weight:500;color:var(--text-1)">Michael Scott</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-4 py-1.5 rounded-full font-semibold"
                              style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">
                            12 Employees
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer"
                                    title="Delete">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Row 3: Marketing --}}
                <tr class="dept-row" data-name="marketing brand outreach">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(251,146,60,.15)">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:15px;font-weight:700;color:var(--text-1)">Marketing</p>
                                <p style="font-size:12px;color:var(--text-3)">Brand &amp; Outreach</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=9"
                                 class="w-9 h-9 rounded-full object-cover flex-shrink-0"
                                 alt="Sarah Connor">
                            <span style="font-size:14px;font-weight:500;color:var(--text-1)">Sarah Connor</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-4 py-1.5 rounded-full font-semibold"
                              style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">
                            28 Employees
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer"
                                    title="Delete">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">Showing 1 to 3 of 15 entries</p>
        <div class="flex items-center gap-1">
            {{-- Prev --}}
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            {{-- Pages --}}
            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-semibold purbtn"
                    style="font-size:13px">1</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-medium ib-bg"
                    style="font-size:13px;color:var(--text-2)">2</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-medium ib-bg"
                    style="font-size:13px;color:var(--text-2)">3</button>
            {{-- Next --}}
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- ── BOTTOM STAT CARDS ── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    {{-- Total Depts --}}
    <div class="rounded-2xl p-5 flex items-center gap-4"
         style="background:linear-gradient(135deg,rgba(124,58,237,.35) 0%,rgba(124,58,237,.15) 100%);border:1px solid rgba(124,58,237,.3)">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
             style="background:#7c3aed">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/>
                <rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/>
                <rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
        </div>
        <div>
            <p style="font-size:11px;font-weight:600;color:rgba(167,139,250,.8);letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Total Depts</p>
            <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">15</p>
        </div>
    </div>

    {{-- Active Heads --}}
    <div class="rounded-2xl p-5 flex items-center gap-4"
         style="background:linear-gradient(135deg,rgba(16,185,129,.25) 0%,rgba(16,185,129,.10) 100%);border:1px solid rgba(16,185,129,.25)">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
             style="background:#059669">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div>
            <p style="font-size:11px;font-weight:600;color:rgba(52,211,153,.8);letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Active Heads</p>
            <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">12</p>
        </div>
    </div>

    {{-- Open Roles --}}
    <div class="rounded-2xl p-5 flex items-center gap-4"
         style="background:linear-gradient(135deg,rgba(251,146,60,.25) 0%,rgba(251,146,60,.10) 100%);border:1px solid rgba(251,146,60,.25)">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
             style="background:#ea580c">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <line x1="19" y1="8" x2="19" y2="14"/>
                <line x1="22" y1="11" x2="16" y2="11"/>
            </svg>
        </div>
        <div>
            <p style="font-size:11px;font-weight:600;color:rgba(251,146,60,.8);letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Open Roles</p>
            <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">08</p>
        </div>
    </div>

</div>

{{-- ── MODAL: ADD DEPARTMENT ── --}}
<div class="mbk" id="mAddDept" onclick="closeOut(event,'mAddDept')">
    <div class="mbox" style="max-width:480px">
        <div class="mhdr">
            <span class="mtitle">Add New Department</span>
            <button class="mclose" onclick="closeM('mAddDept')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <div class="space-y-4">
                <div>
                    <label class="mlabel">Department Name</label>
                    <input type="text" placeholder="e.g. Engineering" class="minput">
                </div>
                <div>
                    <label class="mlabel">Subtitle / Category</label>
                    <input type="text" placeholder="e.g. Tech & Infrastructure" class="minput">
                </div>
                <div>
                    <label class="mlabel">Head of Department</label>
                    <input type="text" placeholder="Full name" class="minput">
                </div>
                <div>
                    <label class="mlabel">Total Employees</label>
                    <input type="number" placeholder="0" class="minput">
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeM('mAddDept')"
                        class="flex-1 py-2.5 rounded-xl font-medium"
                        style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                    Cancel
                </button>
                <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                    Save Department
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.dept-row { transition: background .15s; }
.dept-row:hover { background: var(--bg-hover); }
.dept-row.hidden-row { display: none; }
</style>
@endpush

@push('scripts')
<script>
function filterDepts() {
    const q = document.getElementById('deptSearch').value.toLowerCase();
    document.querySelectorAll('.dept-row').forEach(row => {
        const name = row.getAttribute('data-name') || '';
        row.classList.toggle('hidden-row', q.length > 0 && !name.includes(q));
    });
}
</script>
@endpush