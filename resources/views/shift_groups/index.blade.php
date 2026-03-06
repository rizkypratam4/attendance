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

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <x-shift-groups.stat-card
        label="Total Members"
        value="634"
        subtext="+12 this month"
        subtext-color="#22c55e"
        icon-bg="rgba(124,58,237,.18)"
        icon='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
              </svg>'
    />
    <x-shift-groups.stat-card
        label="Active Shifts"
        value="12"
        subtext="Across 4 groups"
        icon-bg="rgba(124,58,237,.18)"
        icon='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
              </svg>'
    />
    <x-shift-groups.stat-card
        label="Roster Health"
        value="98.2%"
        subtext="Optimized"
        subtext-color="#22c55e"
        icon-bg="rgba(34,197,94,.15)"
        icon='<svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
              </svg>'
    />
</div>

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