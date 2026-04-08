@extends('layouts.app')

@section('title', 'Employee Schedule')

@php $active = 'schedule'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Employee Schedule</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Monitor and manage shifts for Oct 23 - Oct 29, 2023</p>
    </div>
    <div class="flex items-center gap-2 flex-shrink-0">
        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button class="purbtn px-4 py-2 rounded-lg font-semibold" style="font-size:13.5px">Today</button>
        <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
        </button>
        <button class="flex items-center gap-2 px-4 py-2 rounded-lg font-medium ib-bg" style="font-size:13.5px;color:var(--text-2)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export
        </button>
    </div>
</div>

{{-- ── FILTER + LEGEND BAR ── --}}
<div class="card rounded-2xl px-4 py-3 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <div class="flex flex-wrap items-center gap-3">
        {{-- Department --}}
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg" style="background:var(--bg-ghost);border:1px solid var(--border)">
            <span style="font-size:12px;color:var(--text-3)">Department:</span>
            <span style="font-size:13px;font-weight:600;color:var(--text-1)">All Departments</span>
        </div>
        {{-- Position --}}
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg" style="background:var(--bg-ghost);border:1px solid var(--border);cursor:pointer">
            <span style="font-size:12px;color:var(--text-3)">Position:</span>
            <span style="font-size:13px;font-weight:600;color:var(--text-1)">All Positions</span>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
        </div>
        {{-- Date --}}
        <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg" style="background:var(--bg-ghost);border:1px solid var(--border)">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            <span style="font-size:13px;font-weight:600;color:var(--text-1)">Oct 23 - Oct 29, 2023</span>
        </div>
    </div>
    {{-- Legend --}}
    <div class="flex items-center gap-4">
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#7c3aed"></span>
            <span style="font-size:12.5px;color:var(--text-3)">Morning</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#3b82f6"></span>
            <span style="font-size:12.5px;color:var(--text-3)">Night</span>
        </div>
        <div class="flex items-center gap-1.5">
            <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#f97316"></span>
            <span style="font-size:12.5px;color:var(--text-3)">On-Call</span>
        </div>
    </div>
</div>

