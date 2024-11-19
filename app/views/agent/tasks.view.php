<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Tasks</h2>
</div>

<div class="management-cards-container" style="height: 50%; ">
        <a href="<?= ROOT ?>/dashboard/tasks/ongoingtask" style="margin-top: 3px;">
            <div class="management-card">
                <div class="management-text">
                    <h2>Ongoing Task</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/manager.png" alt="Ongoing Task">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/tasks/newtask">
            <div class="management-card">
                <div class="management-text">
                    <h2>New Task</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/house.png" alt="New Task">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/tasks/inventorymanagement">
            <div class="management-card">
                <div class="management-text">
                    <h2>Inventory Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/folder.png" alt="Inventory Management">
                </div>
            </div>
        </a>
</div>

<?php require_once 'agentFooter.view.php'; ?>
