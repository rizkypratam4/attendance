<div class="modal fade" id="importAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <form action="action.php" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg rounded-4">
            <!-- Header -->
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">Import Data Kehadiran</h5>
            </div>
            <!-- Body -->
            <div class="modal-body pt-4">
                <div class="border border-dashed rounded-3 p-4 text-center bg-light">
                    <i class="ti ti-upload fs-1 text-primary mb-2"></i>

                    <p class="mb-1 fw-semibold">
                        Upload File Attendance
                    </p>
                    <small class="text-muted d-block mb-3">
                        Format file: <b>.xlsx</b> atau <b>.csv</b>
                    </small>

                    <input 
                        type="file" 
                        class="form-control" 
                        name="file_excel"
                        accept=".xls,.xlsx"
                        required
                    >
                </div>
            </div>
            <!-- Footer -->
            <div class="modal-footer border-0 pt-0">
                <button 
                    type="button" 
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
                    Batal
                </button>

                <button 
                    type="submit" 
                    class="btn btn-primary rounded-pill px-4" name="import">
                    <i class="ti ti-device-floppy"></i>
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>