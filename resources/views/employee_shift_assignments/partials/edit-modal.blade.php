<div class="mbk" id="mEditAssignment" onclick="closeOut(event,'mEditAssignment')">
    <div class="mbox" style="max-width:520px">
        <div class="mhdr">
            <span class="mtitle">Edit Assignment</span>
            <button class="mclose" onclick="closeM('mEditAssignment')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <form id="editAssignForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="employee_id" id="editAssignEmployeeId">
                <div class="space-y-4">

                    {{-- Employee (readonly) --}}
                    <div>
                        <label class="mlabel">Employee</label>
                        <input type="text" id="editAssignName" class="minput" readonly style="opacity:.6;cursor:not-allowed">
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <label class="mlabel">Tanggal</label>
                        <input type="date" name="date" id="editAssignDate" class="minput" required>
                    </div>

                    {{-- Shift Code Default (readonly, tidak bisa diubah) --}}
                    <div>
                        <label class="mlabel">Shift Code <span style="font-weight:400;color:var(--text-3)">(default — tidak bisa diubah)</span></label>
                        <input type="text" id="editAssignShiftDisplay" class="minput" readonly style="opacity:.6;cursor:not-allowed">
                    </div>

                    {{-- New Working Shift --}}
                    <div>
                        <label class="mlabel">New Working Shift <span style="font-weight:400;color:var(--text-3)">(opsional)</span></label>
                        <select id="editAssignNewShift" name="new_working_shift_id" class="minput" style="cursor:pointer">
                            <option value="">— Tidak Ada —</option>
                            @foreach($shiftCodes as $sc)
                                <option value="{{ $sc->id }}">
                                    {{ $sc->code }}
                                    @if(!$sc->is_day_off && $sc->on_time)
                                        — {{ \Carbon\Carbon::parse($sc->on_time)->format('H:i') }}
                                        s/d {{ \Carbon\Carbon::parse($sc->off_time)->format('H:i') }}
                                    @endif
                                    @if($sc->shift) ({{ $sc->shift->name }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <p style="font-size:11px;color:var(--text-3);margin-top:4px">Shift ini yang digunakan untuk proses attendance.</p>
                    </div>

                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeM('mEditAssignment')"
                            class="flex-1 py-2.5 rounded-xl font-medium"
                            style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
