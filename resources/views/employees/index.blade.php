@extends('layouts.app')

@section('title', 'Employee Directory')

@php $active = 'employees'; @endphp

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Employee Directory</h1>
            <p style="font-size:13px;color:var(--text-3);margin-top:5px">Manage and view all staff records in one place.</p>
        </div>
        <button onclick="openM('mImportEmployee')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:14px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Import
        </button>
    </div>

    <div class="card rounded-2xl" style="overflow:hidden">
        <div class="px-5 py-4" style="border-bottom:1px solid var(--border)">
            <div class="flex items-center gap-3 px-4 py-2.5 rounded-xl"
                style="background:var(--bg-input);border:1px solid var(--border-in);max-width:380px">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)"
                    stroke-width="2" class="flex-shrink-0">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
                <input type="text" id="empSearch" placeholder="Search by name, ID, or department..."
                    oninput="filterEmployees()"
                    style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit">
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse:collapse;min-width:640px">
                <thead>
                    <tr style="border-bottom:1px solid var(--border)">
                        <th class="text-left px-5 py-3 font-semibold"
                            style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Profile</th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Full Name
                        </th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Employee ID
                        </th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Department
                        </th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Position</th>
                        <th class="text-right px-5 py-3 font-semibold"
                            style="font-size:11px;color:#7c3aed;letter-spacing:.08em;text-transform:uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody id="empTableBody">
                    <tr class="emp-row" style="border-bottom:1px solid var(--border)"
                        data-name="john doe engineering emp-001 senior developer">
                        <td class="px-5 py-3.5">
                            <img src="https://i.pravatar.cc/40?img=11" class="w-10 h-10 rounded-full object-cover"
                                alt="John Doe">
                        </td>
                        <td class="px-4 py-3.5">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">John Doe</p>
                            <p style="font-size:12px;color:var(--text-3)">john.doe@company.com</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <span style="font-size:13.5px;color:#7c3aed;font-weight:600">EMP-001</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="px-3 py-1 rounded-full font-bold"
                                style="font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;background:rgba(99,179,237,.18);color:#63b3ed">
                                Engineering
                            </span>
                        </td>
                        <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Senior Developer</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <tr class="emp-row" style="border-bottom:1px solid var(--border)"
                        data-name="jane smith design emp-002 product designer">
                        <td class="px-5 py-3.5">
                            <img src="https://i.pravatar.cc/40?img=5" class="w-10 h-10 rounded-full object-cover"
                                alt="Jane Smith">
                        </td>
                        <td class="px-4 py-3.5">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Jane Smith</p>
                            <p style="font-size:12px;color:var(--text-3)">jane.smith@company.com</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <span style="font-size:13.5px;color:#7c3aed;font-weight:600">EMP-002</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="px-3 py-1 rounded-full font-bold"
                                style="font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;background:rgba(167,139,250,.18);color:#a78bfa">
                                Design
                            </span>
                        </td>
                        <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Product Designer</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <tr class="emp-row" style="border-bottom:1px solid var(--border)"
                        data-name="mike jones marketing emp-003 seo specialist">
                        <td class="px-5 py-3.5">
                            <img src="https://i.pravatar.cc/40?img=3" class="w-10 h-10 rounded-full object-cover"
                                alt="Mike Jones">
                        </td>
                        <td class="px-4 py-3.5">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Mike Jones</p>
                            <p style="font-size:12px;color:var(--text-3)">mike.j@company.com</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <span style="font-size:13.5px;color:#7c3aed;font-weight:600">EMP-003</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="px-3 py-1 rounded-full font-bold"
                                style="font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;background:rgba(52,211,153,.15);color:#34d399">
                                Marketing
                            </span>
                        </td>
                        <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">SEO Specialist</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <tr class="emp-row" style="border-bottom:1px solid var(--border)"
                        data-name="sarah wilson hr emp-004 hr manager">
                        <td class="px-5 py-3.5">
                            <img src="https://i.pravatar.cc/40?img=9" class="w-10 h-10 rounded-full object-cover"
                                alt="Sarah Wilson">
                        </td>
                        <td class="px-4 py-3.5">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Sarah Wilson</p>
                            <p style="font-size:12px;color:var(--text-3)">s.wilson@company.com</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <span style="font-size:13.5px;color:#7c3aed;font-weight:600">EMP-004</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="px-3 py-1 rounded-full font-bold"
                                style="font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;background:rgba(248,113,113,.15);color:#f87171">
                                HR
                            </span>
                        </td>
                        <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">HR Manager</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="19" r="1" />
                                </svg>
                            </button>
                        </td>
                    </tr>

                    <tr class="emp-row" data-name="alex brown sales emp-005 account executive">
                        <td class="px-5 py-3.5">
                            <img src="https://i.pravatar.cc/40?img=15" class="w-10 h-10 rounded-full object-cover"
                                alt="Alex Brown">
                        </td>
                        <td class="px-4 py-3.5">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Alex Brown</p>
                            <p style="font-size:12px;color:var(--text-3)">a.brown@company.com</p>
                        </td>
                        <td class="px-4 py-3.5">
                            <span style="font-size:13.5px;color:#7c3aed;font-weight:600">EMP-005</span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="px-3 py-1 rounded-full font-bold"
                                style="font-size:10.5px;letter-spacing:.06em;text-transform:uppercase;background:rgba(251,191,36,.15);color:#fbbf24">
                                Sales
                            </span>
                        </td>
                        <td class="px-4 py-3.5" style="font-size:13.5px;color:var(--text-2)">Account Executive</td>
                        <td class="px-5 py-3.5 text-right">
                            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1" />
                                    <circle cx="12" cy="12" r="1" />
                                    <circle cx="12" cy="19" r="1" />
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
                Showing <span style="color:#a78bfa;font-weight:600">5</span> of
                <span style="color:#a78bfa;font-weight:600">124</span> employees
            </p>
            <div class="flex items-center gap-1">
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M15 18l-6-6 6-6" />
                    </svg>
                </button>
                <button class="w-8 h-8 rounded-lg flex items-center justify-center font-semibold purbtn"
                    style="font-size:13px">1</button>
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg"
                    style="font-size:13px;color:var(--text-2)">2</button>
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg"
                    style="font-size:13px;color:var(--text-2)">3</button>
                <span style="color:var(--text-3);font-size:13px;padding:0 2px">...</span>
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg"
                    style="font-size:13px;color:var(--text-2)">25</button>
                <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path d="M9 18l6-6-6-6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    @include('employees.import')


@endsection

@push('styles')
    <style>
        .emp-row {
            transition: background .15s;
        }

        .emp-row:hover {
            background: var(--bg-hover);
        }

        .emp-row.hidden-row {
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function filterEmployees() {
            const q = document.getElementById('empSearch').value.toLowerCase();
            document.querySelectorAll('.emp-row').forEach(row => {
                const data = row.getAttribute('data-name') || '';
                row.classList.toggle('hidden-row', q.length > 0 && !data.includes(q));
            });
        }
    </script>
@endpush
