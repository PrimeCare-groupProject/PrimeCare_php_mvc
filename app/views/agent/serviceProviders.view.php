<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Service Providers</h2>
</div>
<div class="management-cards-container">
    <div class="management-card-container-sub">
        <a href="<?= ROOT ?>/dashboard/services/serviceproviders/addserviceprovider">
            <div class="management-card">
                <div class="management-text">
                    <h2>Add Service Provider</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/selection.png" alt="Employee Management">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/services/serviceproviders/removeserviceprovider">
            <div class="management-card">
                <div class="management-text">
                    <h2>Remove Service Provider</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/email.png" alt="Property Management">
                </div>
            </div>
        </a>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>