<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/services'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Payments</h2>
</div>
<div>
    <div>
        <?php require __DIR__ . '/../components/paymentCard.php'; ?>
        <?php require __DIR__ . '/../components/paymentCard.php'; ?>
        <?php require __DIR__ . '/../components/paymentCard.php'; ?>
        <?php require __DIR__ . '/../components/paymentCard.php'; ?>
        <?php require __DIR__ . '/../components/paymentCard.php'; ?>
        <?php require __DIR__ . '/../components/paymentCard.php'; ?>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>