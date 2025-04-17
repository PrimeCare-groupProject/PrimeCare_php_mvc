<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/preInspection'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>PreInspection on: <span style="color: var(--green-color);"><?= $property->name ?></span></h2>
</div>

<?php require __DIR__ . '/../components/propertyView.php' ?>

<?php require_once 'agentFooter.view.php'; ?>