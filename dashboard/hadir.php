<?php
include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
$USE_LAYOUT = true;
require __DIR__ . "/../config/database.php";
require __DIR__ . "/../function.php";

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

$karyawanHadir = getKaryawanHadir($startDate, $endDate, $location, $shift);

ob_start();
?>

<!-- HEADER -->
<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-1">Karyawan Hadir</h2>
            <p class="text-muted mb-0">
                Daftar karyawan yang hadir
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

<!-- FILTER INFO -->
<?php if ($location || $shift || $startDate || $endDate): ?>
    <?php
    $dateString = $startDate instanceof DateTime
        ? $startDate->format('Y-m-d')
        : $startDate;
    ?>
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info">
                <strong><i class="ti ti-info-circle"></i> Filter Aktif:</strong>

                <?php if ($location): ?>
                    Lokasi: <strong><?= htmlspecialchars($location) ?></strong>
                <?php endif; ?>

                <?php if ($shift): ?>
                    | Shift: <strong><?= htmlspecialchars($shift) ?></strong>
                    (Jam Masuk:
                    <strong><?= substr(getJamMasuk($location, $shift, $dateString), 0, 5) ?></strong>)
                <?php endif; ?>

                <?php if ($startDate && $endDate): ?>
                    | Periode:
                    <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong> -
                    <strong><?= date('d/m/Y', strtotime($endDate)) ?></strong>
                <?php elseif ($startDate): ?>
                    | Tanggal:
                    <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- TABLE -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="detailHadirTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Karyawan</th>
                                <th>Departemen</th>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($karyawanHadir)): ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        Tidak ada data karyawan hadir
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php
                                $no = 1;
                                foreach ($karyawanHadir as $karyawan):
                                    $attendanceDate = $karyawan['AttendanceDate'] instanceof DateTime
                                        ? $karyawan['AttendanceDate']->format('Y-m-d')
                                        : $karyawan['AttendanceDate'];

                                    $jamMasuk = getJamMasuk($location, $shift, $attendanceDate);

                                    $timeIn = strtotime(
                                        $karyawan['TimeIn'] instanceof DateTime
                                            ? $karyawan['TimeIn']->format('Y-m-d H:i:s')
                                            : $karyawan['TimeIn']
                                    );

                                    $timeOut = $karyawan['TimeOut']
                                        ? strtotime(
                                            $karyawan['TimeOut'] instanceof DateTime
                                                ? $karyawan['TimeOut']->format('Y-m-d H:i:s')
                                                : $karyawan['TimeOut']
                                        )
                                        : null;

                                    $jadwalMasuk = strtotime($attendanceDate . ' ' . $jamMasuk);
                                    $isTerlambat = $timeIn > $jadwalMasuk;
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($karyawan['barcode']) ?></td>
                                        <td><?= htmlspecialchars($karyawan['employee_name'] ?? 'TIDAK TERDAFTAR') ?></td>
                                        <td><?= htmlspecialchars($karyawan['departement'] ?? '-') ?></td>
                                        <td><?= date('d/m/Y', strtotime($attendanceDate)) ?></td>
                                        <td>
                                            <?= date('H:i:s', $timeIn) ?>
                                            <?php if ($isTerlambat): ?>
                                                <span class="badge bg-warning text-dark ms-1">Terlambat</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                        <?= empty($karyawan['TimeOut'])
                                            ? '-'
                                            : ($karyawan['TimeOut'] instanceof DateTime
                                                ? $karyawan['TimeOut']->format('H:i:s')
                                                : date('H:i:s', strtotime($karyawan['TimeOut']))
                                            )
                                        ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Hadir</span>
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

<!-- DATATABLE -->
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            pageLength: 25,
            order: [
                [4, "desc"],
                [5, "asc"]
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Data tidak ditemukan",
                info: "Halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
