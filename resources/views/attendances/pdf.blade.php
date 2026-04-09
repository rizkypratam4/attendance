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

        /* ================================================
           KOP SURAT
        ================================================ */
        table.kop {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #111111;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        table.kop td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        /* Sel logo 1 */
        td.logo1-cell {
            width: 48px;
        }

        table.logo1-box {
            width: 44px;
            height: 44px;
            border-collapse: collapse;
            background-color: #111111;
        }

        table.logo1-box td {
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        /* Grid 2x2 putih di logo1 */
        table.logo1-grid {
            width: 28px;
            height: 28px;
            border-collapse: collapse;
            margin: 0 auto;
        }

        table.logo1-grid td {
            width: 12px;
            height: 12px;
            padding: 0;
        }

        .sq-white { background: #ffffff; }
        .sq-gray  { background: #888888; }

        /* Pemisah vertikal */
        td.logo-divider {
            width: 14px;
            text-align: center;
            vertical-align: middle;
        }

        table.divider-line {
            width: 1px;
            height: 32px;
            border-collapse: collapse;
            margin: 0 auto;
            background: #aaaaaa;
        }

        table.divider-line td {
            padding: 0;
            background: #aaaaaa;
        }

        /* Sel logo 2 */
        td.logo2-cell {
            width: 48px;
        }

        table.logo2-box {
            width: 44px;
            height: 44px;
            border-collapse: collapse;
            border: 1.5px solid #111111;
        }

        table.logo2-box td {
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }

        /* Lingkaran simulasi di logo2 - pakai border */
        .circle-icon {
            width: 22px;
            height: 22px;
            border: 2px solid #111111;
            border-radius: 11px;
            margin: 0 auto;
        }

        /* Sel info perusahaan */
        td.company-info {
            padding-left: 12px !important;
            vertical-align: middle;
        }

        .co-name {
            font-size: 16px;
            font-weight: bold;
            color: #111111;
            line-height: 1.2;
        }

        .co-division {
            font-size: 7.5px;
            color: #666666;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .co-address {
            font-size: 8.5px;
            color: #777777;
            margin-bottom: 15px;
            line-height: 1.6;
        }

        /* Sel judul dokumen */
        td.doc-title-cell {
            width: 175px;
            text-align: right;
            vertical-align: bottom;
        }

        .doc-title {
            font-size: 13px;
            font-weight: bold;
            color: #111111;
        }

        .doc-meta {
            font-size: 8px;
            color: #777777;
            margin-top: 4px;
            line-height: 1.7;
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

{{-- ================================================
     KOP SURAT
================================================ --}}
<table class="kop">
    <tr>

        {{-- Logo 1 --}}
        <td class="logo1-cell">
            {{-- Jika sudah ada file gambar, ganti seluruh <table class="logo1-box"> dengan:
                 <img src="{{ public_path('images/logo1.png') }}" width="44" height="44">
            --}}
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
        </td>

        {{-- Pemisah --}}
        <td class="logo-divider">
            <table class="divider-line"><tr><td>&nbsp;</td></tr></table>
        </td>

        {{-- Logo 2 --}}
        <td class="logo2-cell">
            {{-- Jika sudah ada file gambar, ganti seluruh <table class="logo2-box"> dengan:
                 <img src="{{ public_path('images/logo2.png') }}" width="44" height="44">
            --}}
            <table class="logo2-box">
                <tr>
                    <td>
                        <div class="circle-icon"></div>
                    </td>
                </tr>
            </table>
        </td>

        {{-- Info Perusahaan --}}
        <td class="company-info">
            <div class="co-name">{{ config('app.company_name', 'PT. Nama Perusahaan') }}</div>
            <div class="co-division">Human Resources Division</div>
            <div class="co-address">
                {{ config('app.company_address', 'Jl. Alamat Perusahaan, Kota') }}<br>
                Telp: {{ config('app.company_phone', '(021) 000-0000') }}
                &nbsp;|&nbsp;
                {{ config('app.company_email', 'hrd@perusahaan.co.id') }}
            </div>
        </td>

        {{-- Judul Dokumen --}}
        <td class="doc-title-cell">
            <div class="doc-title">Laporan Kehadiran</div>
            <div class="doc-meta">
                Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}<br>
                Dicetak: {{ now()->translatedFormat('d F Y, H:i:s') }}
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
                    <td>{{ $att->shiftCode?->code ?? '-' }}</td>
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