<?php

use App\Core\Session;
use App\Core\View; ?>

<?php View::section('title'); ?>
Dashboard
<?php View::endSection(); ?>

<?php View::section('topbar-title'); ?>
<div class="topbar-left">
    <p>Welcome back, <?= Session::getSession('name') ?> — here's your financial overview</p>
</div>
<?php View::endSection(); ?>

<?php View::section('topbar-action'); ?>
<div class="notif-btn" title="Notifications">
    <i class="bi bi-bell"></i>
    <span class="notif-dot"></span>
</div>
<a href="<?= BASE_URL ?>/transaction/dashboard" class="btn-main">
    <i class="bi bi-plus-lg"></i> Add Transaction
</a>
<?php View::endSection(); ?>

<?php View::section('content'); ?>

<!-- ── STAT CARDS ── -->
<div class="dash-stats">

    <div class="dash-stat-card">
        <div class="dash-stat-top">
            <span class="dash-stat-label">Total Income</span>
            <div class="dash-stat-ico green"><i class="bi bi-arrow-up-circle-fill"></i></div>
        </div>
        <div class="dash-stat-value" style="color:var(--green2)">
            ₦<?= number_format($summary['income'], 2) ?>
        </div>
        <div class="dash-stat-change up">
            <i class="bi bi-triangle-fill" style="font-size:8px"></i>
            <?= $summary['income_change'] ?>% <span style="color:var(--muted2)">vs last month</span>
        </div>
    </div>

    <div class="dash-stat-card">
        <div class="dash-stat-top">
            <span class="dash-stat-label">Total Expenses</span>
            <div class="dash-stat-ico red"><i class="bi bi-arrow-down-circle-fill"></i></div>
        </div>
        <div class="dash-stat-value" style="color:var(--red2)">
            ₦<?= number_format($summary['expense'] ?? 2) ?>
        </div>
        <div class="dash-stat-change down">
            <i class="bi bi-triangle-fill" style="font-size:8px"></i>
            <?= $summary['expense_change'] ?>% <span style="color:var(--muted2)">vs last month</span>
        </div>
    </div>
    

    <div class="dash-stat-card">
        <div class="dash-stat-top">
            <span class="dash-stat-label">Monthly Savings</span>
            <div class="dash-stat-ico blue"><i class="bi bi-diamond-fill"></i></div>
        </div>
        <div class="dash-stat-value">
            ₦<?= number_format(max(0, ($monthlySummary['total_income'] ?? 0) - ($monthlySummary['total_expenses'] ?? 0)), 2) ?>
        </div>
        <div class="dash-stat-change up">
            <i class="bi bi-triangle-fill" style="font-size:8px"></i>
            3.1% <span style="color:var(--muted2)">vs last month</span>
        </div>
    </div>

    <div class="dash-stat-card">
        <div class="dash-stat-top">
            <span class="dash-stat-label">Active Budgets</span>
            <div class="dash-stat-ico yellow"><i class="bi bi-circle-fill"></i></div>
        </div>
        <div class="dash-stat-value"><?= count($budgets ?? []) ?: 4 ?></div>
        <div class="dash-stat-change">
            <i class="bi bi-circle-fill" style="font-size:5px;color:var(--green2)"></i>
            <span style="color:var(--muted2)">2 on track, 1 at limit</span>
        </div>
    </div>

</div>

