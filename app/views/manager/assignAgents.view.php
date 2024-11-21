<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/propertymanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>assign properties</h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input 
                type="text" 
                class="search-input" 
                name="searchterm" 
                placeholder="find property ..."
            >
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>
<div>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
    <?php require __DIR__ . '/../components/propertyAssign.php'; ?>
</div>

<?php require_once 'managerFooter.view.php'; ?>