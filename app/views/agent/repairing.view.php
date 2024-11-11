<?php require_once 'agentHeader.view.php'; ?>
<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks/ongoingtask'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2><?php echo $_SESSION['repair']?></h2>
</div>
<div>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>
    <?php require __DIR__ . '/../components/preInspectionCard.php'; ?>  
</div>

<?php require_once 'agentFooter.view.php'; ?>
