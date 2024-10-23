<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>property managements</h2>
</div>

<div class="management-cards-container">
    <div class="management-card-container-sub">
        <a href="<?= ROOT ?>/dashboard/managementhome/propertymanagement/assignagents">
            <div class="management-card">
                <div class="management-text">
                    <h2>Assign Agents</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/selection.png" alt="Employee Management">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/propertymanagement/requestapproval">
            <div class="management-card">
                <div class="management-text">
                    <h2>Approve Requests</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/email.png" alt="Property Management">
                </div>
            </div>
        </a>
    </div>
</div>


<?php require_once 'managerFooter.view.php'; ?>