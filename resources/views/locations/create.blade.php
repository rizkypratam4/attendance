<x-ui.modal id="mAddLocation" title="Add New Location" maxWidth="480px">
    <form action="{{ route('locations.store') }}" method="POST">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Location Name</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Global Headquarters"
                    class="minput @error('name') border-red-500 @enderror"">
                    @error('name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>
            <div>
                <label class="mlabel">Location Type</label>
                <input type="text" name="description" value="{{ old('description') }}"
                    placeholder="e.g. Main Hub, Regional Branch, Warehouse" class="minput @error('description') border-red-500 @enderror">
                    @error('description')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>
            <div>
                <label class="mlabel">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" placeholder="Full street address"
                    class="minput @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mlabel">Latitude</label>
                    <input type="text" name="latitude" value="{{ old('latitude') }}" placeholder="e.g. 37.7749"
                        class="minput @error('latitude') border-red-500 @enderror">
                        @error('latitude')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                </div>
                <div>
                    <label class="mlabel">Longitude</label>
                    <input type="text" name="longitude" value="{{ old('longitude') }}" placeholder="e.g. -122.4194"
                        class="minput @error('longitude') border-red-500 @enderror">
                        @error('longitude')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                </div>
            </div>
            <div>
                <label class="mlabel">Status</label>
                <select name="is_active" class="minput" style="cursor:pointer">
                    <option value="">-- Select Status --</option>
                    <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('is_active')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mAddLocation')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Save Location
            </button>
        </div>
    </form>
</x-ui.modal>
