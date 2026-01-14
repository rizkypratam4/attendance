<?php
include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
ob_start();
?>

<div class="row">
    <div class="col-12">
        <h2 class="fw-bold mb-1">Dashboard</h2>
        <p class="text-muted mb-0">
            Selamat datang, <?= $_SESSION['name']; ?>
        </p>
    </div>
</div>

<div class="row g-3">
    <div class="col-xl-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Total Karyawan</h6>
                <h3 class="fw-bold">125</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Hadir Hari Ini</h6>
                <h3 class="fw-bold text-primary">110</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Hadir Minggu Ini</h6>
                <h3 class="fw-bold text-primary">110</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Hadir Bulan Ini</h6>
                <h3 class="fw-bold text-primary">110</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Terlambat</h6>
                <h3 class="fw-bold text-warning">8</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted">Tidak Hadir</h6>
                <h3 class="fw-bold text-danger">7</h3>
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
    <div class="col-lg-7">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title">Departemen Paling Banyak Terlambat</h5>
                <div class="chart-box">
                    <canvas id="deptLateChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
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
