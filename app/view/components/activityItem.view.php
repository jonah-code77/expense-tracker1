<?php
use App\Core\CategoryIcons;

$aType = $row['activity_type'] ?? 'transaction';
$isIncome = strtolower($row['tx_type'] ?? $row['type'] ?? '') === 'income';
$catName = $row['category'] ?? '';
$ico = CategoryIcons::resolve($catName, $isIncome);

$ts = strtotime($row['activity_date'] ?? '');
$diff = time() - $ts;
if ($diff < 3600) $dateStr = round($diff / 60) . 'm ago';
elseif ($diff < 86400) $dateStr = round($diff / 3600) . 'h ago';
elseif ($diff < 172800) $dateStr = 'Yesterday';
else $dateStr = date('M d, Y', $ts);
?>
<li class="activity-item">
    <?php if ($aType === 'category'): ?>
        <div class="activity-icon" style="background:rgba(99,102,241,.13);color:#818cf8">
            <i class="bi bi-tag-fill"></i>
        </div>
        <div class="activity-meta">
            <div class="activity-name">New category added</div>
            <div class="activity-cat">"<?= htmlspecialchars($row['description'] ?? $catName) ?>"</div>
        </div>
        <div class="activity-right">
            <div class="activity-amount" style="color:#818cf8;font-size:.75rem;font-weight:500">Category</div>
            <div class="activity-date"><?= $dateStr ?></div>
        </div>
    <?php else: ?>
        <div class="activity-icon" style="background:<?= $ico['ibg'] ?>;color:<?= $ico['ic'] ?>">
            <i class="bi <?= $ico['ico'] ?>"></i>
        </div>
        <div class="activity-meta">
            <div class="activity-name"><?= htmlspecialchars($row['description'] ?? '') ?></div>
            <div class="activity-cat"><?= htmlspecialchars($catName) ?></div>
        </div>
        <div class="activity-right">
            <?php if (isset($row['amount']) && $row['amount'] !== null): ?>
            <div class="activity-amount <?= $isIncome ? 'pos' : 'neg' ?>">
                <?= $isIncome ? '+' : '−' ?>₦<?= number_format($row['amount'], 2) ?>
            </div>
            <?php endif; ?>
            <div class="activity-date"><?= $dateStr ?></div>
        </div>
    <?php endif; ?>
</li>