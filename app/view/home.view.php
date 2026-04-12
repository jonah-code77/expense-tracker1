<?php use App\Core\View; ?>
<?php View::section('title'); ?>Dashboard<?php View::endSection(); ?>

<?php View::section('topbar-action'); ?>
<a href="<?= BASE_URL ?>/transaction/dashboard"
   class="btn-main" style="font-size:13px;padding:.4rem .9rem">
    <i class="bi bi-plus-lg"></i> Add Transaction
</a>
<?php View::endSection(); ?>

<?php View::section('content'); ?>


<div class="row g-3 mb-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-piggy-bank"></i></div>
            <div class="stat-label">Monthly Savings</div>
            <div class="stat-value">
                ₦<?= number_format(max(0, $monthlySummary['total_income'] - $monthlySummary['total_expenses']), 2) ?>
            </div>
        </div>
</div>

<div class="row g-4">

    <!-- ── LEFT COLUMN ─────────────── -->
    <div class="col-12 col-xl-8">

        <!-- SPENDING OVERVIEW GRAPH -->
        <div class="content-card mb-4">
            <div class="content-card-head">
                <h6><i class="bi bi-bar-chart-line me-2" style="color:#378add"></i>Spending Overview</h6>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="chartPeriod"
                            style="width:auto;font-size:12px;padding:.25rem .6rem;border-radius:7px">
                        <option value="6">Last 6 months</option>
                        <option value="12">Last 12 months</option>
                        <option value="3">Last 3 months</option>
                    </select>
                </div>
            </div>
            <div class="content-card-body" style="padding:1rem 1.25rem .75rem">
                <canvas id="spendingChart" height="110"></canvas>
            </div>
        </div>

        <!-- BUDGET TRACKER -->
        <div class="content-card mb-4">
            <div class="content-card-head">
                <h6><i class="bi bi-wallet2 me-2" style="color:#378add"></i>Budget Tracker</h6>
                <a href="<?= BASE_URL ?>/budget/dashboard" class="btn-viewall">
                    Manage <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="content-card-body">
                <?php if (!empty($budgets)): ?>
                    <?php foreach ($budgets as $budget): ?>
                    <?php
                        $pct   = $budget['limit'] > 0
                                   ? min(100, round($budget['spent'] / $budget['limit'] * 100))
                                   : 0;
                        $color = $pct >= 90 ? 'danger' : ($pct >= 65 ? 'warning' : 'success');
                    ?>
                    <div class="budget-row mb-3">
                        <div class="d-flex justify-content-between align-items-baseline mb-1">
                            <span class="budget-name"><?= htmlspecialchars($budget['name']) ?></span>
                            <span class="budget-amounts">
                                <strong>₦<?= number_format($budget['spent'], 2) ?></strong>
                                <span class="text-muted"> / ₦<?= number_format($budget['limit'], 2) ?></span>
                            </span>
                        </div>
                        <div class="progress" style="height:7px;border-radius:99px">
                            <div class="progress-bar bg-<?= $color ?>"
                                 style="width:<?= $pct ?>%;border-radius:99px"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span style="font-size:11px;color:#9ca3af"><?= $pct ?>% used</span>
                            <span style="font-size:11px;color:#9ca3af">
                                ₦<?= number_format(max(0, $budget['limit'] - $budget['spent']), 2) ?> left
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Demo placeholders (remove once real data flows) -->
                    <?php
                    $demos = [
                        ['name'=>'Food & Groceries','spent'=>32000,'limit'=>50000,'color'=>'success'],
                        ['name'=>'Rent',            'spent'=>90000,'limit'=>90000,'color'=>'danger'],
                        ['name'=>'Transport',       'spent'=>8500, 'limit'=>15000,'color'=>'warning'],
                        ['name'=>'Entertainment',   'spent'=>4000, 'limit'=>20000,'color'=>'success'],
                    ];
                    foreach ($demos as $d):
                        $pct = round($d['spent'] / $d['limit'] * 100);
                    ?>
                    <div class="budget-row mb-3">
                        <div class="d-flex justify-content-between align-items-baseline mb-1">
                            <span class="budget-name"><?= $d['name'] ?></span>
                            <span class="budget-amounts">
                                <strong>₦<?= number_format($d['spent']) ?></strong>
                                <span class="text-muted"> / ₦<?= number_format($d['limit']) ?></span>
                            </span>
                        </div>
                        <div class="progress" style="height:7px;border-radius:99px">
                            <div class="progress-bar bg-<?= $d['color'] ?>"
                                 style="width:<?= $pct ?>%;border-radius:99px"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <span style="font-size:11px;color:#9ca3af"><?= $pct ?>% used</span>
                            <span style="font-size:11px;color:#9ca3af">
                                ₦<?= number_format(max(0, $d['limit'] - $d['spent'])) ?> left
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div><!-- end left col -->


    <!-- ── RIGHT COLUMN ──────────────────────── -->
    <div class="col-12 col-xl-4">

        <!-- DEFAULT CATEGORIES -->
        <div class="content-card mb-4">
            <div class="content-card-head">
                <h6><i class="bi bi-tag me-2" style="color:#378add"></i>Categories</h6>
                <a href="<?= BASE_URL ?>/category/dashboard" class="btn-viewall">
                    All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="content-card-body">
                <div class="card-soft mb-4">
                    <h6>Categories</h6>

                    <div class="category"><i class="bi bi-basket"></i> Food</div>
                    <div class="category"><i class="bi bi-house"></i> Rent</div>
                    <div class="category"><i class="bi bi-bus-front"></i> Transport</div>
                    <div class="category"><i class="bi bi-lightning"></i> Utilities</div>

                </div>
            </div>
        </div>

        <!-- RECENT ACTIVITIES -->
        <div class="content-card">
            <div class="content-card-head">
                <h6><i class="bi bi-clock-history me-2" style="color:#378add"></i>Recent Activities</h6>
                <a href="<?= BASE_URL ?>/transaction/dashboard" class="btn-viewall">
                    All <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="content-card-body" style="padding:0">

                <?php if (!empty($transactions)): ?>
                <ul class="activity-list">
                    <?php foreach (array_slice($transactions, 0, 8) as $tx): ?>
                    <?php $isIncome = strtolower($tx['type']) === 'income'; ?>
                    <li class="activity-item">
                        <div class="activity-icon <?= $isIncome ? 'activity-icon--income' : 'activity-icon--expense' ?>">
                            <i class="bi <?= $isIncome ? 'bi-arrow-down' : 'bi-arrow-up' ?>"></i>
                        </div>
                        <div class="activity-meta">
                            <span class="activity-name"><?= htmlspecialchars($tx['name']) ?></span>
                            <span class="activity-date"><?= date('M d', strtotime($tx['date'])) ?></span>
                        </div>
                        <div class="activity-amount <?= $isIncome ? 'amount-income' : 'amount-expense' ?>">
                            <?= $isIncome ? '+' : '-' ?>₦<?= number_format($tx['amount'], 2) ?>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <p>No activities yet</p>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div><!-- end right col -->

