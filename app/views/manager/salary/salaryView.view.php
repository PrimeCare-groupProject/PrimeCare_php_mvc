<?php require_once __DIR__ . '\..\managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/employeeListing'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Salary : <span style="color: var(--primary-color);"><?= $agent->fname . ' ' . $agent->lname ?></span></h2>
    <div class="flex-bar">
        <?php if (getPaidStatusOfAgent($agent->pid, date('Y-m'))): ?>
            <button onclick="window.location.href='<?= ROOT ?>/dashboard/managementhome/payForOne/<?= $agent->pid ?>'" class="small-btn blue">Confirm</button>
        <?php else: ?>
            <button class="small-btn green" disabled>Paid</button>
        <?php endif; ?>
    </div>
</div>

<?php require_once APPROOT . '/reports/paysheet.report.php'; ?>

<?php require_once __DIR__ . '\..\managerFooter.view.php'; ?>