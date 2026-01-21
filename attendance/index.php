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
    <?php unset($_SESSION['alert']);
    endif; ?>

    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="card-title"> 
                        Attendances
                    </h4>
                    <p class="card-subtitle">
                        Ringkasan data kehadiran karyawan
                    </p>
                </div>

                <!-- <button data-bs-toggle="modal"
                    data-bs-target="#importAttendanceModal" class="btn btn-primary d-flex align-items-center gap-1">
                    <i class="ti ti-file-import fs-5"></i>
                    <span>Import</span>
                </button> -->
            </div>

            <table id="attendanceTable" class="table table-bordered table-striped mt-4 py-3">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th>Barcode</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Attendance Date</th>
                        <th scope="col">Attendance Time</th>
                        <th scope="col">Attendance Type</th>
                        <!-- <th scope="col">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $attendances = getAttendances();
                    foreach ($attendances as $index => $attendance): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($attendance['barcode']) ?></td>
                            <td><?= htmlspecialchars($attendance['employee_name']) ?></td>
                            <td><?= htmlspecialchars($attendance['AttendanceDate']->format('d-m-Y')) ?></td>
                            <td><?= htmlspecialchars($attendance['AttendanceTime']->format('H:i:s')) ?></td>
                            <td><?= htmlspecialchars($attendance['AttendanceType'])?></td>
                            <!-- <td>
                                <a href="edit.php?id=<?= $attendance['id'] ?>" class="btn btn-sm btn-info">
                                    <i class="ti ti-pencil"></i>
                                    Edit
                                </a>
                                <a href="delete.php?id=<?= $attendance['id'] ?>" class="btn btn-sm btn-danger">
                                    <i class="ti ti-trash"></i>
                                    Delete
                                </a>
                            </td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();

include __DIR__ . '/../layout/main.php';
include __DIR__ . '/import.php';
