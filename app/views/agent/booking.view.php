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

<style>
/* Booking Listing Styles */
.booking-listing {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

.booking-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.booking-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.booking-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
}

.booking-card__image-wrap {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.booking-card__image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.booking-card:hover .booking-card__image {
    transform: scale(1.05);
}

.booking-card__status {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #4f46e5;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.booking-card__body {
    padding: 1.25rem;
}

.booking-card__title {
    margin: 0 0 1rem;
    font-size: 1.1rem;
    color: #1f2937;
    font-weight: 600;
}

.booking-card__meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.booking-meta__item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4b5563;
    font-size: 0.9rem;
}

.booking-meta__icon {
    font-size: 1rem;
}

.booking-card__action {
    display: block;
    text-align: center;
    padding: 0.75rem;
    background: #4f46e5;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.2s ease;
}

.booking-card__action:hover {
    background: #4338ca;
}

.booking-empty {
    text-align: center;
    grid-column: 1 / -1;
    color: #6b7280;
    padding: 2rem;
}

/* Pagination Styles */
.booking-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 2rem;
}

.booking-pagination__prev,
.booking-pagination__next {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.booking-pagination__prev:hover,
.booking-pagination__next:hover {
    background: #f3f4f6;
    border-color: #d1d5db;
}

.booking-pagination__icon {
    width: 20px;
    height: 20px;
    fill: #4b5563;
}

.booking-pagination__current {
    font-weight: 500;
    color: #1f2937;
}
</style>

<?php require_once 'agentFooter.view.php'; ?>