@extends('layouts.app')

@section('title', 'Master Data – Department')

@php $active = 'department'; @endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Master Data</h1>
            <p style="font-size:13px;color:var(--text-3);margin-top:5px">Configure and manage organizational departments.</p>
        </div>
        <button onclick="openM('mAddDepartment')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:14px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Add Department
        </button>
    </div>

    <div class="card rounded-2xl mb-5" style="overflow:hidden">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-4" style="border-bottom:1px solid var(--border)">
            <div class="flex items-center gap-3 flex-1 rounded-xl px-4 py-2.5 min-w-0"
                style="background:var(--bg-input);border:1px solid var(--border-in)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)"
                    stroke-width="2" class="flex-shrink-0">
                    <circle cx="11" cy="11" r="8" />
                    <path d="M21 21l-4.35-4.35" />
                </svg>
                <input type="text" placeholder="Search departments..."
                    style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:14px;width:100%"
                    id="deptSearch" oninput="filterDepts()">
            </div>

            {{-- Right controls --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold"
                    style="background:#7c3aed;color:#fff;border:none;cursor:pointer;font-size:13px">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <line x1="4" y1="6" x2="20" y2="6" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                        <line x1="11" y1="18" x2="13" y2="18" />
                    </svg>
                    Filters
                </button>
                <span style="font-size:13px;color:var(--text-3);white-space:nowrap">Showing 3 of 15 Departments</span>
                <button class="ib-bg w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <x-departments.table :departments="$departments" />
        </div>
            <x-ui.pagination-footer :paginator="$departments" />
    </div>

    @include('departments.create')
    @include('departments.edit')

@endsection

@push('styles')
    <style>
        .dept-row {
            transition: background .15s;
        }

        .dept-row:hover {
            background: var(--bg-hover);
        }

        .dept-row.hidden-row {
            display: none;
        }
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
