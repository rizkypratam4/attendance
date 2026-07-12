<x-ui.modal id="mUpdateUser" title="Perbarui Pengguna" maxWidth="480px">
    <form id="editUserForm" action="" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mlabel">Nama Depan</label>
                    <input type="text"
                           id="editFirstName"
                           name="first_name"
                           placeholder="Nama depan"
                           class="minput @error('first_name') border-red-500 @enderror">
                    @error('first_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mlabel">Nama Belakang</label>
                    <input type="text"
                           id="editLastName"
                           name="last_name"
                           placeholder="Nama belakang"
                           class="minput @error('last_name') border-red-500 @enderror">
                    @error('last_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="mlabel">Alamat Email</label>
                <input type="email"
                       id="editEmail"
                       name="email"
                       placeholder="user@example.com"
                       class="minput @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Peran</label>
                <select id="editRole"
                        name="role"
                        class="minput @error('role') border-red-500 @enderror"
                        style="cursor:pointer">
                    <option value="">-- Pilih Peran --</option>
                    <option value="mis">MIS</option>
                    <option value="manager">Manajer</option>
                    <option value="hr">HR</option>
                    <option value="employee">Karyawan</option>
                </select>
                @error('role')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">
                    Kata Sandi
                    <span style="color:var(--text-3);font-weight:400">(biarkan kosong untuk mempertahankan saat ini)</span>
                </label>
                <input type="password"
                       name="password"
                       placeholder="Masukkan kata sandi baru"
                       class="minput @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <button type="button"
                    onclick="closeM('mUpdateUser')"
                    class="flex-1 py-2.5 rounded-xl font-medium"
                    style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Batal
            </button>

            <button type="submit"
                    class="flex-1 purbtn py-2.5 rounded-xl font-semibold"
                    style="font-size:14px">
                Simpan Perubahan
            </button>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === 'PUT')
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mUpdateUser'));
    </script>
@endif