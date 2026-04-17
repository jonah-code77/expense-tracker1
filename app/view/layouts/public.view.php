<?php use App\Core\View; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php View::yield('title'); ?> — ExpenseTracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">

    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">💰</div>
        <span class="sidebar-brand-name">ExpenseTracker</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Main</div>
        <a href="<?= BASE_URL ?>/home"
           class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], '/home') ? 'active' : '' ?>">
            <i class="bi bi-grid"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/transaction/dashboard"
           class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], 'transaction') ? 'active' : '' ?>">
            <i class="bi bi-arrow-left-right"></i> Transactions
        </a>
        <a href="<?= BASE_URL ?>/category/dashboard"
           class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], 'category') ? 'active' : '' ?>">
            <i class="bi bi-tag"></i> Categories
        </a>
        <a href="<?= BASE_URL ?>/transaction/report"
           class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], 'report') ? 'active' : '' ?>">
            <i class="bi bi-bar-chart"></i> Reports
        </a>

        <div class="nav-label mt-3">Account</div>
        <a href="<?= BASE_URL ?>/profile"
           class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], 'profile') ? 'active' : '' ?>">
            <i class="bi bi-person"></i> Profile
        </a>
        <a href="<?= BASE_URL ?>/settings"
           class="sidebar-link <?= str_contains($_SERVER['REQUEST_URI'], 'settings') ? 'active' : '' ?>">
            <i class="bi bi-gear"></i> Settings
        </a>
    </nav>

    <!-- USER CARD -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">ET</div>
        <div>
            <div class="sidebar-user-name">Expense Tracker</div>
            <div class="sidebar-user-role">Personal Account</div>
        </div>
    </div>

    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>/logout.php" class="logout-btn">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

</div>

<!-- MAIN WRAPPER -->
<div class="main-wrap">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button id="sidebarToggle" onclick="openSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <?php View::yield('topbar-title'); ?>
        </div>
        <div class="topbar-right">
            <?php View::yield('topbar-action'); ?>
        </div>
    </div>

    <!-- PAGE CONTENT -->
    <div class="page-content">
        <?php View::yield('content'); ?>
    </div>

</div><!-- /main-wrap -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebarOverlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }
</script>
<?php View::yield('scripts'); ?>

</body>
</html>