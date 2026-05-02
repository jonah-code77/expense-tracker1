
<?php

use App\Core\Component;
use App\Core\View;
?>

<?php View::section('title'); ?>Dashboard<?php View::endSection(); ?>

<?php View::section('topbar-title'); ?>
<div class="topbar-left">
    <p>
       <?= $isFirstLogin ? 'Welcome,' : 'Welcome back,' ?> 
        <?= $name ?> — here's your financial overview
    </p>
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

<!-- ═══ STAT CARDS -->
<div class="dash-stats">

    <?php Component::Render('StatCard', [
        'title'      => 'Total Income',
        'value'      => '₦' . number_format($summary['income'] ?? 0, 2),
        'iconClass'  => 'bi-arrow-up-circle-fill',
        'iconColor'  => 'green',
        'valueColor' => 'var(--green2)',
        'badge'      => $summary['income_badge'] ?? null,
    ]) ?>

    <?php Component::Render('StatCard', [
        'title'      => 'Total Expenses',
        'value'      => '₦' . number_format($summary['expense'] ?? 0, 2),
        'iconClass'  => 'bi-arrow-down-circle-fill',
        'iconColor'  => 'red',
        'valueColor' => 'var(--red2)',
        'badge'      => $summary['expense_badge'] ?? null,
    ]) ?>

    <?php Component::Render('StatCard', [
        'title'     => 'Monthly Savings',
        'value'     => '₦' . number_format($summary['savings'] ?? 0, 2),
        'iconClass' => 'bi-diamond-fill',
        'iconColor' => 'blue',
        'badge'     => $summary['savings_badge'] ?? null,
    ]) ?>

    <?php
    // budgets card has no percentage — build a plain status line
    ob_start(); ?>
    <div class="dash-stat-change flat">
        <i class="bi bi-circle-fill" style="font-size:5px;color:var(--green2)"></i>
        <span style="color:var(--muted2)"><?= htmlspecialchars($summary['budgets_label'] ?? '—') ?></span>
    </div>
    <?php Component::Render('StatCard', [
        'title'       => 'Active Budgets',
        'value'       => (string) count($budgets ?? []),
        'iconClass'   => 'bi-circle-fill',
        'iconColor'   => 'yellow',
        'customBadge' => ob_get_clean(),
    ]) ?>

</div>


<!--  DASHBOARD GRID  -->
<div class="dash-grid">

    <!-- LEFT COLUMN -->
    <div style="display:flex;flex-direction:column;gap:18px">

        <!-- BUDGET TRACKER -->
   <?php if (!empty($budgets)): ?>
        <?php foreach ($budgets as $b): ?>
            <?php Component::Render('BudgetRow', ['b' => $b]) ?>
        <?php endforeach; ?>

    <?php elseif ($hadBudgetBefore): ?>
        <?php Component::Render('BudgetEmptyReturning') ?>

    <?php else: ?>
        <?php Component::Render('BudgetEmptyFirstTimer') ?>

    <?php endif; ?>

        <!-- RECENT ACTIVITIES -->
    <div class="content-card">
        <div class="content-card-head">
            <h6><i class="bi bi-clock-history" style="color:var(--accent)"></i> Recent Activities</h6>
            <a href="<?= BASE_URL ?>/transaction/dashboard" class="btn-viewall">
                All <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <?php if (!empty($activity)): ?>
            <ul class="activity-list">
                <?php foreach ($activity as $row): ?>
                    <?php Component::Render('ActivityItem', ['row' => $row]) ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <?php Component::Render('ActivityEmpty') ?>
        <?php endif; ?>

    </div>

    </div><!-- /left -->


    <!-- RIGHT COLUMN -->
    <div style="display:flex;flex-direction:column;gap:16px">

        <!-- SAVINGS HIGHLIGHT -->
        <div class="savings-card">
            <div class="savings-lbl"><span class="savings-dot"></span> Monthly Savings</div>
            <div class="savings-val">₦<?= number_format($summary['savings'] ?? 0, 2) ?></div>
            <?php $sv = $summary['savings_badge'] ?? ['visual'=>'flat','label'=>'No change vs last month']; ?>
            <div class="savings-sub" style="color:<?= $sv['visual'] === 'up' ? 'var(--green2)' : ($sv['visual'] === 'down' ? 'var(--red2)' : 'inherit') ?>">
                <?= $sv['visual'] === 'up' ? '▲' : ($sv['visual'] === 'down' ? '▼' : '') ?>
                <?= htmlspecialchars($sv['label']) ?>
            </div>
        </div>

    <!-- CATEGORIES -->
    <div class="content-card" style="animation:fadeUp .45s ease .30s both">
        <div class="content-card-head">
            <h6><i class="bi bi-diamond-fill" style="color:var(--accent)"></i> Categories</h6>
            <a href="<?= BASE_URL ?>/category/dashboard" class="btn-viewall">
                All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="content-card-body">
            <?php foreach ($categories as $cat): ?>
                <?php Component::Render('CategoryRow', ['cat' => $cat]) ?>
            <?php endforeach; ?>
        </div>
    </div>

    </div><!-- /right -->

</div>

<?php View::endSection(); ?>