<?php
require_once __DIR__ . '/../layout/a_config.php';
require __DIR__ . "/../config/database.php";
include __DIR__ . "/../auth/auth.php";

$limit = 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = $page < 1 ? 1 : $page;

$offset = ($page - 1) * $limit;

$totalResult = $conn->query("SELECT COUNT(*) AS total FROM users");
$totalRow = $totalResult->fetch_assoc();
$totalData = $totalRow['total'];
$totalPages = ceil($totalData / $limit);

$result = $conn->query("
    SELECT id, name, username 
    FROM users 
    ORDER BY id DESC 
    LIMIT $limit OFFSET $offset
");


ob_start();
?>
<div class="col-lg-12">
    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title">
                        User Management
                    </h4>

                    <p class="card-subtitle">
                        Kelola data pengguna dan hak akses sistem
                    </p>
                </div>

                <button
                    data-bs-toggle="modal"
                    data-bs-target="#createUserModal"
                    class="btn btn-primary d-flex align-items-center gap-1">

                    <i class="ti ti-user-plus fs-5"></i>
                    <span>Tambah User</span>
                </button>

            </div>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Username</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <?php include __DIR__ . "/list.php"; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<!-- Modal Create User -->
<div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="createUserForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">
                    <i class="ti ti-user-plus fs-6 me-1"></i>
                    Tambah User Baru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="formAlert"></div> <!-- tempat flash message -->
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="editUserForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-edit"></i> Edit User
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
                    <label class="form-label">Username</label>
                    <input type="text" name="username" id="edit_username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password (opsional)</label>
                    <input type="password" name="password" class="form-control">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>



<?php
$content = ob_get_clean();

include __DIR__ . '/../layout/main.php';
