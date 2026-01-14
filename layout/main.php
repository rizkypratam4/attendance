<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $PAGE_TITLE ?? 'HRIS' ?></title>
  <link rel="shortcut icon" type="image/png" href="<?= $BASE_URL ?>assets/images/logos/favicon_attendfy.png" />
  <link rel="stylesheet" href="<?= $BASE_URL ?>assets/css/styles.min.css" />
  <link href="<?= $BASE_URL ?>assets/libs/datatables/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full">
    <?php if (isset($USE_LAYOUT) && $USE_LAYOUT): ?>
        <!-- Sidebar Start -->
        <?php include 'sidebar.php' ?>
        <!-- Sidebar End -->
        <!-- Main wrapper -->
        <div class="body-wrapper">
            <!-- Header Start -->
            <?php include 'header.php' ?>
            <!-- Header End -->
            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    <div class="row">
                        <?= $content ?>
                    </div>
                </div>
            </div>

            <?php include 'footer.php' ?>
        </div>
    <?php else: ?>
        <?= $content ?>
    <?php endif; ?>

    <?php include 'scripts.php' ?>

</body>

</html>