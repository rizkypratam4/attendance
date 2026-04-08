<x-ui.modal id="mEditLocation" title="Edit Location" maxWidth="480px" style="z-index: 1050; position: fixed;">
    <form id="editLocationForm" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="mlabel">Location Name</label>
                <input id="editLocationName" type="text" name="name" placeholder="e.g. Global Headquarters"
                    class="minput @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>
            <div>
                <label class="mlabel">Location Type</label>
                <input id="editLocationDescription" type="text" name="description"
                    placeholder="e.g. Main Hub, Regional Branch, Warehouse" class="minput @error('description') border-red-500 @enderror">
                    @error('description')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>
            <div>
                <label class="mlabel">Address</label>
                <input id="editLocationAddress" type="text" name="address" placeholder="Full street address"
                    class="minput @error('address') border-red-500 @enderror">
                    @error('address')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mlabel">Latitude</label>
                    <input id="editLocationLatitude" type="text" name="latitude" placeholder="e.g. 37.7749"
                        class="minput @error('latitude') border-red-500 @enderror">
                        @error('latitude')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                </div>
                <div>
                    <label class="mlabel">Longitude</label>
                    <input id="editLocationLongitude" type="text" name="longitude" placeholder="e.g. -122.4194"
                        class="minput @error('longitude') border-red-500 @enderror">
                        @error('longitude')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                </div>
            </div>
            <div>
                <label class="mlabel">Status</label>
                <select id="editLocationIsActive" name="is_active" class="minput @error('is_active') border-red-500 @enderror" style="cursor:pointer">
                    <option value="">-- Select Status --</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('is_active')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="flex gap-3 mt-6">
            <button onclick="closeM('mEditLocation')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>
            <button class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Save Location
            </button>
        </div>
    </form>
</x-ui.modal>

@if ($errors->any() && old('_method') === 'PUT')
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mEditLocation'));
    </script>
@endif