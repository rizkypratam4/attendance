<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editEmployeeForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-edit"></i> Edit 
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="editFormAlert"></div>
                <input type="hidden" name="id" id="edit_id">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" id="edit_nik" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" name="updateEmployee">Simpan</button>
            </div>
        </form>
    </div>
</div>