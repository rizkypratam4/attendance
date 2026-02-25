@extends('layouts.app')

@section('title', 'Attendance Logs')

@php $active = 'attendance'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Attendance List</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Manage and monitor employee punctuality records across departments.</p>
    </div>
    <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-2);font-size:13.5px;cursor:pointer">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <path d="M3 9h18M9 21V9"/>
        </svg>
        Download PDF
    </button>
</div>

<div class="card rounded-2xl p-4 mb-5">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:8px">Date Range</label>
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                 style="background:var(--bg-input);border:1px solid var(--border-in);cursor:pointer">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span style="font-size:13.5px;color:var(--text-2);white-space:nowrap">Last 7 Days (Oct 24 - Oct 30)</span>
            </div>
        </div>

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:8px">Department</label>
            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl"
                 style="background:var(--bg-input);border:1px solid var(--border-in)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                </svg>
                <select style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;cursor:pointer;width:100%;font-family:inherit">
                    <option>All Departments</option>
                    <option>Engineering</option>
                    <option>Marketing</option>
                    <option>Sales</option>
                    <option>Design</option>
                </select>
            </div>
        </div>

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:8px">Status</label>
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl flex-1"
                     style="background:var(--bg-input);border:1px solid var(--border-in)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                        <line x1="4" y1="6" x2="20" y2="6"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                        <line x1="11" y1="18" x2="13" y2="18"/>
                    </svg>
                    <select style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;cursor:pointer;width:100%;font-family:inherit">
                        <option>All Statuses</option>
                        <option>On Time</option>
                        <option>Late</option>
                        <option>Absent</option>
                    </select>
                </div>
                <button class="purbtn px-4 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:13.5px;white-space:nowrap">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card rounded-2xl mb-5" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:640px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Employee</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Date</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Clock In</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Clock Out</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Status</th>
                    <th class="text-right px-5 py-3.5 font-semibold" style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr class="att-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=11" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">Felix Henderson</p>
                                <p style="font-size:12px;color:var(--text-3)">Engineering</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Oct 30, 2023</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-1);font-weight:500">08:52 AM</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">05:15 PM</td>
                    <td class="px-4 py-3.5">
                        <span class="px-3 py-1 rounded-full font-bold" style="font-size:10px;letter-spacing:.06em;background:rgba(34,197,94,.18);color:#22c55e">ON TIME</span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib w-8 h-8 rounded-lg flex items-center justify-center ml-auto ib-bg">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- Sarah Jenkins - LATE 18M --}}
                <tr class="att-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=1" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">Sarah Jenkins</p>
                                <p style="font-size:12px;color:var(--text-3)">Marketing</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Oct 30, 2023</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:#fb923c;font-weight:600">09:18 AM</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">06:05 PM</td>
                    <td class="px-4 py-3.5">
                        <span class="px-3 py-1 rounded-full font-bold" style="font-size:10px;letter-spacing:.06em;background:rgba(251,146,60,.18);color:#fb923c">LATE (18M)</span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib w-8 h-8 rounded-lg flex items-center justify-center ml-auto ib-bg">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- David Miller - ABSENT --}}
                <tr class="att-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=7" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">David Miller</p>
                                <p style="font-size:12px;color:var(--text-3)">Sales</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Oct 30, 2023</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-3)">-</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-3)">-</td>
                    <td class="px-4 py-3.5">
                        <span class="px-3 py-1 rounded-full font-bold" style="font-size:10px;letter-spacing:.06em;background:rgba(239,68,68,.18);color:#f87171">ABSENT</span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib w-8 h-8 rounded-lg flex items-center justify-center ml-auto ib-bg">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- Emily Zhang - ON TIME --}}
                <tr class="att-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=20" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">Emily Zhang</p>
                                <p style="font-size:12px;color:var(--text-3)">Design</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Oct 30, 2023</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-1);font-weight:500">08:45 AM</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">05:30 PM</td>
                    <td class="px-4 py-3.5">
                        <span class="px-3 py-1 rounded-full font-bold" style="font-size:10px;letter-spacing:.06em;background:rgba(34,197,94,.18);color:#22c55e">ON TIME</span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib w-8 h-8 rounded-lg flex items-center justify-center ml-auto ib-bg">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                {{-- Marcus Thorne - LATE 5M --}}
                <tr class="att-row">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/38?img=15" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <div>
                                <p style="font-size:14px;font-weight:600;color:var(--text-1)">Marcus Thorne</p>
                                <p style="font-size:12px;color:var(--text-3)">Engineering</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Oct 30, 2023</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:#fb923c;font-weight:600">09:05 AM</td>
                    <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">05:00 PM</td>
                    <td class="px-4 py-3.5">
                        <span class="px-3 py-1 rounded-full font-bold" style="font-size:10px;letter-spacing:.06em;background:rgba(251,146,60,.18);color:#fb923c">LATE (5M)</span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib w-8 h-8 rounded-lg flex items-center justify-center ml-auto ib-bg">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
        <p style="font-size:13px;color:var(--text-3)">Showing 5 of 142 records</p>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-semibold purbtn" style="font-size:13px">1</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" style="font-size:13px;color:var(--text-2)">2</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" style="font-size:13px;color:var(--text-2)">3</button>
            <span style="color:var(--text-3);font-size:13px;padding:0 4px">...</span>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" style="font-size:13px;color:var(--text-2)">29</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
            </button>
        </div>
    </div>
</div>

{{-- ── BOTTOM STAT CARDS ── --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

    {{-- On Time Rate --}}
    <div class="rounded-2xl p-5" style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.2)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(34,197,94,.25)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(34,197,94,.8);letter-spacing:.07em;text-transform:uppercase">On Time Rate</p>
        </div>
        <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">92.4%</p>
        <p style="font-size:12px;color:#22c55e;margin-top:6px;font-weight:500">+1.2% ↑</p>
    </div>

    {{-- Avg. Lateness --}}
    <div class="rounded-2xl p-5" style="background:rgba(251,146,60,.12);border:1px solid rgba(251,146,60,.2)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(251,146,60,.25)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#fb923c" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(251,146,60,.8);letter-spacing:.07em;text-transform:uppercase">Avg. Lateness</p>
        </div>
        <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">4.2m</p>
        <p style="font-size:12px;color:#f87171;margin-top:6px;font-weight:500">-0.5m ↓</p>
    </div>

    {{-- Total Absents --}}
    <div class="rounded-2xl p-5" style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.2)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(239,68,68,.25)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="18" y1="8" x2="23" y2="13"/>
                    <line x1="23" y1="8" x2="18" y2="13"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(239,68,68,.8);letter-spacing:.07em;text-transform:uppercase">Total Absents</p>
        </div>
        <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">3</p>
        <p style="font-size:12px;color:#f87171;margin-top:6px;font-weight:500">+2 today</p>
    </div>

    {{-- Active Shifts --}}
    <div class="rounded-2xl p-5" style="background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.25)">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.3)">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
            <p style="font-size:11px;font-weight:600;color:rgba(167,139,250,.8);letter-spacing:.07em;text-transform:uppercase">Active Shifts</p>
        </div>
        <div class="flex items-baseline gap-2">
            <p style="font-size:32px;font-weight:800;color:#ffffff;line-height:1">128</p>
            <span style="font-size:12px;color:var(--text-3)">out of 142</span>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
.att-row { transition: background .15s; }
.att-row:hover { background: var(--bg-hover); }
</style>
@endpush
