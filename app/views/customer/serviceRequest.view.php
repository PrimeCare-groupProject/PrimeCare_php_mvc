<?php require 'customerHeader.view.php' ?>

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
        <button class="modal-close-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/requestServiceOccupied'">Continue</button>
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
            window.location.href = '<?= ROOT ?>/dashboard/requestServiceOccupied';
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
            <input type="hidden" name="service_type" id="service_type" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>">
            <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">
            <input type="hidden" name="property_id" value="<?= htmlspecialchars($_GET['property_id'] ?? '') ?>">
            <input type="hidden" name="status" value="Pending">
            <input type="hidden" name="cost_per_hour" value="<?= htmlspecialchars($_GET['cost_per_hour'] ?? '') ?>">
            <input type="hidden" name="requested_person_id" value="<?= $_SESSION['user']->pid ?? '' ?>">

            <div class="form-header">
                <h3>Request Details</h3>
                <p>Please provide the following information for your service request</p>
            </div>

            <label class="bold-text">Service Details</label>

            <div class="input-group">
                <span class="input-label">Service Type:</span>
                <span class="input-field"><?= htmlspecialchars($_GET['type'] ?? 'Not Selected') ?></span>
            </div>

            <div class="input-group">
                <span class="input-label">Request Date:</span>
                <span class="input-field"><?= date('Y-m-d') ?></span>
            </div>

            <div class="input-group">
                <span class="input-label">Property Name:</span>
                <span class="input-field"><?= htmlspecialchars($_GET['property_name'] ?? 'Not Selected') ?></span>
                <input type="hidden" name="property_name" value="<?= htmlspecialchars($_GET['property_name'] ?? '') ?>">
            </div>

            <div class="input-group">
                <span class="input-label">Cost Per Hour (LKR):</span>
                <span class="input-field"><?= htmlspecialchars($_GET['cost_per_hour'] ?? '') ?></span>
            </div>
            
            <!-- <div class="input-group">
                <span class="input-label">Estimated Hours:</span>
                <select name="estimated_hours" class="input-field" required>
                    <option value="">Select estimated hours</option>
                    <option value="1">1 hour</option>
                    <option value="2">2 hours</option>
                    <option value="3">3 hours</option>
                    <option value="4">4 hours</option>
                    <option value="5">5+ hours (to be determined)</option>
                </select>
            </div> -->

            <span class="input-label">Service Description:</span>
            <textarea name="service_description" class="input-field textarea-padding" rows="4" placeholder="Please describe your service request in detail..." required></textarea>

            <div class="flex-buttons-space-between">
                <button type="submit" class="primary-btn">Submit Request</button>
                <button type="button" class="secondary-btn" onclick="window.location.href='<?=ROOT?>/dashboard/requestServiceOccupied'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Form Styling */
.property-unit-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.left-container-property-unit {
    flex: 1;
    min-width: 300px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.slider {
    width: 100%;
    height: 100%;
    position: relative;
}

.slides {
    width: 100%;
    height: 100%;
}

.slide {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.slide img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 8px;
}

.property-details-section {
    flex: 1.5;
    min-width: 300px;
    padding: 15px;
}

.form-header {
    margin-bottom: 25px;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}

.form-header h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 8px;
}

.form-header p {
    color: #666;
    font-size: 14px;
}

.bold-text {
    font-weight: 600;
    font-size: 16px;
    color: #333;
    display: block;
    margin: 15px 0 10px;
}

.input-group {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 15px;
    padding: 12px;
    background: #f9f9f9;
    border-radius: 8px;
}

.input-label {
    min-width: 150px;
    font-weight: 500;
    color: #555;
    margin-bottom: 5px;
}

.input-field {
    flex: 1;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    background: white;
    color: #333;
    font-weight: 500;
}

textarea.input-field {
    width: 100%;
    margin-top: 8px;
    resize: vertical;
}

select.input-field {
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
    padding-right: 30px;
}

.textarea-padding {
    padding: 12px;
}

.flex-buttons-space-between {
    display: flex;
    justify-content: space-between;
    margin-top: 25px;
}

.primary-btn {
    padding: 12px 24px;
    background: linear-gradient(135deg, #ffcc00, #ffb700);
    color: #333;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    flex: 1;
    max-width: 200px;
    box-shadow: 0 3px 8px rgba(255, 204, 0, 0.3);
}

.primary-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
}

.secondary-btn {
    padding: 12px 24px;
    background: white;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    flex: 1;
    max-width: 200px;
}

.secondary-btn:hover {
    background: #f5f5f5;
}

@media (max-width: 768px) {
    .flex-buttons-space-between {
        flex-direction: column;
        gap: 15px;
    }
    
    .primary-btn, .secondary-btn {
        max-width: 100%;
    }
}
</style>

<?php require 'customerFooter.view.php' ?>