<?php
include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
require __DIR__ . "/../config/database.php";
require __DIR__ . "../../function.php";

$startDate = $_GET['start_date'] ?? null;
$endDate   = $_GET['end_date'] ?? null;

if (empty($startDate) && empty($endDate)) {
    $startDate = date('Y-m-d');
    $endDate   = date('Y-m-d');
}

$totalHadir = getTotalHadirByDateRange($startDate, $endDate);
$totalTerlambat = getTotalTerlambatByDateRange($startDate, $endDate);
$totalTidakHadir = getTotalTidakHadirByDateRange($startDate, $endDate);

$trendKehadiran = getTrendKehadiran7Hari();

if ($_GET['start_date'] ?? null) {
    $karyawanTerlambat = getKaryawanPalingBanyakTerlambat(5, $startDate, $endDate);
    $periodeLabel = date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));
} else {
    $karyawanTerlambat = getKaryawanPalingBanyakTerlambat(6);
    $periodeLabel = 'Tahun 2026';
}

ob_start();

?>

<div class="row">
    <div class="col-12 d-flex justify-content-between align-items-end flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">Dashboard</h2>
            <p class="text-muted mb-0">
                Selamat datang, <?= $_SESSION['name']; ?>
            </p>
        </div>

        <form method="GET" class="d-flex align-items-end gap-2">
            <div>
                <label class="form-label mb-0">Dari</label>
                <input
                    type="date"
                    name="start_date"
                    class="form-control">
            </div>

            <div>
                <label class="form-label mb-0">Sampai</label>
                <input
                    type="date"
                    name="end_date"
                    class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">
                Filter
            </button>
        </form>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h6 class="text-muted mb-2 d-flex align-items-center gap-2">
                    <i class="ti ti-users fs-4"></i>
                    Total Karyawan
                </h6>

                <div class="d-flex align-items-center justify-content-between mt-auto">
                    <h3 class="fw-bold mb-0">
                        <?= countEmployees(); ?>
                    </h3>

                    <a href="employees.php" class="text-muted mt-2" title="Lihat Detail">
                        <i class="ti ti-arrow-right fs-7"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h6 class="text-muted mb-2 d-flex align-items-center gap-2">
                    <i class="ti ti-circle-check fs-4 text-primary"></i>
                    Hadir
                </h6>

                <div class="d-flex align-items-center justify-content-between mt-auto">
                    <h3 class="fw-bold mb-0">
                        <?= $totalHadir; ?>
                    </h3>

                    <a href="employees.php" class="text-muted mt-2" title="Lihat Detail">
                        <i class="ti ti-arrow-right fs-7"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h6 class="text-muted mb-2 d-flex align-items-center gap-2">
                    <i class="ti ti-clock-hour-9 fs-4 text-warning"></i>
                    Terlambat
                </h6>

                <div class="d-flex align-items-center justify-content-between mt-auto">
                    <h3 class="fw-bold mb-0">
                        <?= $totalTerlambat ?>
                    </h3>

                    <a href="employees.php" class="text-muted mt-2" title="Lihat Detail">
                        <i class="ti ti-arrow-right fs-7"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h6 class="text-muted mb-2 d-flex align-items-center gap-2">
                    <i class="ti ti-user-off fs-4 text-danger"></i>
                    Tidak Hadir
                </h6>

                <div class="d-flex align-items-center justify-content-between mt-auto">
                    <h3 class="fw-bold mb-0">
                        <?= $totalTidakHadir ?>
                    </h3>

                    <a href="employees.php" class="text-muted mt-2" title="Lihat Detail">
                        <i class="ti ti-arrow-right fs-7"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Kehadiran Hari Ini</h5>
                <div class="chart-box">
                    <canvas id="todayChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Trend Kehadiran (7 Hari)</h5>
                <div class="chart-box">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Departemen Paling Banyak Terlambat</h5>
                <div class="chart-box">
                    <canvas id="deptLateChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Karyawan Paling Banyak Terlambat</h5>
                <p class="text-muted small mb-3">Data tahun 2026</p>
                <ul class="list-group list-group-flush mt-3">
                    <?php if (empty($karyawanTerlambat)) : ?>
                        <li class="list-group-item text-muted text-center">
                            Tidak ada data keterlambatan
                        </li>
                    <?php else : ?>
                        <?php foreach ($karyawanTerlambat as $kt) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?= htmlspecialchars($kt['employee_name']) ?></strong><br>
                                    <small class="text-muted">
                                        <?= htmlspecialchars($kt['departement'] ?? '-') ?>
                                    </small>
                                </div>
                                <span class="badge <?= (int)$kt['total_terlambat'] >= 10 ? 'bg-danger' : 'bg-warning' ?>">
                                    <?= (int)$kt['total_terlambat'] ?>x
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>




<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
