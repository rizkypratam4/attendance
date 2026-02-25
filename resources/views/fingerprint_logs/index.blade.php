@extends('layouts.app')

@section('title', 'Fingerprint Logs')

@php $active = 'fingerprint'; @endphp

@section('content')

<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Fingerprint Logs</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Real-time monitoring of all biometric terminal activity.</p>
    </div>
    <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-2);font-size:13.5px;cursor:pointer">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="23 4 23 10 17 10"/>
            <path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/>
        </svg>
        Refresh Logs
    </button>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="card rounded-2xl p-5">
        <div class="flex items-start justify-between mb-3">
            <p style="font-size:13px;color:var(--text-3)">Total Scans Today</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:rgba(124,58,237,.18)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                    <path d="M9 3v18M15 3v18M3 9h18M3 15h18"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline gap-2 mb-3">
            <p style="font-size:30px;font-weight:800;color:var(--text-1);line-height:1">1,284</p>
            <span style="font-size:12px;font-weight:600;color:#22c55e">+12.4%</span>
        </div>
        <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
            <div class="h-full rounded-full" style="width:78%;background:linear-gradient(90deg,#7c3aed,#a78bfa)"></div>
        </div>
    </div>

    <div class="card rounded-2xl p-5">
        <div class="flex items-start justify-between mb-3">
            <p style="font-size:13px;color:var(--text-3)">Success Rate</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:rgba(34,197,94,.15)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline gap-2 mb-3">
            <p style="font-size:30px;font-weight:800;color:var(--text-1);line-height:1">98.2%</p>
            <span style="font-size:12px;font-weight:600;color:#22c55e">+0.5%</span>
        </div>
        <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
            <div class="h-full rounded-full" style="width:98%;background:linear-gradient(90deg,#16a34a,#22c55e)"></div>
        </div>
    </div>

    <div class="card rounded-2xl p-5">
        <div class="flex items-start justify-between mb-3">
            <p style="font-size:13px;color:var(--text-3)">Failed Attempts</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:rgba(239,68,68,.15)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline gap-2 mb-3">
            <p style="font-size:30px;font-weight:800;color:var(--text-1);line-height:1">24</p>
            <span style="font-size:12px;font-weight:600;color:#f87171">-4.2%</span>
        </div>
        <div class="h-1.5 rounded-full overflow-hidden" style="background:var(--bg-ghost)">
            <div class="h-full rounded-full" style="width:12%;background:linear-gradient(90deg,#dc2626,#f87171)"></div>
        </div>
    </div>

</div>

<div class="card rounded-2xl p-5 mb-5">
    <div class="flex items-center gap-2 mb-4">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2.5">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        <p style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.08em;text-transform:uppercase">Filter Logs</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase;display:block;margin-bottom:7px">Date Range</label>
            <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl"
                 style="background:var(--bg-input);border:1px solid var(--border-in);cursor:pointer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span style="font-size:13px;color:var(--text-2)">Oct 24, 2023 - Oct 31</span>
            </div>
        </div>

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase;display:block;margin-bottom:7px">Machine ID</label>
            <div class="relative">
                <select class="w-full px-3 py-2.5 rounded-xl"
                        style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;cursor:pointer;outline:none;appearance:none;font-family:inherit">
                    <option>All Machines</option>
                    <option>MCH-001 (Main Gate)</option>
                    <option>MCH-002 (North Exit)</option>
                    <option>MCH-003 (Staff Room)</option>
                </select>
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                    <path d="M6 9l6 6 6-6"/>
                </svg>
            </div>
        </div>

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase;display:block;margin-bottom:7px">Employee</label>
            <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl"
                 style="background:var(--bg-input);border:1px solid var(--border-in)">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Search name..."
                       style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:inherit">
            </div>
        </div>

        <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase;display:block;margin-bottom:7px">Status</label>
            <div class="flex items-center gap-1.5">
                <button onclick="setFpStatus('all',this)"
                        class="fp-status-btn px-3 py-2 rounded-lg font-semibold purbtn flex-1"
                        style="font-size:12.5px">All</button>
                <button onclick="setFpStatus('success',this)"
                        class="fp-status-btn px-3 py-2 rounded-lg font-semibold ib-bg flex-1"
                        style="font-size:12.5px;color:var(--text-2)">Success</button>
                <button onclick="setFpStatus('failed',this)"
                        class="fp-status-btn px-3 py-2 rounded-lg font-semibold ib-bg flex-1"
                        style="font-size:12.5px;color:var(--text-2)">Failed</button>
            </div>
        </div>

    </div>
