@extends('layouts.app')

@section('title', 'Shift Groups')

@php $active = 'shift-groups'; @endphp

@section('content')

<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Shift Groups</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Manage and organize your organizational work shift groups efficiently.</p>
    </div>
    <button onclick="openM('mAddGroup')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="font-size:14px">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add Shift Group
    </button>
</div>

<div class="card rounded-2xl p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="flex items-center gap-3 flex-1 px-4 py-2.5 rounded-xl"
             style="background:var(--bg-input);border:1px solid var(--border-in)">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" id="sgSearch" placeholder="Search shift groups by name or description..."
                   oninput="filterGroups()"
                   style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit">
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium ib-bg"
                    style="font-size:13.5px;color:var(--text-2)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
                </svg>
                Filter
            </button>
            <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium ib-bg"
                    style="font-size:13.5px;color:var(--text-2)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                    <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
                </svg>
                Export
            </button>
        </div>
    </div>
</div>

<div class="card rounded-2xl mb-5" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:580px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Shift Group Name</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Description</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Members</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Last Modified</th>
                    <th class="text-right px-5 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="sgTableBody">
                <tr class="sg-row" style="border-bottom:1px solid var(--border)" data-name="security team 24/7 rotating shifts">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(96,165,250,.15)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            </div>
                            <span style="font-size:14px;font-weight:700;color:var(--text-1)">Security Team</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2);max-width:260px">24/7 rotating shifts for site safety and surveillance.</td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold" style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">24</span>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Oct 12,<br>2023</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="sg-row" style="border-bottom:1px solid var(--border)" data-name="office staff standard 9-5 corporate">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(52,211,153,.15)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                </svg>
                            </div>
                            <span style="font-size:14px;font-weight:700;color:var(--text-1)">Office Staff</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2);max-width:260px">Standard 9-5 corporate schedule for administrative departments.</td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold" style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">156</span>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Sep 28,<br>2023</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="sg-row" style="border-bottom:1px solid var(--border)" data-name="production floor three-shift factory">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(251,146,60,.15)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                                </svg>
                            </div>
                            <span style="font-size:14px;font-weight:700;color:var(--text-1)">Production Floor</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2);max-width:260px">Three-shift system for continuous factory floor operations.</td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold" style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">412</span>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Oct 05,<br>2023</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr class="sg-row" data-name="maintenance technical staff equipment upkeep">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(167,139,250,.15)">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                                    <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"/>
                                </svg>
                            </div>
                            <span style="font-size:14px;font-weight:700;color:var(--text-1)">Maintenance</span>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2);max-width:260px">Technical staff responsible for equipment upkeep and repairs.</td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold" style="font-size:12px;background:rgba(124,58,237,.25);color:#c4b5fd">42</span>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">Oct 01,<br>2023</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">Showing 1 to 4 of 12 groups</p>
        <div class="flex items-center gap-1">
            <button class="px-3 h-8 rounded-lg ib-bg font-medium" style="font-size:13px;color:var(--text-3)">Previous</button>
            <button class="w-8 h-8 rounded-lg font-semibold purbtn" style="font-size:13px">1</button>
            <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">2</button>
            <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">3</button>
            <button class="px-3 h-8 rounded-lg ib-bg font-medium" style="font-size:13px;color:var(--text-2)">Next</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="card rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.18)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Total Members</p>
        </div>
        <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">634</p>
        <p style="font-size:12px;color:#22c55e;margin-top:6px;font-weight:500">+12 this month</p>
    </div>

    <div class="card rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.18)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Active Shifts</p>
        </div>
        <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">12</p>
        <p style="font-size:12px;color:var(--text-3);margin-top:6px">Across 4 groups</p>
    </div>

    <div class="card rounded-2xl p-5">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(34,197,94,.15)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Roster Health</p>
        </div>
        <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">98.2%</p>
        <p style="font-size:12px;color:#22c55e;margin-top:6px;font-weight:500">Optimized</p>
    </div>

</div>

<div class="mbk" id="mAddGroup" onclick="closeOut(event,'mAddGroup')">
    <div class="mbox" style="max-width:460px">
        <div class="mhdr">
            <span class="mtitle">Add Shift Group</span>
            <button class="mclose" onclick="closeM('mAddGroup')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <div class="space-y-4">
                <div><label class="mlabel">Group Name</label><input type="text" placeholder="e.g. Security Team" class="minput"></div>
                <div><label class="mlabel">Description</label><textarea placeholder="Brief description..." class="minput" rows="3" style="resize:none"></textarea></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeM('mAddGroup')" class="flex-1 py-2.5 rounded-xl font-medium"
                        style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">Cancel</button>
                <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">Save Group</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.sg-row { transition: background .15s; }
.sg-row:hover { background: var(--bg-hover); }
.sg-row.hidden-row { display: none; }
</style>
@endpush

@push('scripts')
<script>
function filterGroups() {
    const q = document.getElementById('sgSearch').value.toLowerCase();
    document.querySelectorAll('.sg-row').forEach(row => {
        const d = row.getAttribute('data-name') || '';
        row.classList.toggle('hidden-row', q.length > 0 && !d.includes(q));
    });
}
</script>
@endpush