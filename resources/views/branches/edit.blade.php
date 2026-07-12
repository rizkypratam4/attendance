<x-ui.modal id="mEditBranch" title="Perbarui Perusahaan" maxWidth="480px">
    <form id="editBranchForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="mlabel">Nama Perusahaan</label>
                <input id="editBranchName" type="text" name="name" placeholder="mis. Kantor Pusat" 
                class="minput @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Status</label>
                <select id="editBranchIsActive" name="is_active" class="minput @error('is_active') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Pilih Status --</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
                @error('is_active')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
             <button type="button" onclick="closeM('mEditBranch')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Batal
            </button>

            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Simpan Perusahaan
            </button>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === 'PUT')
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mEditBranch'));
    </script>
@endif