<!-- ── MAIN GRID ── -->
<div class="dash-grid">

    <!-- LEFT COLUMN -->
    <div style="display:flex;flex-direction:column;gap:18px;">

        <!-- BUDGET TRACKER -->
        <div class="content-card" style="animation:fadeUp .45s ease .25s both">
            <div class="content-card-head">
                <h6>
                    <i class="bi bi-circle-half" style="color:var(--accent)"></i>
                    Budget Tracker
                </h6>
                <a href="<?= BASE_URL ?>/budget/dashboard" class="btn-manage">
                    Manage <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="content-card-body" style="display:flex;flex-direction:column;gap:18px;">
                <?php
                $budgetRows = !empty($budgets) ? $budgets : [
                    ['name' => 'Food & Groceries', 'spent' => 32000, 'limit' => 50000],
                    ['name' => 'Rent',             'spent' => 90000, 'limit' => 90000],
                    ['name' => 'Transport',        'spent' => 8500,  'limit' => 15000],
                    ['name' => 'Entertainment',    'spent' => 4000,  'limit' => 20000],
                ];
                foreach ($budgetRows as $b):
                    $pct   = $b['limit'] > 0 ? min(100, round($b['spent'] / $b['limit'] * 100)) : 0;
                    $color = $pct >= 90 ? '#fb7185' : ($pct >= 65 ? '#fcd34d' : '#34d399');
                ?>
                <div>
                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                        <span class="budget-name"><?= htmlspecialchars($b['name']) ?></span>
                        <span class="budget-amounts">
                            <strong>₦<?= number_format($b['spent']) ?></strong>
                            / ₦<?= number_format($b['limit']) ?>
                        </span>
                    </div>
                    <div class="budget-bar-track">
                        <div class="budget-bar-fill" style="width:<?= $pct ?>%;background:<?= $color ?>"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-1">
                        <span style="font-size:11px;color:var(--muted2)"><?= $pct ?>% used</span>
                        <span style="font-size:11px;color:<?= $color ?>">
                            ₦<?= number_format(max(0, $b['limit'] - $b['spent'])) ?> left
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- RECENT ACTIVITIES -->
        <div class="content-card" style="animation:fadeUp .45s ease .35s both">
            <div class="content-card-head">
                <h6>
                    <i class="bi bi-clock-history" style="color:var(--accent)"></i>
                    Recent Activities
                </h6>
                <a href="<?= BASE_URL ?>/transaction/dashboard" class="btn-viewall">
                    All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <?php
            $demoTx = [
                ['name'=>'Shoprite Groceries','cat'=>'Food & Groceries','type'=>'expense','amount'=>12400,'date'=>'Today, 2:14 PM',   'ico'=>'bi-basket',    'ibg'=>'rgba(249,115,22,.12)', 'ic'=>'#fb923c'],
                ['name'=>'Salary Credit',     'cat'=>'Income',          'type'=>'income', 'amount'=>80000,'date'=>'Jun 1, 9:00 AM',  'ico'=>'bi-briefcase', 'ibg'=>'rgba(16,185,129,.12)','ic'=>'var(--green2)'],
                ['name'=>'Uber Ride',          'cat'=>'Transport',       'type'=>'expense','amount'=>2100, 'date'=>'May 31, 7:45 PM', 'ico'=>'bi-car-front', 'ibg'=>'rgba(245,158,11,.1)', 'ic'=>'var(--yellow2)'],
                ['name'=>'EKEDC Light Bill',   'cat'=>'Utilities',       'type'=>'expense','amount'=>8000, 'date'=>'May 30, 11:20 AM','ico'=>'bi-lightning-charge','ibg'=>'rgba(139,92,246,.12)','ic'=>'var(--purple)'],
                ['name'=>'Monthly Rent',       'cat'=>'Rent',            'type'=>'expense','amount'=>90000,'date'=>'May 28, 10:00 AM','ico'=>'bi-house',     'ibg'=>'rgba(59,130,246,.12)','ic'=>'var(--accent2)'],
            ];
            $txRows = !empty($transactions) ? array_slice($transactions, 0, 6) : $demoTx;
            ?>
            <ul class="activity-list">
                <?php foreach ($txRows as $tx):
                    $isIncome = (isset($tx['type']) && strtolower($tx['type']) === 'income');
                    $ico   = $tx['ico']  ?? ($isIncome ? 'bi-arrow-down' : 'bi-arrow-up');
                    $ibg   = $tx['ibg']  ?? ($isIncome ? 'rgba(16,185,129,.12)' : 'rgba(30,42,64,.8)');
                    $ic    = $tx['ic']   ?? ($isIncome ? 'var(--green2)' : 'var(--muted)');
                    $cat   = $tx['cat']  ?? ($tx['category'] ?? '');
                    $dateStr = isset($tx['date']) ? (is_numeric(strtotime($tx['date'])) && !str_contains($tx['date'],',') ? date('M d, g:i A', strtotime($tx['date'])) : $tx['date']) : '';
                ?>
                <li class="activity-item">
                    <div class="activity-icon" style="background:<?= $ibg ?>;color:<?= $ic ?>">
                        <i class="bi <?= $ico ?>"></i>
                    </div>
                    <div class="activity-meta">
                        <div class="activity-name"><?= htmlspecialchars($tx['name']) ?></div>
                        <div class="activity-cat"><?= htmlspecialchars($cat) ?></div>
                    </div>
                    <div class="activity-right">
                        <div class="activity-amount <?= $isIncome ? 'pos' : 'neg' ?>">
                            <?= $isIncome ? '+' : '-' ?>₦<?= number_format($tx['amount'], 2) ?>
                        </div>
                        <div class="activity-date"><?= $dateStr ?></div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div><!-- /left -->

    <!-- RIGHT COLUMN -->
    <div style="display:flex;flex-direction:column;gap:16px;">

        <!-- SAVINGS CARD -->
        <div class="savings-card">
            <div class="savings-lbl">
                <span class="savings-dot"></span> Monthly Savings
            </div>
            <div class="savings-val">
                ₦<?= number_format(max(0, ($monthlySummary['total_income'] ?? 70500) - ($monthlySummary['total_expenses'] ?? 0)), 2) ?>
            </div>
            <div class="savings-sub">+₦2,100 more than last month</div>
        </div>

        <!-- CATEGORIES -->
        <div class="content-card" style="animation:fadeUp .45s ease .30s both">
            <div class="content-card-head">
                <h6>
                    <i class="bi bi-diamond-fill" style="color:var(--accent)"></i>
                    Categories
                </h6>
                <a href="<?= BASE_URL ?>/category/dashboard" class="btn-viewall">
                    All <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="content-card-body">
                <?php
                $catMap = [
                    'food'          => ['ico' => 'bi-basket',          'bg' => 'rgba(249,115,22,.15)',  'color' => '#fb923c'],
                    'rent'          => ['ico' => 'bi-house-fill',       'bg' => 'rgba(59,130,246,.15)',  'color' => 'var(--accent2)'],
                    'transport'     => ['ico' => 'bi-car-front-fill',   'bg' => 'rgba(245,158,11,.15)',  'color' => 'var(--yellow2)'],
                    'utilities'     => ['ico' => 'bi-lightning-fill',   'bg' => 'rgba(139,92,246,.15)',  'color' => 'var(--purple)'],
                    'entertainment' => ['ico' => 'bi-controller',       'bg' => 'rgba(236,72,153,.12)',  'color' => '#f472b6'],
                    'groceries'     => ['ico' => 'bi-bag-fill',         'bg' => 'rgba(249,115,22,.15)',  'color' => '#fb923c'],
                ];
                $demoCats = [
                    ['name' => 'Food',      'transactions' => 12, 'total' => -32000],
                    ['name' => 'Rent',      'transactions' => 1,  'total' => -90000],
                    ['name' => 'Transport', 'transactions' => 8,  'total' => -8500],
                    ['name' => 'Utilities', 'transactions' => 3,  'total' => -15000],
                ];
                $catRows = !empty($categories) ? $categories : $demoCats;
                foreach ($catRows as $cat):
                    $key = strtolower($cat['name']);
                    $ico = $catMap[$key] ?? ['ico' => 'bi-circle-fill', 'bg' => 'var(--bg3)', 'color' => 'var(--muted)'];
                    $neg = ($cat['total'] ?? 0) <= 0;
                ?>
                <div class="cat-row">
                    <div class="cat-row-ico" style="background:<?= $ico['bg'] ?>;color:<?= $ico['color'] ?>">
                        <i class="bi <?= $ico['ico'] ?>"></i>
                    </div>
                    <div class="cat-row-info">
                        <div class="cat-row-name"><?= htmlspecialchars($cat['name']) ?></div>
                        <div class="cat-row-sub"><?= $cat['transactions'] ?? 0 ?> transaction<?= ($cat['transactions'] ?? 0) !== 1 ? 's' : '' ?></div>
                    </div>
                    <div class="cat-row-amt <?= $neg ? 'neg' : 'pos' ?>">
                        <?= $neg ? '-' : '+' ?>₦<?= number_format(abs($cat['total'] ?? 0)) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div><!-- /right -->

</div><!-- /dash-grid -->

<?php View::endSection(); ?>