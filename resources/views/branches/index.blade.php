@extends('layouts.app')

@section('title', 'Branch Management')

@php $active = 'branch'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Branch Management</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Configure and manage physical office locations across your organization.</p>
    </div>
    <button onclick="openM('mAddBranch')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="font-size:14px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
            <circle cx="12" cy="10" r="3"/>
        </svg>
        Add New Branch
    </button>
</div>

{{-- ── FILTER BAR ── --}}
<div class="flex flex-col sm:flex-row gap-3 mb-4">

    {{-- All Cities dropdown --}}
    <div class="relative">
        <button id="cityBtn" onclick="toggleDropdown('cityMenu')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium"
                style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-2);font-size:13.5px;cursor:pointer;white-space:nowrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="4" y1="6" x2="20" y2="6"/>
                <line x1="8" y1="12" x2="16" y2="12"/>
                <line x1="11" y1="18" x2="13" y2="18"/>
            </svg>
            All Cities
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </button>
        <div id="cityMenu" class="dd-menu hidden absolute left-0 mt-2 rounded-xl overflow-hidden z-50"
             style="min-width:160px;background:var(--dd-bg);border:1px solid var(--dd-border);box-shadow:0 16px 40px rgba(0,0,0,.35)">
            <div class="ddi" onclick="setCity('All Cities')">All Cities</div>
            <div class="ddi" onclick="setCity('New York, NY')">New York, NY</div>
            <div class="ddi" onclick="setCity('San Francisco, CA')">San Francisco, CA</div>
            <div class="ddi" onclick="setCity('Chicago, IL')">Chicago, IL</div>
            <div class="ddi" onclick="setCity('London, UK')">London, UK</div>
        </div>
    </div>

    {{-- Sort dropdown --}}
    <div class="relative">
        <button id="sortBtn" onclick="toggleDropdown('sortMenu')"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-medium"
                style="background:var(--bg-card);border:1px solid var(--border);color:var(--text-2);font-size:13.5px;cursor:pointer;white-space:nowrap">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="8" y1="6" x2="21" y2="6"/>
                <line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
                <line x1="3" y1="6" x2="3.01" y2="6"/>
                <line x1="3" y1="12" x2="3.01" y2="12"/>
                <line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            Sort: Name A-Z
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </button>
        <div id="sortMenu" class="dd-menu hidden absolute left-0 mt-2 rounded-xl overflow-hidden z-50"
             style="min-width:180px;background:var(--dd-bg);border:1px solid var(--dd-border);box-shadow:0 16px 40px rgba(0,0,0,.35)">
            <div class="ddi" onclick="setSort('Name A-Z')">Name A-Z</div>
            <div class="ddi" onclick="setSort('Name Z-A')">Name Z-A</div>
            <div class="ddi" onclick="setSort('City')">City</div>
            <div class="ddi" onclick="setSort('Status')">Status</div>
        </div>
    </div>

    {{-- Search --}}
    <div class="flex items-center gap-3 flex-1 rounded-xl px-4 py-2.5"
         style="background:var(--bg-card);border:1px solid var(--border)">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
            <circle cx="11" cy="11" r="8"/>
            <path d="M21 21l-4.35-4.35"/>
        </svg>
        <input type="text" id="branchSearch" placeholder="Search branches by name or manager..."
               oninput="filterBranches()"
               style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%">
    </div>
</div>

