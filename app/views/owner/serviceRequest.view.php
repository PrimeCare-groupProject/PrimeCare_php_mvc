<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Service Request Form</h2>
            </div>
        </div>
    </div>
</div>

<?php if(isset($_SESSION['success_message'])): ?>
    <div style="color: green; text-align: center; margin: 10px 0;">
        <?= $_SESSION['success_message'] ?>
        <p id="countdown">Redirecting in 10 seconds...</p>
    </div>
    <script>
        let timeLeft = 10;
        const countdownElement = document.getElementById('countdown');
        
        const countdown = setInterval(() => {
            timeLeft--;
            countdownElement.textContent = `Redirecting in ${timeLeft} seconds...`;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                window.location.href = '<?= ROOT ?>/dashboard/propertylisting';
            }
        }, 1000);
    </script>
<?php endif; ?>

<?php if(isset($_SESSION['errors'])): ?>
    <div style="color: red; text-align: center; margin: 10px 0;">
        <?php foreach($_SESSION['errors'] as $error): ?>
            <?= $error ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="property-unit-container" style="margin-top: 20px;">
    <div class="left-container-property-unit">
        <div class="slider">
            <div class="slides">
                <div class="slide">
                    <?php if ($property_image): ?>
                        <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $property_image ?>" 
                             alt="Property Image">
                    <?php else: ?>
                        <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Property Image">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="property-details-section">
        <form method="POST" action="">
            <!-- Hidden fields -->
            <input type="hidden" name="service_type" id="service_type" value="<?= $_GET['type'] ?? '' ?>">
            <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">
            <input type="hidden" name="property_id" value="<?= $property_id ?? '' ?>">
            <input type="hidden" name="status" value="Pending">
            <input type="hidden" name="cost_per_hour" value="<?= $_GET['cost_per_hour'] ?? '' ?>">

            <label class="bold-text">Service Details</label>

            <div class="input-group">
                <span class="input-label">Service Type:</span>
                <span class="input-field"><?= $_GET['type'] ?? 'Not Selected' ?></span>
            </div>

            <div class="input-group">
                <span class="input-label">Request Date:</span>
                <span class="input-field"><?= date('Y-m-d') ?></span>
            </div>

            <!-- Temporary !!!! Dropdown to select property and hidden field for property ID -->
            <div class="input-group">
                <span class="input-label">Property Name:</span>
                <span class="input-field"><?= htmlspecialchars($_GET['property_name'] ?? 'Not Selected') ?></span>
                <input type="hidden" name="property_name" value="<?= htmlspecialchars($_GET['property_name'] ?? '') ?>">
                <input type="hidden" name="property_id" value="<?= htmlspecialchars($_GET['property_id'] ?? '') ?>">
            </div>

            <div class="input-group">
                <div class="input-group">
                    <span class="input-label">Cost Per Hour (LKR):</span>
                    <span class="input-field"><?= $_GET['cost_per_hour'] ?? '' ?></span>
                </div>
                <div class="input-group">
                    <span class="input-label">Estimated Hours:</span>
                    <span class="input-field"><?= $_GET['estimated_hours'] ?? '' ?></span>
                </div>
            </div>

            <span class="input-label">Service Description:</span>
            <textarea name="service_description" class="input-field textarea-padding" rows="4" required></textarea>

            <div class="flex-buttons-space-between">
                <button type="submit" class="primary-btn">Submit Request</button>
                <button type="button" class="secondary-btn" onclick="window.location.href='<?=ROOT?>/dashboard/repairlisting'">Cancel</button>
            </div>
        </form>
    </div>
</div>
