<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/propertymanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>assign properties</h2>
</div>
<div>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
</div>

<?php require_once 'managerFooter.view.php'; ?>