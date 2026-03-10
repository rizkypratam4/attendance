<x-ui.modal id="mEditShiftCode" title="Edit Shift Code" maxWidth="480px">
    <form id="editShiftCodeForm" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="mlabel">Shift Code</label>
                <input name="code" type="text" placeholder="e.g. M-01" class="minput">
                @error('code')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Shift Name</label>
                <select name="shift_id" class="minput" style="cursor:pointer">
                    <option value="">Select Shift</option>
                    @foreach(\App\Models\Shift::all() as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                    @endforeach
                </select>
                @error('shift_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Status IDT</label>
                <select name="has_idt" class="minput" style="cursor:pointer">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
                @error('has_idt')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mEditShiftCode')" class="flex-1 py-2.5 rounded-xl font-medium"
                    style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">Cancel</button>
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">Update Shift Code</button>
        </div>
    </form>
</x-ui.modal>
