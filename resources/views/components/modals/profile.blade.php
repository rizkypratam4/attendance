<div class="flex flex-col items-center mb-6">
    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-orange-400 to-pink-500
                flex items-center justify-center font-bold text-white mb-3"
         style="font-size:24px">
        {{ strtoupper(substr(auth()->user()->name ?? 'AT', 0, 2)) }}
    </div>
    <button style="font-size:13px;color:#a78bfa;font-weight:600;background:none;border:none;cursor:pointer">
        Change Photo
    </button>
</div>

<form method="POST" action="">
    @csrf
    @method('PATCH')
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mlabel">First Name</label>
                <input type="text" name="first_name"
                       value="{{ auth()->user()->first_name ?? 'Alex' }}" class="minput">
            </div>
            <div>
                <label class="mlabel">Last Name</label>
                <input type="text" name="last_name"
                       value="{{ auth()->user()->last_name ?? 'Thompson' }}" class="minput">
            </div>
        </div>
        <div>
            <label class="mlabel">Email Address</label>
            <input type="email" name="email"
                   value="{{ auth()->user()->email ?? 'alex.thompson@company.com' }}" class="minput">
        </div>
        <div>
            <label class="mlabel">Phone Number</label>
            <input type="tel" name="phone"
                   value="{{ auth()->user()->phone ?? '' }}"
                   placeholder="+1 (555) 000-0000" class="minput">
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