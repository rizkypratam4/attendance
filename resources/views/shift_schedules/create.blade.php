<x-ui.modal id="mAddShiftSchedule" title="Create New Shift Schedule" maxWidth="540px">
    <form id="addShiftScheduleForm" action="{{ route('shift_schedules.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Shift Code</label>
                <select name="shift_code_id" class="minput" style="cursor:pointer">
                    <option value="">Select Shift Code</option>
                    @foreach($shiftCodes as $code)
                        <option value="{{ $code->id }}">{{ $code->code }} ({{ $code->shift->name ?? '' }})</option>
                    @endforeach
                </select>
                @error('shift_code_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Day Type</label>
                <select name="day_type" class="minput" style="cursor:pointer">
                    <option value="">Select Day Type</option>
                    <option value="senin_kamis">Senin - Kamis</option>
                    <option value="jumat">Jumat</option>
                    <option value="sabtu">Sabtu</option>
                </select>
                @error('day_type')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Schedule Code</label>
                <input name="schedule_code" type="text" placeholder="optional code" class="minput">
                @error('schedule_code')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mlabel">Start Time</label>
                    <input name="start_time" type="time" class="minput">
                    @error('start_time')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="mlabel">End Time</label>
                    <input name="end_time" type="time" class="minput">
                    @error('end_time')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_day_off">
                    <span class="text-sm">Day Off</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="is_overnight">
                    <span class="text-sm">Overnight</span>
                </label>
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeM('mAddShiftSchedule')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">Cancel</button>
            <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">Save Schedule</button>
        </div>
    </form>
</x-ui.modal>