<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/manageProviders'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Pre Inspection on : <span style="color: var(--green-color);"><?= $property->name ?></span></h2>
</div>
<div>
    <button onclick="window.location.href='<?= ROOT ?>/dashboard/preInspection/reportGen/<?= $property->property_id ?>'"></button>
</div>

<?php require_once 'agentFooter.view.php'; ?>