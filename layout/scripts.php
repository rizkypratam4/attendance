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
    $(function () {
        $('#detailHadirTable').DataTable();
    });
</script>

<script>
    $(function () {
        $('#detailTerlambatTable').DataTable();
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