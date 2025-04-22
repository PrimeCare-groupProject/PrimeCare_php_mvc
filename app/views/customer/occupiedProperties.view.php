<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Occupied Properties</h2>
</div>

<div class="OC__property_container">
    <?php if (!empty($orders)): ?>
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
                    <div class="button-group">
                        <?php if (strtolower($order->booking_status) === 'cancelled'): ?>
                            <button class="primary-btn red-solid" disabled>Booking Cancelled</button>
                        <?php else: ?>
                            <?php if (strtolower($order->payment_status) !== 'paid' && strtolower($order->booking_status) === 'confirmed'): ?>
                                <form method="POST" action="https://sandbox.payhere.lk/pay/checkout" style="display:inline;">
                                    <input type="hidden" name="merchant_id" value="<?= MERCHANT_ID ?>">
                                    <input type="hidden" name="return_url" value="<?= ROOT ?>/dashboard/markAsPaid/<?= esc($order->booking_id) ?>">
                                    <input type="hidden" name="cancel_url" value="<?= ROOT ?>/dashboard/occupiedProperties">
                                    <input type="hidden" name="notify_url" value="<?= ROOT ?>/dashboard/payhereNotify">
                                    <input type="hidden" name="order_id" value="<?= esc((string)$order->booking_id) ?>">
                                    <input type="hidden" name="items" value="Property Booking #<?= esc((string)$order->booking_id) ?>">
                                    <input type="hidden" name="currency" value="LKR">
                                    <input type="hidden" name="amount" value="<?= esc((string)number_format((float)$order->total_amount, 2, '.', '')) ?>">
                                    <input type="hidden" name="first_name" value="<?= esc((string)($_SESSION['user']->first_name ?? 'User')) ?>">
                                    <input type="hidden" name="last_name" value="<?= esc((string)($_SESSION['user']->last_name ?? '')) ?>">
                                    <input type="hidden" name="email" value="<?= esc((string)($_SESSION['user']->email ?? 'test@example.com')) ?>">
                                    <input type="hidden" name="phone" value="<?= esc((string)($_SESSION['user']->phone ?? '0700000000')) ?>">
                                    <input type="hidden" name="address" value="N/A">
                                    <input type="hidden" name="city" value="N/A">
                                    <input type="hidden" name="country" value="Sri Lanka">
                                    <input type="hidden" name="hash" value="<?= esc($order->payhere_hash ?? '') ?>">
                                    <button type="submit" class="primary-btn green-solid">Proceed to pay</button>
                                </form>
                            <?php endif; ?>
                            <button type="button" class="primary-btn red-solid"
                                onclick="openCancelModal('<?= ROOT ?>/dashboard/cancelBooking/<?= esc($order->booking_id) ?>')">
                                Cancel
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center; font-size:1.2em; margin:40px 0;">No occupied properties found.</p>
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

<?php require 'customerFooter.view.php' ?>