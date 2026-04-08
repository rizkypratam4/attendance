<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Attendance</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #111;
            line-height: 1.5;
        }

        .container { width: 100%; padding: 24px; }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 24px;
            padding-bottom: 12px;
            border-bottom: 1px solid #111;
        }
        .header h1 { font-size: 16px; font-weight: bold; margin-bottom: 4px; }
        .header p  { font-size: 10px; color: #555; margin: 2px 0; }

        /* Filter Info */
        .filter-info {
            border: 1px solid #ccc;
            padding: 10px 14px;
            margin-bottom: 20px;
        }
        .filter-info h3 { font-size: 10px; font-weight: bold; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .filter-row { display: flex; gap: 24px; }
        .filter-item { font-size: 10px; color: #333; }
        .filter-item strong { color: #111; }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            margin-bottom: 20px;
        }
        .stat-box {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        .stat-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            margin-bottom: 4px;
        }
        .stat-value { font-size: 18px; font-weight: bold; color: #111; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }

        thead { background-color: #111; color: #fff; }
        th {
            padding: 9px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        td {
            padding: 8px;
            font-size: 10px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: top;
        }
        tbody tr:nth-child(even) { background-color: #f7f7f7; }
        tbody tr:last-child td  { border-bottom: none; }

        .emp-name { font-weight: bold; color: #111; }
        .emp-dept { font-size: 9px; color: #666; margin-top: 1px; }

        .status-badge {
            display: inline-block;
            padding: 2px 7px;
            border: 1px solid #999;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #111;
            background: #f0f0f0;
        }

        .no-data {
            text-align: center;
            padding: 36px;
            color: #999;
            font-style: italic;
            border: 1px solid #e0e0e0;
        }

        /* Footer */
        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            text-align: right;
            font-size: 9px;
            color: #888;
        }

        @media print {
            .container { padding: 10px; }
        }
    </style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        <h1>Laporan Kehadiran Karyawan</h1>
        <p>Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</p>
        <p>Waktu Cetak: {{ now()->translatedFormat('d F Y, H:i:s') }}</p>
    </div>

    {{-- Filter Info --}}
    <div class="filter-info">
        <h3>Filter yang Diterapkan</h3>
        <div class="filter-row">
            <div class="filter-item">
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </div>
            @if($shiftId)
                <div class="filter-item">
                    <strong>Shift:</strong> {{ $shiftCodes->firstWhere('id', $shiftId)?->code ?? 'Unknown' }}
                </div>
            @endif
            @if($deptId)
                <div class="filter-item">
                    <strong>Department:</strong> {{ $departments->firstWhere('id', $deptId)?->name ?? 'Unknown' }}
                </div>
            @endif
            @if($status)
                <div class="filter-item">
                    <strong>Status:</strong>
                    {{ match($status) {
                        'present' => 'Hadir & Terlambat',
                        'late'    => 'Terlambat',
                        'absent'  => 'Tidak Hadir',
                        default   => ucfirst($status)
                    } }}
                </div>
            @endif
        </div>
    </div>

    {{-- Statistics --}}
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Hadir</div>
            <div class="stat-value">{{ $stats['present'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Terlambat</div>
            <div class="stat-value">{{ $stats['late'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Tidak Hadir</div>
            <div class="stat-value">{{ $stats['absent'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Day Off</div>
            <div class="stat-value">{{ $stats['day_off'] }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
    </div>

    {{-- Table --}}
    @if($attendances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 25%">Karyawan</th>
                    <th style="width: 12%">Shift</th>
                    <th style="width: 12%">Clock In</th>
                    <th style="width: 12%">Clock Out</th>
                    <th style="width: 15%">Status</th>
                    <th style="width: 24%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $att)
                    @php
                        $emp = $att->employee;
                        $statusLabel = match($att->status) {
                            'present' => 'Hadir',
                            'late'    => 'Terlambat ' . ($att->late_minutes ?? 0) . 'm',
                            'absent'  => 'Tidak Hadir',
                            'day_off' => 'Day Off',
                            'permit'  => 'Izin',
                            'sick'    => 'Sakit',
                            default   => ucfirst($att->status),
                        };
                    @endphp
                    <tr>
                        <td>
                            <div class="emp-name">{{ $emp->name }}</div>
                            <div class="emp-dept">{{ $emp->department?->name ?? 'N/A' }}</div>
                        </td>
                        <td>{{ $att->shiftCode?->code ?? '-' }}</td>
                        <td>{{ $att->clock_in  ? $att->clock_in->format('H:i:s')  : '-' }}</td>
                        <td>{{ $att->clock_out ? $att->clock_out->format('H:i:s') : '-' }}</td>
                        <td><span class="status-badge">{{ $statusLabel }}</span></td>
                        <td>{{ $att->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">Tidak ada data kehadiran untuk filter yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data kehadiran yang sesuai dengan filter yang dipilih.</div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        &copy; {{ now()->year }} Attendance System &mdash;
        Dicetak pada {{ now()->translatedFormat('d F Y H:i:s') }}
    </div>

</div>
</body>
</html>