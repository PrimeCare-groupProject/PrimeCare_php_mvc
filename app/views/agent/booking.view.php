<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Bookings</h2>
</div>
<div class="listing-the-property">
    <div class="property-listing-grid">
        <?php require __DIR__ . '/../components/bookingCard.php'; ?>
        <?php require __DIR__ . '/../components/bookingCard.php'; ?>
        <?php require __DIR__ . '/../components/bookingCard.php'; ?>
        <?php require __DIR__ . '/../components/bookingCard.php'; ?>
        <?php require __DIR__ . '/../components/bookingCard.php'; ?>
        <?php require __DIR__ . '/../components/bookingCard.php'; ?>
    </div>
</div>
<!-- Pagination Buttons -->
<div class="pagination">
        <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
</div>

<?php require_once 'agentFooter.view.php'; ?>