<?php require_once __DIR__ . '\..\agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/property'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2><?= $property->name ?></h2>
</div>

<?php require_once __DIR__ . '\..\..\components\propertyView.php' ?>


<?php require_once __DIR__ . '\..\agentFooter.view.php'; ?>