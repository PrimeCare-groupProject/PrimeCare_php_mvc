<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>managements</h2>
</div>

<div class="management-cards-container">
    <div class="management-card-container-sub">
        <a href="<?= ROOT ?>/dashboard/managementhome/employeemanagement">
            <div class="management-card">
                <div class="management-text">
                    <h2>Employee Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/manager.png" alt="Employee Management">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/propertymanagement">
            <div class="management-card">
                <div class="management-text">
                    <h2>Property Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/house.png" alt="Property Management">
                </div>
            </div>
        </a>
    </div>

    <div class="management-card-container-sub">
        <a href="<?= ROOT ?>/dashboard/managementhome">
            <div class="management-card">
                <div class="management-text">
                    <h2>Finance Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/folder.png" alt="Finance Management">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/agentmanagement">
            <div class="management-card">
                <div class="management-text">
                    <h2>Agent Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/agent-manegement.png" alt="Agent Management">
                </div>
            </div>
        </a>
    </div>
</div>


<?php require_once 'managerFooter.view.php'; ?>