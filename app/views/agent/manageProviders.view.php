<?php require_once 'agentHeader.view.php'; ?>

<!-- <style>
    .UR__container {
        display: flex;
        gap: 30px;
    }

    .UR__side_containers {
        position: relative;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .UR__side_containers:hover {
        transform: scale(1.05); /* Adds hover zoom effect */
    }

    .primary-btn {
        text-decoration: none; /* Removes underline from button text */
        background-color: #FCA311;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .primary-btn:hover {
        background-color: #F7DC6F;
    }

    .popup {
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.7);
        color: #fff;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 14px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
        white-space: nowrap;
    }

    .UR__side_containers:hover .popup {
        opacity: 1;
        visibility: visible;
    }
</style> -->

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Management</h2>
</div>
<!-- <div style="padding-top: 20px;" class="UR__container"> -->
    <!-- <div class="UR__side_containers">
        <a href="<?= ROOT ?>/dashboard/manageProviders/serviceproviders">
            <div class="UR__image_container">
                <img src="<?= ROOT ?>/assets/images/empmanage.jpg" alt="Owner">
            </div>
            <div class="button-container">
                <button class="primary-btn">
                    Service Providers
                </button>
            </div>
        </a>
        <div class="popup">Manage all service providers</div>
    </div> -->

    <!-- <div class="UR__side_containers">
        <a href="<?= ROOT ?>/dashboard/manageProviders/payments">
            <div class="UR__image_container">
                <img src="<?= ROOT ?>/assets/images/paymentManage.png" alt="Owner">
            </div>
            <div class="button-container">
                <button class="primary-btn">
                    Payments
                </button>
            </div>
        </a>
        <div class="popup">Manage all payments</div>
    </div> -->
<!-- </div> -->

<div class="MM__cards_container">
    <a href="<?= ROOT ?>/dashboard/manageProviders/serviceproviders">
        <div class="MM__cards">
            <div class="MM__text">
                <h2>Service Providers</h2>
            </div>
            <div class="management-icon">
                <img src="<?= ROOT ?>/assets/images/empmanage.jpg" alt="Owner" class="MM__img">
            </div>
        </div>
    </a>

    <a href="<?= ROOT ?>/dashboard/manageProviders/payments">
        <div class="MM__cards">
            <div class="MM__text">
                <h2>Payment Management</h2>
            </div>
            <div class="management-icon">
                <img src="<?= ROOT ?>/assets/images/paymentManage.png" alt="Owner" class="MM__img">
            </div>
        </div>
    </a>

    <a href="<?= ROOT ?>/dashboard/manageProviders/propertyowners">
        <div class="MM__cards">
            <div class="MM__text">
                <h2>Property Owners</h2>
            </div>
            <div class="management-icon">
                <img src="<?= ROOT ?>/assets/images/BookProperty.jpg" alt="Finance Management" class="MM__img">
            </div>
        </div>
    </a>

    <a href="<?= ROOT ?>/dashboard/manageProviders/ManageTenents">
        <div class="MM__cards">
            <div class="MM__text">
                <h2>Tenents</h2>
            </div>
            <div class="management-icon">
                <img src="<?= ROOT ?>/assets/images/reportform.jpg" alt="Manage Tenents" class="MM__img">
            </div>
        </div>
    </a>

    <div class="loader-container" style="display: none;">
        <div class="spinner-loader"></div>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>
