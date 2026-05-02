<?php
use App\Core\CategoryIcons;

$ico = CategoryIcons::resolve($cat['name'] ?? '');
$neg = (($cat['total'] ?? 0) <= 0);
$txCount = $cat['transactions'] ?? $cat['transaction_count'] ?? 0;
?>
<div class="cat-row">
    <div class="cat-row-ico" style="background:<?= $ico['ibg'] ?>;color:<?= $ico['ic'] ?>">
        <i class="bi <?= $ico['ico'] ?>"></i>
    </div>
    <div class="cat-row-info">
        <div class="cat-row-name"><?= htmlspecialchars($cat['name'] ?? '') ?></div>
        <div class="cat-row-sub"><?= $txCount ?> transaction<?= $txCount != 1 ? 's' : '' ?></div>
    </div>
    <div class="cat-row-amt <?= $neg ? 'neg' : 'pos' ?>">
        <?= $neg ? '−' : '+' ?>₦<?= number_format(abs($cat['total'] ?? 0)) ?>
    </div>
</div>