<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Update your role</h2>
</div>

<div class="UR__container">
    <div class="UR__side_containers">
        <div class="UR__image_container">
            <img src="<?= ROOT ?>/assets/images/addOwner.jpg" alt="Owner">
        </div>
        <button class="primary-btn" id="owner_btn">Add your property</button>
    </div>

    <div class="UR__side_containers">
        <div class="UR__image_container">
            <img src="<?= ROOT ?>/assets/images/beSerPro.jpg" alt="Agent">
        </div>
        <button class="primary-btn" id="serpro_btn">Be a Service Provider</button>
    </div>


    <div class="hidden_msg_owner" id="hidden_msg_owner" style="display: none;">
        <div class="close-btn">
            <img src="<?= ROOT ?>/assets/images/close.png" alt="Close" id="close_owner">
        </div>
        <h3>Be a Seller</h3>
        <div class="instructions">
            <p?>Sellers are responsible for managing inquiries and ensuring timely responses to potential buyers or tenants</p>
            <p>The system charges a nominal service fee for listings or successful transactions, as per the pricing policy</P>
            <p>Non-compliance with system policies or repeated complaints may lead to the suspension of your account.</p>
        </div>
        <div style="display: flex; width: 100%; justify-content: center; align-items: center;">
            <button class="primary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/updateToOwner'">Upgrade</button>
        </div>
    </div>
    <div class="hidden_msg_serpro" id="hidden_msg_serpro" style="display: none;">
        <div class="close-btn">
            <img src="<?= ROOT ?>/assets/images/close.png" alt="Close" id="close_serpro">
        </div>
        <h3>Apply as a Service Provider</h3>
        <div class="instructions">
            <p>Are you sure you want to register as a service provider in the system? This will set your default role as a service provider</p>
        </div>
        <div>
            <p>Choose the service you want to provide</p>
            <form action="" method="post" class="UR__form">
                <select name="service" id="service" class="input-field">
                    <?php foreach($services as $service): ?>
                        <option value="<?= $service->service_id ?>"><?= $service->name ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="primary-btn" style="margin-top: 0;">Confirm</button>
            </form>
        </div>
    </div>
</div>

<script src="<?= ROOT ?>/assets/js/customer/updateRole.js"></script>
<?php require 'customerFooter.view.php' ?>