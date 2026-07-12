<x-ui.modal id="mAddShiftGroup" title="Tambah Grup Shift Baru" maxWidth="480px">
    <form action="{{ route('shift_groups.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Nama Shift</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="mis. Tim Keamanan"
                    class="minput @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Deskripsi</label>
                <textarea name="description" value="{{ old('description') }}" placeholder="Deskripsi singkat..."
                    class="minput @error('description') border-red-500 @enderror" rows="3" style="resize:none"></textarea>
                @error('description')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mAddShiftGroup')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">Batal</button>
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">Simpan Grup</button>
        </div>
    </form>
</x-ui.modal>
