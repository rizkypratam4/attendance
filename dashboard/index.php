<?php
include __DIR__ . "/../auth/auth.php";
require_once __DIR__ . '/../layout/a_config.php';
ob_start();
?>
    <h1>Dashboard</h1>
    <p>Selamat datang, <?= $_SESSION['username']; ?></p>
<?php
$content = ob_get_clean();

include __DIR__ . '/../layout/main.php';
