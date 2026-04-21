<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kehadiran Karyawan</title>
    <style>
        @page {
            size: A4;
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            padding: 28px 44px;
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #111111;
            background: #ffffff;
        }

        table.kop {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2.5px solid #111111;
            padding-bottom: 16px;
            margin-bottom: 16px;
        }

        table.kop td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        /* Sel logo 1 */
        td.logo1-cell {
            width: 75px;
        }

        table.logo1-box {
            width: 70px;
            height: 50px;
            border-collapse: collapse;
            background-color: #111111;
        }

        table.logo1-box td {
            text-align: center;
            vertical-align: middle;
            padding: 4px;
        }

        /* Pemisah vertikal */
        td.logo-divider {
            width: 20px;
            text-align: center;
            vertical-align: middle;
            padding: 0 4px;
        }

        table.divider-line {
            width: 1px;
            height: 40px;
            border-collapse: collapse;
            margin: 0 auto;
            background: #cccccc;
        }

        table.divider-line td {
            padding: 0;
            background: #cccccc;
            width: 1px;
        }

        /* Sel logo 2 */
        td.logo2-cell {
            width: 75px;
        }

        table.logo2-box {
            width: 70px;
            height: 50px;
            border-collapse: collapse;
            border: 1.5px solid #111111;
        }

        table.logo2-box td {
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        .circle-icon {
            width: 28px;
            height: 28px;
            border: 2.5px solid #111111;
            border-radius: 50%;
            margin: 0 auto;
        }

        /* Sel info perusahaan */
        td.company-info {
            padding-left: 16px !important;
            vertical-align: middle;
        }

        .co-name {
            font-size: 17px;
            font-weight: bold;
            color: #1b71dd;
            line-height: 1.2;
            margin-bottom: 3px;
        }

        .co-division {
            font-size: 7.5px;
            color: #888888;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .co-address {
            font-size: 8.5px;
            color: #777777;
            line-height: 1.6;
        }

        /* Sel judul dokumen */
        td.doc-title-cell {
            width: 185px;
            text-align: right;
            vertical-align: middle;
            padding-left: 12px;
        }

        .doc-title {
            font-size: 14px;
            font-weight: bold;
            color: #111111;
            margin-bottom: 5px;
        }

        .doc-meta {
            font-size: 8px;
            color: #888888;
            line-height: 1.8;
        }

        .doc-meta span {
            color: #444444;
            font-weight: 600;
        }


        /* ================================================
           FILTER BAR
        ================================================ */
        div.filter-bar {
            border: 1px solid #dddddd;
            padding: 6px 10px;
            margin-bottom: 12px;
        }

        table.filter-tbl {
            border-collapse: collapse;
        }

        table.filter-tbl td {
            border: none;
            padding: 0 16px 0 0;
            font-size: 9px;
            color: #444444;
            vertical-align: middle;
            white-space: nowrap;
        }

        table.filter-tbl td strong {
            color: #111111;
            font-weight: bold;
        }

        /* ================================================
           TABLE DATA
        ================================================ */
        table.data-tbl {
            width: 100%;
            border-collapse: collapse;
        }

        table.data-tbl thead tr {
            background-color: #111111;
        }

        table.data-tbl th {
            padding: 7px 8px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        table.data-tbl td {
            padding: 7px 8px;
            font-size: 9.5px;
            border-bottom: 1px solid #e5e2dc;
            vertical-align: middle;
            color: #222222;
        }

        table.data-tbl tbody tr:nth-child(even) td {
            background-color: #f9f8f6;
        }

        .emp-name {
            font-weight: bold;
            font-size: 9.5px;
            color: #111111;
        }

        .emp-dept {
            font-size: 8px;
            color: #aaaaaa;
            margin-top: 1px;
        }

        /* Badge status */
        table.badge-tbl {
            border-collapse: collapse;
            border: 1px solid #c0392b;
        }

        table.badge-tbl td {
            padding: 2px 6px;
            font-size: 7.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            color: #c0392b;
            background-color: #fff5f4;
            border: none;
            white-space: nowrap;
        }

        table.badge-hadir  { border-color: #27ae60; }
        table.badge-hadir td  { color: #27ae60; background-color: #f0faf4; }

        table.badge-absent { border-color: #666666; }
        table.badge-absent td { color: #666666; background-color: #f5f5f5; }

        table.badge-dayoff { border-color: #2980b9; }
        table.badge-dayoff td { color: #2980b9; background-color: #f0f6ff; }

        table.badge-permit { border-color: #d35400; }
        table.badge-permit td { color: #d35400; background-color: #fff7f0; }

        /* ================================================
           FOOTER
        ================================================ */
        table.footer-tbl {
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid #dddddd;
            margin-top: 18px;
        }

        table.footer-tbl td {
            border: none;
            padding: 8px 0 0 0;
            font-size: 7.5px;
            color: #bbbbbb;
            vertical-align: bottom;
        }

        td.sign-cell {
            width: 130px;
            text-align: center;
            margin-top: 15px;
        }

        .sign-spacer {
            height: 55px;
        }

        table.sign-line-tbl {
            width: 110px;
            border-collapse: collapse;
            border-bottom: 1px solid #999999;
            margin: 0 auto 4px auto;
        }

        table.sign-line-tbl td {
            padding: 0;
            border: none;
        }

        .sign-name  { font-size: 8.5px; font-weight: bold; color: #111111; }
        .sign-title { font-size: 7.5px; color: #888888; }

        .no-data {
            text-align: center;
            padding: 28px;
            color: #aaaaaa;
            font-style: italic;
        }
    </style>
</head>
<body>

<table class="kop">
    <tr>

        {{-- Logo 1 --}}
        <td class="logo1-cell">
            @if(file_exists(public_path('images/logo_cni.png')))
                <img src="{{ public_path('images/logo_cni.png') }}" width="70" height="50" style="border:none;margin:0;padding:0;">
            @else
                <table class="logo1-box">
                    <tr>
                        <td>
                            <table class="logo1-grid">
                                <tr>
                                    <td class="sq-white"></td>
                                    <td style="width:2px;background:#111111;"></td>
                                    <td class="sq-white"></td>
                                </tr>
                                <tr>
                                    <td style="height:2px;background:#111111;" colspan="3"></td>
                                </tr>
                                <tr>
                                    <td class="sq-white"></td>
                                    <td style="width:2px;background:#111111;"></td>
                                    <td class="sq-gray"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            @endif
        </td>

        {{-- Pemisah --}}
        <td class="logo-divider">
            <table class="divider-line"><tr><td>&nbsp;</td></tr></table>
        </td>

        {{-- Logo 2 --}}
        <td class="logo2-cell">
            @if(file_exists(public_path('images/logo_csi.png')))
                <img src="{{ public_path('images/logo_csi.png') }}" width="70" height="50" style="border:none;margin:0;padding:0;">
            @else
                <table class="logo2-box">
                    <tr>
                        <td>
                            <div class="circle-icon"></div>
                        </td>
                    </tr>
                </table>
            @endif
        </td>

        {{-- Info Perusahaan --}}
        <td class="company-info">
                <div class="co-name" style="color: #1b71dd;">
                    {{ config('app.company_name', 'PT. Cipta Saksama Indonesia') }}
                </div>
            <div class="co-address">
                {{ config('app.company_address', 'Jl. Raya Bekasi No.km 23, Cakung Bar., Kec. Cakung, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13920') }}<br>
                Telp: {{ config('app.company_phone', '(021) 4600942') }}
                &nbsp;|&nbsp;
                {{ config('app.company_email', 'hrd@ciptasaksama.co.id') }}
            </div>
        </td>

        {{-- Judul Dokumen --}}
        {{-- Judul Dokumen --}}
<td class="doc-title-cell">
    <div class="doc-title">Laporan Kehadiran</div>
    <div class="doc-meta">
        Tanggal: <span>{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}</span><br>
        Dicetak: <span>{{ now()->translatedFormat('d F Y, H:i:s') }}</span>
    </div>
</td>

    </tr>
</table>

{{-- ================================================
     FILTER BAR
================================================ --}}
<div class="filter-bar">
    <table class="filter-tbl">
        <tr>
            <td>
                <strong>Tanggal:</strong>
                {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
            </td>
            @if($status)
                <td>
                    <strong>Status:</strong>
                    {{ match($status) {
                        'present' => 'Hadir & Terlambat',
                        'late'    => 'Terlambat',
                        'absent'  => 'Tidak Hadir',
                        default   => ucfirst($status)
                    } }}
                </td>
            @endif
            @if($shiftId)
                <td>
                    <strong>Shift:</strong>
                    {{ $shiftCodes->firstWhere('id', $shiftId)?->code ?? 'Unknown' }}
                </td>
            @endif
            @if($deptId)
                <td>
                    <strong>Department:</strong>
                    {{ $departments->firstWhere('id', $deptId)?->name ?? 'Unknown' }}
                </td>
            @endif
        </tr>
    </table>
</div>

{{-- ================================================
     TABLE DATA
================================================ --}}
@if($attendances->count() > 0)
    <table class="data-tbl">
        <thead>
            <tr>
                <th style="width:26%">Karyawan</th>
                <th style="width:10%">Shift</th>
                <th style="width:12%">Clock In</th>
                <th style="width:12%">Clock Out</th>
                <th style="width:18%">Status</th>
                <th style="width:22%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $att)
                @php
                    $emp = $att->employee;

                    // Gunakan new_working_shift jika tersedia, jika tidak gunakan shift_code
                    $displayShift = $att->newWorkingShift ?? $att->shiftCode;

                    $statusLabel = match($att->status) {
                        'present' => 'Hadir',
                        'late'    => 'Terlambat ' . ($att->late_minutes ?? 0) . 'm',
                        'absent'  => 'Tidak Hadir',
                        'day_off' => 'Day Off',
                        'permit'  => 'Izin',
                        'sick'    => 'Sakit',
                        default   => ucfirst($att->status),
                    };

                    $badgeClass = match($att->status) {
                        'present'       => 'badge-hadir',
                        'late'          => '',
                        'absent'        => 'badge-absent',
                        'day_off'       => 'badge-dayoff',
                        'permit','sick' => 'badge-permit',
                        default         => 'badge-absent',
                    };
                @endphp
                <tr>
                    <td>
                        <div class="emp-name">{{ $emp->name }}</div>
                        <div class="emp-dept">{{ $emp->department?->name ?? 'N/A' }}</div>
                    </td>
                    <td>{{ $displayShift?->code ?? '-' }}</td>
                    <td>{{ $att->clock_in  ? $att->clock_in->format('H:i:s')  : '-' }}</td>
                    <td>{{ $att->clock_out ? $att->clock_out->format('H:i:s') : '-' }}</td>
                    <td>
                        <table class="badge-tbl {{ $badgeClass }}">
                            <tr><td>{{ $statusLabel }}</td></tr>
                        </table>
                    </td>
                    <td>{{ $att->notes ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="no-data">
                        Tidak ada data kehadiran untuk filter yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@else
    <div class="no-data">Tidak ada data kehadiran yang sesuai dengan filter yang dipilih.</div>
@endif

{{-- ================================================
     FOOTER
================================================ --}}
<table class="footer-tbl">
    <tr>
        <td>
            &copy; {{ now()->year }} Attendance System &mdash;
            Dicetak pada {{ now()->translatedFormat('d F Y H:i:s') }}
        </td>
        <td class="sign-cell">
            <div class="sign-spacer"></div>
            <table class="sign-line-tbl"><tr><td></td></tr></table>
            <div class="sign-name">Manager HRD</div>
            <div class="sign-title">{{ config('app.company_name', 'PT. Nama Perusahaan') }}</div>
        </td>
    </tr>
</table>

</body>
</html>