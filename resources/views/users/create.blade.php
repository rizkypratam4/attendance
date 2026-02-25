<x-ui.modal id="mCreateUser" title="Create New User" maxWidth="480px">
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="space-y-4">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mlabel">First Name</label>
                    <input type="text"
                           name="first_name"
                           value="{{ old('first_name') }}"
                           placeholder="First name"
                           class="minput @error('first_name') border-red-500 @enderror">
                    @error('first_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mlabel">Last Name</label>
                    <input type="text"
                           name="last_name"
                           value="{{ old('last_name') }}"
                           placeholder="Last name"
                           class="minput @error('last_name') border-red-500 @enderror">
                    @error('last_name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="mlabel">Email Address</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="user@example.com"
                       class="minput @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Role</label>
                <select name="role"
                        class="minput @error('role') border-red-500 @enderror"
                        style="cursor:pointer">
                    <option value="">-- Select Role --</option>
                    <option value="mis"      {{ old('role') == 'mis'      ? 'selected' : '' }}>MIS</option>
                    <option value="manager"  {{ old('role') == 'manager'  ? 'selected' : '' }}>Manager</option>
                    <option value="hr"       {{ old('role') == 'hr'       ? 'selected' : '' }}>HR</option>
                    <option value="employee" {{ old('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                </select>
                @error('role')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Password</label>
                <input type="password"
                       name="password"
                       placeholder="Set password"
                       class="minput @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

        </div>

        <div class="flex gap-3 mt-6">
            <button type="button"
                    onclick="closeM('mCreateUser')"
                    class="flex-1 py-2.5 rounded-xl font-medium"
                    style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>

            <button type="submit"
                    class="flex-1 purbtn py-2.5 rounded-xl font-semibold"
                    style="font-size:14px">
                Create User
            </button>
        </div>

    </form>

</x-ui.modal>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mCreateUser'));
    </script>
@endif