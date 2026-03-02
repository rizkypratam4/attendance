<x-ui.modal id="mAddDepartment" title="Add New Department" maxWidth="480px">
    <form action="{{ route('departments.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Department Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Engineering" 
                class="minput @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Subtitle / Category</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Tech & Infrastructure" 
                class="minput @error('subtitle') border-red-500 @enderror">
                @error('subtitle')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="mlabel">Head of Department</label>
                <input type="text" name="head_employee_id" value="{{ old('head_employee_id') }}" placeholder="Full name" 
                class="minput @error('head_employee_id') border-red-500 @enderror">
                @error('head_employee_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mAddDepartment')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Save Department
            </button>
        </div>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === 'PUT')
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mEditDepartment'));
    </script>
@endif