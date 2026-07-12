<x-ui.modal id="mUpdateEmployee" title="Perbarui Karyawan" maxWidth="480px">
    <form id="editEmployeeForm" action="" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="mlabel">Nama</label>
                <input type="text" id="editEmployeeName" name="name" placeholder="Nama lengkap"
                    class="minput @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">NIK</label>
                <input type="text" id="editEmployeeNIK" name="nik" placeholder="NIK"
                    class="minput @error('nik') border-red-500 @enderror">
                @error('nik')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Barcode Mesin</label>
                <input type="text" id="editMachineBarcode" name="machine_barcode" placeholder="Barcode Mesin"
                    class="minput @error('machine_barcode') border-red-500 @enderror">
                @error('machine_barcode')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Cabang</label>
                <select id="editBranch" name="branch_id" class="minput @error('branch_id') border-red-500 @enderror"
                    style="cursor:pointer">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Departemen</label>
                <select id="editDepartment" name="department_id"
                    class="minput @error('department_id') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Pilih Departemen --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Posisi</label>
                <input type="text" id="editPosition" name="position" placeholder="Posisi" required
                    class="minput @error('position') border-red-500 @enderror">
                @error('position')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Lokasi</label>
                <select id="editLocation" name="location_id"
                    class="minput @error('location_id') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                    @endforeach
                </select>
                @error('location_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Status Karyawan</label>
                <input type="text" id="editEmployeeStatus" name="employee_status" placeholder="Status Karyawan" required
                    class="minput @error('employee_status') border-red-500 @enderror">
                @error('employee_status')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Status Aktif</label>
                <select id="editIsActive" name="is_active"
                    class="minput @error('is_active') border-red-500 @enderror" style="cursor:pointer">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
                @error('is_active')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeM('mUpdateEmployee')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Batal
            </button>

            <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === 'PUT')
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mUpdateEmployee'));
    </script>
@endif
