@extends('layouts.app')

@section('title', 'Dashboard')

@php $active = 'dashboard'; $pageTitle = 'Dashboard'; @endphp

@section('content')

    <div class="stat-grid grid grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-5">

        <x-stat-card title="Total Users" :value="129" meta="+12%">
            <x-slot:icon>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                </svg>
            </x-slot:icon>
        </x-stat-card>
    </div>

    {{-- CHART + ACTIVITY --}}
    <div class="ca-grid grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-5">

        {{-- Weekly Attendance Chart --}}
        <div class="card rounded-2xl p-4 lg:p-6 xl:col-span-2">
            <div class="flex items-start justify-between mb-4 lg:mb-5">
                <div>
                    <h2 style="font-size:16px;font-weight:700;color:var(--text-1)">
                        Weekly Attendance Trend
                    </h2>
                    <p style="font-size:13px;color:var(--text-3);margin-top:4px">
                        Average 89% across all departments
                    </p>
                </div>
                <button class="purbtn px-4 py-2 rounded-lg font-semibold flex-shrink-0 ml-4"
                        style="font-size:13px">
                    VIEW REPORT
                </button>
            </div>
            <div class="chart-canvas-wrap" style="height:220px">
                <canvas id="myChart"></canvas>
            </div>
            <div class="flex items-center gap-6 mt-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span>
                    <span style="font-size:13px;color:var(--text-3)">Above Threshold</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <span class="w-3 h-3 rounded-full bg-orange-400 inline-block"></span>
                    <span style="font-size:13px;color:var(--text-3)">Below Target</span>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="card rounded-2xl p-6">
            <div class="mb-5">
                <h2 style="font-size:17px;font-weight:700;color:var(--text-1)">Recent Activity</h2>
                <p style="font-size:13px;color:var(--text-3);margin-top:4px">Live check-in updates</p>
            </div>
            <div class="space-y-4">
                @forelse ($recentAttendances ?? [] as $attendance)
                    <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                <span style="font-size:12px;font-weight:700;color:#7c3aed">{{ substr($attendance->employee->first_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p style="font-size:13px;font-weight:600;color:var(--text-1)">
                                    {{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}
                                </p>
                                <p style="font-size:12px;color:var(--text-3)">
                                    {{ $attendance->clock_in ? $attendance->clock_in->format('H:i') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <span style="font-size:12px;padding:4px 8px;border-radius:4px;color:#fff;@if($attendance->status === 'present') background-color:#10b981; @elseif($attendance->status === 'late') background-color:#f59e0b; @else background-color:#ef4444; @endif">
                            {{ ucfirst($attendance->status) }}
                        </span>
                    </div>
                @empty
                    <p style="font-size:13px;color:var(--text-3);text-align:center;padding:20px 0">No recent activity</p>
                @endforelse
            </div>
            <div class="mt-5 text-center">
                <a href="{{ route('attendances.index') }}"

                @foreach ($recentActivity ?? $defaultActivity as $act)
                    <x-activity-row :activity="$act" />
                @endforeach
            </div>
            <div class="mt-5 text-center">
                <a href="#"

                   style="font-size:13px;color:#a78bfa;font-weight:600;"
                   onmouseover="this.style.color='#7c3aed'" onmouseout="this.style.color='#a78bfa'">
                    VIEW ALL ACTIVITY
                </a>
            </div>
        </div>

    </div>

    {{-- DEPARTMENT OVERVIEW --}}
    <div class="card rounded-2xl p-4 lg:p-6">
        <div class="dept-hdr flex flex-wrap items-center justify-between gap-3 mb-4 lg:mb-5">
            <h2 style="font-size:16px;font-weight:700;color:var(--text-1)">Department Overview</h2>
            <div class="dept-btns flex gap-2 lg:gap-3">
                <button class="ib-bg px-4 py-2 rounded-xl font-medium"
                        style="font-size:13px;color:var(--text-2)">
                    Filter By Branch
                </button>
                <button class="purbtn px-4 py-2 rounded-xl font-semibold" style="font-size:13px">
                    Download CSV
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full" style="min-width:540px;border-collapse:collapse">
                <thead>
                    <tr style="border-bottom:1px solid var(--border)">
                        <th class="text-left pb-4 font-semibold" style="font-size:13px;color:var(--text-3)">DEPARTMENT</th>
                        <th class="text-left pb-4 font-semibold" style="font-size:13px;color:var(--text-3)">TOTAL STAFF</th>
                        <th class="text-left pb-4 font-semibold" style="font-size:13px;color:var(--text-3)">PRESENT</th>
                        <th class="text-left pb-4 font-semibold" style="font-size:13px;color:var(--text-3)">LATE</th>
                        <th class="text-left pb-4 font-semibold" style="font-size:13px;color:var(--text-3)">EFFICIENCY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments ?? $defaultDepts as $dept)
                        <x-department-row :department="$dept" :last="$loop->last" />
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let chart = null;
    function buildChart() {
        const d  = document.documentElement.getAttribute('data-theme') === 'dark';
        const gc = d ? 'rgba(255,255,255,.05)' : 'rgba(0,0,0,.07)';
        const tc = d ? '#6b7280' : '#9ca3af';
        const tbg  = d ? '#1c1c26' : '#ffffff';
        const ttt  = d ? '#ffffff' : '#111827';
        const tbd2 = d ? '#9ca3af' : '#6b7280';
        const tbrd = d ? 'rgba(255,255,255,.08)' : 'rgba(0,0,0,.08)';

        if (chart) chart.destroy();

        const ctx = document.getElementById('myChart').getContext('2d');
        chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
                datasets: [
                    {
                        label: 'Above Threshold',
                        data: [88, 92, 85, 95, 89, 70, 60],
                        borderColor: '#7c3aed',
                        backgroundColor: 'rgba(124,58,237,.15)',
                        fill: true, tension: .4,
                        pointBackgroundColor: '#7c3aed',
                        pointRadius: 5, pointHoverRadius: 7, borderWidth: 2.5,
                    },
                    {
                        label: 'Below Target',
                        data: [null, null, 80, null, null, 65, 55],
                        borderColor: '#fb923c',
                        backgroundColor: 'rgba(251,146,60,.10)',
                        fill: true, tension: .4,
                        pointBackgroundColor: '#fb923c',
                        pointRadius: 5, pointHoverRadius: 7, borderWidth: 2.5,
                        spanGaps: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
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
                        ticks: { color: tc, font: { size: 13 }, callback: v => v + '%' },
                        min: 40, max: 100
                    }
                }
            }
        });
    }

    buildChart();
    document.addEventListener('themeChange', buildChart);
</script>
@endpush