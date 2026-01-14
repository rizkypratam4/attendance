<?php
include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
require __DIR__ . "../../function.php";
ob_start();

?>
<div class="col-lg-12">

    <?php if (isset($_SESSION['alert'])): ?>
        <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
            <?= $_SESSION['alert']['msg'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php unset($_SESSION['alert']); endif; ?>

    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="card-title">
                        Employees
                    </h4>

                    <p class="card-subtitle">
                        Ringkasan data karyawan
                    </p>
                </div>

                <button data-bs-toggle="modal"
                    data-bs-target="#importAttendanceModal" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="ti ti-file-import fs-5"></i>
                    <span>Import</span>
                </button>
            </div>

            <table id="employeeTable" class="table table-bordered  py-3">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">No</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Branch</th>
                        <th scope="col">Jabatan</th>
                        <th scope="col">Status</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="employeeTableBody">
                    <?php
                    $employees = getEmployees($conn);
                    foreach ($employees as $index => $employee): ?>
                        <tr>
                            <td class="text-center"><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($employee['name']) ?></td>
                            <td><?= htmlspecialchars($employee['branch']) ?></td>
                            <td><?= htmlspecialchars($employee['position']) ?></td>
                            <td><?= htmlspecialchars($employee['employee_status']) ?></td>
                            <td class="col-md-2 text-center">
                                <button
                                    class="btn btn-sm btn-info btn-edit-employee"
                                    
                                    data-id="<?= $employee['id']; ?>"
                                    data-name="<?= htmlspecialchars($employee['name']); ?>"
                                    title="Edit Employee"
                                    >
                                        <i class="ti ti-pencil"></i>
                                        Edit
                                </button>

                                <button
                                        class="btn btn-sm btn-danger btn-delete-employee ms-1"
                                        data-id="<?= $employee['id']; ?>"    
                                        data-name="<?= $employee['name']; ?>"    
                                        title="Hapus Employee"
                                    >
                                        <i class="ti ti-trash"></i>
                                Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
</div>



<?php include __DIR__ . '/import.php'; ?>
<?php include __DIR__ . '/edit.php'; ?>


<?php
$content = ob_get_clean();

include __DIR__ . '/../layout/main.php';
