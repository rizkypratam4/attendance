@extends('layouts.app')

@section('title', 'Aturan Hari Shift')

@php $active = 'shift-rules'; @endphp

@section('content')

{{-- ── PAGE HEADER ── --}}
<div class="mb-6">
    <h1 style="font-size:24px;font-weight:800;color:var(--text-1);line-height:1.2">Aturan Hari Shift</h1>
    <p style="font-size:13px;color:var(--text-3);margin-top:5px">Konfigurasi ambang batas kehadiran, masa tenggang, dan perhitungan lembur untuk berbagai jenis shift.</p>
</div>

{{-- ── STAT CARDS ── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- Total Rules --}}
    <div class="card rounded-2xl p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(124,58,237,.18)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#a78bfa" stroke-width="2">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
        </div>
        <div>
            <p style="font-size:12px;color:var(--text-3);margin-bottom:4px">Total Aturan</p>
            <p style="font-size:30px;font-weight:800;color:var(--text-1);line-height:1">12</p>
        </div>
    </div>

    {{-- Active Rules --}}
    <div class="card rounded-2xl p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(34,197,94,.15)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                <polyline points="9 12 11 14 15 10"/>
            </svg>
        </div>
        <div>
            <p style="font-size:12px;color:var(--text-3);margin-bottom:4px">Aturan Aktif</p>
            <p style="font-size:30px;font-weight:800;color:var(--text-1);line-height:1">10</p>
        </div>
    </div>

    {{-- Inactive Rules --}}
    <div class="card rounded-2xl p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0" style="background:rgba(107,114,128,.15)">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#6b7280" stroke-width="2">
                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
        </div>
        <div>
            <p style="font-size:12px;color:var(--text-3);margin-bottom:4px">Aturan Nonaktif</p>
            <p style="font-size:30px;font-weight:800;color:var(--text-1);line-height:1">2</p>
        </div>
    </div>

</div>

