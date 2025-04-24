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

<!-- Split container with image on left and form on right -->
<div class="split-container">
    <!-- Left side: Property Image -->
    <div class="image-side" style="background-image: url('<?= ROOT ?>/assets/images/uploads/property_images/<?= $property_image ? $property_image : 'listing_alt.jpg' ?>');">
        <div class="service-type-badge">
            <span><?= htmlspecialchars($_GET['type'] ?? 'Service Request') ?></span>
        </div>
        <div class="property-name-overlay">
            <h3><?= htmlspecialchars($_GET['property_name'] ?? 'Property') ?></h3>
        </div>
    </div>
    
    <!-- Right side: Service Request Form -->
    <div class="form-side">
        <form method="POST" action="">
            <!-- Hidden fields -->
            <input type="hidden" name="service_type" id="service_type" value="<?= htmlspecialchars($_GET['type'] ?? '') ?>">
            <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">
            <input type="hidden" name="property_id" value="<?= htmlspecialchars($_GET['property_id'] ?? '') ?>">
            <input type="hidden" name="status" value="Pending">
            <input type="hidden" name="cost_per_hour" value="<?= htmlspecialchars($_GET['cost_per_hour'] ?? '') ?>">
            <input type="hidden" name="requested_person_id" value="<?= $_SESSION['user']->pid ?? '' ?>">
            <input type="hidden" name="property_name" value="<?= htmlspecialchars($_GET['property_name'] ?? '') ?>">

            <div class="form-header">
                <h3>Service Request Form</h3>
                <p>Please provide the details for your service request</p>
            </div>

            <div class="service-info-container">
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
                </div>

                <div class="input-group">
                    <span class="input-label">Cost Per Hour (LKR):</span>
                    <span class="input-field"><?= htmlspecialchars($_GET['cost_per_hour'] ?? '') ?></span>
                </div>
            </div>

            <div class="description-container">
                <label for="service_description" class="bold-text">Service Description:</label>
                <textarea name="service_description" id="service_description" class="input-field textarea-padding" rows="5" placeholder="Please describe your service request in detail..." required></textarea>
            </div>

            <div class="flex-buttons-space-between">
                <button type="submit" class="primary-btn">Submit Request</button>
                <button type="button" class="secondary-btn" onclick="window.location.href='<?=ROOT?>/dashboard/requestServiceOccupied'">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
/* Main Container */
.split-container {
    display: flex;
    max-width: 1200px;
    margin: 30px auto;
    min-height: 680px;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
}

/* Left Side - Image */
.image-side {
    flex: 1;
    position: relative;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    min-width: 40%;
}

.service-type-badge {
    position: absolute;
    top: 20px;
    left: 20px;
    padding: 8px 16px;
    background: linear-gradient(135deg, #ffcc00, #ffb700);
    color: #333;
    font-weight: 600;
    font-size: 16px;
    border-radius: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    z-index: 2;
}

.property-name-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 25px 20px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0));
}

.property-name-overlay h3 {
    color: white;
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
}

/* Right Side - Form */
.form-side {
    flex: 1.2;
    padding: 30px;
    background: white;
    overflow-y: auto;
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
    margin: 0;
}

.bold-text {
    font-weight: 600;
    font-size: 16px;
    color: #333;
    display: block;
    margin-bottom: 15px;
}

/* Service Info Section */
.service-info-container {
    margin-bottom: 25px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 10px;
}

.input-group {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding: 12px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.input-group:last-child {
    margin-bottom: 0;
}

.input-label {
    font-weight: 500;
    color: #555;
    width: 140px;
}

.input-field {
    flex: 1;
    padding: 10px 12px;
    border-radius: 6px;
    border: 1px solid #ddd;
    background: white;
    color: #333;
    font-weight: 500;
    min-width: 100px;
}

/* Description Section */
.description-container {
    margin-bottom: 30px;
    padding: 20px;
    background: #f9f9f9;
    border-radius: 10px;
}

textarea.input-field {
    width: 100%;
    resize: vertical;
    min-height: 150px;
    margin-top: 10px;
    font-size: 15px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

textarea.input-field:focus {
    outline: none;
    border-color: #ffcc00;
    box-shadow: 0 0 0 3px rgba(255, 204, 0, 0.15);
}

.textarea-padding {
    padding: 15px;
}

/* Button Section */
.flex-buttons-space-between {
    display: flex;
    justify-content: space-between;
    gap: 20px;
}

.primary-btn {
    padding: 14px 28px;
    background: linear-gradient(135deg, #ffcc00, #ffb700);
    color: #333;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    max-width: 200px;
    box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3);
}

.primary-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(255, 204, 0, 0.4);
}

.secondary-btn {
    padding: 14px 28px;
    background: white;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    max-width: 200px;
}

.secondary-btn:hover {
    background: #f5f5f5;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Success Modal Styling */
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

/* Responsive Design */
@media (max-width: 900px) {
    .split-container {
        flex-direction: column;
        margin: 20px 15px;
    }
    
    .image-side {
        min-height: 280px;
    }
    
    .form-side {
        padding: 20px;
    }
    
    .flex-buttons-space-between {
        flex-direction: column;
    }
    
    .primary-btn, .secondary-btn {
        max-width: 100%;
    }
    
    .input-group {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .input-label {
        margin-bottom: 8px;
        width: 100%;
    }
    
    .input-field {
        width: 100%;
    }
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

<?php require 'customerFooter.view.php' ?>