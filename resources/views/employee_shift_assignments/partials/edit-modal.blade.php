<div class="mbk" id="mEditAssignment" onclick="closeOut(event,'mEditAssignment')">
    <div class="mbox" style="max-width:500px">
        <div class="mhdr">
            <span class="mtitle">Edit Assignment</span>
            <button class="mclose" onclick="closeM('mEditAssignment')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <form id="editAssignForm" method="post">
                @csrf
                @method('patch')
                <div class="space-y-4">
                    <div>
                        <label class="mlabel">Employee</label>
                        <input type="text" id="editAssignName" class="minput" readonly style="opacity:.7;cursor:not-allowed">
                    </div>
                    <div>
                        <label class="mlabel">Shift</label>
                        <select id="editAssignShift" name="shift_code_id" class="minput" style="cursor:pointer">
                            <option value="">-- Select Shift --</option>
                            @foreach($shiftCodes as $sc)
                                <option value="{{ $sc->id }}">
                                    {{ $sc->code }}@if($sc->shift) ({{ ucfirst($sc->shift->name) }})@endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mlabel">Effective Date</label>
                            <input type="date" name="effective_date" id="editEffDate" class="minput">
                        </div>
                        <div>
                            <label class="mlabel">End Date</label>
                            <input type="date" name="end_date" id="editEndDate" class="minput">
                        </div>
                    </div>
                </div>
                <div class="flex gap" style="gap:1rem; margin-top:1.5rem;">
                    <button type="button" onclick="closeM('mEditAssignment')" class="flex-1 py-2.5 rounded-xl font-medium"
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