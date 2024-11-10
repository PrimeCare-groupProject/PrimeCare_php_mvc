<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>Pre Inspection</h2>
</div>

<div class="dashboard">
<div class="tab">
<div class="approval-container">
    <div class="approval-left-content">
        <h3>Approval Request 1</h3>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Property ID:</strong></span><span class="input-field-small">P1236</span>
            </div>
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Price(LKR):</strong></span><span class="input-field-small">200000</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Date:</strong></span><span class="input-field-small">2024/08/23</span>
            </div>
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Agent ID:</strong></span><span class="input-field-small">A3546</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Address:</strong></span><span class="input-field-small">No 90 , Colombo , Sri Lanka</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Description:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
    </div>
    <div class="approval-right-content">
        <button class="primary-btn">Accept</button>
        <button class="secondary-btn">Decline</button>
        <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="property" class="approval-right-content-img">
    </div>
</div>
</div>
</div>

<?php require_once 'agentFooter.view.php'; ?>
