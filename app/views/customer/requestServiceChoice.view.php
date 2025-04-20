<?php require 'customerHeader.view.php' ?>

<style>
.UR__container {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 40px;
}
.UR__side_containers {
    width: 320px;
    height: 350px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.UR__image_container {
    width: 100%;
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    overflow: hidden;
}
.UR__image_container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.UR__side_containers .primary-btn {
    width: 220px;
    min-width: 180px;
    max-width: 100%;
    display: block;
    margin: 20px auto 0 auto;
}
</style>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Request a Service</h2>
</div>

<div class="UR__container">
    <div class="UR__side_containers">
        <div class="UR__image_container">
            <img src="<?= ROOT ?>/assets/images/occupied_property.jpg" alt="Occupied Property">
        </div>
        <button class="primary-btn" onclick="window.location.href='<?= ROOT ?>/customer/requestServiceOccupied'">
            Request for Occupied Property
        </button>
    </div>

    <div class="UR__side_containers">
        <div class="UR__image_container">
            <img src="<?= ROOT ?>/assets/images/external_property.jpg" alt="External Property">
        </div>
        <button class="primary-btn" onclick="window.location.href='<?= ROOT ?>/customer/externalRepairListing'">
            Request Service for External Property
        </button>
    </div>
</div>

<?php require 'customerFooter.view.php' ?>