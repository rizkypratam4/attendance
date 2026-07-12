<x-ui.modal id="mAddDepartment" title="Tambah Departemen Baru" maxWidth="480px">
    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Nama Departemen</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="mis. Teknik" 
                class="minput @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Subjudul / Kategori</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="mis. Teknologi & Infrastruktur" 
                class="minput @error('subtitle') border-red-500 @enderror">
                @error('subtitle')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Kepala Departemen</label>
                <input type="text" name="head_employee_id" value="{{ old('head_employee_id') }}" placeholder="Nama lengkap" 
                class="minput @error('head_employee_id') border-red-500 @enderror">
                @error('head_employee_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mAddDepartment')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Batal
            </button>
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Simpan Departemen
            </button>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === 'PUT')
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mEditDepartment'));
    </script>
@endif