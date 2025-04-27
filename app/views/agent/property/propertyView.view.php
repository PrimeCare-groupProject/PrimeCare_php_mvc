<?php require_once __DIR__ . '\..\agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <button class="back-btn" onclick="history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
            <h2><?= $property->name ?></h2>
        </div>
        <div class="right-content">
            <div class="tooltip-container">
            <img src="<?= ROOT ?>/assets/images/docs.png" alt="Print" class="small-icons align-to-right color_financial" onclick="window.open('<?= ROOT ?>/assets/documents/uploads/property_documents/<?= $property->property_deed_images ?>', '_blank')">
                <span class="tooltip-text">Deed Document</span>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '\..\..\components\propertyView.php' ?>


<?php require_once __DIR__ . '\..\agentFooter.view.php'; ?>