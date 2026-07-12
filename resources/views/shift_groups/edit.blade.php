<x-ui.modal id="mEditShiftGroup" title="Perbarui Grup Shift" maxWidth="480px">
    <form id="editShiftGroupForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="mlabel">Nama Shift</label>
                <input id="editShiftGroupName" type="text" name="name" placeholder="mis. Tim Keamanan"
                    class="minput @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Deskripsi</label>
                <textarea id="editShiftGroupDescription" name="description" placeholder="Deskripsi singkat..." class="minput @error('description') 
                border-red-500 @enderror" rows="3" style="resize:none"></textarea>
                @error('description')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mEditShiftGroup')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">Batal</button>
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">Simpan Grup</button>
        </div>
    </form>
</x-ui.modal>
