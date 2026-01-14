<script src="<?= $BASE_URL ?>assets/libs/jquery/dist/jquery.min.js"></script>
<script src="<?= $BASE_URL ?>assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="<?= $BASE_URL ?>assets/js/sidebarmenu.js"></script>
<script src="<?= $BASE_URL ?>assets/js/app.min.js"></script>
<script src="<?= $BASE_URL ?>assets/libs/simplebar/dist/simplebar.js"></script>

<!-- MAIN.JS TARUH DI SINI -->
<script src="<?= $BASE_URL ?>assets/js/main.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= $BASE_URL ?>assets/js/dashboard.js"></script>
<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>


  <script>
    $(function () {
        $('#employeeTable').DataTable();
    });
  </script>

  <script>
    $(function () {
        $('#attendanceTable').DataTable();
    });
  </script>

  <script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('todayChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Terlambat', 'Tidak Hadir'],
            datasets: [{
                data: [110, 8, 7],
                backgroundColor: [
                    '#1e4db7',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const trendCtx = document.getElementById('trendChart').getContext('2d');

    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Hadir',
                data: [20, 40, 60, 50, 108, 110, 112],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

});
</script>

<script>
  const deptCtx = document.getElementById('deptLateChart').getContext('2d');

new Chart(deptCtx, {
    type: 'bar',
    data: {
        labels: ['Produksi', 'Gudang', 'HR', 'IT', 'Finance'],
        datasets: [{
            label: 'Jumlah Keterlambatan',
            data: [42, 31, 18, 9, 6],
            backgroundColor: '#dc3545',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 5 }
            }
        }
    }
});

</script>



  <?php if (isset($_SESSION['swal']['icon'])): ?>
    <script>
      Swal.fire({
        title: '<?= $_SESSION['swal']['title'] ?>',
        text: '<?= $_SESSION['swal']['text'] ?>',
        icon: '<?= $_SESSION['swal']['icon'] ?>',
      });
    </script>
  <?php unset($_SESSION['swal']); endif;?>