{{-- ── MAIN LAYOUT: Table + Panel ── --}}
<div class="flex flex-col lg:flex-row gap-5 items-start">

    {{-- ── LEFT: Rules Table ── --}}
    <div class="flex-1 card rounded-2xl overflow-hidden min-w-0">
        <div class="overflow-x-auto">
            <table class="w-full" style="border-collapse:collapse;min-width:460px">
                <thead>
                    <tr style="border-bottom:1px solid var(--border)">
                        <th class="text-left px-5 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Nama<br>Aturan</th>
                        <th class="text-left px-3 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Toleransi<br>(T/P)</th>
                        <th class="text-left px-3 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Mulai<br>Lembur</th>
                        <th class="text-left px-3 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Tipe<br>Kerja</th>
                        <th class="text-left px-3 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Status</th>
                        <th class="text-right px-4 py-3.5 font-semibold" style="font-size:11px;color:var(--text-3);letter-spacing:.07em;text-transform:uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- Normal Weekday --}}
                    <tr class="rule-row" style="border-bottom:1px solid var(--border)">
                        <td class="px-5 py-4">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Hari Kerja Normal</p>
                            <p style="font-size:11px;color:var(--text-3);margin-top:2px">Jam kantor standar</p>
                        </td>
                        <td class="px-3 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-md font-bold text-white" style="font-size:11px;background:#d97706">15m</span>
                                <span class="px-2 py-0.5 rounded-md font-bold text-white" style="font-size:11px;background:#d97706">5m</span>
                            </div>
                        </td>
                        <td class="px-3 py-4">
                            <p style="font-size:12.5px;color:var(--text-3)">Setelah</p>
                            <p style="font-size:13px;font-weight:600;color:var(--text-2)">8.0j</p>
                        </td>
                        <td class="px-3 py-4">
                            <span class="px-2.5 py-1 rounded-lg font-semibold" style="font-size:11px;background:rgba(124,58,237,.2);color:#a78bfa">Hari Kerja</span>
                        </td>
                        <td class="px-3 py-4">
                            {{-- Toggle ON --}}
                            <button onclick="toggleRule(this)" class="rule-toggle on" style="position:relative;width:42px;height:24px;border-radius:12px;background:#7c3aed;border:none;cursor:pointer;transition:background .3s;flex-shrink:0">
                                <span style="position:absolute;top:3px;left:21px;width:18px;height:18px;background:#fff;border-radius:50%;transition:left .3s"></span>
                            </button>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <button class="ib-bg w-7 h-7 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Weekend Overtime --}}
                    <tr class="rule-row" style="border-bottom:1px solid var(--border)">
                        <td class="px-5 py-4">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Lembur Akhir Pekan</p>
                            <p style="font-size:11px;color:var(--text-3);margin-top:2px">Aturan khusus untuk SAB/MIN</p>
                        </td>
                        <td class="px-3 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-md font-bold text-white" style="font-size:11px;background:#dc2626">30m</span>
                                <span class="px-2 py-0.5 rounded-md font-bold text-white" style="font-size:11px;background:#dc2626">0m</span>
                            </div>
                        </td>
                        <td class="px-3 py-4">
                            <p style="font-size:12.5px;color:var(--text-3)">Setelah</p>
                            <p style="font-size:13px;font-weight:600;color:var(--text-2)">4.0j</p>
                        </td>
                        <td class="px-3 py-4">
                            <span class="px-2.5 py-1 rounded-lg font-semibold" style="font-size:11px;background:rgba(107,114,128,.2);color:var(--text-3)">Hari Libur</span>
                        </td>
                        <td class="px-3 py-4">
                            {{-- Toggle ON --}}
                            <button onclick="toggleRule(this)" class="rule-toggle on" style="position:relative;width:42px;height:24px;border-radius:12px;background:#7c3aed;border:none;cursor:pointer;transition:background .3s;flex-shrink:0">
                                <span style="position:absolute;top:3px;left:21px;width:18px;height:18px;background:#fff;border-radius:50%;transition:left .3s"></span>
                            </button>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <button class="ib-bg w-7 h-7 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                    {{-- Holiday Rule --}}
                    <tr class="rule-row">
                        <td class="px-5 py-4">
                            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Aturan Hari Libur</p>
                            <p style="font-size:11px;color:var(--text-3);margin-top:2px">Perhitungan tarif premium</p>
                        </td>
                        <td class="px-3 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="px-2 py-0.5 rounded-md font-bold text-white" style="font-size:11px;background:#dc2626">60m</span>
                                <span class="px-2 py-0.5 rounded-md font-bold text-white" style="font-size:11px;background:#dc2626">0m</span>
                            </div>
                        </td>
                        <td class="px-3 py-4">
                            <p style="font-size:12.5px;color:var(--text-3)">Setelah</p>
                            <p style="font-size:13px;font-weight:600;color:var(--text-2)">0.0j</p>
                        </td>
                        <td class="px-3 py-4">
                            <span class="px-2.5 py-1 rounded-lg font-semibold" style="font-size:11px;background:rgba(107,114,128,.2);color:var(--text-3)">Hari Libur</span>
                        </td>
                        <td class="px-3 py-4">
                            {{-- Toggle OFF --}}
                            <button onclick="toggleRule(this)" class="rule-toggle off" style="position:relative;width:42px;height:24px;border-radius:12px;background:#374151;border:none;cursor:pointer;transition:background .3s;flex-shrink:0">
                                <span style="position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;transition:left .3s"></span>
                            </button>
                        </td>
                        <td class="px-4 py-4 text-right">
                            <button class="ib-bg w-7 h-7 rounded-lg flex items-center justify-center ml-auto">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
                                </svg>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
             style="border-top:1px solid var(--border)">
            <p style="font-size:13px;color:var(--text-3)">Menampilkan 3 dari 12 aturan</p>
            <div class="flex items-center gap-1.5">
                <button class="w-7 h-7 rounded-lg flex items-center justify-center ib-bg">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6"/></svg>
                </button>
                <button class="w-7 h-7 rounded-lg flex items-center justify-center font-semibold purbtn" style="font-size:13px">1</button>
                <button class="w-7 h-7 rounded-lg flex items-center justify-center ib-bg">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18l6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Create New Rule Panel ── --}}
    <div class="card rounded-2xl w-full lg:w-72 xl:w-80 flex-shrink-0" style="overflow:hidden">

        {{-- Panel Header --}}
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid var(--border)">
            <h3 style="font-size:15px;font-weight:700;color:var(--text-1)">Buat Aturan Baru</h3>
            <button class="mclose w-7 h-7 flex items-center justify-center rounded-lg"
                    style="background:var(--bg-ghost);border:none;cursor:pointer;color:var(--text-3)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Panel Body --}}
        <div class="p-5 space-y-5">

            {{-- Rule Name --}}
            <div>
                <label class="mlabel">Nama Aturan</label>
                <input type="text" placeholder="mis. Shift Malam Reguler" class="minput">
            </div>

            {{-- Late Grace + Early Out --}}
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mlabel">Toleransi Telat (Menit)</label>
                    <input type="number" value="15" class="minput" style="font-size:15px;font-weight:600">
                </div>
                <div>
                    <label class="mlabel">Toleransi Pulang Cepat (Menit)</label>
                    <input type="number" value="5" class="minput" style="font-size:15px;font-weight:600">
                </div>
            </div>

            {{-- Work Day Type --}}
            <div>
                <label class="mlabel">Tipe Hari Kerja</label>
                <div class="flex rounded-xl overflow-hidden" style="border:1px solid var(--border);background:var(--bg-input)">
                    <button onclick="setWorkType('working',this)"
                            class="work-type-btn flex-1 py-2.5 font-semibold text-sm purbtn rounded-xl"
                            data-type="working">Kerja</button>
                    <button onclick="setWorkType('rest',this)"
                            class="work-type-btn flex-1 py-2.5 font-semibold text-sm ib-bg"
                            data-type="rest" style="color:var(--text-2)">Libur</button>
                </div>
            </div>

            {{-- OT Threshold --}}
            <div>
                <label class="mlabel">Ambang Batas Lembur (Jam)</label>
                <input type="number" value="8.0" step="0.5" class="minput" style="font-size:15px;font-weight:600">
            </div>

            {{-- Toggles --}}
            <div class="space-y-4">

                {{-- Auto-Deduct Break --}}
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p style="font-size:13.5px;font-weight:600;color:var(--text-1)">Potong Istirahat Otomatis</p>
                        <p style="font-size:11.5px;color:var(--text-3);margin-top:2px">Potong waktu istirahat secara otomatis</p>
                    </div>
                    <button onclick="toggleSwitch(this)" class="feat-toggle on flex-shrink-0"
                            style="position:relative;width:44px;height:24px;border-radius:12px;background:#7c3aed;border:none;cursor:pointer;transition:background .3s">
                        <span style="position:absolute;top:3px;left:23px;width:18px;height:18px;background:#fff;border-radius:50%;transition:left .3s;box-shadow:0 1px 3px rgba(0,0,0,.3)"></span>
                    </button>
                </div>

                {{-- Enforce Early Out --}}
                <div class="flex items-center justify-between gap-3" style="padding-top:12px;border-top:1px solid var(--border)">
                    <div>
                        <p style="font-size:13.5px;font-weight:600;color:var(--text-1)">Tegakkan Pulang Cepat</p>
                        <p style="font-size:11.5px;color:var(--text-3);margin-top:2px">Tandai keberangkatan sebelum shift berakhir</p>
                    </div>
                    <button onclick="toggleSwitch(this)" class="feat-toggle off flex-shrink-0"
                            style="position:relative;width:44px;height:24px;border-radius:12px;background:#374151;border:none;cursor:pointer;transition:background .3s">
                        <span style="position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;transition:left .3s;box-shadow:0 1px 3px rgba(0,0,0,.3)"></span>
                    </button>
                </div>

            </div>

        </div>

        {{-- Panel Footer --}}
        <div class="flex gap-3 px-5 pb-5">
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">Simpan Aturan</button>
            <button class="flex-1 py-2.5 rounded-xl font-semibold"
                    style="font-size:14px;background:var(--bg-ghost);border:1px solid var(--border);color:var(--text-2);cursor:pointer">Atur Ulang</button>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.rule-row { transition: background .15s; }