</div>

<div class="card rounded-2xl mb-5" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:620px">
            <thead>
                <tr style="border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Log ID</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Employee</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Machine</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Timestamp</th>
                    <th class="text-left px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Status</th>
                    <th class="text-right px-5 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr class="fp-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <span style="font-size:12.5px;color:var(--text-3);font-family:monospace">#FP-98231</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=11" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <span style="font-size:14px;font-weight:600;color:var(--text-1)">Marcus Thompson</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13.5px;font-weight:500;color:var(--text-1)">MCH-001</p>
                        <p style="font-size:11.5px;color:var(--text-3)">(Main Gate)</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13px;color:var(--text-2)">2023-10-31</p>
                        <p style="font-size:13px;color:var(--text-2)">08:42:15</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                              style="font-size:10.5px;background:rgba(34,197,94,.15);color:#22c55e;letter-spacing:.05em">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>SUCCESS
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                <tr class="fp-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <span style="font-size:12.5px;color:var(--text-3);font-family:monospace">#FP-98230</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=1" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <span style="font-size:14px;font-weight:600;color:var(--text-1)">Sarah Jenkins</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13.5px;font-weight:500;color:var(--text-1)">MCH-003</p>
                        <p style="font-size:11.5px;color:var(--text-3)">(Staff Room)</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13px;color:var(--text-2)">2023-10-31</p>
                        <p style="font-size:13px;color:var(--text-2)">08:39:02</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                              style="font-size:10.5px;background:rgba(239,68,68,.15);color:#f87171;letter-spacing:.05em">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>FAILED
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                <tr class="fp-row" style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3.5">
                        <span style="font-size:12.5px;color:var(--text-3);font-family:monospace">#FP-98229</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=7" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <span style="font-size:14px;font-weight:600;color:var(--text-1)">Robert Wilson</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13.5px;font-weight:500;color:var(--text-1)">MCH-001</p>
                        <p style="font-size:11.5px;color:var(--text-3)">(Main Gate)</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13px;color:var(--text-2)">2023-10-31</p>
                        <p style="font-size:13px;color:var(--text-2)">08:35:44</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                              style="font-size:10.5px;background:rgba(34,197,94,.15);color:#22c55e;letter-spacing:.05em">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>SUCCESS
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

                <tr class="fp-row">
                    <td class="px-5 py-3.5">
                        <span style="font-size:12.5px;color:var(--text-3);font-family:monospace">#FP-98228</span>
                    </td>
                    <td class="px-4 py-3.5">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=20" class="w-9 h-9 rounded-full object-cover flex-shrink-0" alt="">
                            <span style="font-size:14px;font-weight:600;color:var(--text-1)">Emily Chen</span>
                        </div>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13.5px;font-weight:500;color:var(--text-1)">MCH-002</p>
                        <p style="font-size:11.5px;color:var(--text-3)">(North Exit)</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <p style="font-size:13px;color:var(--text-2)">2023-10-31</p>
                        <p style="font-size:13px;color:var(--text-2)">08:30:12</p>
                    </td>
                    <td class="px-4 py-3.5">
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full font-bold w-fit"
                              style="font-size:10.5px;background:rgba(34,197,94,.15);color:#22c55e;letter-spacing:.05em">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 inline-block"></span>SUCCESS
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-right">
                        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                            </svg>
                        </button>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">
            Showing <strong style="color:var(--text-2)">1</strong> to
            <strong style="color:var(--text-2)">10</strong> of
            <strong style="color:var(--text-2)">1,284</strong> entries
        </p>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
            </button>
            <button class="w-8 h-8 rounded-lg font-semibold purbtn" style="font-size:13px">1</button>
            <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">2</button>
            <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">3</button>
            <span style="color:var(--text-3);font-size:13px;padding:0 2px">...</span>
            <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">129</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
            </button>
        </div>
    </div>
