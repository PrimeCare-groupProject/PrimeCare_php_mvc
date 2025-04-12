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
<div class="listing-the-property">
    <div class="property-listing-grid1">
        <?php if (!empty($bookings)): ?>
            <?php foreach ($bookings as $booking): ?>
                <div class="compact-booking-card">
                    <img class="compact-card__img" src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Property">
                    <div class="compact-card__content">
                        <h4 class="compact-card__title"><?= $booking->name?></h4>
                
                        <div class="compact-card__details">
                            <div class="compact-detail">
                                <span class="compact-icon">💰</span>
                                <span><?= $booking->price?> LKR</span>
                            </div>
                    
                            <div class="compact-detail">
                                <span class="compact-icon">📅</span>
                                <span><?= $booking->renting_period?> monts</span>
                            </div>
                        </div>
                        <a href="<?=ROOT?>/dashboard/bookings/bookingaccept/<?= $booking->booking_id ?>">
                            <button class="compact-card__btn">Update</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?> 
        <?php else: ?>
            <p>No Booking found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Pagination Buttons -->
<div class="pagination">
        <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
</div>

<style>
 /* Booking Cards Grid */
/* Compact Booking Cards Grid */
.property-listing-grid1 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    padding: 1rem;
    max-width: 1200px;
    margin: 0 auto;
}

/* Compact Card Styling */
.compact-booking-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.compact-booking-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.compact-card__img {
    width: 100%;
    height: 120px;
    object-fit: cover;
}

.compact-card__content {
    padding: 1rem;
}

.compact-card__title {
    font-size: 1.1rem;
    color: #333;
    margin: 0 0 0.8rem 0;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.compact-card__details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.compact-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #555;
}

.compact-icon {
    font-size: 1rem;
}

.compact-card__btn {
    width: 100%;
    padding: 0.5rem;
    background: #4a6bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: background 0.2s;
}

.compact-card__btn:hover {
    background: #3a5bef;
}

/* Responsive Adjustments */
@media (max-width: 900px) {
    .property-listing-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .property-listing-grid {
        grid-template-columns: 1fr;
    }
    
    .compact-booking-card {
        max-width: 300px;
        margin: 0 auto;
    }
}
</style>

<?php require_once 'agentFooter.view.php'; ?>