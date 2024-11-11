<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>ongoing task</h2>
</div>
<!-- RepairListingComponent -->
<div class="property-listing-grid">
        <?php require __DIR__ . '/../components/repaircards/doorRepairing.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/bathroomRepairing.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/roofRepairing.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/plumbing.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/deckRepairing.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/electricalRepairing.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/painting.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/furnitureReparing.php'; ?>
        <?php require __DIR__ . '/../components/repaircards/concreteRepairing.php'; ?>        
    </div>

<?php require_once 'agentFooter.view.php'; ?>
