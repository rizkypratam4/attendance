<div class="modal fade" id="editEmployeeModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <form id="editEmployeeForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="ti ti-edit"></i> Edit Employee
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div id="editFormAlert"></div>
        <input type="hidden" name="id" id="edit_id">

        <!-- PERSONAL INFORMATION -->
        <h6 class="fw-semibold mb-3 text-muted">Personal Information</h6>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Nama</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">NIK</label>
            <input type="text" name="nik" id="edit_nik" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Gender</label>
            <input type="text" name="gender" id="edit_gender" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Religion</label>
            <input type="text" name="religion" id="edit_religion" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Place of Birth</label>
            <input type="text" name="place_of_birth" id="edit_place_of_birth" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Date of Birth</label>
            <input type="date" name="date_of_birth" id="edit_date_of_birth" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Marital Status</label>
            <input type="text" name="marital_status" id="edit_marital_status" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" id="edit_phone" class="form-control" required>
          </div>

          <div class="col-12">
            <label class="form-label">Address</label>
            <textarea name="address" id="edit_address" class="form-control" rows="2" required></textarea>
          </div>
        </div>

        <hr class="my-4">

        <!-- EMPLOYMENT INFORMATION -->
        <h6 class="fw-semibold mb-3 text-muted">Employment Information</h6>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Branch</label>
            <input type="text" name="branch" id="edit_branch" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Departement</label>
            <input type="text" name="departement" id="edit_departement" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Position</label>
            <input type="text" name="position" id="edit_position" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Title</label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Employee Status</label>
            <input type="text" name="employee_status" id="edit_employee_status" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Contract Count</label>
            <input type="number" name="contract_count" id="edit_contract_count" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Join Date</label>
            <input type="date" name="join_date" id="edit_join_date" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label class="form-label">Effective Date</label>
            <input type="date" name="effective_date" id="edit_effective_date" class="form-control" required>
          </div>
        </div>

        <hr class="my-4">

        <!-- DOCUMENT & CONTACT -->
        <h6 class="fw-semibold mb-3 text-muted">Document & Contact</h6>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Office Email</label>
            <input type="email" name="office_email" id="edit_office_email" class="form-control" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Personal Email</label>
            <input type="email" name="personal_email" id="edit_personal_email" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label class="form-label">NPWP</label>
            <input type="text" name="npwp" id="edit_npwp" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">BPJS TK</label>
            <input type="text" name="bpjs_tk" id="edit_bpjs_tk" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label">BPJS Health</label>
            <input type="text" name="bpjs_health" id="edit_bpjs_health" class="form-control" required>
          </div>

          <div class="col-md-12">
            <label class="form-label">KTP Number</label>
            <input type="text" name="ktp_number" id="edit_ktp_number" class="form-control" required>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
      </div>
    </form>
  </div>
</div>
