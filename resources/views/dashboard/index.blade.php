@extends('layouts.app')

@section('title', 'Dashboard')

@php $active = 'dashboard'; $pageTitle = 'Dashboard'; @endphp

@section('content')

    {{-- ROW 1: STATISTICS CARDS --}}
    <div class="stat-grid grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5 mb-4 lg:mb-6">
        
        {{-- Total Active Employees --}}
        <x-stat-card title="Total Karyawan Aktif" :value="$totalActiveEmployees ?? 129" meta="">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        {{-- Present Today --}}
        <x-stat-card title="Hadir Hari Ini" :value="$presentToday ?? 112" meta="+8" style="background-color: rgba(16, 185, 129, .1)">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                    <path d="M9 12l2 2 4-4m7-1a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        {{-- Late Today --}}
        <x-stat-card title="Terlambat Hari Ini" :value="$lateToday ?? 12" meta="+2" style="background-color: rgba(245, 158, 11, .1)">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <polyline points="12 6 12 12 16 14" />
                </svg>
            </x-slot:icon>
        </x-stat-card>

        {{-- Absent Today --}}
        <x-stat-card title="Tidak Hadir Hari Ini" :value="$absentToday ?? 5" meta="+1" style="background-color: rgba(239, 68, 68, .1)">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="15" y1="9" x2="9" y2="15" />
                    <line x1="9" y1="9" x2="15" y2="15" />
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- ROW 2: VISUALIZATION DATA --}}
    <div class="chart-grid grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-5 mb-4 lg:mb-6">
        
        {{-- Line Chart: 7 Days Attendance --}}
        <div class="card rounded-2xl p-4 lg:p-6 xl:col-span-2">
            <div class="flex items-start justify-between mb-4 lg:mb-5">
                <div>
                    <h2 style="font-size:16px;font-weight:700;color:var(--text-1)">
                        Tren Kehadiran 7 Hari Terakhir
                    </h2>
                    <p style="font-size:13px;color:var(--text-3);margin-top:4px">
                        Perbandingan hadir vs tidak hadir
                    </p>
                </div>
            </div>
            <div class="chart-canvas-wrap" style="height:240px">
                <canvas id="attendanceChart"></canvas>
            </div>
            <div class="flex items-center gap-6 mt-5">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                    <span style="font-size:13px;color:var(--text-3)">Hadir</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span>
                    <span style="font-size:13px;color:var(--text-3)">Tidak Hadir</span>
                </div>
            </div>
        </div>

        {{-- Pie Chart: Today Status --}}
        <div class="card rounded-2xl p-4 lg:p-6">
            <div class="mb-5">
                <h2 style="font-size:16px;font-weight:700;color:var(--text-1)">Status Kehadiran Hari Ini</h2>
                <p style="font-size:13px;color:var(--text-3);margin-top:4px">Distribusi status</p>
            </div>
            <div class="chart-canvas-wrap" style="height:240px">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ROW 3: INSIGHTS & MONITORING --}}
    <div class="insights-grid grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-5 mb-4 lg:mb-6">
        
        {{-- Top 5 Late Employees --}}
        <div class="card rounded-2xl p-4 lg:p-6">
            <div class="mb-5">
                <h2 style="font-size:16px;font-weight:700;color:var(--text-1)">Top 5 Karyawan Terlambat Bulan Ini</h2>
                <p style="font-size:13px;color:var(--text-3);margin-top:4px">Frekuensi keterlambatan</p>
            </div>
            <div class="space-y-3">
                @forelse ($topLateEmployees ?? [] as $index => $employee)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center gap-3 flex-1">
                            <span style="font-size:12px;font-weight:700;color:#a78bfa;min-width:20px">{{ $index + 1 }}</span>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:var(--text-1)">
                                    {{ $employee->employee->name ?? 'N/A' }}
                                </p>
                                <p style="font-size:12px;color:var(--text-3)">
                                    {{ $employee->department->name ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#f59e0b">{{ $employee->late_count ?? 0 }}x</span>
                    </div>
                @empty
                    <p style="font-size:13px;color:var(--text-3);text-align:center;padding:30px 0">Tidak ada data keterlambatan</p>
                @endforelse
            </div>
        </div>

        {{-- Top 5 Late Departments --}}
        <div class="card rounded-2xl p-4 lg:p-6">
            <div class="mb-5">
                <h2 style="font-size:16px;font-weight:700;color:var(--text-1)">5 Departemen Tingkat Keterlambatan Tertinggi</h2>
                <p style="font-size:13px;color:var(--text-3);margin-top:4px">Berdasarkan persentase</p>
            </div>
            <div class="space-y-3">
                @forelse ($topLateDepartments ?? [] as $index => $dept)
                    <div class="flex items-center justify-between py-2">
                        <div class="flex items-center gap-3 flex-1">
                            <span style="font-size:12px;font-weight:700;color:#a78bfa;min-width:20px">{{ $index + 1 }}</span>
                            <div class="flex-1">
                                <p style="font-size:13px;font-weight:600;color:var(--text-1)">
                                    {{ $dept->name ?? 'N/A' }}
                                </p>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 mt-1.5">
                                    <div class="bg-orange-400 h-1.5 rounded-full" style="width:{{ $dept->late_percentage ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <span style="font-size:13px;font-weight:700;color:#f59e0b;min-width:45px;text-align:right">{{ $dept->late_percentage ?? 0 }}%</span>
                    </div>
                @empty
                    <p style="font-size:13px;color:var(--text-3);text-align:center;padding:30px 0">Tidak ada data departemen</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ROW 4: QUICK ACCESS SHORTCUTS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-5">
        
        {{-- Process Attendance --}}
        <a href="{{ route('process_attendances.index') ?? '#' }}" class="card rounded-2xl p-6 hover:shadow-lg transition-all text-center" style="text-decoration:none">
            <div class="flex items-center justify-center mb-4">
                <div style="width:48px;height:48px;border-radius:12px;background-color:rgba(124,58,237,.2);display:flex;align-items:center;justify-content:center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2">
                        <path d="M13 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V9z" />
                        <polyline points="13 2 13 9 20 9" />
                    </svg>
                </div>
            </div>
            <h3 style="font-size:15px;font-weight:700;color:var(--text-1);margin-bottom:8px">Process Attendance</h3>
            <p style="font-size:13px;color:var(--text-3)">Proses kehadiran karyawan</p>
        </a>

        {{-- Fingerprint Logs --}}
        <a href="{{ route('fingerprint.index') ?? '#' }}" class="card rounded-2xl p-6 hover:shadow-lg transition-all text-center" style="text-decoration:none">
            <div class="flex items-center justify-center mb-4">
                <div style="width:48px;height:48px;border-radius:12px;background-color:rgba(16,185,129,.2);display:flex;align-items:center;justify-content:center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2">
                        <path d="M12 1v6m0 6v6M4.22 4.22a8 8 0 0111.56 0m0 11.56a8 8 0 01-11.56 0" />
                    </svg>
                </div>
            </div>
            <h3 style="font-size:15px;font-weight:700;color:var(--text-1);margin-bottom:8px">Fingerprint Logs</h3>
            <p style="font-size:13px;color:var(--text-3)">Lihat data sidik jari</p>
        </a>

        {{-- Attendance List --}}
        <a href="{{ route('attendances.index') ?? '#' }}" class="card rounded-2xl p-6 hover:shadow-lg transition-all text-center" style="text-decoration:none">
            <div class="flex items-center justify-center mb-4">
                <div style="width:48px;height:48px;border-radius:12px;background-color:rgba(245,158,11,.2);display:flex;align-items:center;justify-content:center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2">
                        <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <h3 style="font-size:15px;font-weight:700;color:var(--text-1);margin-bottom:8px">Attendance List</h3>
            <p style="font-size:13px;color:var(--text-3)">Daftar lengkap kehadiran</p>
        </a>
    </div>

@endsection

@push('scripts')
<script>
    let attendanceChart = null;
    let statusChart = null;

    function buildCharts() {
        const d  = document.documentElement.getAttribute('data-theme') === 'dark';
        const gc = d ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.07)';
        const tc = d ? '#6b7280' : '#9ca3af';
        const tbg  = d ? '#1c1c26' : '#ffffff';
        const ttt  = d ? '#ffffff' : '#111827';
        const tbd2 = d ? '#9ca3af' : '#6b7280';
        const tbrd = d ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.08)';

        // Data dari Controller
        const attendanceLabels = {!! json_encode($attendanceChartLabels ?? []) !!};
        const attendancePresentData = {!! json_encode($attendanceChartPresent ?? []) !!};
        const attendanceAbsentData = {!! json_encode($attendanceChartAbsent ?? []) !!};
        const statusChartData = {!! json_encode($statusChartData ?? [0, 0, 0, 0]) !!};

        if (attendanceChart) attendanceChart.destroy();
        
        const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
        attendanceChart = new Chart(ctxAttendance, {
            type: 'line',
            data: {
                labels: attendanceLabels,
                datasets: [
                    {
                        label: 'Hadir',
                        data: attendancePresentData,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16,185,129,.15)',
                        fill: true, tension: .4,
                        pointBackgroundColor: '#10b981',
                        pointRadius: 5, pointHoverRadius: 7, borderWidth: 2.5,
                    },
                    {
                        label: 'Tidak Hadir',
                        data: attendanceAbsentData,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,.10)',
                        fill: true, tension: .4,
                        pointBackgroundColor: '#ef4444',
                        pointRadius: 5, pointHoverRadius: 7, borderWidth: 2.5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        labels: { color: tc, font: { size: 13 } }
                    },
                    tooltip: {
                        mode: 'index', intersect: false,
                        backgroundColor: tbg, titleColor: ttt,
                        bodyColor: tbd2, borderColor: tbrd,
                        borderWidth: 1, padding: 12,
                        titleFont: { size: 13, weight: '600' },
                        bodyFont:  { size: 13 },
                    }
                },
                scales: {
                    x: { grid: { color: gc }, ticks: { color: tc, font: { size: 13 } } },
                    y: {
                        grid: { color: gc },
                        ticks: { color: tc, font: { size: 13 } },
                        beginAtZero: true
                    }
                }
            }
        });

        // Build Status Pie Chart
        if (statusChart) statusChart.destroy();

        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        statusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Terlambat', 'Tidak Hadir', 'Day Off'],
                datasets: [{
                    data: statusChartData,
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444',
                        '#6b7280'
                    ],
                    borderColor: [
                        d ? '#1c1c26' : '#ffffff',
                        d ? '#1c1c26' : '#ffffff',
                        d ? '#1c1c26' : '#ffffff',
                        d ? '#1c1c26' : '#ffffff'
                    ],
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: tc, font: { size: 12 }, padding: 15 }
                    },
                    tooltip: {
                        backgroundColor: tbg, titleColor: ttt,
                        bodyColor: tbd2, borderColor: tbrd,
                        borderWidth: 1, padding: 12,
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 13 },
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' orang';
                            }
                        }
                    }
                }
            }
        });
    }

    buildCharts();
    document.addEventListener('themeChange', buildCharts);
</script>
@endpush