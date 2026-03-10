@extends('layouts.app')

@section('title', 'Shift Schedules')

@php $active = 'shift-schedules'; @endphp

@section('content')

{{-- PAGE HEADER --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Shift Schedules</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Manage detailed schedules tied to shift codes.</p>
    </div>
    <button onclick="openAddShiftSchedule()"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="font-size:14px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="16"/>
            <line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        Add New Schedule
    </button>
</div>

<div class="card rounded-2xl px-4 py-3 mb-5 flex items-center gap-3">
    <div class="flex items-center gap-2 flex-1 px-3 py-2 rounded-xl" style="background:var(--bg-input);border:1px solid var(--border-in)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        <input type="text" id="scheduleSearch" placeholder="Search by schedule or shift code..."
               style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit">
    </div>

    <div class="relative flex-shrink-0">
        <select id="dayTypeFilter" onchange="filterSchedules()"
                class="px-4 py-2 rounded-xl font-medium appearance-none pr-8"
                style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13.5px;outline:none;cursor:pointer;font-family:inherit">
            <option value="">All Day Types</option>
            <option value="senin_kamis">Senin - Kamis</option>
            <option value="jumat">Jumat</option>
            <option value="sabtu">Sabtu</option>
        </select>
        <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5">
            <path d="M6 9l6 6 6-6"/>
        </svg>
    </div>

    <button onclick="toggleSortSchedules(this)"
            class="w-9 h-9 rounded-xl flex items-center justify-center font-bold flex-shrink-0 ib-bg"
            style="font-size:12px;color:var(--text-2)">
        AZ
    </button>
</div>

<div class="card rounded-2xl mb-5" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table id="scheduleTable" class="w-full" style="border-collapse:collapse;min-width:700px">
            <thead>
                <tr style="background:rgba(124,58,237,.10);border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Code</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Shift Code</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Day Type</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Time</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Off?</th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Overnight?</th>
                    <th class="text-right px-5 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $schedule)
                <tr class="schedule-row" style="border-bottom:1px solid var(--border)"
                    data-schedule-code="{{ $schedule->schedule_code }}"
                    data-shift-code-name="{{ $schedule->shiftCode->code ?? '' }}"
                    data-day-type="{{ $schedule->day_type }}">
                    <td class="px-5 py-4">
                        <p style="font-size:14px;font-weight:700;color:var(--text-1)">{{ $schedule->schedule_code }}</p>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">
                        {{ $schedule->shiftCode->code ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">
                        {{ ucfirst(str_replace('_',' ', $schedule->day_type)) }}
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $schedule->is_day_off ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $schedule->is_day_off ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $schedule->is_overnight ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $schedule->is_overnight ? 'Yes' : 'No' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEditShiftSchedule(this)"
                                    data-update-route="{{ route('shift_schedules.update', $schedule->id) }}"
                                    data-shift-code-id="{{ $schedule->shift_code_id }}"
                                    data-day-type="{{ $schedule->day_type }}"
                                    data-schedule-code="{{ $schedule->schedule_code }}"
                                    data-start-time="{{ $schedule->start_time }}"
                                    data-end-time="{{ $schedule->end_time }}"
                                    data-is-day-off="{{ $schedule->is_day_off ? 1 : 0 }}"
                                    data-is-overnight="{{ $schedule->is_overnight ? 1 : 0 }}"
                                    class="text-blue-600 hover:text-blue-800 p-1">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button onclick="openDeleteShiftSchedule('{{ $schedule->schedule_code }}', '{{ $schedule->id }}')"
                                    class="text-red-600 hover:text-red-800 p-1">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14a2 2 0 1 1-2 2H7a2 2 0 1 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                            </button>
                            <form id="delete-form-{{ $schedule->id }}" action="{{ route('shift_schedules.destroy', $schedule->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 px-6 text-center text-gray-500" style="border-bottom:1px solid var(--border)">
                        No schedules found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($schedules->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $schedules->links() }}
    </div>
    @endif
</div>

@include('shift_schedules.create')
@include('shift_schedules.edit')

@endsection

@push('styles')
<style>
    .schedule-row { transition: background .15s; }
    .schedule-row:hover { background: var(--bg-hover); }
</style>
@endpush