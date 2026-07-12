<form method="POST" action="{{ route('password.change') }}">
    @csrf
    @method('PATCH')
    <div class="space-y-4">

        <div>
            <label class="mlabel">Kata Sandi Saat Ini</label>
            <div class="relative">
                <input type="password" id="p1" name="current_password"
                       placeholder="Masukkan kata sandi saat ini" class="minput" style="padding-right:44px">
                <button type="button" onclick="togPwd('p1',this)"
                        class="ib absolute right-3 top-1/2 -translate-y-1/2" style="opacity:.5">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
            @error('current_password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mlabel">Kata Sandi Baru</label>
            <div class="relative">
                <input type="password" id="p2" name="password"
                       placeholder="Masukkan kata sandi baru" class="minput" style="padding-right:44px">
                <button type="button" onclick="togPwd('p2',this)"
                        class="ib absolute right-3 top-1/2 -translate-y-1/2" style="opacity:.5">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="mlabel">Konfirmasi Kata Sandi Baru</label>
            <div class="relative">
                <input type="password" id="p3" name="password_confirmation"
                       placeholder="Konfirmasi kata sandi baru" class="minput" style="padding-right:44px">
                <button type="button" onclick="togPwd('p3',this)"
                        class="ib absolute right-3 top-1/2 -translate-y-1/2" style="opacity:.5">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="rounded-xl p-4 space-y-2"
             style="background:var(--bg-ghost);border:1px solid var(--border)">
            <p style="font-size:13px;font-weight:600;color:var(--text-2);margin-bottom:8px">
                Persyaratan kata sandi:
            </p>
            <div class="flex items-center gap-2.5" style="font-size:13px;color:var(--text-3)">
                <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0"
                      style="background:var(--text-3)"></span>Minimal 8 karakter
            </div>
            <div class="flex items-center gap-2.5" style="font-size:13px;color:var(--text-3)">
                <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0"
                      style="background:var(--text-3)"></span>Satu huruf kapital
            </div>
            <div class="flex items-center gap-2.5" style="font-size:13px;color:var(--text-3)">
                <span class="w-1.5 h-1.5 rounded-full inline-block flex-shrink-0"
                      style="background:var(--text-3)"></span>Satu angka atau karakter khusus
            </div>
        </div>

    </div>
    <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeM('mPassword')"
                class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2)">
            Batal
        </button>
        <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold"
                style="font-size:14px">
            Perbarui Kata Sandi
        </button>
    </div>
</form>

@if ($errors->hasAny(['current_password', 'password', 'password_confirmation']))
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mPassword'));
    </script>
@endif