@extends('layouts.app')

@section('title', 'Data Karyawan')

@php $active = 'employees'; @endphp

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Data Karyawan</h1>
            <p style="font-size:13px;color:var(--text-3);margin-top:5px">Kelola dan lihat semua data staf di satu tempat.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button onclick="openAddEmployee()"
                class="purbtn flex items-center justify-center gap-2 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-xs sm:text-sm">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    class="flex-shrink-0">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                <span class="hidden sm:inline">Tambah Karyawan</span>
                <span class="sm:hidden">Tambah</span>
            </button>

            <button onclick="openM('mImportEmployee')"
                class="flex items-center justify-center gap-2 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-xs sm:text-sm"
                style="background:rgba(20,184,166,.2);color:#14b8a6;border:1px solid rgba(20,184,166,.3);cursor:pointer;transition:all 0.2s"
                onmouseover="this.style.background='rgba(20,184,166,.3)';this.style.borderColor='rgba(20,184,166,.5)';this.style.transform='translateY(-2px)'"
                onmouseout="this.style.background='rgba(20,184,166,.2)';this.style.borderColor='rgba(20,184,166,.3)';this.style.transform='translateY(0)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" class="flex-shrink-0">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                    <polyline points="17 8 12 3 7 8" />
                    <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
                Import
            </button>

            <a href="{{ route('employees.template') }}"
                class="flex items-center justify-center gap-2 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-xs sm:text-sm"
                style="background:rgba(251,191,36,.15);color:#fbbf24;border:1px solid rgba(251,191,36,.3);text-decoration:none;transition:all 0.2s"
                onmouseover="this.style.background='rgba(251,191,36,.25)';this.style.transform='translateY(-2px)'"
                onmouseout="this.style.background='rgba(251,191,36,.15)';this.style.transform='translateY(0)'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5" class="flex-shrink-0">
                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="3" x2="12" y2="15" />
                </svg>
                Template
            </a>
        </div>
    </div>

    <div class="card rounded-2xl" style="overflow:hidden">
        @if (session('success'))
            <div class="px-5 py-3"
                style="color:#16a34a;background:rgba(16,163,127,.06);border-bottom:1px solid var(--border)">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="px-5 py-3"
                style="color:#ef4444;background:rgba(239,68,68,.04);border-bottom:1px solid var(--border)">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('import_errors'))
            <div class="px-5 py-3"
                style="color:#f97316;background:rgba(249,115,22,.04);border-bottom:1px solid var(--border)">
                <strong>Kesalahan impor:</strong>
                <ul style="margin:6px 0 0 18px">
                    @foreach (session('import_errors') as $err)
                        <li style="font-size:13px;color:var(--text-2)">{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── SEARCH + FILTER ── --}}
        <x-search-filter searchId="empSearch" searchPlaceholder="Cari..." :filters="[
            [
                'id' => 'department',
                'label' => 'Departemen',
                'options' => $departments->pluck('name', 'id')->toArray(),
            ],
            [
                'id' => 'branch',
                'label' => 'Cabang',
                'options' => $branches->pluck('name', 'id')->toArray(),
            ],
            [
                'id' => 'status',
                'label' => 'Status',
                'options' => [
                    '1' => 'Aktif',
                    '0' => 'Nonaktif',
                ],
            ],
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
