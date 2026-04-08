@extends('layouts.app')

@section('title', 'Shift Groups')

@php $active = 'shift-groups'; @endphp

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Shift Groups</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Manage and organize your organizational work shift groups efficiently.</p>
    </div>
    <button onclick="openM('mAddShiftGroup')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="font-size:14px">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add Shift Group
    </button>
</div>

{{-- Search Bar --}}
<x-shift-groups.search-bar />

{{-- Table --}}
<x-shift-groups.table
    :shiftGroups="$shiftGroups"
    :current-page="$currentPage"
    :total-pages="$totalPages"
    :from="$from"
    :to="$to"
    :total="$total"
/>


{{-- Modal --}}
@include('shift_groups.create')
@include('shift_groups.edit')

@endsection

@push('styles')
<style>
.sg-row { transition: background .15s; }
.sg-row:hover { background: var(--bg-hover); }
.sg-row.hidden-row { display: none; }
</style>
@endpush

@push('scripts')
<script>
function filterGroups() {
    const q = document.getElementById('sgSearch').value.toLowerCase();
    document.querySelectorAll('.sg-row').forEach(row => {
        const d = row.getAttribute('data-name') || '';
        row.classList.toggle('hidden-row', q.length > 0 && !d.includes(q));
    });
}
</script>
@endpush