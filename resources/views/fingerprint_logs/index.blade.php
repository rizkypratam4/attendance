@extends('layouts.app')

@section('title', 'Fingerprint Logs')

@php $active = 'fingerprint-logs'; @endphp

@section('content')

    {{-- ── PAGE HEADER ── --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Fingerprint Logs</h1>
            <p style="font-size:13px;color:var(--text-3);margin-top:5px">Raw biometric data from <span
                    style="font-family:monospace;color:var(--text-2)">dbo.AttendanceMachinePolling</span></p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            {{-- Sync Button --}}
            <div class="flex items-center gap-2">
                {{-- Sync Hari Ini --}}
                <form method="POST" action="{{ route('fingerprint.sync') }}" id="syncForm">
                    @csrf
                    <button type="submit" id="syncBtn"
                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold"
                        style="font-size:13.5px;background:rgba(34,197,94,.2);color:#22c55e;border:1px solid rgba(34,197,94,.3);cursor:pointer">
                        <svg id="syncIcon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5">
                            <path d="M23 4v6h-6" />
                            <path d="M1 20v-6h6" />
                            <path d="M3.51 9a9 9 0 0114.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0020.49 15" />
                        </svg>
                        <span id="syncLabel">Sync Hari Ini</span>
                    </button>
                </form>

                {{-- Sync Range --}}
                <button onclick="openM('mSyncRange')"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold ib-bg"
                    style="font-size:13.5px;color:var(--text-2)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                    </svg>
                    Sync Range
                </button>

                {{-- Export --}}
                <button class="flex items-center gap-2 px-4 py-2.5 rounded-xl font-semibold ib-bg"
                    style="font-size:13.5px;color:var(--text-2)">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                        <polyline points="7 10 12 15 17 10" />
                        <line x1="12" y1="15" x2="12" y2="3" />
                    </svg>
                    Export
                </button>
            </div>
        </div>
    </div>

    {{-- ── FLASH MESSAGE ── --}}
    @if (session('success'))
        <div class="mb-4 px-4 py-3 rounded-xl flex items-center gap-3"
            style="background:rgba(34,197,94,.12);border:1px solid rgba(34,197,94,.25);color:#22c55e;font-size:13px">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M22 11.08V12a10 10 0 11-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 px-4 py-3 rounded-xl flex items-center gap-3"
            style="background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.25);color:#f87171;font-size:13px">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── STATS ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">

        <div class="card rounded-2xl p-4">
            <p
                style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">
                Total Log</p>
            <p style="font-size:26px;font-weight:800;color:var(--text-1);line-height:1;font-family:monospace">
                {{ number_format($stats['total']) }}</p>
        </div>

        <div class="card rounded-2xl p-4">
            <p
                style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">
                Belum Diproses</p>
            <p style="font-size:26px;font-weight:800;color:#f59e0b;line-height:1;font-family:monospace">
                {{ number_format($stats['unprocessed']) }}</p>
        </div>

        <div class="card rounded-2xl p-4">
            <p
                style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">
                Sudah Diproses</p>
            <p style="font-size:26px;font-weight:800;color:#22c55e;line-height:1;font-family:monospace">
                {{ number_format($stats['processed']) }}</p>
        </div>

        <div class="card rounded-2xl p-4">
            <p
                style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px">
                Log Hari Ini</p>
            <p style="font-size:26px;font-weight:800;color:var(--text-1);line-height:1;font-family:monospace">
                {{ number_format($stats['today']) }}</p>
        </div>

    </div>

    {{-- ── FILTER BAR ── --}}
    <div class="card rounded-2xl p-4 mb-5">
        <form method="GET" action="{{ route('fingerprint.index') }}">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">

                {{-- Tanggal --}}
                <div>
                    <label
                        style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Tanggal</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl"
                        style="background:var(--bg-input);border:1px solid var(--border-in)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)"
                            stroke-width="2" class="flex-shrink-0">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        <input type="date" name="date" value="{{ request('date', today()->toDateString()) }}"
                            style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:inherit;cursor:pointer">
                    </div>
                </div>

                {{-- Barcode --}}
                <div>
                    <label
                        style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Barcode</label>
                    <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl"
                        style="background:var(--bg-input);border:1px solid var(--border-in)">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)"
                            stroke-width="2" class="flex-shrink-0">
                            <line x1="3" y1="5" x2="3" y2="19" />
                            <line x1="7" y1="5" x2="7" y2="19" />
                            <line x1="11" y1="5" x2="11" y2="19" />
                            <line x1="15" y1="5" x2="15" y2="19" />
                            <line x1="19" y1="5" x2="19" y2="19" />
                        </svg>
                        <input type="text" name="barcode" placeholder="Cari barcode..."
                            value="{{ request('barcode') }}"
                            style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:monospace">
                    </div>
                </div>

                {{-- Tipe --}}
                <div>
                    <label
                        style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Tipe</label>
                    <div class="relative">
                        <select name="type" class="w-full px-3 py-2.5 rounded-xl appearance-none"
                            style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;outline:none;cursor:pointer;font-family:inherit">
                            <option value="">Semua Tipe</option>
                            <option value="0" {{ request('type') === '0' ? 'selected' : '' }}>Clock In</option>
                            <option value="1" {{ request('type') === '1' ? 'selected' : '' }}>Clock Out</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" width="12"
                            height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)"
                            stroke-width="2.5">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </div>
                </div>

                {{-- Status + Reset --}}
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label
                            style="font-size:10.5px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;display:block;margin-bottom:7px">Status</label>
                        <div class="relative">
                            <select name="processed" class="w-full px-3 py-2.5 rounded-xl appearance-none"
                                style="background:var(--bg-input);border:1px solid var(--border-in);color:var(--text-2);font-size:13px;outline:none;cursor:pointer;font-family:inherit">
                                <option value="">Semua</option>
                                <option value="0" {{ request('processed') === '0' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="1" {{ request('processed') === '1' ? 'selected' : '' }}>Diproses
                                </option>
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" width="12"
                                height="12" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)"
                                stroke-width="2.5">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </div>
                    </div>
                    <div style="padding-top:24px">
                        <button type="submit" class="px-4 py-2.5 rounded-xl font-semibold"
                            style="font-size:13.5px;background:rgba(124,58,237,.15);color:#a78bfa;border:1px solid rgba(124,58,237,.3);cursor:pointer;white-space:nowrap">
                            Filter
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- ── TABLE ── --}}
    <div class="card rounded-2xl" style="overflow:hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="logsTable" style="border-collapse:collapse;min-width:750px">
                <thead>
                    <tr style="border-bottom:1px solid var(--border)">
                        <th class="text-left px-5 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">No
                        </th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                            Barcode</th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                            Karyawan</th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                            Tanggal</th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                            Waktu</th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">Tipe
                        </th>
                        <th class="text-left px-4 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                            Status</th>
                        <th class="text-right px-5 py-3 font-semibold"
                            style="font-size:10.5px;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase">
                            Action</th>
                    </tr>
                </thead>
                <tbody>

                    @forelse($logs as $index => $log)
                        <tr class="log-row" style="border-bottom:1px solid var(--border)">

                            {{-- No --}}
                            <td class="px-5 py-3.5">
                                <span style="font-size:12.5px;color:var(--text-3);font-family:monospace;font-weight:600">
                                    {{ $logs->firstItem() + $index }}
                                </span>
                            </td>

                            {{-- Barcode --}}
                            <td class="px-4 py-3.5">
                                <span style="font-size:13px;color:var(--text-2);font-family:monospace;font-weight:600">
                                    {{ $log->barcode }}
                                </span>
                            </td>

                            {{-- Karyawan --}}
                            <td class="px-4 py-3.5">
                                @if ($log->employee)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-white flex-shrink-0"
                                            style="background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:11px">
                                            {{ strtoupper(substr($log->employee->name, 0, 1)) }}{{ strtoupper(substr(strrchr($log->employee->name, ' ') ?: ' ', 1, 1)) }}
                                        </div>
                                        <div>
                                            <p style="font-size:13.5px;font-weight:700;color:var(--text-1)">
                                                {{ $log->employee->name }}</p>
                                            <p style="font-size:11px;color:var(--text-3)">
                                                {{ $log->employee->department->name ?? '—' }}</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                            style="background:rgba(239,68,68,.15)">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                                stroke="#f87171" stroke-width="2.5">
                                                <circle cx="12" cy="12" r="10" />
                                                <line x1="12" y1="8" x2="12" y2="12" />
                                                <line x1="12" y1="16" x2="12.01" y2="16" />
                                            </svg>
                                        </div>
                                        <span style="font-size:12.5px;color:#f87171">Tidak ditemukan</span>
                                    </div>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-4 py-3.5" style="font-size:13px;color:var(--text-2)">
                                {{ \Carbon\Carbon::parse($log->attendance_date)->format('d M Y') }}
                            </td>

                            {{-- Waktu --}}
                            <td class="px-4 py-3.5">
                                <span style="font-size:13px;font-weight:700;color:var(--text-1);font-family:monospace">
                                    {{ \Carbon\Carbon::parse($log->attendance_time)->format('H:i:s') }}
                                </span>
                            </td>

                            {{-- Tipe --}}
                            <td class="px-4 py-3.5">
                                @if ($log->attendance_type == 0)
                                    <span class="px-2.5 py-1 rounded-lg font-bold"
                                        style="font-size:10.5px;background:rgba(34,197,94,.2);color:#22c55e;letter-spacing:.04em">
                                        ↓ CLOCK IN
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg font-bold"
                                        style="font-size:10.5px;background:rgba(0,145,255,.2);color:#60a5fa;letter-spacing:.04em">
                                        ↑ CLOCK OUT
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-3.5">
                                @if ($log->is_processed)
                                    <span class="px-2.5 py-1 rounded-lg font-bold"
                                        style="font-size:10.5px;background:rgba(34,197,94,.1);color:#22c55e;letter-spacing:.04em;border:1px solid rgba(34,197,94,.2)">
                                        ✓ Diproses
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg font-bold"
                                        style="font-size:10.5px;background:rgba(245,158,11,.1);color:#f59e0b;letter-spacing:.04em;border:1px solid rgba(245,158,11,.2)">
                                        ⏳ Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Action --}}
                            <td class="px-5 py-3.5 text-right">
                                <button
                                    class="log-trigger ib-bg w-8 h-8 rounded-lg flex items-center justify-center ml-auto"
                                    data-id="{{ $log->id }}" data-barcode="{{ $log->barcode }}"
                                    data-name="{{ $log->employee->name ?? 'Unknown' }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <div style="color:var(--text-3)">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="1.5" style="margin:0 auto 12px;opacity:.3">
                                        <path d="M3 5h18M3 10h18M3 15h18M3 20h18" />
                                    </svg>
                                    <p style="font-size:14px;font-weight:600;color:var(--text-3)">Tidak ada data</p>
                                    <p style="font-size:12px;margin-top:4px;opacity:.6">Coba ubah filter atau sync data
                                        dari mesin</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
                style="border-top:1px solid var(--border)">
                <p style="font-size:13px;color:var(--text-3)">
                    Showing <strong style="color:var(--text-2)">{{ $logs->firstItem() }}–{{ $logs->lastItem() }}</strong>
                    of
                    <strong style="color:var(--text-2)">{{ number_format($logs->total()) }}</strong> logs
                </p>
                <div class="flex items-center gap-1">
                    {{-- Prev --}}
                    @if ($logs->onFirstPage())
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" disabled
                            style="opacity:.4">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </button>
                    @else
                        <a href="{{ $logs->previousPageUrl() }}"
                            class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M15 18l-6-6 6-6" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach ($logs->getUrlRange(max(1, $logs->currentPage() - 2), min($logs->lastPage(), $logs->currentPage() + 2)) as $page => $url)
                        @if ($page == $logs->currentPage())
                            <button class="w-8 h-8 rounded-lg font-semibold purbtn"
                                style="font-size:13px">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}"
                                class="w-8 h-8 rounded-lg ib-bg flex items-center justify-center"
                                style="font-size:13px;color:var(--text-2)">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($logs->hasMorePages())
                        <a href="{{ $logs->nextPageUrl() }}"
                            class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </a>
                    @else
                        <button class="w-8 h-8 rounded-lg flex items-center justify-center ib-bg" disabled
                            style="opacity:.4">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <path d="M9 18l6-6-6-6" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>

