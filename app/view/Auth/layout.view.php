
<?php use App\Core\View; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php View::yield('title'); ?> — ExpenseTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <!-- LEFT PANEL -->
        <div class="col-lg-7 d-none d-lg-flex left-panel">
            <div class="left-inner d-flex flex-column align-items-center justify-content-center w-100 px-5 py-5">

                <!-- Brand -->
                <div class="d-flex align-items-center gap-2 mb-5 align-self-start">
                    <div class="brand-icon"><i class="bi bi-currency-dollar"></i></div>
                    <span style="font-size:20px;font-weight:600;color:white">ExpenseTracker</span>
                </div>

                <!-- Left panel content (differs per page) -->
                <?php View::yield('left-panel'); ?>

            </div>
        </div>

        <!-- RIGHT PANEL -->
        <div class="col-lg-5 right-panel">
            <div class="form-card">

                <!-- Logo -->
                <div class="d-flex align-items-center gap-2 mb-4">
                    <div class="logo-sm-icon"><i class="bi bi-currency-dollar"></i></div>
                    <span style="font-size:15px;font-weight:600;color:#1a1f2e">ExpenseTracker</span>
                </div>

                <!-- Page heading -->
                <?php View::yield('heading'); ?>

                <!-- Error message -->
                <div id="msg"></div>

                <!-- Form content -->
                <?php View::yield('form'); ?>

                <!-- Footer link -->
                <?php View::yield('footer-link'); ?>

            </div>
        </div>

    </div>
</div>
<script src="<?= BASE_URL ?>/assets/js/form.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php View::yield('scripts'); ?>
</body>
</html>