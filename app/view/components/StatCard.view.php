<?php

$valueColor  ??= 'inherit';
$badge       ??= null;
$customBadge ??= null;
?>
<div class="dash-stat-card">
    <div class="dash-stat-top">
        <span class="dash-stat-label"><?= htmlspecialchars($title) ?></span>
        <div class="dash-stat-ico <?= htmlspecialchars($iconColor) ?>">
            <i class="bi <?= htmlspecialchars($iconClass) ?>"></i>
        </div>
    </div>
    <div class="dash-stat-value" style="color:<?= $valueColor ?>">
        <?= $value ?>
    </div>
    <?php if ($badge !== null): ?>
        <?php \App\Core\Component::Render('StatBadge', ['badge' => $badge]) ?>
    <?php elseif ($customBadge !== null): ?>
        <?= $customBadge ?>
    <?php endif; ?>
</div>