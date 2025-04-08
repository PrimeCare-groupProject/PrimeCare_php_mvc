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

<!-- Success Modal Popup -->
<?php if(isset($_SESSION['success_message'])): ?>
<div class="success-modal-overlay" id="successModal">
    <div class="success-modal-content">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <h2>Success!</h2>
        <p><?= $_SESSION['success_message'] ?></p>
        <p id="countdown" class="countdown">Redirecting in 10 seconds...</p>
        <button class="modal-close-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting'">Continue</button>
    </div>
</div>

<style>
    .success-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: fadeIn 0.3s ease-out;
    }
    
    .success-modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        width: 90%;
        max-width: 400px;
        text-align: center;
        animation: slideUp 0.4s ease-out;
    }
    
    .success-icon {
        margin-bottom: 20px;
    }
    
    .success-modal-content h2 {
        color: #4CAF50;
        margin-bottom: 15px;
        font-size: 24px;
    }
    
    .success-modal-content p {
        margin-bottom: 20px;
        color: #555;
        font-size: 16px;
    }
    
    .countdown {
        font-size: 14px;
        color: #777;
        margin-top: 5px;
    }
    
    .modal-close-btn {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.2s;
    }
    
    .modal-close-btn:hover {
        background-color: #3e8e41;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideUp {
        from { transform: translateY(50px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

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
