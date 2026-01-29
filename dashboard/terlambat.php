<?php

include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
$USE_LAYOUT = true;
require __DIR__ . "/../config/database.php";
require __DIR__ . "/../function.php";

ob_start();

$location   = $_GET['location'] ?? null;
$shift      = $_GET['shift'] ?? null;
$startDate  = $_GET['start_date'] ?? null;
$endDate    = $_GET['end_date'] ?? null;

$location  = $location ?: null;
$shift     = $shift ?: null;
$startDate = $startDate ?: null;
$endDate   = $endDate ?: null;

if ($location === 'CKG' && $shift !== 'Shift 1' && !empty($shift)) {
    $shift = null;
}

$karyawanTerlambat = getKaryawanTerlambat($startDate, $endDate, $location, $shift);
ob_start();
?>

<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Karyawan Terlambat</h2>
            <p class="text-muted mb-0">
                Daftar karyawan yang terlambat
                <?php if ($startDate && $endDate): ?>
                    periode <?= date('d/m/Y', strtotime($startDate)) ?> - <?= date('d/m/Y', strtotime($endDate)) ?>
                <?php elseif ($startDate): ?>
                    tanggal <?= date('d/m/Y', strtotime($startDate)) ?>
                <?php else: ?>
                    hari ini
                <?php endif; ?>
            </p>
        </div>
        <a href="index.php?location=<?= $location ?>&shift=<?= $shift ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" 
           class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<?php if ($location || $shift || $startDate || $endDate): ?>
<div class="row mb-3">
    <div class="col-12">
        <div class="alert alert-info">
            <strong><i class="ti ti-info-circle"></i> Filter Aktif:</strong>

            <?php if ($location): ?>
                Lokasi: <strong><?= htmlspecialchars($location) ?></strong>
            <?php endif; ?>

            <?php if ($shift): ?>
                <?php
                $startDateStr = $startDate instanceof DateTime
                    ? $startDate->format('Y-m-d')
                    : $startDate;
                ?>
                | Shift: <strong><?= htmlspecialchars($shift) ?></strong>
                (Jam Masuk: <strong><?= substr(getJamMasuk($location, $shift, $startDateStr), 0, 5) ?></strong>)
            <?php endif; ?>

            <?php if ($startDate && $endDate): ?>
                | Periode:
                <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong> -
                <strong><?= date('d/m/Y', strtotime($endDate)) ?></strong>
            <?php elseif ($startDate): ?>
                | Tanggal: <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="detailTerlambatTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Karyawan</th>
                                <th>Departemen</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Terlambat</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($karyawanTerlambat)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">
                                    Tidak ada data karyawan terlambat
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php
                            $no = 1;
                            foreach ($karyawanTerlambat as $karyawan):
                                $tanggal = $karyawan['AttendanceDate'] instanceof DateTime
                                    ? $karyawan['AttendanceDate']->format('Y-m-d')
                                    : $karyawan['AttendanceDate'];

                                $jamMasuk = getJamMasuk($location, $shift, $tanggal);
                                $timeIn = $karyawan['TimeIn'] instanceof DateTime
                                    ? $karyawan['TimeIn']->format('H:i:s')
                                    : $karyawan['TimeIn'];

                                $jamMasukTime  = strtotime($tanggal . ' ' . $jamMasuk);
                                $jamDatangTime = strtotime($tanggal . ' ' . $timeIn);

                                $selisihMenit = max(0, round(($jamDatangTime - $jamMasukTime) / 60));
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($karyawan['barcode']) ?></td>
                                <td><?= htmlspecialchars($karyawan['employee_name'] ?? 'TIDAK TERDAFTAR') ?></td>
                                <td><?= htmlspecialchars($karyawan['departement'] ?? '-') ?></td>
                                <td><?= date('d/m/Y', strtotime($tanggal)) ?></td>
                                <td>
                                    <span class="text-danger fw-bold"><?= $timeIn ?></span><br>
                                    <small class="text-muted">
                                        Seharusnya: <?= substr($jamMasuk, 0, 5) ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <?= $selisihMenit ?> menit
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
?>