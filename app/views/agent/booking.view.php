<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Bookings</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/bookings/history'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/assignment.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">History</span>
        </div>
    </div>
</div>
<div class="booking-listing">
    <div class="booking-grid">
        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <?php foreach ($images as $image): ?>
                <?php if ($booking->property_id == $image->property_id): ?>
                <div class="booking-card">
                    <div class="booking-card__image-wrap">
                        <img class="booking-card__image" src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image->image_url ?>" alt="Property">
                        <span class="booking-card__status">Booked</span>
                    </div>
                    <div class="booking-card__body">
                        <h4 class="booking-card__title"><?= $booking->name?></h4>
                        <div class="booking-card__meta">
                            <div class="booking-meta__item">
                                <span class="booking-meta__icon">💰</span>
                                <span><?= $booking->price?> LKR</span>
                            </div>
                            <div class="booking-meta__item">
                                <span class="booking-meta__icon">📅</span>
                                <span><?= $booking->renting_period?> months</span>
                            </div>
                        </div>
                        <a href="<?=ROOT?>/dashboard/bookings/bookingaccept/<?= $booking->booking_id ?>" class="booking-card__action">
                            Update Booking
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?> 
        <?php else: ?>
            <p class="booking-empty">No bookings found</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="booking-pagination">
        <button class="booking-pagination__prev">
            <svg class="booking-pagination__icon" viewBox="0 0 24 24">
                <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/>
            </svg>
        </button>
        <span class="booking-pagination__current">1</span>
        <button class="booking-pagination__next">
            <svg class="booking-pagination__icon" viewBox="0 0 24 24">
                <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
            </svg>
        </button>
    </div>
</div>


<?php require_once 'agentFooter.view.php'; ?>