.rule-row:hover { background: var(--bg-hover); }

/* Rule table toggle */
.rule-toggle span { transition: left .25s cubic-bezier(.4,0,.2,1); }
.rule-toggle.on { background: #7c3aed !important; }
.rule-toggle.on span { left: 21px !important; }
.rule-toggle.off { background: #374151 !important; }
.rule-toggle.off span { left: 3px !important; }

/* Feature toggle in panel */
.feat-toggle span { transition: left .25s cubic-bezier(.4,0,.2,1); }
.feat-toggle.on { background: #7c3aed !important; }
.feat-toggle.on span { left: 23px !important; }
.feat-toggle.off { background: #374151 !important; }
.feat-toggle.off span { left: 3px !important; }

/* Work type btn */
.work-type-btn { transition: background .2s, color .2s; border: none; cursor: pointer; }
</style>
@endpush

@push('scripts')
<script>
// Toggle rule status (table)
function toggleRule(btn) {
    const isOn = btn.classList.contains('on');
    btn.classList.toggle('on', !isOn);
    btn.classList.toggle('off', isOn);
}

// Toggle feature switch (panel)
function toggleSwitch(btn) {
    const isOn = btn.classList.contains('on');
    btn.classList.toggle('on', !isOn);
    btn.classList.toggle('off', isOn);
}

// Work Day Type selector
function setWorkType(type, btn) {
    document.querySelectorAll('.work-type-btn').forEach(b => {
        b.classList.remove('purbtn');
        b.classList.add('ib-bg');
        b.style.color = 'var(--text-2)';
    });
    btn.classList.add('purbtn');
    btn.classList.remove('ib-bg');
    btn.style.color = '#fff';
}
</script>
@endpush