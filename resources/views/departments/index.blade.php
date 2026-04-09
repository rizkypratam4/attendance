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
        {{-- ── SEARCH + FILTER ── --}}
        <x-search-filter 
            searchId="deptSearch"
            searchPlaceholder="Search departments by name or description..."
            :filters="[]" />
        
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
