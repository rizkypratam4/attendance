<?php
include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
require __DIR__ . "/../config/database.php";
require __DIR__ . "../../function.php";

$startDate = $_GET['start_date'] ?? null;
$endDate   = $_GET['end_date'] ?? null;

$totalTerlambat = getTotalTerlambatHariIni();

if ($startDate && $endDate) {
    $totalTerlambat = getTotalTerlambatByDateRange($startDate, $endDate);
}

$totalHadir = getTotalHadirHariIni();

if ($startDate && $endDate) {
    $totalHadir = getTotalHadirByDateRange($startDate, $endDate);
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

        <!-- Date Range -->
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
    <!-- Total Karyawan -->
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

    <!-- Hadir -->
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

    <!-- Terlambat -->
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

    <!-- Tidak Hadir -->
    <div class="col-xl-3 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column">
                <h6 class="text-muted mb-2 d-flex align-items-center gap-2">
                    <i class="ti ti-user-off fs-4 text-danger"></i>
                    Tidak Hadir
                </h6>

               <div class="d-flex align-items-center justify-content-between mt-auto">
                    <h3 class="fw-bold mb-0">
                    
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
                <ul class="list-group list-group-flush mt-3">
                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <strong>Andi Pratama</strong><br>
                            <small class="text-muted">Produksi</small>
                        </div>
                        <span class="badge bg-danger d-flex align-items-center">15x</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <strong>Budi Santoso</strong><br>
                            <small class="text-muted">Gudang</small>
                        </div>
                        <span class="badge bg-warning d-flex align-items-center">11x</span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between">
                        <div>
                            <strong>Siti Rahma</strong><br>
                            <small class="text-muted">HR</small>
                        </div>
                        <span class="badge bg-warning d-flex align-items-center">9x</span>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
