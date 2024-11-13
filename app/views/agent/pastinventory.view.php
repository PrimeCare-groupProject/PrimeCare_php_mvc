<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks/inventorymanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Past Inventory</h2>
</div>
<div>
    <?php require __DIR__.'/../components/inventory.php'; ?>
    <?php require __DIR__.'/../components/inventory.php'; ?>
    <?php require __DIR__.'/../components/inventory.php'; ?>
    <?php require __DIR__.'/../components/inventory.php'; ?>
</div>

<?php require_once 'agentFooter.view.php'; ?>