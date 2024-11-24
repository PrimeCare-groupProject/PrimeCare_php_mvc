<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Agent Management</h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input 
                type="text" 
                class="search-input" 
                name="searchterm" 
                placeholder="find agent ..."
            >
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add New Agent</span>
        </div>
    </div>
</div>
<div>
    <?php require __DIR__.'/../components/agentManagement.php'; ?>
    <?php require __DIR__.'/../components/agentManagement.php'; ?>
    <?php require __DIR__.'/../components/agentManagement.php'; ?>
    <?php require __DIR__.'/../components/agentManagement.php'; ?>
</div>

<?php require_once 'managerFooter.view.php'; ?>