<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/manageProviders'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Service Providers</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/manageProviders/serviceproviders/addserviceprovider'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new service Provider</span>
        </div>
    </div>
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
<!-- Pagination Buttons -->
<div class="pagination">
        <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
    </div>

<?php require_once 'agentFooter.view.php'; ?>