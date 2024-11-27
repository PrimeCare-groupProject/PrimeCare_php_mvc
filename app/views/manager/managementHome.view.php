<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>managements</h2>
</div>

<!-- <div class="management-cards-container"> -->
        <!-- <a href="<?= ROOT ?>/dashboard/managementhome/employeemanagement">
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
        </a> -->

<div class="MM__cards_container">
        <a href="<?= ROOT ?>/dashboard/managementhome/employeemanagement">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Employee Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/employeeManagement.jpg" alt="Employee Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/propertymanagement">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Property Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/addOwner.jpg" alt="Property Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/financialManagement">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Finance Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/financialManagement.jpg" alt="Finance Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/managementhome/agentmanagement">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Agent Management</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/agentManagement.jpg" alt="Agent Management" class="MM__img">
                </div>
            </div>
        </a>
</div>


<?php require_once 'managerFooter.view.php'; ?>