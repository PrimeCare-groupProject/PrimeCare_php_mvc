<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>Services</h2>
</div>

<div class="management-cards-container">
    <div class="management-card-container-sub1">
        <a href="<?= ROOT ?>/dashboard/services/serviceproviders">
            <div class="management-card">
                <div class="management-text">
                    <h2>Services</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/manager.png" alt="Ongoing Task">
                </div>
            </div>
        </a>
    </div>

    <div class="management-card-container-sub1">
        <a href="<?= ROOT ?>/dashboard/services/payments">
            <div class="management-card">
                <div class="management-text">
                    <h2>Payments</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/folder.png" alt="Inventory Management">
                </div>
            </div>
        </a>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>
