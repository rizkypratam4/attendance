<x-ui.modal id="mImportAssignment" title="Import Assignments" maxWidth="480px">
    <form action="{{ route('employee_shift_assignments.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Select File</label>
                <div class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition"
                    style="border-color:var(--border-in);background:var(--bg-input);" id="dropZoneAssign"
                    onclick="document.getElementById('fileInputAssign').click()">
                    <input type="file" id="fileInputAssign" name="file" accept=".csv,.xlsx,.xls"
                        style="display:none" onchange="handleAssignFile(event)">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" style="margin:0 auto 8px;color:var(--text-3)">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <p id="assignFileName" style="font-size:14px;color:var(--text-2);margin:0">Choose a file or drag it
                        here</p>
                    <p style="font-size:12px;color:var(--text-3);margin-top:4px">CSV, XLSX, or XLS</p>
                </div>
                @error('file')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div style="background:var(--bg-input);padding:12px;border-radius:8px;border-left:3px solid #7c3aed">
                <p style="font-size:12px;color:var(--text-2);margin:0;line-height:1.5"><strong>Required
                        columns:</strong></p>
                <p style="font-size:11px;color:var(--text-3);margin:4px 0 0 0">nik, shift_code, effective_date, end_date
                    (optional)</p>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button" onclick="closeM('mImportAssignment')" class="flex-1 py-2.5 rounded-xl font-medium"
                style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>

            <button type="submit" class="flex-1 purbtn py-2.5 rounded-xl font-semibold" style="font-size:14px">
                Import
            </button>
        </div>
    </form>
</x-ui.modal>

@push('scripts')
    <script>
        const dropZoneAssign = document.getElementById('dropZoneAssign');
        const fileInputAssign = document.getElementById('fileInputAssign');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evt => {
            dropZoneAssign.addEventListener(evt, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(evt => {
            dropZoneAssign.addEventListener(evt, () => {
                dropZoneAssign.style.borderColor = '#7c3aed';
                dropZoneAssign.style.background = 'rgba(124,58,237,.05)';
            });
        });
        ['dragleave', 'drop'].forEach(evt => {
            dropZoneAssign.addEventListener(evt, () => {
                dropZoneAssign.style.borderColor = 'var(--border-in)';
                dropZoneAssign.style.background = 'var(--bg-input)';
            });
        });

        dropZoneAssign.addEventListener('drop', e => {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInputAssign.files = files;
            handleAssignFile({
                target: {
                    files
                }
            });
        });

        function handleAssignFile(event) {
            const files = event.target.files;
            if (files.length > 0) {
                const fileName = files[0].name;
                const fileSize = (files[0].size / 1024).toFixed(2);
                document.getElementById('assignFileName').textContent = `${fileName} (${fileSize} KB)`;
                dropZoneAssign.style.borderColor = '#22c55e';
            }
        }
    </script>
@endpush

@if ($errors->has('file') || (session('import_errors') && !empty(session('import_errors'))))
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mImportAssignment'));
    </script>
@endif
