<?php
include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
require __DIR__ . "/../config/database.php";
require __DIR__ . "../../function.php";

$location = $_GET['location'] ?? null;
$shift = $_GET['shift'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

if (empty($location)) $location = null;
if (empty($shift)) $shift = null;
if (empty($startDate)) $startDate = null;
if (empty($endDate)) $endDate = null;

// Validasi shift berdasarkan lokasi
if ($location === 'CKG' && $shift !== 'Shift 1' && !empty($shift)) {
    $shift = null;
}

$totalKaryawan = countEmployees($startDate, $endDate, $location);
$totalHadir = getTotalHadirByDateRange($startDate, $endDate, $location, $shift);
$totalTerlambat = getTotalTerlambatByDateRange($startDate, $endDate, null, $location, $shift);
$totalTidakHadir = getTotalTidakHadirByDateRange($startDate, $endDate, $location, $shift);
$totalEmployees = countEmployees($startDate, $endDate, $location, $shift);
$topTerlambat = getKaryawanPalingBanyakTerlambat(5, $startDate, $endDate, null, $location, $shift);
$deptTerlambat = getDepartemenPalingBanyakTerlambat(10, $startDate, $endDate, null, $location, $shift);

$deptLabels = [];
$deptData = [];
foreach ($deptTerlambat as $dept) {
    $deptLabels[] = $dept['departement'];
    $deptData[] = (int)$dept['total_keterlambatan'];
}

$jamMasukYangDigunakan = getJamMasuk($location, $shift, $startDate);

$queryString = http_build_query([
    'location' => $location,
    'shift' => $shift,
    'start_date' => $startDate,
    'end_date' => $endDate
]);

ob_start();
?>
<script>
    const deptChartData = {
        labels: <?= json_encode($deptLabels) ?>,
        data: <?= json_encode($deptData) ?>
    };
</script>

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
                <label class="form-label mb-0">Lokasi</label>
                <select class="form-select" name="location" id="locationSelect" aria-label="Select location">
                    <option value="">Semua Lokasi</option>
                    <option value="CKG" <?= ($location === 'CKG') ? 'selected' : '' ?>>CKG</option>
                    <option value="KIP" <?= ($location === 'KIP') ? 'selected' : '' ?>>KIP</option>
                </select>
            </div>

            <div>
                <label class="form-label mb-0">Shift</label>
                <select class="form-select" name="shift" id="shiftSelect" aria-label="Select shift">
                    <option value="">Semua Shift</option>
                    <option value="Shift 1" <?= ($shift === 'Shift 1') ? 'selected' : '' ?>>Shift 1</option>
                    <option value="Shift 2" <?= ($shift === 'Shift 2') ? 'selected' : '' ?> class="kip-only">Shift 2</option>
                    <option value="Shift 3" <?= ($shift === 'Shift 3') ? 'selected' : '' ?> class="kip-only">Shift 3</option>
                </select>
            </div>

            <div>
                <label class="form-label mb-0">Dari</label>
                <input
                    type="date"
                    name="start_date"
                    class="form-control"
                    value="<?= htmlspecialchars($startDate ?? '') ?>">
            </div>

            <div>
                <label class="form-label mb-0">Sampai</label>
                <input
                    type="date"
                    name="end_date"
                    class="form-control"
                    value="<?= htmlspecialchars($endDate ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="ti ti-filter"></i> Filter
            </button>

            <?php if ($startDate || $endDate || $location || $shift): ?>
                <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary">
                    <i class="ti ti-x"></i> Reset
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Informasi Filter Aktif -->
<?php if ($location || $shift || $startDate || $endDate): ?>
<div class="row mt-3">
    <div class="col-12">
        <div class="alert alert-info">
            <strong><i class="ti ti-info-circle"></i> Filter Aktif:</strong>
            <?php if ($location): ?>
                Lokasi: <strong><?= htmlspecialchars($location) ?></strong>
            <?php endif; ?>
            <?php if ($shift): ?>
                | Shift: <strong><?= htmlspecialchars($shift) ?></strong>
                (Jam Masuk: <strong><?= substr($jamMasukYangDigunakan, 0, 5) ?></strong>)
            <?php endif; ?>
            <?php if ($startDate && $endDate): ?>
                | Periode: <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong> - <strong><?= date('d/m/Y', strtotime($endDate)) ?></strong>
            <?php elseif ($startDate): ?>
                | Tanggal: <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row g-3 mt-2">
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
                        <?= $totalKaryawan ?>
                    </h3>

                    <a href="employees?<?= $queryString ?>" class="text-muted mt-2" title="Lihat Detail">
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
                        <?= $totalHadir ?>
                    </h3>

                    <a href="hadir?<?= $queryString ?>" class="text-muted mt-2" title="Lihat Detail">
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

                    <a href="terlambat?<?= $queryString ?>" class="text-muted mt-2" title="Lihat Detail">
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
                        <?= $totalTidakHadir ?>
                    </h3>

                    <a href="tidak_hadir?<?= $queryString ?>"
                        class="text-muted mt-2"
                        title="Lihat Detail">
                        <i class="ti ti-arrow-right fs-7"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-3 mt-2">
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

<div class="row g-3 mt-2">
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
                    <?php if (empty($topTerlambat)) : ?>
                        <li class="list-group-item text-muted text-center">
                            Tidak ada data keterlambatan
                        </li>
                    <?php else : ?>
                        <?php foreach ($topTerlambat as $kt) : ?>
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

<!-- Pass data ke JavaScript -->
<script>
    window.dashboardData = {
        trend: <?= json_encode($trendKehadiran ?? []) ?>,
        today: {
            hadir: <?= $totalHadir ?>,
            terlambat: <?= $totalTerlambat ?>,
            tidakHadir: <?= $totalTidakHadir ?>
        }
    };
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const locationSelect = document.getElementById('locationSelect');
    const shiftSelect = document.getElementById('shiftSelect');
    
    function updateShiftOptions() {
        const selectedLocation = locationSelect.value;
        const kipOnlyOptions = shiftSelect.querySelectorAll('.kip-only');
        
        if (selectedLocation === 'CKG') {
            kipOnlyOptions.forEach(option => {
                option.disabled = true;
                option.style.display = 'none';  
            });
            
            if (shiftSelect.value === 'Shift 2' || shiftSelect.value === 'Shift 3') {
                shiftSelect.value = '';
            }
        } else {
            kipOnlyOptions.forEach(option => {
                option.disabled = false;
                option.style.display = '';
            });
        }
    }
    
    updateShiftOptions();
    
    locationSelect.addEventListener('change', updateShiftOptions);
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout/main.php';
?>