{{-- ── BRANCH TABLE ── --}}
<div class="card rounded-2xl mb-5" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table class="w-full" style="border-collapse:collapse;min-width:600px">
            <thead>
                <tr style="background:rgba(124,58,237,.10);border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Branch Name</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">City</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Manager</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Status</th>
                    <th class="text-right px-5 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="branchTableBody">

                {{-- Row 1: Downtown Headquarters --}}
                <tr class="branch-row" style="border-bottom:1px solid var(--border)"
                    data-name="downtown headquarters sarah jenkins new york">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(124,58,237,.20)">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                    <line x1="12" y1="12" x2="12" y2="16"/>
                                    <line x1="10" y1="14" x2="14" y2="14"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">Downtown Headquarters</p>
                                <p style="font-size:12px;color:var(--text-3)">ID: BR-001</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:14px;color:var(--text-2)">New York, NY</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=1"
                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="Sarah Jenkins">
                            <span style="font-size:14px;color:var(--text-1);font-weight:500">Sarah Jenkins</span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold"
                              style="font-size:11px;color:#22c55e;border:1px solid rgba(34,197,94,.35);background:transparent;letter-spacing:.04em">
                            Active
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer" title="Delete">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Row 2: West Coast Hub --}}
                <tr class="branch-row" style="border-bottom:1px solid var(--border)"
                    data-name="west coast hub michael chen san francisco">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(124,58,237,.20)">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                                    <circle cx="12" cy="12" r="2"/>
                                    <path d="M12 2a10 10 0 000 20 10 10 0 000-20z"/>
                                    <path d="M12 6v2M12 16v2M6 12H4M20 12h-2"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">West Coast Hub</p>
                                <p style="font-size:12px;color:var(--text-3)">ID: BR-002</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:14px;color:var(--text-2)">San Francisco, CA</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=3"
                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="Michael Chen">
                            <span style="font-size:14px;color:var(--text-1);font-weight:500">Michael Chen</span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold"
                              style="font-size:11px;color:#22c55e;border:1px solid rgba(34,197,94,.35);background:transparent;letter-spacing:.04em">
                            Active
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer" title="Delete">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Row 3: East Side Logistics --}}
                <tr class="branch-row" style="border-bottom:1px solid var(--border)"
                    data-name="east side logistics james wilson chicago">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(124,58,237,.20)">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <line x1="8" y1="21" x2="16" y2="21"/>
                                    <line x1="12" y1="17" x2="12" y2="21"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">East Side Logistics</p>
                                <p style="font-size:12px;color:var(--text-3)">ID: BR-003</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:14px;color:var(--text-2)">Chicago, IL</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=7"
                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="James Wilson">
                            <span style="font-size:14px;color:var(--text-1);font-weight:500">James Wilson</span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold"
                              style="font-size:11px;color:#fb923c;border:1px solid rgba(251,146,60,.35);background:rgba(251,146,60,.08);letter-spacing:.04em">
                            Maintenance
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer" title="Delete">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Row 4: European Regional Office --}}
                <tr class="branch-row" data-name="european regional office emma thompson london">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                                 style="background:rgba(124,58,237,.20)">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                                </svg>
                            </div>
                            <div>
                                <p style="font-size:14px;font-weight:700;color:var(--text-1)">European Regional Office</p>
                                <p style="font-size:12px;color:var(--text-3)">ID: BR-004</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4" style="font-size:14px;color:var(--text-2)">London, UK</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <img src="https://i.pravatar.cc/36?img=20"
                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0" alt="Emma Thompson">
                            <span style="font-size:14px;color:var(--text-1);font-weight:500">Emma Thompson</span>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 rounded-full font-semibold"
                              style="font-size:11px;color:#22c55e;border:1px solid rgba(34,197,94,.35);background:transparent;letter-spacing:.04em">
                            Active
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center" title="Edit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button class="w-8 h-8 rounded-lg flex items-center justify-center"
                                    style="background:rgba(239,68,68,.10);color:#f87171;border:none;cursor:pointer" title="Delete">
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

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">Showing 1 to 4 of 24 branches</p>
        <div class="flex items-center gap-1">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M15 18l-6-6 6-6"/>
                </svg>
            </button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-semibold purbtn"
                    style="font-size:13px">1</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-medium ib-bg"
                    style="font-size:13px;color:var(--text-2)">2</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center font-medium ib-bg"
                    style="font-size:13px;color:var(--text-2)">3</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- ── BOTTOM STAT CARDS ── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

    {{-- Total Employees --}}
    <div class="card rounded-2xl p-5">
        <div class="flex items-start justify-between mb-4">
            <p style="font-size:12px;font-weight:600;color:var(--text-3);letter-spacing:.06em;text-transform:uppercase">Total Employees</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:rgba(124,58,237,.18)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">1,420</p>
            <span style="font-size:13px;font-weight:600;color:#22c55e">+12%</span>
        </div>
        <p style="font-size:12px;color:var(--text-3);margin-top:6px">Across all branches worldwide</p>
    </div>

    {{-- Avg Attendance --}}
    <div class="card rounded-2xl p-5">
        <div class="flex items-start justify-between mb-4">
            <p style="font-size:12px;font-weight:600;color:var(--text-3);letter-spacing:.06em;text-transform:uppercase">Avg Attendance</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:rgba(124,58,237,.18)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">94.2%</p>
            <span style="font-size:13px;font-weight:600;color:#22c55e">+2.4%</span>
        </div>
        <p style="font-size:12px;color:var(--text-3);margin-top:6px">Current monthly average</p>
    </div>

    {{-- Regional Coverage --}}
    <div class="card rounded-2xl p-5">
        <div class="flex items-start justify-between mb-4">
            <p style="font-size:12px;font-weight:600;color:var(--text-3);letter-spacing:.06em;text-transform:uppercase">Regional Coverage</p>
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                 style="background:rgba(124,58,237,.18)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="2" y1="12" x2="22" y2="12"/>
                    <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>
                </svg>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">12</p>
            <span style="font-size:14px;font-weight:500;color:var(--text-2)">Countries</span>
        </div>
        <p style="font-size:12px;color:var(--text-3);margin-top:6px">Expanding to 2 new regions</p>
    </div>

