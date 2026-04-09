<?php use App\Core\View; ?>
<?php View::section('title'); ?>
Dashboard
<?php View::endSection(); ?>

<?php View::section('topbar-action'); ?>
<a href="<?= BASE_URL ?>/transaction/dashboard"
   class="btn btn-sm d-flex align-items-center gap-1"
   style="background:#1a3a5c;color:white;border-radius:8px;font-size:13px;font-weight:500;padding:.4rem .9rem">
    <i class="bi bi-plus-lg"></i> Add Transaction
</a>
<?php View::endSection(); ?>

<?php View::section('content'); ?>

<!-- Stat cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stat-label">Total Income</div>
            <div class="stat-value">₦<?= number_format($totals['total_income'], 2) ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-arrow-up-circle"></i></div>
            <div class="stat-label">Total Expenses</div>
            <div class="stat-value">₦<?= number_format($totals['total_expenses'], 2) ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-wallet2"></i></div>
            <div class="stat-label">Balance</div>
            <div class="stat-value <?= $totals['balance'] >= 0 ? 'text-success' : 'text-danger' ?>">
                ₦<?= number_format($totals['balance'], 2) ?>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon orange"><i class="bi bi-calendar-month"></i></div>
            <div class="stat-label">Monthly Expenses</div>
            <div class="stat-value">₦<?= number_format($monthlySummary['total_expenses'], 2) ?></div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-graph-up-arrow"></i></div>
            <div class="stat-label">Monthly Income</div>
            <div class="stat-value">₦<?= number_format($monthlySummary['total_income'], 2) ?></div>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="content-card">
    <div class="content-card-head">
        <h6><i class="bi bi-arrow-left-right me-2" style="color:#378add"></i>Recent Transactions</h6>
        <a href="<?= BASE_URL ?>/transaction/dashboard" class="btn-viewall">
            View all <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

    <?php if(!empty($transactions)): ?>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Amount</th><th>Category</th>
                    <th class="d-none d-md-table-cell">Description</th>
                    <th class="d-none d-md-table-cell">Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($transactions as $index => $transaction): ?>
                <tr>
                    <td style="color:#9ca3af"><?= $index + 1 ?></td>
                    <td style="font-weight:500"><?= htmlspecialchars($transaction['name']) ?></td>
                    <td>
                        <span class="<?= strtolower($transaction['type']) === 'income' ? 'amount-income' : 'amount-expense' ?>">
                            <?= strtolower($transaction['type']) === 'income' ? '+' : '-' ?>₦<?= number_format($transaction['amount'], 2) ?>
                        </span>
                    </td>
                    <td>
                        <span class="<?= strtolower($transaction['type']) === 'income' ? 'badge-income' : 'badge-expense' ?>">
                            <?= htmlspecialchars($transaction['type']) ?>
                        </span>
                    </td>
                    <td class="d-none d-md-table-cell" style="color:#6b7280;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        <?= htmlspecialchars($transaction['description']) ?>
                    </td>
                    <td class="d-none d-md-table-cell" style="color:#6b7280">
                        <?= date('M d, Y', strtotime($transaction['date'])) ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="<?= BASE_URL ?>/transaction/single/<?= $transaction['id'] ?>" class="btn-view"><i class="bi bi-eye"></i></a>
                            <a href="<?= BASE_URL ?>transaction/DeleteTransaction/<?= $transaction['id'] ?>"
                               onclick="return confirm('Are you sure you want to delete this transaction?')"
                               class="btn-delete"><i class="bi bi-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="empty-state">
        <i class="bi bi-inbox"></i>
        <p>No transactions yet</p>
        <a href="<?= BASE_URL ?>/transaction/dashboard" class="btn-viewall mt-3 d-inline-block">
            Add your first transaction
        </a>
    </div>
    <?php endif; ?>
</div>

<?php View::endSection(); ?>