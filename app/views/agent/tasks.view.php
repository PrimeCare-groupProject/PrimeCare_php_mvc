<?php require_once 'agentHeader.view.php'; ?>
<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Tasks</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/tasks/newtask'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new task</span>
        </div>
    </div>
</div>
<div>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?> 
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
    <?php require __DIR__ . '/../components/repairingCard.php'; ?>
</div>

<!-- Pagination Buttons -->
<div class="pagination">
        <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
    </div>

<?php require_once 'agentFooter.view.php'; ?>