</div><!-- end main grid -->

<?php View::endSection(); ?>


<?php View::section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
(function () {
    /* ── Spending chart data from PHP ── */
    const rawLabels  = <?= json_encode($chartLabels  ?? ['Jan','Feb','Mar','Apr','May','Jun']) ?>;
    const rawIncome  = <?= json_encode($chartIncome  ?? [45000,62000,38000,71000,55000,80000]) ?>;
    const rawExpense = <?= json_encode($chartExpense ?? [32000,41000,29000,53000,47000,61000]) ?>;

    const ctx = document.getElementById('spendingChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: rawLabels,
            datasets: [
                {
                    label: 'Income',
                    data: rawIncome,
                    backgroundColor: 'rgba(22,163,74,.15)',
                    borderColor:     '#16a34a',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    type: 'bar',
                },
                {
                    label: 'Expenses',
                    data: rawExpense,
                    backgroundColor: 'rgba(220,38,38,.12)',
                    borderColor:     '#dc2626',
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                    type: 'bar',
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'top',
                    labels: { font: { family: "'DM Sans', sans-serif", size: 12 }, boxWidth: 12, boxHeight: 12 }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ₦' + ctx.parsed.y.toLocaleString()
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: "'DM Sans', sans-serif", size: 12 } } },
                y: {
                    grid: { color: 'rgba(0,0,0,.05)' },
                    ticks: {
                        font: { family: "'DM Sans', sans-serif", size: 11 },
                        callback: v => '₦' + (v >= 1000 ? (v/1000).toFixed(0)+'k' : v)
                    }
                }
            }
        }
    });

    /* period switcher — wire to your AJAX endpoint or reload */
    document.getElementById('chartPeriod').addEventListener('change', function () {
        /* Example: window.location = BASE_URL + '/home?period=' + this.value */
        console.log('Period changed to', this.value, 'months — add your reload/AJAX logic here');
    });
})();
</script>
<?php View::endSection(); ?>