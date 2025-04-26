<?php require_once __DIR__ . '\..\agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Property Management</h2>
</div>


<div class="MM__cards_container">
        <a href="<?= ROOT ?>/dashboard/property/showMyProperties">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>My Properties</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/employeeManagement.jpg" alt="Employee Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/property/changeRequests">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Update Requests</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/addOwner.jpg" alt="Property Management" class="MM__img">
                </div>
            </div>
        </a>

        <a href="<?= ROOT ?>/dashboard/property/removalRequests">
            <div class="MM__cards">
                <div class="MM__text">
                    <h2>Removal Request</h2>
                </div>
                <div class="management-icon">
                    <img src="<?= ROOT ?>/assets/images/no.jpg" alt="Finance Management" class="MM__img">
                </div>
            </div>
        </a>

        <div class="loader-container" style="display: none;">
            <div class="spinner-loader"></div>
        </div>
</div>

<?php require_once __DIR__ . '\..\agentFooter.view.php'; ?>
