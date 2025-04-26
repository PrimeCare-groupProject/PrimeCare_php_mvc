<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Bookings Request</h2>
    <div class="flex-bar" style="gap: 0px;">	
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container" style="margin-left: 10px;">
            <a href='<?= ROOT ?>/dashboard/bookings/history'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/assignment.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">History</span>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/bookings/bookingremoval'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/delete.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Approve Removal</span>
        </div>
    </div>
</div>

<div class="OC__property_container">
    <?php if (!empty($orders) && !is_bool($orders)): ?>
        <?php foreach ($orders as $order): ?>
            <?php
                // Get the first image from property_images, or use default if not set
                $images = isset($order->property_images) ? explode(',', $order->property_images) : [];
                $firstImage = !empty($images[0]) ? trim($images[0]) : '';
                $imgSrc = get_img($firstImage, 'property');
            ?>
            <div class="OC__property_card">
                <div class="image-section">
                    <a href="<?= ROOT ?>/property/propertyUnit/<?= esc($order->property_id) ?>">
                        <img src="<?= $imgSrc ?>" alt="Property">
                    </a>
                </div>
                <div class="info-section">
                    <div class="info-header">Booking Details</div>
                    <div class="info-grid" style="gap: 10px;">
                        <div class="info-row">
                            <span class="info-label">Booking ID</span>
                            <span class="info-value"><?= esc($order->booking_id) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Property ID</span>
                            <span class="info-value"><?= esc($order->property_id) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Owner ID</span>
                            <span class="info-value"><?= esc($order->person_id) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Agent ID</span>
                            <span class="info-value"><?= esc($order->agent_id ?? '-') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Start Date</span>
                            <span class="info-value"><?= esc($order->start_date) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">End Date</span>
                            <span class="info-value"><?= esc($order->end_date ?? '-') ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Duration</span>
                            <span class="info-value"><?= esc($order->duration) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Type</span>
                            <span class="info-value"><?= esc($order->rental_period) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Rental Price (LKR)</span>
                            <span class="info-value"><?= esc($order->rental_price) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Total Amount (LKR)</span>
                            <span class="info-value"><?= esc($order->total_amount) ?></span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Payment Status</span>
                            <span class="payment-status <?= strtolower($order->payment_status) === 'paid' ? 'paid-status' : 'unpaid-status' ?>">
                                <?= esc($order->payment_status) ?>
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Booking Status</span>
                            <span class="info-value"><?= esc($order->booking_status) ?></span>
                        </div>
                    </div>
                    <div class="button-group" style="justify-content: right;">
                        <?php if (
                            strtolower($order->booking_status) === 'cancelled' ||
                            strtolower($order->booking_status) === 'completed'
                        ): ?>
                            <!-- No buttons shown if cancelled or completed -->
                        <?php elseif (strtolower($order->booking_status) === 'pending'): ?>
                            <form method="POST" action="<?= ROOT ?>/dashboard/confirmBooking/<?= esc($order->booking_id) ?>" style="display:inline;">
                                <button type="submit" class="primary-btn green-solid">Confirm</button>
                            </form>
                            <form method="POST" action="<?= ROOT ?>/dashboard/cancelBooking/<?= esc($order->booking_id) ?>" style="display:inline;">
                                <button type="submit" class="primary-btn red-solid">Cancel</button>
                            </form>
                        <?php elseif (strtolower($order->booking_status) === 'cancel requested'): ?>
                            <form method="POST" action="<?= ROOT ?>/dashboard/continueBooking/<?= esc($order->booking_id) ?>" style="display:inline;">
                                <button type="submit" class="primary-btn green-solid">Continue Booking</button>
                            </form>
                            <form method="POST" action="<?= ROOT ?>/dashboard/cancelBooking/<?= esc($order->booking_id) ?>" style="display:inline;">
                                <button type="submit" class="primary-btn red-solid">Cancel</button>
                            </form>
                        <?php elseif (strtolower($order->booking_status) === 'confirmed'): ?>
                            <form method="POST" action="<?= ROOT ?>/dashboard/cancelBooking/<?= esc($order->booking_id) ?>" style="display:inline;">
                                <button type="submit" class="primary-btn red-solid">Cancel</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="booking-empty" style="text-align:center; padding: 15px">No Booking Request found.</p>
    <?php endif; ?>
</div>

<!-- Cancel Confirmation Modal -->
<div id="cancelModal" style="display:none; position:fixed; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.2); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#fff; padding:24px 32px; border-radius:10px; box-shadow:0 2px 16px rgba(0,0,0,0.15); min-width:260px; text-align:center;">
        <p style="margin-bottom:18px;">Are you sure you want to leave/cancel this booking?</p>
        <form id="cancelForm" method="POST" style="display:inline;">
            <button type="submit" class="primary-btn red-solid" style="margin-right:10px;">Yes, Cancel</button>
            <button type="button" class="btn green" onclick="closeCancelModal()">No</button>
        </form>
    </div>
</div>

<script>
function openCancelModal(actionUrl) {
    document.getElementById('cancelModal').style.display = 'flex';
    document.getElementById('cancelForm').action = actionUrl;
}
function closeCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
}
</script>


<?php require_once 'agentFooter.view.php'; ?>