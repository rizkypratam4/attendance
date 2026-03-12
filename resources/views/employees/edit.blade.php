<x-ui.modal id="mUpdateEmployee" title="Update Employee" maxWidth="480px">
    <form id="editEmployeeForm" action="" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="mlabel">Name</label>
                <input type="text" id="editEmployeeName" name="name" placeholder="Full name"
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
                <label class="mlabel">Machine Barcode</label>
                <input type="text" id="editMachineBarcode" name="machine_barcode" placeholder="Machine Barcode"
                    class="minput @error('machine_barcode') border-red-500 @enderror">
                @error('machine_barcode')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Branch</label>
                <select id="editBranch" name="branch_id" class="minput @error('branch_id') border-red-500 @enderror"
                    style="cursor:pointer">
                    <option value="">-- Select Branch --</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                @error('branch_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Department</label>
                <select id="editDepartment" name="department_id"
                    class="minput @error('department_id') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
                @error('department_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Position</label>
                <input type="text" id="editPosition" name="position" placeholder="Position" required
                    class="minput @error('position') border-red-500 @enderror">
                @error('position')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Location</label>
                <select id="editLocation" name="location_id"
                    class="minput @error('location_id') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Select Location --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                    @endforeach
                </select>
                @error('location_id')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Employee Status</label>
                <input type="text" id="editEmployeeStatus" name="employee_status" placeholder="Employee Status" required
                    class="minput @error('employee_status') border-red-500 @enderror">
                @error('employee_status')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mlabel">Active</label>
                <select id="editIsActive" name="is_active"
                    class="minput @error('is_active') border-red-500 @enderror" style="cursor:pointer">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('is_active')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeM('mUpdateEmployee')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>

            <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Save Changes
            </button>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === 'PUT')
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mUpdateEmployee'));
    </script>
@endif
