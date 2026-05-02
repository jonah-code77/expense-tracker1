<?php
$badge ??= ['visual'=>'flat','rotate'=>false,'label'=>'No change vs last month'];
?>
<?php if ($badge['visual'] === 'flat'): ?>
<div class="dash-stat-change flat">
    <i class="bi bi-dash" style="font-size:11px"></i>
    <?= htmlspecialchars($badge['label']) ?>
</div>
<?php else: ?>
<div class="dash-stat-change <?= $badge['visual'] ?>">
    <i class="bi bi-triangle-fill"
       style="font-size:8px<?= $badge['rotate'] ? ';transform:rotate(180deg);display:inline-block' : '' ?>"></i>
    <?= htmlspecialchars($badge['label']) ?>
</div>
<?php endif; ?>