</div>

{{-- ── MODAL: ADD NEW BRANCH ── --}}
<div class="mbk" id="mAddBranch" onclick="closeOut(event,'mAddBranch')">
    <div class="mbox" style="max-width:480px">
        <div class="mhdr">
            <span class="mtitle">Add New Branch</span>
            <button class="mclose" onclick="closeM('mAddBranch')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <div class="space-y-4">
                <div>
                    <label class="mlabel">Branch Name</label>
                    <input type="text" placeholder="e.g. Downtown Headquarters" class="minput">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mlabel">Branch ID</label>
                        <input type="text" placeholder="e.g. BR-005" class="minput">
                    </div>
                    <div>
                        <label class="mlabel">City</label>
                        <input type="text" placeholder="e.g. New York, NY" class="minput">
                    </div>
                </div>
                <div>
                    <label class="mlabel">Manager Name</label>
                    <input type="text" placeholder="Full name" class="minput">
                </div>
                <div>
                    <label class="mlabel">Status</label>
                    <select class="minput" style="cursor:pointer">
                        <option value="active">Active</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-6">
                <button onclick="closeM('mAddBranch')"
                        class="flex-1 py-2.5 rounded-xl font-medium"
                        style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                    Cancel
                </button>
                <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                    Save Branch
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.branch-row { transition: background .15s; }
.branch-row:hover { background: var(--bg-hover); }
.branch-row.hidden-row { display: none; }
.dd-menu { animation: dropIn .15s ease; }
</style>
@endpush

@push('scripts')
<script>
// ── SEARCH FILTER ──
function filterBranches() {
    const q = document.getElementById('branchSearch').value.toLowerCase();
    document.querySelectorAll('.branch-row').forEach(row => {
        const name = row.getAttribute('data-name') || '';
        row.classList.toggle('hidden-row', q.length > 0 && !name.includes(q));
    });
}

// ── DROPDOWN TOGGLE ──
function toggleDropdown(id) {
    const menu = document.getElementById(id);
    const allMenus = document.querySelectorAll('.dd-menu');
    allMenus.forEach(m => { if (m.id !== id) m.classList.add('hidden'); });
    menu.classList.toggle('hidden');
    event.stopPropagation();
}

document.addEventListener('click', () => {
    document.querySelectorAll('.dd-menu').forEach(m => m.classList.add('hidden'));
});

function setCity(val) {
    document.getElementById('cityBtn').childNodes[0].textContent = '';
    document.getElementById('cityBtn').innerHTML =
        `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="11" y1="18" x2="13" y2="18"/>
        </svg>
        ${val}
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M6 9l6 6 6-6"/>
        </svg>`;
    document.getElementById('cityMenu').classList.add('hidden');

    // Filter table by city
    const q = val === 'All Cities' ? '' : val.toLowerCase();
    document.querySelectorAll('.branch-row').forEach(row => {
        const name = row.getAttribute('data-name') || '';
        row.classList.toggle('hidden-row', q.length > 0 && !name.includes(q));
    });
}

function setSort(val) {
    document.getElementById('sortBtn').innerHTML =
        `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
            <line x1="8" y1="18" x2="21" y2="18"/>
            <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/>
            <line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
        Sort: ${val}
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <path d="M6 9l6 6 6-6"/>
        </svg>`;
    document.getElementById('sortMenu').classList.add('hidden');
}
</script>
@endpush