@endsection

{{-- Modal Sync Range --}}
<div class="mbk" id="mSyncRange" onclick="closeOut(event,'mSyncRange')">
    <div class="mbox" style="max-width:420px">
        <div class="mhdr">
            <span class="mtitle">Sync Range Tanggal</span>
            <button class="mclose" onclick="closeM('mSyncRange')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <form method="POST" action="{{ route('fingerprint.sync') }}" id="syncRangeForm">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="mlabel">Tanggal Mulai</label>
                        <input type="date" name="from" class="minput"
                               value="{{ today()->toDateString() }}" required>
                    </div>
                    <div>
                        <label class="mlabel">Tanggal Selesai</label>
                        <input type="date" name="to" class="minput"
                               value="{{ today()->toDateString() }}" required>
                    </div>
                    <div class="px-3 py-2.5 rounded-xl"
                         style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2)">
                        <p style="font-size:12px;color:#f59e0b">
                            ⚠️ Sync range besar bisa memakan waktu lama. Gunakan dengan bijak.
                        </p>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeM('mSyncRange')"
                            class="flex-1 py-2.5 rounded-xl font-medium"
                            style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                        Batal
                    </button>
                    <button type="submit" id="syncRangeBtn"
                            class="flex-1 purbtn py-2.5 rounded-xl font-semibold"
                            style="font-size:14px">
                        Sync Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .log-row {
            transition: background .15s;
        }

        .log-row:hover {
            background: var(--bg-hover);
        }

        #logActDD {
            position: fixed;
            z-index: 9999;
            min-width: 160px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 5px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .5);
            display: none;
        }

        #logActDD.show {
            display: block;
            animation: ddIn .14s ease;
        }

        @keyframes ddIn {
            from {
                opacity: 0;
                transform: translateY(-5px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .log-dd-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-2);
            background: transparent;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: background .12s;
            font-family: inherit;
            white-space: nowrap;
        }

        .log-dd-item:hover {
            background: var(--bg-hover);
            color: var(--text-1);
        }

        .log-dd-danger {
            color: #f87171 !important;
        }

        .log-dd-danger:hover {
            background: rgba(239, 68, 68, .12) !important;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .spin {
            animation: spin .8s linear infinite;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // ── Sync loading state ──
        document.getElementById('syncForm').addEventListener('submit', function() {
            const btn = document.getElementById('syncBtn');
            const icon = document.getElementById('syncIcon');
            const label = document.getElementById('syncLabel');
            btn.disabled = true;
            icon.classList.add('spin');
            label.textContent = 'Syncing...';
        });

        // ── Global Dropdown ──
        let _activeLogTrigger = null;

        document.addEventListener('DOMContentLoaded', function() {
            const dd = document.createElement('div');
            dd.id = 'logActDD';
            dd.innerHTML = `
        <button class="log-dd-item" id="logViewBtn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            View Detail
        </button>
        <div style="height:1px;background:var(--border);margin:4px 8px"></div>
        <button class="log-dd-item log-dd-danger" id="logDeleteBtn">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
            </svg>
            Delete
        </button>
    `;
            document.body.appendChild(dd);

            document.querySelectorAll('.log-trigger').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const same = _activeLogTrigger === btn && dd.classList.contains('show');
                    closeLogDD();
                    if (same) return;

                    _activeLogTrigger = btn;
                    const rect = btn.getBoundingClientRect();
                    const ddWidth = 164;
                    let left = rect.right - ddWidth;
                    let top = rect.bottom + 6;
                    if (left < 8) left = 8;
                    if (top + 110 > window.innerHeight) top = rect.top - 114;

                    dd.style.left = left + 'px';
                    dd.style.top = top + 'px';
                    dd.classList.add('show');
                });
            });

            document.getElementById('logViewBtn').addEventListener('click', () => {
                if (!_activeLogTrigger) return;
                const id = _activeLogTrigger.dataset.id;
                closeLogDD();
                // redirect ke detail page jika ada
                // window.location.href = `/fingerprint/${id}`;
            });

            document.getElementById('logDeleteBtn').addEventListener('click', () => {
                if (!_activeLogTrigger) return;
                const id = _activeLogTrigger.dataset.id;
                const barcode = _activeLogTrigger.dataset.barcode;
                const name = _activeLogTrigger.dataset.name;
                closeLogDD();

                Swal.fire({
                    title: 'Hapus log ini?',
                    text: `Barcode ${barcode} (${name}) akan dihapus permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#374151',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    background: '#1e1b2e',
                    color: '#e2e8f0',
                }).then(result => {
                    if (result.isConfirmed) {
                        // submit form delete
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/fingerprint/${id}`;
                        form.innerHTML = `
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#logActDD') && !e.target.closest('.log-trigger')) {
                    closeLogDD();
                }
            });

            window.addEventListener('scroll', closeLogDD, true);
        });

        function closeLogDD() {
            document.getElementById('logActDD')?.classList.remove('show');
        }
    </script>
@endpush
