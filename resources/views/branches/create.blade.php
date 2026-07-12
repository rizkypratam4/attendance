<x-ui.modal id="mAddBranch" title="Tambah Perusahaan Baru" maxWidth="480px">
    <form action="{{ route('branches.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Nama Perusahaan</label>
                <input type="text" name="name" placeholder="mis. Kantor Pusat" 
                class="minput @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Status</label>
                <select name="is_active" class="minput" style="cursor:pointer">
                    <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('is_active')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mAddBranch')" class="flex-1 py-2.5 rounded-xl font-medium"
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
        document.addEventListener('DOMContentLoaded', () => openM('mEditDepartment'));
    </script>
@endif
