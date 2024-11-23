<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>occupied Properties</h2>
</div>

<div class="OC__property_container">
    <div class="OC__property_card">
        <div class="image-section">
            <a href="<?= ROOT ?>/property/propertyUnit/28"><img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Oceanview Property"></a>
        </div>
        <div class="info-section">
            <div class="info-header">Service Place Information</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value">Oceanview</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Property ID</span>
                    <span class="info-value">PID003</span>
                </div>
                <div class="info-row">
                    <span class="info-label">State/province</span>
                    <span class="info-value">Western</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Country</span>
                    <span class="info-value">Sri Lanka</span>
                </div>
                <div class="info-row" style="grid-column: 1 / -1;">
                    <span class="info-label">Address</span>
                    <span class="info-value">Oceanview, Seagate, Amarica</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lease start Date</span>
                    <span class="info-value">12/08/2023</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lease end Date</span>
                    <span class="info-value">12/08/2024</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rental (LKR)</span>
                    <span class="info-value">20000.00</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Status</span>
                    <span class="payment-status paid-status">Paid</span>
                </div>
                <div class="info-row" style="grid-column: 1 / -1;">
                    <span class="info-label">Next Payment Date</span>
                    <span class="info-value">12/09/2024</span>
                </div>
            </div>
            <div class="button-group">
                <button class="primary-btn green-solid">Proceed to pay</button>
                <button class="primary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/reportProblem/'">Repairs</button>
                <button class="primary-btn red-solid">Leave</button>
            </div>
        </div>
    </div>

    <div class="OC__property_card">
        <div class="image-section">
            <a href="<?= ROOT ?>/property/propertyUnit/28"><img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Oceanview Property"></a>
        </div>
        <div class="info-section">
            <div class="info-header">Service Place Information</div>
            <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Name</span>
                    <span class="info-value">Oceanview</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Property ID</span>
                    <span class="info-value">PID003</span>
                </div>
                <div class="info-row">
                    <span class="info-label">State/province</span>
                    <span class="info-value">Western</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Country</span>
                    <span class="info-value">Sri Lanka</span>
                </div>
                <div class="info-row" style="grid-column: 1 / -1;">
                    <span class="info-label">Address</span>
                    <span class="info-value">Oceanview, Seagate, Amarica</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lease start Date</span>
                    <span class="info-value">12/08/2023</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lease end Date</span>
                    <span class="info-value">12/08/2024</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Rental (LKR)</span>
                    <span class="info-value">20000.00</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Status</span>
                    <span class="payment-status unpaid-status">Pay</span>
                </div>
                <div class="info-row" style="grid-column: 1 / -1;">
                    <span class="info-label">Next Payment Date</span>
                    <span class="info-value">12/09/2024</span>
                </div>
            </div>
            <div class="button-group">
                <button class="primary-btn green-solid">Proceed to pay</button>
                <button class="primary-btn">Repairs</button>
                <button class="primary-btn red-solid">Leave</button>
            </div>
        </div>
    </div>
</div>



<?php require 'customerFooter.view.php' ?>