<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/manageProviders/serviceproviders'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Service Providers</h2>
</div>
<div>
    <div>
        <?php require __DIR__ . '/../components/serviceProviderCard.php'; ?>
        <?php require __DIR__ . '/../components/serviceProviderCard.php'; ?>
        <?php require __DIR__ . '/../components/serviceProviderCard.php'; ?>
        <?php require __DIR__ . '/../components/serviceProviderCard.php'; ?>
        <?php require __DIR__ . '/../components/serviceProviderCard.php'; ?>
        <?php require __DIR__ . '/../components/serviceProviderCard.php'; ?>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>