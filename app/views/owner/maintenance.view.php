<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar" style="margin-bottom: 15px;">
    <div class="gap"></div>
    <h2>Maintenance</h2>
    <div class="flex-bar">
        <span style="color: var(--dark-grey-color)">Total Expenses:</span>
        <span class="bolder-text"><?= number_format($totalExpenses, 2) ?> LKR</span>
    </div>
</div>

<div style="max-width: 1400px; margin: 0 auto; padding: 0 10px 20px;">
    <?php if (empty($serviceLogs)): ?>
        <div style="padding: 15px; color: #0c5460; background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; margin-bottom: 15px; text-align: center; font-size: 16px;">
            No maintenance records found.
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 20px; width: 100%;">
            <?php foreach ($serviceLogs as $serviceLog): ?>
                <div style="width: 100%;">
                    <?php include __DIR__ . '/../components/serviceCard.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'ownerFooter.view.php'; ?>