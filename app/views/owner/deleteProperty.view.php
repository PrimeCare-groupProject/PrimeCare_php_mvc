<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/propertylisting/propertyunitowner/<?= $property->property_id ?>'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>Delete Property : <span style="color: var(--red-color);"><?= $property->name ?></span></h2>
</div>

<div class="PD__delete-property-container">
    <div class="PD__delete-notice-box">
        <div class="center-div-h2">
            <h2>Important Notice Before Removing This Property</h2>
        </div>

        <p><strong>1. Review Your Listing:</strong> Please confirm this is the correct property. Once removed, it will no longer be visible to potential tenants.</p>

        <p><strong>2. Backup Information:</strong> Download or save any images, messages, or documents related to this listing.</p>

        <p><strong>3. Pending Actions:</strong> Ensure there are no active tenants, service requests, or pending rental agreements.</p>

        <hr>

        <h4>📜 Regulations & Terms</h4>

        <ul>
            <li><strong>Irreversible Action:</strong> Removal is permanent. You will lose access to this listing's data.</li>
            <li><strong>Active Contracts:</strong> Settle all tenant leases before deletion.</li>
            <li><strong>Outstanding Payments:</strong> Ensure no unpaid dues exist.</li>
            <li><strong>Reactivation:</strong> Re-listing will require a new approval process.</li>
            <li><strong>Legal Responsibility:</strong> You confirm you are authorized to remove this property.</li>
        </ul>

        <div class="PD__confirm-buttons">
            <button class="primary-btn red-solid" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/deleteRequest/<?= $property->property_id ?>'">Remove Property</button>
            <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/propertyunitowner/<?= $property->property_id ?>'">Cancel</button>
        </div>
    </div>
</div>

<?php require_once 'ownerFooter.view.php'; ?>
