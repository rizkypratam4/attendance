<div class="mbk" id="mNewAssignment" onclick="closeOut(event,'mNewAssignment')">
    <div class="mbox" style="max-width:500px">
        <div class="mhdr">
            <span class="mtitle">New Assignment</span>
            <button class="mclose" onclick="closeM('mNewAssignment')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="mbdy">
            <form action="{{ route('employee_shift_assignments.store') }}" method="post">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="mlabel">Employee</label>
                        <select name="employee_id" id="newEmployee" class="minput" style="cursor:pointer">
                            <option value="">-- Select Employee --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" data-dept="{{ $emp->department?->name }}">
                                    {{ $emp->name }} ({{ $emp->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mlabel">Department</label>
                        <select name="department" id="newDept" class="minput" style="cursor:pointer">
                            <option value="">-- Select Department --</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mlabel">Shift</label>
                        <select name="shift_code_id" class="minput" style="cursor:pointer">
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
                            <input type="date" name="effective_date" class="minput" value="{{ old('effective_date') }}">
                        </div>
                        <div>
                            <label class="mlabel">End Date</label>
                            <div class="relative">
                                <input type="date" name="end_date" id="newEndDateInput" class="minput">
                                <div class="flex items-center gap-2 mt-2">
                                    <input type="checkbox" id="newPermanentCheck" onchange="togglePermanent(this,'newEndDateInput')" class="cursor-pointer">
                                    <label for="newPermanentCheck" style="font-size:12px;color:var(--text-3);cursor:pointer">Permanent</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeM('mNewAssignment')" class="flex-1 py-2.5 rounded-xl font-medium"
                            style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                        Save Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>