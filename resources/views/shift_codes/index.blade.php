@extends('layouts.app')

@section('title', 'Shift Codes')

@php $active = 'shift-codes'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Shift Codes</h1>
        <p style="font-size:13px;color:var(--text-3);margin-top:5px">Configure and manage standard working hours for your teams.</p>
    </div>
    <button onclick="openM('mCreateShiftCode')"
            class="purbtn flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold flex-shrink-0"
            style="font-size:14px">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="16"/>
            <line x1="8" y1="12" x2="16" y2="12"/>
        </svg>
        Add New Shift Code
    </button>
</div>

<div class="card rounded-2xl px-4 py-3 mb-5 flex items-center gap-3">
    <div class="flex items-center gap-2 flex-1 px-3 py-2 rounded-xl" style="background:var(--bg-input);border:1px solid var(--border-in)">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
        </svg>
        <input type="text" id="shiftSearch" placeholder="Search by code or description..."
               style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13.5px;width:100%;font-family:inherit">
    </div>

    <div class="relative flex-shrink-0">
        <select id="statusFilter" onchange="filterShifts()"
                class="px-4 py-2 rounded-xl font-medium appearance-none pr-8"
                style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13.5px;outline:none;cursor:pointer;font-family:inherit">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <svg class="absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2.5">
            <path d="M6 9l6 6 6-6"/>
        </svg>
    </div>

    <button onclick="toggleSort(this)"
            class="w-9 h-9 rounded-xl flex items-center justify-center font-bold flex-shrink-0 ib-bg"
            style="font-size:12px;color:var(--text-2)">
        AZ
    </button>
</div>

<div class="card rounded-2xl mb-5" style="overflow:hidden">
    <div class="overflow-x-auto">
        <table id="shiftTable" class="w-full" style="border-collapse:collapse;min-width:580px">
            <thead>
                <tr style="background:rgba(124,58,237,.10);border-bottom:1px solid var(--border)">
                    <th class="text-left px-5 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Code
                    </th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Shift Name
                    </th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        IDT Status
                    </th>
                    <th class="text-left px-4 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Created At
                    </th>
                    <th class="text-right px-5 py-3.5 font-semibold"
                        style="font-size:11px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($shiftCodes as $shiftCode)
                <tr class="shift-row" style="border-bottom:1px solid var(--border)" data-code="{{ $shiftCode->code }}" data-name="{{ $shiftCode->shift->name ?? '' }}" data-status="{{ $shiftCode->has_idt ? 'active' : 'inactive' }}">
                    <td class="px-5 py-4">
                        <p style="font-size:14px;font-weight:700;color:var(--text-1)">
                            {{ $shiftCode->code }}
                        </p>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">
                        {{ $shiftCode->shift->name ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $shiftCode->has_idt ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $shiftCode->has_idt ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td class="px-4 py-4" style="font-size:13px;color:var(--text-2)">
                        {{ $shiftCode->created_at->format('d M Y') }}
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEditShiftCode({{ $shiftCode->id }}, '{{ $shiftCode->code }}', '{{ $shiftCode->shift_id }}', {{ $shiftCode->has_idt ? 1 : 0 }})"
                                    class="text-blue-600 hover:text-blue-800 p-1">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </button>
                            <button onclick="openDeleteShiftCode('{{ $shiftCode->code }}', '{{ $shiftCode->id }}')"
                                    class="text-red-600 hover:text-red-800 p-1">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 6h18"/>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                            </button>
                            <form id="delete-form-shift-code-{{ $shiftCode->id }}"
                                action="{{ route('shift_codes.destroy', $shiftCode->id) }}"
                                method="POST"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 px-6 text-center text-gray-500" style="border-bottom:1px solid var(--border)">
                        No shift codes found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($shiftCodes->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $shiftCodes->links() }}
    </div>
    @endif
</div>



{{-- ── MODAL: ADD SHIFT CODE ── --}}
@include('shift_codes.create')

{{-- ── MODAL: EDIT SHIFT CODE ── --}}
@include('shift_codes.edit')


@endsection

@push('styles')
<style>
    .shift-row { transition: background .15s; }
    .shift-row:hover { background: var(--bg-hover); }
    .color-dot, .edit-color-dot { transition: transform .15s, border-color .15s; }
    .color-dot:hover, .edit-color-dot:hover { transform: scale(1.2); }
    .color-dot.selected, .edit-color-dot.selected { border-color: #fff !important; transform: scale(1.15); }
</style>
@endpush