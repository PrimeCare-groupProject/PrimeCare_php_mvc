<?php require_once 'managerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/propertymanagement/viewproperty/<?= $property->property_id ?>'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>Delete Property (Admin Review): <span style="color: var(--red-color);"><?= $property->name ?></span></h2>
</div>

<div class="PD__delete-property-container">
    <div class="PD__delete-notice-box">
        <div class="center-div-h2">
            <h2>Administrative Warning Before Deletion</h2>
        </div>

        <p><strong>1. Double-Check Property Identity:</strong> Ensure this is the correct listing intended for removal. Deletion affects system-wide records and reporting.</p>

        <p><strong>2. Data Audit:</strong> Backup any relevant documents, communications, images, or inspection reports associated with this listing before deletion.</p>

        <p><strong>3. Operational Dependencies:</strong> Confirm that no ongoing agent activities, inspections, service requests, or tenant leases are linked to this property.</p>

        <hr>

        <h4>⚖️ Managerial Compliance Checklist</h4>

        <ul>
            <li><strong>Permanent Action:</strong> Deleted properties cannot be recovered from the system.</li>
            <li><strong>Linked Contracts:</strong> Ensure termination or closure of any active contracts or payments related to this property.</li>
            <li><strong>Audit Trail:</strong> A deletion log will be maintained for transparency and accountability.</li>
            <li><strong>Approval Required:</strong> Property deletion may require upper-management verification if assigned to another agent.</li>
            <li><strong>Legal Standing:</strong> Confirm all disputes, liabilities, or obligations are cleared before deletion.</li>
        </ul>

        <div class="PD__confirm-buttons">
            <button class="primary-btn red-solid" onclick="window.location.href='<?= ROOT ?>/dashboard/managementhome/propertymanagement/propertyDeleteConfirmation/<?= $property->property_id ?>'">Confirm Delete</button>
            <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/managementhome/propertymanagement/viewproperty/<?= $property->property_id ?>'">Cancel</button>
        </div>
    </div>
</div>



<?php require_once 'managerFooter.view.php'; ?>