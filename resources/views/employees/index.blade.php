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
        <div class="flex gap-2">
            <button onclick="openAddEmployee()"
                class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:14px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Add Employee
            </button>

            <button onclick="openM('mImportEmployee')"
                class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:14px">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Import
            </button>
        </div>    </div>

    <div class="card rounded-2xl" style="overflow:hidden">
        @if(session('success'))
            <div class="px-5 py-3" style="color:#16a34a;background:rgba(16,163,127,.06);border-bottom:1px solid var(--border)">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="px-5 py-3" style="color:#ef4444;background:rgba(239,68,68,.04);border-bottom:1px solid var(--border)">
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('import_errors'))
            <div class="px-5 py-3" style="color:#f97316;background:rgba(249,115,22,.04);border-bottom:1px solid var(--border)">
                <strong>Import errors:</strong>
                <ul style="margin:6px 0 0 18px">
                    @foreach(session('import_errors') as $err)
                        <li style="font-size:13px;color:var(--text-2)">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── SEARCH + FILTER ── --}}
        <x-search-filter 
            searchId="empSearch"
            searchPlaceholder="Search by name, NIK, or barcode..."
            :filters="[
                [
                    'id' => 'department',
                    'label' => 'All Departments',
                    'options' => $departments->pluck('name', 'id')->toArray()
                ],
                [
                    'id' => 'branch',
                    'label' => 'All Branches',
                    'options' => $branches->pluck('name', 'id')->toArray()
                ],
                [
                    'id' => 'status',
                    'label' => 'Employee Status',
                    'options' => [
                        '1' => 'Active',
                        '0' => 'Inactive'
                    ]
                ]
            ]" />

        <div class="overflow-x-auto">
            <x-employees.table :employees="$employees" />
            <x-ui.pagination-footer :paginator="$employees" />
        </div>

    </div>

    @include('employees.create')
    @include('employees.import')    
    @include('employees.edit')


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
