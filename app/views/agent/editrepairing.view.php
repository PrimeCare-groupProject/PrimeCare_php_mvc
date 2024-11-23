<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/repairings'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Edit Repair</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/update" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <!-- Hidden field for service_id -->
            <input type="hidden" name="service_id" value="<?= isset($service1) ? htmlspecialchars($service1->service_id) : '' ?>">

            <label class="input-label">Repair Name</label>
            <!-- Populate the input field with the actual name -->
            <input type="text" name="name" value="<?= isset($service1) ? htmlspecialchars($service1->name) : '' ?>" class="input-field" required>

            <label class="input-label">Cost Of Hour</label>
            <!-- Populate the cost per hour field (assuming $service1->cost_per_hour exists) -->
            <input type="text" name="cost_per_hour" value="<?= isset($service1) ? htmlspecialchars($service1->cost_per_hour) : '' ?>" class="input-field" required>
            
            <label class="input-label">Description About The Repair</label>
            <!-- Populate the description textarea with the actual description -->
            <textarea name="description" class="input-field1" required><?= isset($service1) ? htmlspecialchars($service1->description) : '' ?></textarea>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Save</button>
            </div>
        </div>
    </div>
</form>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message">
        <?= $_SESSION['flash_message']; ?>
        <?php unset($_SESSION['flash_message']); ?> <!-- Clear the message after displaying -->
    </div>
<?php endif; ?>

<?php require_once 'agentFooter.view.php'; ?>