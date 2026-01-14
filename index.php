<?php
session_start();
require_once __DIR__ . '/layout/a_config.php';
ob_start();
?>
 <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="./assets/images/logos/logo.png" alt="logo" width="180">
                </a>
                <p class="text-center">Manajemen Kehadiran Karyawan</p>
                <?php
                  if (isset($_SESSION['error'])) {
                      echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
                      unset($_SESSION['error']);
                  }
                ?>
                <form action="auth/login_process.php" method="POST">
                  <div class="mb-3">
                    <label for="exampleInputUsername1" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="exampleInputUsername1" aria-describedby="usernameHelp">
                  </div>
                  <div class="mb-4">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="exampleInputPassword1">
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remeber this Device
                      </label>
                    </div>
                  </div>
                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Sign In</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php
$content = ob_get_clean();

include 'layout/main.php';
