@extends('layouts.app')

@section('title', 'Location Management')

@php $active = 'location'; @endphp

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 style="font-size:22px;font-weight:800;color:var(--text-1);line-height:1.2">Location Management</h1>
            <p style="font-size:13px;color:var(--text-3);margin-top:5px">Configure physical work sites and geofencing
                parameters.</p>
        </div>
        <button onclick="openM('mAddLocation')"
            class="purbtn flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold flex-shrink-0" style="font-size:14px">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="16" />
                <line x1="8" y1="12" x2="16" y2="12" />
            </svg>
            Add Location
        </button>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <x-stat-card title="Total Locations" :value="$totalLocations" subtitle="" subtitleColor="#7c3aed">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                <circle cx="12" cy="10" r="3" />
            </svg>
        </x-stat-card>

        <x-stat-card title="Active Locations" :value="$activeLocations" subtitle="" subtitleColor="#16a34a">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="12" r="4" />
            </svg>
        </x-stat-card>

        <x-stat-card title="Type Breakdown" :value="$typeSummary" subtitle="" subtitleColor="#f59e0b">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                <circle cx="9" cy="7" r="4" />
            </svg>
        </x-stat-card>
    </div>

    {{-- ── LOCATION TABLE ── --}}
    <x-ui.card class="mb-5" style="overflow:hidden">
        <div class="overflow-x-auto">
            <x-locations.table :locations="$locations" />
        </div>
        <x-ui.pagination-footer :paginator="$locations" />
    </x-ui.card>

    <x-ui.card class="overflow-hidden">
        <div class="flex items-center gap-3 px-5 py-4" style="border-bottom:1px solid var(--border)">
            <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0"
                style="background:rgba(124,58,237,.20)">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="2" y1="12" x2="22" y2="12" />
                    <path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z" />
                </svg>
            </div>
            <h2 style="font-size:15px;font-weight:700;color:var(--text-1)">Visual Geographic View</h2>
        </div>
        <div id="map" style="height:320px;position:relative;"></div>
    </x-ui.card>

    <script>
        window.locationData = @json($locations->items());
    </script>


    @include('locations.create')
    @include('locations.edit')
@endsection

@push('styles')
    <style>
        .loc-row {
            transition: background .15s;
        }

        .loc-row:hover {
            background: var(--bg-hover);
        }
    </style>
@endpush
