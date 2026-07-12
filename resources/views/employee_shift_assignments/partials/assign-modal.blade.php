{{-- Modal Bulk Assign Shift --}}
<div id="mAssignShift" class="mbk" onclick="closeOut(event,'mAssignShift')">
    <div class="mbox" style="max-width:1100px;width:95%;max-height:90vh;display:flex;flex-direction:column">
        <div class="mhdr" style="padding:20px 24px;border-bottom:1px solid var(--border)">
            <span class="mtitle" style="font-size:18px">Tugaskan Shift Karyawan</span>
            <button onclick="closeM('mAssignShift')" class="mclose" style="width:32px;height:32px;font-size:20px">&times;</button>
        </div>

        <form id="assignShiftForm" method="POST" action="{{ route('employee_shift_assignments.bulk_assign') }}"
              style="display:flex;flex-direction:column;flex:1;overflow:hidden">
            @csrf
            <div class="mbdy" style="padding:0;flex:1;overflow:auto">
                <div class="grid grid-cols-1 lg:grid-cols-2" style="min-height:480px">

                    {{-- KOLOM KIRI --}}
                    <div style="padding:24px;border-right:1px solid var(--border)">
                        <h4 style="font-size:11px;font-weight:700;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:20px">FORM INPUT</h4>

                        {{-- Tab Assign To --}}
                        <div style="margin-bottom:20px">
                            <label style="font-size:12px;font-weight:600;color:var(--text-2);display:block;margin-bottom:10px">Tugaskan Ke</label>
                            <div style="display:flex;gap:8px">
                                <button type="button" class="tab-btn active" data-tab="employee"
                                        style="flex:1;padding:10px 12px;border-radius:10px;font-size:13px;font-weight:600;border:1.5px solid rgba(124,58,237,.4);background:rgba(124,58,237,.15);color:#a78bfa;cursor:pointer;transition:all .2s">
                                    Karyawan
                                </button>
                                <button type="button" class="tab-btn" data-tab="department"
                                        style="flex:1;padding:10px 12px;border-radius:10px;font-size:13px;font-weight:600;border:1.5px solid var(--border);background:transparent;color:var(--text-3);cursor:pointer;transition:all .2s">
                                    Departemen
                                </button>
                                <button type="button" class="tab-btn" data-tab="operator"
                                        style="flex:1;padding:10px 12px;border-radius:10px;font-size:13px;font-weight:600;border:1.5px solid var(--border);background:transparent;color:var(--text-3);cursor:pointer;transition:all .2s">
                                    Operator HL
                                </button>
                            </div>
                            <input type="hidden" name="assign_type" id="assignType" value="employee">
                        </div>

                        {{-- Pilihan Shift --}}
                        <div style="margin-bottom:20px">
                            <label style="font-size:12px;font-weight:600;color:var(--text-2);display:block;margin-bottom:10px">Pilihan Shift</label>

                            {{-- Selected chips --}}
                            <div id="selectedShiftsDisplay" style="margin-bottom:12px;padding:12px;border-radius:10px;min-height:50px;background:var(--bg-ghost);border:1px solid var(--border)">
                                <div id="selectedShiftChips" style="display:flex;flex-wrap:wrap;gap:8px">
                                    <span style="font-size:12px;color:var(--text-3)">Belum ada shift dipilih</span>
                                </div>
                            </div>

                            {{-- Dropdown --}}
                            <div style="position:relative">
                                <button type="button" id="shiftDropdownBtn"
                                        style="width:100%;padding:11px 14px;border-radius:10px;text-align:left;display:flex;align-items:center;justify-content:space-between;font-size:13px;border:1.5px solid var(--border);background:var(--in-bg);color:var(--text-2);cursor:pointer">
                                    <span>Pilih shift...</span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                                </button>
                                <div id="shiftDropdownList"
                                     style="display:none;position:absolute;width:100%;margin-top:8px;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,.3);z-index:10;max-height:320px;overflow-y:auto;background:var(--dd-bg);border:1px solid var(--dd-border)">

                                    @php
                                        $shiftGroups = [
                                            'Senin – Kamis' => ['1AA','1PR','1PQ','1ZA'],
                                            'Jumat'         => ['1AB','1PRB','1PQB','1ZAB'],
                                            'Senin – Jumat' => ['2ZB','3ZZ','3ZC'],
                                            'Sabtu'         => ['Day Off','1PQBN','1SSN','2SSN','3SSN'],
                                        ];
                                    @endphp

                                    @foreach($shiftGroups as $groupLabel => $codes)
                                        <div style="padding:8px 14px 4px;font-size:10px;font-weight:700;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;background:var(--bg-ghost);border-bottom:1px solid var(--dd-border)">
                                            {{ $groupLabel }}
                                        </div>
                                        @foreach($codes as $code)
                                            @php
                                                $sc = $shiftCodes->firstWhere('code', $code);
                                                $onTime  = $sc?->on_time  ? \Carbon\Carbon::parse($sc->on_time)->format('H:i')  : '--:--';
                                                $offTime = $sc?->off_time ? \Carbon\Carbon::parse($sc->off_time)->format('H:i') : '--:--';
                                            @endphp
                                            @if($sc)
                                            <label class="shift-option"
                                                   style="display:flex;align-items:center;gap:12px;padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--dd-border);transition:background .15s"
                                                   onmouseover="this.style.background='var(--dd-hover)'" onmouseout="this.style.background='transparent'">
                                                <input type="checkbox" name="shift_codes[]" value="{{ $sc->id }}"
                                                       class="shift-checkbox"
                                                       data-code="{{ $sc->code }}"
                                                       data-group="{{ $groupLabel }}"
                                                       data-shift="{{ $sc->shift->name ?? '' }}"
                                                       data-time="{{ $onTime }} – {{ $offTime }}"
                                                       style="width:16px;height:16px;cursor:pointer;accent-color:#7c3aed">
                                                <div style="flex:1">
                                                    <div style="font-size:13px;font-weight:600;color:var(--text-1)">
                                                        @if($sc->shift)<span style="color:#a78bfa">[{{ $sc->shift->name }}]</span> @endif{{ $sc->code }}
                                                    </div>
                                                    <div style="font-size:11px;color:var(--text-3);margin-top:2px">{{ $onTime }} – {{ $offTime }}</div>
                                                </div>
                                            </label>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>

                            {{-- Error konflik shift --}}
                            <div id="shiftConflictError" style="display:none;margin-top:10px;padding:10px 12px;border-radius:10px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);font-size:12px;color:#ef4444">
                                Shift untuk hari tersebut sudah dipilih. Hanya boleh satu shift per hari.
                            </div>
                        </div>

                        {{-- Date Range --}}
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
                            <div>
                                <label style="font-size:12px;font-weight:600;color:var(--text-2);display:block;margin-bottom:8px">Tanggal Mulai</label>
                                <input type="date" name="start_date" id="startDate" required
                                       style="width:100%;padding:11px 14px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:var(--in-bg);color:var(--text-1);outline:none">
                            </div>
                            <div>
                                <label style="font-size:12px;font-weight:600;color:var(--text-2);display:block;margin-bottom:8px">Tanggal Selesai</label>
                                <input type="date" name="end_date" id="endDate" required
                                       style="width:100%;padding:11px 14px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:var(--in-bg);color:var(--text-1);outline:none">
                            </div>
                        </div>
                        <div id="dateValidationError" style="display:none;padding:10px 12px;border-radius:10px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);font-size:12px;color:#ef4444">
                            End date tidak boleh lebih kecil dari start date
                        </div>
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div style="padding:24px">
                        <h4 style="font-size:11px;font-weight:700;color:var(--text-3);letter-spacing:.08em;text-transform:uppercase;margin-bottom:20px">DAFTAR NAMA</h4>

                        <div style="margin-bottom:12px;padding:12px;border-radius:10px;background:var(--bg-ghost);border:1px solid var(--border)">
                            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                                <input type="checkbox" id="checkAll" style="width:18px;height:18px;cursor:pointer;accent-color:#7c3aed">
                                <span style="font-size:13px;font-weight:600;color:var(--text-1)">Pilih Semua</span>
                            </label>
                        </div>

                        <div style="margin-bottom:12px;position:relative">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-3);pointer-events:none">
                                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                            </svg>
                            <input type="text" id="searchList" placeholder="Cari nama karyawan..."
                                   style="width:100%;padding:11px 14px 11px 40px;border-radius:10px;font-size:13px;border:1.5px solid var(--border);background:var(--in-bg);color:var(--text-1);outline:none">
                        </div>

                        <div id="itemList" style="border-radius:10px;max-height:300px;overflow-y:auto;border:1px solid var(--border);background:var(--in-bg)">
                            {{-- Employee List --}}
                            <div id="employeeList" class="list-content">
                                @foreach($employees as $emp)
                                    <label class="list-item"
                                           style="display:flex;align-items:center;gap:12px;padding:12px 14px;cursor:pointer;border-bottom:1px solid var(--border);transition:background .15s"
                                           data-name="{{ strtolower($emp->name) }}"
                                           onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                                        <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}" class="item-checkbox"
                                               style="width:16px;height:16px;cursor:pointer;accent-color:#7c3aed">
                                        <div style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;flex-shrink:0;background:linear-gradient(135deg,#7c3aed,#a78bfa);font-size:11px">
                                            {{ strtoupper(substr($emp->name,0,1)) }}{{ strtoupper(substr(strrchr($emp->name,' ') ?: ' ',1,1)) }}
                                        </div>
                                        <div style="flex:1;min-width:0">
                                            <p style="font-size:13px;font-weight:600;color:var(--text-1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $emp->name }}</p>
                                            <p style="font-size:11px;color:var(--text-3)">{{ $emp->nik }} • {{ $emp->department->name ?? '-' }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Department List --}}
                            <div id="departmentList" class="list-content" style="display:none">
                                @foreach($departments as $dept)
                                    <label class="list-item"
                                           style="display:flex;align-items:center;gap:12px;padding:12px 14px;cursor:pointer;border-bottom:1px solid var(--border);transition:background .15s"
                                           data-name="{{ strtolower($dept->name) }}"
                                           onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                                        <input type="checkbox" name="department_ids[]" value="{{ $dept->id }}" class="item-checkbox"
                                               style="width:16px;height:16px;cursor:pointer;accent-color:#7c3aed">
                                        <div style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;flex-shrink:0;background:linear-gradient(135deg,#14b8a6,#5eead4);font-size:11px">
                                            {{ strtoupper(substr($dept->name,0,1)) }}
                                        </div>
                                        <div style="flex:1;min-width:0">
                                            <p style="font-size:13px;font-weight:600;color:var(--text-1)">{{ $dept->name }}</p>
                                            <p style="font-size:11px;color:var(--text-3)">{{ $employees->where('department_id',$dept->id)->count() }} karyawan</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            {{-- Operator HL List --}}
                            <div id="operatorList" class="list-content" style="display:none">
                                @foreach($employees->whereIn('position',['Production Operator HL KIP','Production Operator HL CKG']) as $emp)
                                    <label class="list-item"
                                           style="display:flex;align-items:center;gap:12px;padding:12px 14px;cursor:pointer;border-bottom:1px solid var(--border);transition:background .15s"
                                           data-name="{{ strtolower($emp->name) }}"
                                           onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='transparent'">
                                        <input type="checkbox" name="operator_ids[]" value="{{ $emp->id }}" class="item-checkbox"
                                               style="width:16px;height:16px;cursor:pointer;accent-color:#7c3aed">
                                        <div style="width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;flex-shrink:0;background:linear-gradient(135deg,#f59e0b,#fbbf24);font-size:11px">
                                            {{ strtoupper(substr($emp->name,0,1)) }}{{ strtoupper(substr(strrchr($emp->name,' ') ?: ' ',1,1)) }}
                                        </div>
                                        <div style="flex:1;min-width:0">
                                            <p style="font-size:13px;font-weight:600;color:var(--text-1);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $emp->name }}</p>
                                            <p style="font-size:11px;color:var(--text-3)">{{ $emp->nik }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div style="margin-top:12px;padding:10px 12px;border-radius:10px;text-align:center;background:var(--bg-ghost);border:1px solid var(--border)">
                            <p style="font-size:13px;font-weight:600;color:var(--text-2)">
                                <span id="selectedCount" style="color:#a78bfa">0</span> dari
                                <span id="totalCount">{{ $employees->count() }}</span>
                                <span id="itemType">karyawan</span> dipilih
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mftr" style="padding:16px 24px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:flex-end;gap:12px">
                <button type="button" onclick="closeM('mAssignShift')"
                        style="padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;border:1.5px solid var(--border);background:transparent;color:var(--text-2);cursor:pointer">
                    Batal
                </button>
                <button type="submit" id="assignSubmitBtn" class="purbtn"
                        style="padding:10px 24px;border-radius:10px;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
                    <span id="assignBtnText">Tugaskan</span>
                    <span id="assignBtnLoading" style="display:none;align-items:center;gap:8px">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
@keyframes spin { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }
.tab-btn.active { background:rgba(124,58,237,.15) !important; border-color:rgba(124,58,237,.4) !important; color:#a78bfa !important; }
.tab-btn:hover:not(.active) { background:var(--bg-hover) !important; }
.shift-chip { display:inline-flex;align-items:flex-start;gap:10px;padding:10px 12px;border-radius:10px;font-size:11px;font-weight:600;background:rgba(124,58,237,.12);border:1px solid rgba(124,58,237,.25);color:#a78bfa;line-height:1.3; }
.shift-chip button { width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:rgba(124,58,237,.2);border:none;cursor:pointer;flex-shrink:0;margin-top:2px; }
.list-item:last-child { border-bottom:none !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tab switching ──
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active');
                b.style.background = 'transparent';
                b.style.borderColor = 'var(--border)';
                b.style.color = 'var(--text-3)';
            });
            this.classList.add('active');
            document.getElementById('assignType').value = this.dataset.tab;

            document.querySelectorAll('.list-content').forEach(l => l.style.display = 'none');
            const map = { employee: ['employeeList','karyawan'], department: ['departmentList','department'], operator: ['operatorList','operator'] };
            const [listId, label] = map[this.dataset.tab];
            document.getElementById(listId).style.display = 'block';
            document.getElementById('itemType').textContent = label;
            document.getElementById('totalCount').textContent = document.querySelectorAll('#' + listId + ' .item-checkbox').length;
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('checkAll').checked = false;
            updateCounter();
        });
    });

    // ── Shift dropdown ──
    document.getElementById('shiftDropdownBtn').addEventListener('click', function () {
        const dd = document.getElementById('shiftDropdownList');
        dd.style.display = dd.style.display === 'none' ? 'block' : 'none';
    });
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#shiftDropdownList') && !e.target.closest('#shiftDropdownBtn')) {
            document.getElementById('shiftDropdownList').style.display = 'none';
        }
    });

    // ── Shift selection + validasi konflik overlap hari ──
    // Mapping group → hari (1=Sen,2=Sel,3=Rab,4=Kam,5=Jum,6=Sab)
    const groupDays = {
        'Senin – Kamis' : [1,2,3,4],
        'Jumat'         : [5],
        'Senin – Jumat' : [1,2,3,4,5],
        'Sabtu'         : [6],
    };

    document.querySelectorAll('.shift-checkbox').forEach(cb => {
        cb.addEventListener('change', function () {
            if (this.checked && hasDayConflict(this)) {
                this.checked = false;
                document.getElementById('shiftConflictError').style.display = 'block';
                return;
            }
            document.getElementById('shiftConflictError').style.display = 'none';
            updateSelectedShifts();
        });
    });

    function hasDayConflict(target) {
        const targetDays = groupDays[target.dataset.group] || [];
        const selected   = Array.from(document.querySelectorAll('.shift-checkbox:checked'));
        return selected.some(cb => {
            if (cb === target) return false;
            const cbDays = groupDays[cb.dataset.group] || [];
            return targetDays.some(d => cbDays.includes(d));
        });
    }

    function updateSelectedShifts() {
        const selected = Array.from(document.querySelectorAll('.shift-checkbox:checked'));
        const container = document.getElementById('selectedShiftChips');
        if (selected.length === 0) {
            container.innerHTML = '<span style="font-size:12px;color:var(--text-3)">Belum ada shift dipilih</span>';
            return;
        }
        container.innerHTML = selected.map(cb => `
            <div class="shift-chip">
                <div>
                    <div style="font-weight:700;margin-bottom:3px">${cb.dataset.shift ? '['+cb.dataset.shift+'] ' : ''}${cb.dataset.code}</div>
                    <div style="font-size:10px;opacity:.8">${cb.dataset.time}</div>
                </div>
                <button type="button" onclick="removeShift('${cb.value}')">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>`).join('');
    }

    window.removeShift = function (value) {
        const cb = document.querySelector(`.shift-checkbox[value="${value}"]`);
        if (cb) {
            cb.checked = false;
            document.getElementById('shiftConflictError').style.display = 'none';
            updateSelectedShifts();
        }
    };

    // ── Check All ──
    document.getElementById('checkAll').addEventListener('change', function () {
        const active = document.querySelector('.list-content:not([style*="display: none"])');
        active.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
        updateCounter();
    });
    document.querySelectorAll('.item-checkbox').forEach(cb => cb.addEventListener('change', updateCounter));

    function updateCounter() {
        const active = document.querySelector('.list-content:not([style*="display: none"])');
        const all    = active.querySelectorAll('.item-checkbox');
        const checked = active.querySelectorAll('.item-checkbox:checked').length;
        document.getElementById('selectedCount').textContent = checked;
        document.getElementById('checkAll').checked = checked === all.length && checked > 0;
    }

    // ── Search ──
    document.getElementById('searchList').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        const active = document.querySelector('.list-content:not([style*="display: none"])');
        active.querySelectorAll('.list-item').forEach(item => {
            item.style.display = item.dataset.name.includes(q) ? 'flex' : 'none';
        });
    });

    // ── Date validation ──
    function validateDates() {
        const s = document.getElementById('startDate').value;
        const e = document.getElementById('endDate').value;
        const err = document.getElementById('dateValidationError');
        if (s && e && e < s) { err.style.display = 'block'; return false; }
        err.style.display = 'none'; return true;
    }
    document.getElementById('startDate').addEventListener('change', validateDates);
    document.getElementById('endDate').addEventListener('change', validateDates);

    // ── Form submit ──
    document.getElementById('assignShiftForm').addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validateDates()) return;
        if (!document.querySelectorAll('.shift-checkbox:checked').length) { alert('Harap pilih minimal satu shift'); return; }
        if (document.getElementById('shiftConflictError').style.display !== 'none') return;
        const active = document.querySelector('.list-content:not([style*="display: none"])');
        if (!active.querySelectorAll('.item-checkbox:checked').length) { alert('Harap pilih minimal satu item'); return; }

        const btn = document.getElementById('assignSubmitBtn');
        btn.disabled = true; btn.style.opacity = '.7';
        document.getElementById('assignBtnText').style.display = 'none';
        document.getElementById('assignBtnLoading').style.display = 'flex';
        this.submit();
    });
});
</script>
@endpush