{{-- ── SCHEDULE GRID ── --}}
<div class="card rounded-2xl overflow-hidden mb-5">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:780px">

            {{-- Day Headers --}}
            <thead>
                <tr style="background:rgba(124,58,237,.06);border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3" style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;min-width:160px;width:160px">Employee</th>
                    <th class="text-center px-3 py-3" style="min-width:120px">
                        <p style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em">MON</p>
                        <p style="font-size:14px;font-weight:700;color:var(--text-2)">OCT 23</p>
                    </th>
                    <th class="text-center px-3 py-3" style="min-width:120px">
                        <p style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em">TUE</p>
                        <p style="font-size:14px;font-weight:700;color:var(--text-2)">OCT 24</p>
                    </th>
                    {{-- WED highlighted --}}
                    <th class="text-center px-3 py-3" style="min-width:120px;background:rgba(124,58,237,.08)">
                        <p style="font-size:10.5px;font-weight:700;color:#a78bfa;letter-spacing:.08em">WED</p>
                        <p style="font-size:14px;font-weight:800;color:#a78bfa">OCT 25</p>
                    </th>
                    <th class="text-center px-3 py-3" style="min-width:120px">
                        <p style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em">THU</p>
                        <p style="font-size:14px;font-weight:700;color:var(--text-2)">OCT 26</p>
                    </th>
                    <th class="text-center px-3 py-3" style="min-width:120px">
                        <p style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em">FRI</p>
                        <p style="font-size:14px;font-weight:700;color:var(--text-2)">OCT 27</p>
                    </th>
                    <th class="text-center px-3 py-3" style="min-width:100px">
                        <p style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em">SAT</p>
                        <p style="font-size:14px;font-weight:700;color:var(--text-2)">OCT...</p>
                    </th>
                </tr>
            </thead>

            <tbody>

                {{-- Sarah Jenkins - Morning --}}
                <tr style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=1" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                            <div>
                                <p style="font-size:13.5px;font-weight:700;color:var(--text-1)">Sarah Jenkins</p>
                                <p style="font-size:11px;color:var(--text-3)">UI Designer</p>
                            </div>
                        </div>
                    </td>
                    @php
                    $morningMain = '<div style="background:rgba(124,58,237,.25);border:1px solid rgba(124,58,237,.4);border-radius:8px;padding:7px 10px;">
                        <p style="font-size:11px;font-weight:700;color:#c4b5fd">09:00 - 17:00</p>
                        <p style="font-size:10.5px;color:#a78bfa;margin-top:2px">Main Office</p>
                    </div>';
                    $morningRemote = '<div style="background:rgba(124,58,237,.18);border:1px solid rgba(124,58,237,.3);border-radius:8px;padding:7px 10px;">
                        <p style="font-size:11px;font-weight:700;color:#c4b5fd">09:00 - 17:00</p>
                        <p style="font-size:10.5px;color:#a78bfa;margin-top:2px">Remote</p>
                    </div>';
                    @endphp
                    <td class="px-2 py-3">{!! $morningMain !!}</td>
                    <td class="px-2 py-3">{!! $morningMain !!}</td>
                    <td class="px-2 py-3" style="background:rgba(124,58,237,.04)">{!! $morningRemote !!}</td>
                    <td class="px-2 py-3">{!! $morningRemote !!}</td>
                    <td class="px-2 py-3">{!! $morningMain !!}</td>
                    <td class="px-2 py-3"></td>
                </tr>

                {{-- Michael Chen - Night + On-Call --}}
                <tr style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=3" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                            <div>
                                <p style="font-size:13.5px;font-weight:700;color:var(--text-1)">Michael Chen</p>
                                <p style="font-size:11px;color:var(--text-3)">System Admin</p>
                            </div>
                        </div>
                    </td>
                    @php
                    $nightCell = '<div style="background:rgba(37,99,235,.2);border:1px solid rgba(59,130,246,.35);border-radius:8px;padding:7px 10px;">
                        <p style="font-size:11px;font-weight:700;color:#93c5fd">22:00 - 06:00</p>
                        <p style="font-size:10.5px;color:#60a5fa;margin-top:2px">Night Shift</p>
                    </div>';
                    $oncallCell = '<div style="background:rgba(194,65,12,.3);border:1px solid rgba(249,115,22,.4);border-radius:8px;padding:7px 10px;">
                        <p style="font-size:11px;font-weight:700;color:#fed7aa">On Call</p>
                        <p style="font-size:10.5px;color:#fb923c;margin-top:2px">Emergency</p>
                    </div>';
                    @endphp
                    <td class="px-2 py-3">{!! $nightCell !!}</td>
                    <td class="px-2 py-3">{!! $nightCell !!}</td>
                    <td class="px-2 py-3" style="background:rgba(124,58,237,.04)">{!! $oncallCell !!}</td>
                    <td class="px-2 py-3">{!! $nightCell !!}</td>
                    <td class="px-2 py-3">{!! $nightCell !!}</td>
                    <td class="px-2 py-3"></td>
                </tr>

                {{-- Amara Okafor - Morning 08-16 --}}
                <tr style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=9" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                            <div>
                                <p style="font-size:13.5px;font-weight:700;color:var(--text-1)">Amara Okafor</p>
                                <p style="font-size:11px;color:var(--text-3)">Team Lead</p>
                            </div>
                        </div>
                    </td>
                    @php
                    $earlyMain = '<div style="background:rgba(124,58,237,.22);border:1px solid rgba(124,58,237,.38);border-radius:8px;padding:7px 10px;">
                        <p style="font-size:11px;font-weight:700;color:#c4b5fd">08:00 - 16:00</p>
                        <p style="font-size:10.5px;color:#a78bfa;margin-top:2px">Main Office</p>
                    </div>';
                    $earlyRemote = '<div style="background:rgba(124,58,237,.15);border:1px solid rgba(124,58,237,.28);border-radius:8px;padding:7px 10px;">
                        <p style="font-size:11px;font-weight:700;color:#c4b5fd">08:00 - 16:00</p>
                        <p style="font-size:10.5px;color:#a78bfa;margin-top:2px">Remote</p>
                    </div>';
                    @endphp
                    <td class="px-2 py-3">{!! $earlyMain !!}</td>
                    <td class="px-2 py-3">{!! $earlyMain !!}</td>
                    <td class="px-2 py-3" style="background:rgba(124,58,237,.04)">{!! $earlyMain !!}</td>
                    <td class="px-2 py-3">{!! $earlyRemote !!}</td>
                    <td class="px-2 py-3">{!! $earlyMain !!}</td>
                    <td class="px-2 py-3"></td>
                </tr>

                {{-- David Wilson - partial (Mon off, rest Main Office) --}}
                <tr style="border-bottom:1px solid var(--border)">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=7" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                            <div>
                                <p style="font-size:13.5px;font-weight:700;color:var(--text-1)">David Wilson</p>
                                <p style="font-size:11px;color:var(--text-3)">Project Manager</p>
                            </div>
                        </div>
                    </td>
                    @php
                    $dwCell = '<div style="background:rgba(124,58,237,.22);border:1px solid rgba(124,58,237,.38);border-radius:8px;padding:7px 10px;">
                        <p style="font-size:11px;font-weight:700;color:#c4b5fd">09:00 - 17:00</p>
                        <p style="font-size:10.5px;color:#a78bfa;margin-top:2px">Main Office</p>
                    </div>';
                    @endphp
                    <td class="px-2 py-3"></td>
                    <td class="px-2 py-3">{!! $dwCell !!}</td>
                    <td class="px-2 py-3" style="background:rgba(124,58,237,.04)">{!! $dwCell !!}</td>
                    <td class="px-2 py-3">{!! $dwCell !!}</td>
                    <td class="px-2 py-3">{!! $dwCell !!}</td>
                    <td class="px-2 py-3"></td>
                </tr>

                {{-- Empty row with "Click to assign" --}}
                <tr>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex-shrink-0" style="background:var(--bg-ghost);border:1px dashed var(--border)"></div>
                            <div>
                                <div class="h-3 rounded" style="width:80px;background:var(--bg-ghost)"></div>
                                <div class="h-2.5 rounded mt-1.5" style="width:55px;background:var(--bg-ghost)"></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-2 py-3"></td>
                    <td class="px-2 py-3"></td>
                    <td class="px-2 py-3 text-center" style="background:rgba(124,58,237,.04)">
                        <span style="font-size:12px;color:var(--text-3);font-style:italic">Click to assign shift.</span>
                    </td>
                    <td class="px-2 py-3"></td>
                    <td class="px-2 py-3"></td>
                    <td class="px-2 py-3"></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

{{-- ── PAGINATION ── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
    <p style="font-size:13px;color:var(--text-3)">Showing 1 to 4 of 24 employees</p>
    <div class="flex items-center gap-1">
        <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 17l-5-5 5-5M19 17l-5-5 5-5"/></svg>
        </button>
        <button class="w-8 h-8 rounded-lg font-semibold purbtn" style="font-size:13px">1</button>
        <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">2</button>
        <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">3</button>
        <span style="color:var(--text-3);font-size:13px;padding:0 2px">...</span>
        <button class="w-8 h-8 rounded-lg ib-bg" style="font-size:13px;color:var(--text-2)">6</button>
        <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 17l5-5-5-5M5 17l5-5-5-5"/></svg>
        </button>
    </div>
</div>

@endsection

@push('styles')
<style>
table tbody tr { transition: background .15s; }
table tbody tr:hover { background: var(--bg-hover); }
</style>
@endpush