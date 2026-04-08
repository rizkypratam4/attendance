<x-ui.modal id="mAddEmployee" title="Add New Employee" maxWidth="480px">
    <form id="addEmployeeForm" action="{{ route('employees.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="mlabel">Name</label>
                <input type="text" id="addEmployeeName" name="name" placeholder="Full name"
                    class="minput @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">NIK</label>
                <input type="text" id="addEmployeeNIK" name="nik" placeholder="NIK"
                    class="minput @error('nik') border-red-500 @enderror" value="{{ old('nik') }}">
                @error('nik')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Machine Barcode</label>
                <input type="text" id="addMachineBarcode" name="machine_barcode" placeholder="Machine Barcode"
                    class="minput @error('machine_barcode') border-red-500 @enderror" value="{{ old('machine_barcode') }}">
                @error('machine_barcode')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Branch</label>
                <select id="addBranch" name="branch_id" class="minput @error('branch_id') border-red-500 @enderror"
                    style="cursor:pointer">
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Department</label>
                <select id="addDepartment" name="department_id"
                    class="minput @error('department_id') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Position</label>
                <input type="text" id="addPosition" name="position" placeholder="Position" required
                    class="minput @error('position') border-red-500 @enderror" value="{{ old('position') }}">
                @error('position')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Location</label>
                <select id="addLocation" name="location_id"
                    class="minput @error('location_id') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Select Location --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ old('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
                @error('location_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Employee Status</label>
                <input type="text" id="addEmployeeStatus" name="employee_status" placeholder="Employee Status" required
                    class="minput @error('employee_status') border-red-500 @enderror" value="{{ old('employee_status') }}">
                @error('employee_status')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Active</label>
                <select id="addIsActive" name="is_active"
                    class="minput @error('is_active') border-red-500 @enderror" style="cursor:pointer">
                    <option value="1" {{ old('is_active',1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeM('mAddEmployee')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>

            <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Create Employee
            </button>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === null)
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mAddEmployee'));
    </script>
@endif
