<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Inventory Management</h2>
</div>
<div class="management-cards-container">
    <div class="management-card-container-sub">
        <a href="<?= ROOT ?>/dashboard/tasks/inventorymanagement/pastinventory">
            <div class="management-card">
                <div class="management-text">
                    <h2>Past Inventory</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/selection.png" alt="Employee Management">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/tasks/inventorymanagement/newinventory">
            <div class="management-card">
                <div class="management-text">
                    <h2>New Inventory</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/email.png" alt="Property Management">
                </div>
            </div>
        </a>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>