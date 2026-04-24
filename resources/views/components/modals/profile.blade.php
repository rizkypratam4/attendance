<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PATCH')

    <div class="flex flex-col items-center mb-6">
        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-orange-400 to-pink-500
                    flex items-center justify-center font-bold text-white mb-3 overflow-hidden">
            @if(auth()->user()->image)
                <img src="{{ asset('storage/' . auth()->user()->image) }}" alt="Profile Picture"
                     class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr(auth()->user()->name ?? 'AT', 0, 2)) }}
            @endif
        </div>
        <label for="profileImageInput" class="cursor-pointer">
            <span style="font-size:13px;color:#a78bfa;font-weight:600;">Change Photo</span>
            <input type="file" id="profileImageInput" name="image" accept="image/*" class="hidden">
        </label>
        @error('image')
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mlabel">First Name</label>
                <input type="text" name="first_name"
                       value="{{ auth()->user()->first_name ?? 'Alex' }}" class="minput">
                @error('first_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Last Name</label>
                <input type="text" name="last_name"
                       value="{{ auth()->user()->last_name ?? 'Thompson' }}" class="minput">
                @error('last_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div>
            <label class="mlabel">Email Address</label>
            <input type="email" name="email"
                   value="{{ auth()->user()->email ?? 'alex.thompson@company.com' }}" class="minput">
            @error('email')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="mlabel">Role</label>
            <input type="text" value="{{ ucfirst(auth()->user()->role ?? 'Super Admin') }}"
                   class="minput" readonly style="opacity:.55;cursor:not-allowed">
        </div>
    </div>
    <div class="flex gap-3 mt-6">
        <button type="button" onclick="closeM('mProfile')"
                class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2)">
            Cancel
        </button>
        <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold"
                style="font-size:14px">
            Save Changes
        </button>
    </div>
</form>

@if ($errors->hasAny(['first_name', 'last_name', 'email', 'image']))
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mProfile'));
    </script>
@endif

<script>
document.getElementById('profileImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const profileImg = document.querySelector('.w-20.h-20.rounded-full');
            profileImg.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>