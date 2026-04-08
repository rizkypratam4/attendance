@extends('layouts.app')

@section('title', 'Branch Management')

@php $active = 'branch'; @endphp

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Branch Management</h1>
            <p style="font-size:13px;color:var(--text-3);margin-top:5px">Configure and manage physical office locations
                across your organization.</p>
        </div>
        <button onclick="openM('mAddBranch')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:14px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                <circle cx="12" cy="10" r="3" />
            </svg>
            Add New Branch
        </button>
    </div>

    <x-filter-bar />

    {{-- ── BRANCHES TABLE ── --}}
    <div class="card rounded-2xl mb-5" style="overflow:hidden">
        <div class="overflow-x-auto">
            <x-branches.table :branches="$branches" />
            <x-ui.pagination-footer :paginator="$branches" />
        </div>
    </div>

    {{-- ── MODAL: ADD NEW BRANCH ── --}}
    @include('branches.create')

    {{-- ── MODAL: EDIT BRANCH ── --}}
    @include('branches.edit')

@endsection

@push('styles')
    <style>
        .branch-row {
            transition: background .15s;
        }

        .branch-row:hover {
            background: var(--bg-hover);
        }

        .branch-row.hidden-row {
            display: none;
        }

        .dd-menu {
            animation: dropIn .15s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function filterBranches() {
            const q = document.getElementById('branchSearch').value.toLowerCase();
            document.querySelectorAll('.branch-row').forEach(row => {
                const name = row.getAttribute('data-name') || '';
                row.classList.toggle('hidden-row', q.length > 0 && !name.includes(q));
            });
        }

        function toggleDropdown(id) {
            const menu = document.getElementById(id);
            const allMenus = document.querySelectorAll('.dd-menu');
            allMenus.forEach(m => {
                if (m.id !== id) m.classList.add('hidden');
            });
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
