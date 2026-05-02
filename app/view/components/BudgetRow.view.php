<?php

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