</div>

{{-- ── BOTTOM: Terminal Status + Scan Preview ── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Biometric Terminal Status --}}
    <div class="card rounded-2xl p-5">
        <div class="flex items-center gap-2 mb-4">
            <span class="w-2 h-2 rounded-full bg-green-400 inline-block" style="box-shadow:0 0 6px #22c55e"></span>
            <p style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.08em;text-transform:uppercase">Biometric Terminal Status</p>
        </div>
        <div class="space-y-3">

            {{-- MCH-001 --}}
            <div class="flex items-center justify-between gap-3 p-3 rounded-xl" style="background:var(--bg-ghost);border:1px solid var(--border)">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.2)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M9 3v18M15 3v18M3 9h18M3 15h18"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:13px;font-weight:600;color:var(--text-1)">MCH-001 - Main Gate</p>
                        <p style="font-size:11px;color:#22c55e;font-weight:600">CONNECTED</p>
                    </div>
                </div>
                <p style="font-size:12px;color:var(--text-3);white-space:nowrap">Signal: <span style="color:var(--text-2);font-weight:500">Excellent</span></p>
            </div>

            {{-- MCH-002 --}}
            <div class="flex items-center justify-between gap-3 p-3 rounded-xl" style="background:var(--bg-ghost);border:1px solid var(--border)">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.2)">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <path d="M9 3v18M15 3v18M3 9h18M3 15h18"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:13px;font-weight:600;color:var(--text-1)">MCH-002 - North Exit</p>
                        <p style="font-size:11px;color:#22c55e;font-weight:600">CONNECTED</p>
                    </div>
                </div>
                <p style="font-size:12px;color:var(--text-3);white-space:nowrap">Signal: <span style="color:var(--text-2);font-weight:500">Good</span></p>
            </div>

        </div>
    </div>

    {{-- Latest Scan Preview --}}
    <div class="card rounded-2xl p-5">
        <p style="font-size:12px;font-weight:700;color:var(--text-2);letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px">Latest Scan Preview</p>
        <div class="rounded-xl p-4 mt-3" style="background:rgba(124,58,237,.08);border:1px solid rgba(124,58,237,.2)">
            <div class="flex items-center gap-4">
                {{-- Fingerprint icon --}}
                <div class="w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(124,58,237,.2);border:1px solid rgba(124,58,237,.3)">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="1.5">
                        <path d="M12 18.5c-2.5 0-4.5-2-4.5-4.5V9c0-2.5 2-4.5 4.5-4.5s4.5 2 4.5 4.5"/>
                        <path d="M12 22c-3.5 0-6.5-2.5-7-6"/>
                        <path d="M12 2C6.5 2 2 6.5 2 12c0 2 .6 3.9 1.6 5.5"/>
                        <path d="M22 12c0-2-.6-3.9-1.6-5.5"/>
                        <path d="M12 14v4"/>
                        <path d="M9 9a3 3 0 016 0v5"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p style="font-size:15px;font-weight:700;color:var(--text-1)">Marcus Thompson</p>
                    <p style="font-size:12px;color:var(--text-3);margin-top:2px">Matched ID: 0361-A</p>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="px-3 py-1 rounded-full font-bold" style="font-size:11px;background:rgba(34,197,94,.18);color:#22c55e">99.4% Match</span>
                        <span style="font-size:12px;color:var(--text-3)">08:42:15 AM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
.fp-row { transition: background .15s; }
.fp-row:hover { background: var(--bg-hover); }
.fp-status-btn { transition: background .2s, color .2s; border: none; cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
function setFpStatus(status, btn) {
    document.querySelectorAll('.fp-status-btn').forEach(b => {
        b.classList.remove('purbtn');
        b.classList.add('ib-bg');
        b.style.color = 'var(--text-2)';
    });
    btn.classList.add('purbtn');
    btn.classList.remove('ib-bg');
    btn.style.color = '#fff';
}
</script>
@endpush