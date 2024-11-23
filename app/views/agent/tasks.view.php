<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Tasks</h2>
</div>

<div class="management-cards-container">
    <div class="management-card-container-sub">
        <a href="<?= ROOT ?>/dashboard/tasks/ongoingtask">
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
    </div>

    <div class="management-card-container-sub">
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
</div>

<?php require_once 'agentFooter.view.php'; ?>
