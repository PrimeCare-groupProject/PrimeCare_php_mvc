<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/dashboard/repairlisting"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2><?= $service->name ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="SL__multi_container">
    <div class="SL__content_container">
        <div class="SL__service_about">
            <div class="SL__service_about__image">
                <img src="<?= ROOT ?>/<?= $service->service_img ?>" alt="service">
            </div>
            <div class="SL__service_about__details">
                <p><?= $service->description ?>lor</p>
            </div>
        </div>
    
        <div class="SL__main-container">
            <div class="stat-card earnings SL_full_width">
                <h3 class="positive">Charge per hous</h3>
                <div class="stat-amount" style="color: var(--black-color);">Rs. <?= $service->cost_per_hour ?></div>
            </div>
    
            <div class="stat-card spendings SL_full_width">
                <h3 class="negative">Ratings</h3>
                <div class="stat-amount" style="color: var(--black-color);">4.6</div>
            </div>
    
            <div class="stat-card SL_full_width">
                <button class="menu-item" style="font-size: 16px;">
                    Request Service
                </button>
            </div>
        </div>
    
    </div>
    
    <div class="SL__request_container">
        <form action="" method="post" style="width: 100%;">
            <label class="input-label">Address*</label>
            <input type="text" name="address" placeholder="Enter Address" class="input-field" required>
    
            <label class="input-label">Supposed Date*</label>
            <input type="date" name="date" id="date" class="input-field">
    
            <div style="display: flex; justify-content: space-around;">
                <button type="submit" class="primary-btn">Save</button>
                <button id="cancel_btn" class="secondary-btn">Cancel</button>
            </div>

        </form>
    </div>
</div>

<script src="<?= ROOT ?>/assets/js/customer/service.js"></script>

<?php require_once 'customerFooter.view.php'; ?>