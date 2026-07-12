@extends('layouts.app')

@section('title', 'Definisi Shift')

@php $active = 'shift-definition'; @endphp

@section('content')

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="card rounded-2xl p-5">
        <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px">Total Shift Aktif</p>
        <div class="flex items-baseline gap-2">
            <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">12</p>
            <span style="font-size:12px;font-weight:600;color:#22c55e">+2 bulan ini</span>
        </div>
    </div>

    <div class="card rounded-2xl p-5">
        <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px">Rata-rata Durasi Shift</p>
        <div class="flex items-baseline gap-2">
            <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">8.5</p>
            <span style="font-size:14px;font-weight:500;color:var(--text-3)">jam</span>
            <span style="font-size:12px;font-weight:600;color:#a78bfa;margin-left:4px">Dioptimalkan</span>
        </div>
    </div>

    <div class="card rounded-2xl p-5">
        <p style="font-size:11px;font-weight:600;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px">Mulai Shift Berikutnya</p>
        <div class="flex items-baseline gap-3">
            <p style="font-size:34px;font-weight:800;color:var(--text-1);line-height:1">08:00</p>
            <span style="font-size:13px;color:var(--text-3)">Shift Pagi</span>
        </div>
    </div>

</div>

<div class="card rounded-2xl mb-6" style="overflow:hidden">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4"
         style="border-bottom:1px solid var(--border)">
        <div>
            <h2 style="font-size:16px;font-weight:700;color:var(--text-1)">Shift yang Dikonfigurasi</h2>
            <p style="font-size:13px;color:var(--text-3);margin-top:3px">Jam operasional standar untuk tenaga kerja Anda</p>
        </div>

        <div class="flex items-center gap-2 px-4 py-2 rounded-xl" style="background:var(--bg-input);border:1px solid var(--border-in);min-width:200px">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2" class="flex-shrink-0">
                <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
            </svg>
            <input type="text" placeholder="Cari shift..."
                   style="background:transparent;border:none;outline:none;color:var(--text-2);font-size:13px;width:100%;font-family:inherit">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                <path d="M6 9l6 6 6-6"/>
            </svg>
        </div>
    </div>

    <div class="grid px-5 py-3" style="grid-template-columns:1.8fr 2fr 1.2fr 1.2fr 1.5fr 0.8fr;border-bottom:1px solid var(--border)">
        <span style="font-size:11px;font-weight:600;color:#7c3aed;letter-spacing:.07em;text-transform:uppercase">Nama Shift</span>
        <span style="font-size:11px;font-weight:600;color:#7c3aed;letter-spacing:.07em;text-transform:uppercase">Jendela Waktu</span>
        <span style="font-size:11px;font-weight:600;color:#7c3aed;letter-spacing:.07em;text-transform:uppercase">Durasi Istirahat</span>
        <span style="font-size:11px;font-weight:600;color:#7c3aed;letter-spacing:.07em;text-transform:uppercase">Total Jam</span>
        <span style="font-size:11px;font-weight:600;color:#7c3aed;letter-spacing:.07em;text-transform:uppercase">Staf Ditugaskan</span>
        <span style="font-size:11px;font-weight:600;color:#7c3aed;letter-spacing:.07em;text-transform:uppercase;text-align:right">Aksi</span>
    </div>

    <div class="grid px-5 py-4 shift-def-row" style="grid-template-columns:1.8fr 2fr 1.2fr 1.2fr 1.5fr 0.8fr;align-items:center;border-bottom:1px solid var(--border)">
        <div>
            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Shift Pagi</p>
            <p style="font-size:12px;color:var(--text-3)">Reguler · Hari Kerja</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-lg font-bold" style="font-size:12px;background:rgba(34,197,94,.18);color:#22c55e">08:00</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            <span class="px-2.5 py-1 rounded-lg font-bold" style="font-size:12px;background:rgba(239,68,68,.15);color:#f87171">16:00</span>
        </div>
        <div style="font-size:13.5px;color:var(--text-2)">45 menit</div>
        <div class="flex items-center gap-2">
            <div class="h-1.5 rounded-full" style="width:48px;background:linear-gradient(90deg,#7c3aed,#a78bfa)"></div>
            <span style="font-size:13px;font-weight:600;color:var(--text-2)">8.0 jam</span>
        </div>
        <div class="flex items-center">
            <img src="https://i.pravatar.cc/28?img=1" class="w-7 h-7 rounded-full object-cover border-2" style="border-color:var(--bg-card)">
            <img src="https://i.pravatar.cc/28?img=3" class="w-7 h-7 rounded-full object-cover border-2 -ml-2" style="border-color:var(--bg-card)">
            <img src="https://i.pravatar.cc/28?img=5" class="w-7 h-7 rounded-full object-cover border-2 -ml-2" style="border-color:var(--bg-card)">
            <span class="w-7 h-7 rounded-full flex items-center justify-center -ml-2 font-bold"
                  style="background:rgba(124,58,237,.3);color:#a78bfa;font-size:10px;border:2px solid var(--bg-card)">+</span>
        </div>
        <div class="flex justify-end gap-2">
            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="grid px-5 py-4 shift-def-row" style="grid-template-columns:1.8fr 2fr 1.2fr 1.2fr 1.5fr 0.8fr;align-items:center;border-bottom:1px solid var(--border)">
        <div>
            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Shift Malam 🌙</p>
            <p style="font-size:12px;color:var(--text-3)">Operasi 24/7</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-2.5 py-1 rounded-lg font-bold" style="font-size:12px;background:rgba(124,58,237,.2);color:#a78bfa">22:00</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            <div>
                <span class="px-2.5 py-1 rounded-lg font-bold" style="font-size:12px;background:rgba(124,58,237,.2);color:#a78bfa">06:00</span>
                <span style="font-size:10px;color:#a78bfa;font-weight:600;margin-left:3px">+1Hari</span>
            </div>
        </div>
        <div style="font-size:13.5px;color:var(--text-2)">60 menit</div>
        <div class="flex items-center gap-2">
            <div class="h-1.5 rounded-full" style="width:48px;background:linear-gradient(90deg,#7c3aed,#a78bfa)"></div>
            <span style="font-size:13px;font-weight:600;color:var(--text-2)">8.0 jam</span>
        </div>
        <div class="flex items-center">
            <img src="https://i.pravatar.cc/28?img=7" class="w-7 h-7 rounded-full object-cover border-2" style="border-color:var(--bg-card)">
            <span class="w-7 h-7 rounded-full flex items-center justify-center -ml-2 font-bold"
                  style="background:rgba(124,58,237,.3);color:#a78bfa;font-size:10px;border:2px solid var(--bg-card)">+</span>
        </div>
        <div class="flex justify-end gap-2">
            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Afternoon Shift --}}
    <div class="grid px-5 py-4 shift-def-row" style="grid-template-columns:1.8fr 2fr 1.2fr 1.2fr 1.5fr 0.8fr;align-items:center">
        <div>
            <p style="font-size:14px;font-weight:700;color:var(--text-1)">Shift Sore</p>
            <p style="font-size:12px;color:var(--text-3)">Operasi Larut</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-2.5 py-1 rounded-lg font-bold" style="font-size:12px;background:rgba(251,146,60,.18);color:#fb923c">14:00</span>
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            <span class="px-2.5 py-1 rounded-lg font-bold" style="font-size:12px;background:rgba(251,146,60,.18);color:#fb923c">22:00</span>
        </div>
        <div style="font-size:13.5px;color:var(--text-2)">45 menit</div>
        <div class="flex items-center gap-2">
            <div class="h-1.5 rounded-full" style="width:48px;background:linear-gradient(90deg,#7c3aed,#a78bfa)"></div>
            <span style="font-size:13px;font-weight:600;color:var(--text-2)">8.0 jam</span>
        </div>
        <div class="flex items-center">
            <img src="https://i.pravatar.cc/28?img=9" class="w-7 h-7 rounded-full object-cover border-2" style="border-color:var(--bg-card)">
            <img src="https://i.pravatar.cc/28?img=11" class="w-7 h-7 rounded-full object-cover border-2 -ml-2" style="border-color:var(--bg-card)">
            <span class="w-7 h-7 rounded-full flex items-center justify-center -ml-2 font-bold"
                  style="background:rgba(124,58,237,.3);color:#a78bfa;font-size:10px;border:2px solid var(--bg-card)">+8</span>
        </div>
        <div class="flex justify-end gap-2">
            <button class="ib-bg w-8 h-8 rounded-lg flex items-center justify-center">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Table Footer --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-3.5"
         style="border-top:1px solid var(--border)">
        <p style="font-size:13px;color:var(--text-3)">Menampilkan 3 dari 12 shift</p>
        <div class="flex items-center gap-2">
            <button class="px-3 h-8 rounded-lg ib-bg font-medium" style="font-size:13px;color:var(--text-3)">Sebelumnya</button>
            <button class="px-3 h-8 rounded-lg ib-bg font-medium" style="font-size:13px;color:var(--text-2)">Selanjutnya</button>
        </div>
    </div>
