<x-ui.modal id="mImportEmployee" title="Import Employee" maxWidth="480px">
    <form id="importEmployeeForm" action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="mlabel">Select File</label>
                <div class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition"
                    style="border-color:var(--border-in);background:var(--bg-input);"
                    id="dropZone"
                    onclick="document.getElementById('fileInput').click()">
                    <input type="file" id="fileInput" name="file" accept=".csv,.xlsx,.xls" style="display:none" onchange="handleFileSelect(event)">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin:0 auto 8px;color:var(--text-3)">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <p id="fileName" style="font-size:14px;color:var(--text-2);margin:0">Choose a file or drag it here</p>
                    <p style="font-size:12px;color:var(--text-3);margin-top:4px">CSV, XLSX, or XLS</p>
                </div>
                @error('file')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div style="background:var(--bg-input);padding:12px;border-radius:8px;border-left:3px solid #7c3aed">
                <p style="font-size:12px;color:var(--text-2);margin:0;line-height:1.5"><strong>Required columns:</strong></p>
                <p style="font-size:11px;color:var(--text-3);margin:4px 0 0 0">first_name, last_name, email, phone, employee_id, department_id, position, branch_id</p>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="button"
                    onclick="closeM('mImportEmployee')"
                    class="flex-1 py-2.5 rounded-xl font-medium"
                    style="font-size:14px;border:1px solid var(--border);background:var(--bg-ghost);color:var(--text-2);cursor:pointer">
                Cancel
            </button>

            <button type="submit"
                    class="flex-1 purbtn py-2.5 rounded-xl font-semibold"
                    style="font-size:14px">
                Import Employee
            </button>
        </div>
    </form>

</x-ui.modal>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = '#7c3aed';
            dropZone.style.background = 'rgba(124,58,237,.05)';
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.style.borderColor = 'var(--border-in)';
            dropZone.style.background = 'var(--bg-input)';
        });
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFileSelect({ target: { files } });
    });

    function handleFileSelect(event) {
        const files = event.target.files;
        if (files.length > 0) {
            const fileName = files[0].name;
            const fileSize = (files[0].size / 1024).toFixed(2);
            document.getElementById('fileName').textContent = `${fileName} (${fileSize} KB)`;
            dropZone.style.borderColor = '#22c55e';
        }
    }
</script>

@if ($errors->has('file') || (session('import_errors') && !empty(session('import_errors'))))
    <script>
        document.addEventListener('DOMContentLoaded', () => openM('mImportEmployee'));
    </script>
@endif