</div>

{{-- ── CREATE NEW SHIFT SECTION ── --}}
<div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

    {{-- Left: Info panel --}}
    <div class="lg:col-span-2">
        <h2 style="font-size:18px;font-weight:800;color:var(--text-1);margin-bottom:6px">Buat Shift Baru</h2>
        <p style="font-size:13px;color:var(--text-3);line-height:1.6;margin-bottom:16px">
            Tentukan jam khusus dan kebijakan istirahat untuk kategori shift baru. Setelah disimpan, ini dapat ditugaskan ke anggota staf di bagian Jadwal.
        </p>
        {{-- Info box --}}
        <div class="rounded-xl p-4 flex items-start gap-3"
             style="background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25)">
            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5"
                 style="background:#7c3aed;font-size:11px;font-weight:700;color:#fff">i</div>
            <p style="font-size:12.5px;color:var(--text-2);line-height:1.6">
                Shift yang melewati tengah malam akan secara otomatis ditandai sebagai "Hari Berikutnya" untuk tujuan penjadwalan.
            </p>
        </div>
    </div>

    {{-- Right: Form --}}
    <div class="lg:col-span-3 card rounded-2xl p-6">
        <div class="space-y-4">

            {{-- Shift Name --}}
            <div>
                <label class="mlabel">Nama Shift</label>
                <input type="text" placeholder="mis. Akhir Pekan Larut Malam" class="minput">
            </div>

            {{-- Start + End Time --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mlabel">Waktu Mulai</label>
                    <div class="relative">
                        <input type="text" value="09:00 AM" class="minput" style="padding-right:40px">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="mlabel">Waktu Selesai</label>
                    <div class="relative">
                        <input type="text" value="05:00 PM" class="minput" style="padding-right:40px">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Break Duration + Color Tag --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mlabel">Durasi Istirahat (Menit)</label>
                    <div class="relative">
                        <select class="minput" style="cursor:pointer;padding-right:36px;appearance:none">
                            <option>45 menit</option>
                            <option>30 menit</option>
                            <option>60 menit</option>
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--text-3)" stroke-width="2">
                            <path d="M6 9l6 6 6-6"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <label class="mlabel">Label Warna</label>
                    <div class="flex items-center gap-2.5 mt-1">
                        <button onclick="selectColor(this,'#7c3aed')" class="color-dot w-7 h-7 rounded-full border-2 border-transparent" style="background:#7c3aed" title="Purple"></button>
                        <button onclick="selectColor(this,'#22c55e')" class="color-dot w-7 h-7 rounded-full border-2 border-transparent" style="background:#22c55e" title="Green"></button>
                        <button onclick="selectColor(this,'#3b82f6')" class="color-dot w-7 h-7 rounded-full border-2 border-transparent" style="background:#3b82f6" title="Blue"></button>
                        <button onclick="selectColor(this,'#f59e0b')" class="color-dot w-7 h-7 rounded-full border-2 border-transparent" style="background:#f59e0b" title="Yellow"></button>
                        <button onclick="selectColor(this,'#ef4444')" class="color-dot w-7 h-7 rounded-full border-2 border-transparent" style="background:#ef4444" title="Red"></button>
                    </div>
                </div>
            </div>

        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3 mt-6">
            <button class="px-5 py-2.5 rounded-xl font-medium"
                    style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Buang
            </button>
            <button class="purbtn px-5 py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Simpan Definisi Shift
            </button>
        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
.shift-def-row { transition: background .15s; }
.shift-def-row:hover { background: var(--bg-hover); }
.color-dot { cursor:pointer; transition:transform .15s, border-color .15s; border:2px solid transparent; }
.color-dot:hover { transform: scale(1.15); }
.color-dot.selected { border-color: #fff !important; transform: scale(1.15); }

@media(max-width:767px) {
    .shift-def-row { display:flex; flex-direction:column; gap:8px; }
    .shift-def-row > div { width:100% !important; }
}
</style>
@endpush

@push('scripts')
<script>
function selectColor(el, color) {
    document.querySelectorAll('.color-dot').forEach(d => d.classList.remove('selected'));
    el.classList.add('selected');
}
// Pre-select first color
document.addEventListener('DOMContentLoaded', () => {
    const first = document.querySelector('.color-dot');
    if (first) first.classList.add('selected');
});
</script>
@endpush