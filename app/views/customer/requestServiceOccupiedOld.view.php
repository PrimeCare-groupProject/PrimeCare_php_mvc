<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/dashboard"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
    <h2>Request Repair Service</h2>
</div>

<div class="service-request-container">
    <!-- Display Errors -->
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <p><i class="fas fa-exclamation-triangle"></i> Please fix the following issues:</p>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <?php if (!empty($error)): // Ensure error message is not empty ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($activeBookings)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-home"></i>
            </div>
            <h3>No Active Rentals Found</h3>
            <p>You don't have any active rental properties at the moment to request service for.</p>
            <a href="<?= ROOT ?>/customer/search" class="primary-btn">
                <i class="fas fa-search"></i>
                Browse Properties
            </a>
        </div>
    <?php else: ?>
        <div class="form-card">
            <!-- Progress Tracker -->
            <div class="progress-tracker-container">
                <div class="progress-tracker">
                    <div class="step completed">
                        <div class="step-circle">
                            <span class="step-icon"><i class="fas fa-check"></i></span>
                            <span class="step-pulse"></span>
                        </div>
                        <div class="step-label">Select Property</div>
                    </div>
                    <div class="progress-line">
                        <span class="progress-line-inner active"></span>
                    </div>
                    <div class="step active">
                        <div class="step-circle">
                            <span class="step-number">2</span>
                            <span class="step-pulse"></span>
                        </div>
                        <div class="step-label">Service Details</div>
                    </div>
                    <div class="progress-line">
                        <span class="progress-line-inner"></span>
                    </div>
                    <div class="step">
                        <div class="step-circle">
                            <span class="step-number">3</span>
                        </div>
                        <div class="step-label">Confirmation</div>
                    </div>
                </div>
            </div>

            <form action="<?= ROOT ?>/customer/requestServiceOccupied" method="POST" class="service-request-form">
                <!-- Property Selection Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-home" style="color:#f1c40f"></i> Property Selection</h3>
                    <div class="form-group">
                        <label for="property_id" class="form-label">Choose your property</label>
                        <div class="enhanced-select-wrapper">
                            <div class="selected-property <?= isset($errors['property_id']) ? 'has-error' : '' ?>" id="selected-property-display">
                                <div class="placeholder">
                                    <i class="fas fa-home"></i>
                                    <span>Select a property</span>
                                </div>
                            </div>
                            <div class="property-dropdown" id="property-dropdown">
                                <?php if(count($activeBookings) > 0): ?>
                                    <?php foreach($activeBookings as $booking): ?>
                                        <div class="property-option" data-value="<?= $booking->property_id ?>">
                                        <div class="property-image">
                                                    <?php
                                                        // Default image path
                                                        $defaultImagePath = ROOT . "/assets/images/properties/default-property.jpg";
                                                        $imageUrl = $defaultImagePath;

                                                        // Check if property_images data exists for the booking
                                                        if (isset($booking->property_images)) {
                                                            $images = $booking->property_images;
                                                            // If property_images is a JSON string, decode it first
                                                            if (is_string($images)) {
                                                                $images = json_decode($images);
                                                            }
                                                            // Check if we have an array with at least one image filename
                                                            if (is_array($images) && !empty($images[0])) {

                                                                $imagePath = $images[0]; 

                                                                $imageUrl = ROOT . "/assets/images/uploads/property_images/" . $imagePath;
                                                            }
                                                        }
                                                    ?>
                                                    <img src="<?= $imageUrl ?>" alt="Property image"
                                                        onerror="this.onerror=null; this.src='<?= $defaultImagePath ?>';">
                                                </div>
                                            <div class="property-details">
                                                <h4><?= htmlspecialchars($booking->property_name) ?></h4>
                                                <p class="property-address"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($booking->property_address ?? 'No address') ?></p>
                                                <p class="property-dates">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <?= date('M d, Y', strtotime($booking->start_date)) ?> -
                                                    <?= date('M d, Y', strtotime($booking->end_date)) ?>
                                                </p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="no-properties">
                                        <i class="fas fa-exclamation-circle"></i>
                                        No active properties found
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Hidden actual select field -->
                            <select name="property_id" id="property_id" required class="hidden-select">
                                <option value="">-- Select a Property --</option>
                                <?php foreach ($activeBookings as $booking): ?>
                                    <option value="<?= $booking->property_id ?>" <?= (isset($old['property_id']) && $old['property_id'] == $booking->property_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($booking->property_name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (isset($errors['property_id'])): ?>
                            <div class="input-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errors['property_id']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Service Information Section -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-screwdriver-wrench" style="color:#f1c40f"></i> Service Information</h3>

                    <div class="form-group">
                        <label for="service_date">Date of service needed</label>
                        <div class="input-wrapper">
                            <input
                                type="date"
                                name="service_date"
                                id="service_date"
                                value="<?= htmlspecialchars($old['service_date'] ?? '') ?>"
                                required
                                min="<?= date('Y-m-d') ?>"
                                class="<?= isset($errors['service_date']) ? 'has-error' : '' ?>"
                            >
                            <i class="fas fa-calendar-alt input-icon"></i>
                        </div>
                        <?php if (isset($errors['service_date'])): ?>
                            <div class="input-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errors['service_date']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="service_type">Type of service needed</label>
                        <div class="enhanced-service-wrapper">
                            <div class="selected-service <?= isset($errors['service_type']) ? 'has-error' : '' ?>" id="selected-service-display">
                                <div class="placeholder">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                    <span>Select a service type</span>
                                </div>
                            </div>
                            <div class="service-dropdown" id="service-dropdown">
                                <?php if (!empty($serviceTypes)): ?>
                                    <div class="service-grid">
                                        <?php foreach ($serviceTypes as $service): ?>
                                            <div class="service-card"
                                                 data-value="<?= $service->service_id ?>"
                                                 data-cost="<?= $service->cost_per_hour ?>"
                                                 data-name="<?= htmlspecialchars($service->name) ?>">
                                                <div class="service-image">
                                                    <?php
                                                        $defaultServiceImagePath = ROOT . "/assets/images/uploads/services_images/default-service.jpg";
                                                        // Assuming service_img path is relative like 'assets/images/uploads/services_images/image.jpg'
                                                        $serviceImageUrl = (!empty($service->service_img) && file_exists(ROOTPATH . 'public/' . $service->service_img))
                                                            ? ROOT . "/" . $service->service_img
                                                            : $defaultServiceImagePath;
                                                    ?>
                                                    <img src="<?= $serviceImageUrl ?>" alt="<?= htmlspecialchars($service->name) ?>"
                                                         onerror="this.onerror=null; this.src='<?= $defaultServiceImagePath ?>';">
                                                </div>
                                                <div class="service-info">
                                                    <h4><?= htmlspecialchars($service->name) ?></h4>
                                                    <div class="service-cost">
                                                        <i class="fas fa-tag"></i> LKR <?= number_format($service->cost_per_hour, 2) ?>/hour
                                                    </div>
                                                    <div class="service-description">
                                                        <?= htmlspecialchars(substr($service->description ?? '', 0, 80)) ?>
                                                        <?= strlen($service->description ?? '') > 80 ? '...' : '' ?>
                                                    </div>
                                                    <div class="service-select-btn">
                                                        <span>Select</span>
                                                        <i class="fas fa-arrow-right"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="no-services">
                                        <i class="fas fa-exclamation-circle"></i>
                                        No services available
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Hidden fields for form submission -->
                            <input type="hidden" name="service_type" id="service_type" value="<?= htmlspecialchars($old['service_type'] ?? '') ?>" required>
                            <input type="hidden" name="cost_per_hour" id="cost_per_hour" value="<?= htmlspecialchars($old['cost_per_hour'] ?? '') ?>">
                            <input type="hidden" name="service_name" id="service_name" value="<?= htmlspecialchars($old['service_name'] ?? '') ?>">
                        </div>
                        <?php if (isset($errors['service_type'])): ?>
                            <div class="input-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errors['service_type']) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="service_description">Description of the issue</label>
                        <textarea
                            name="service_description"
                            id="service_description"
                            rows="5"
                            placeholder="Please describe the issue in detail. Include any relevant information that would help us understand the problem."
                            required
                            class="<?= isset($errors['service_description']) ? 'has-error' : '' ?>"
                        ><?= htmlspecialchars($old['service_description'] ?? '') ?></textarea>
                        <?php if (isset($errors['service_description'])): ?>
                            <div class="input-error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($errors['service_description']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <a href="<?= ROOT ?>/dashboard" class="secondary-btn">
                        <i class="fas fa-arrow-left"></i>
                        Back to Dashboard
                    </a>
                    <button type="submit" class="primary-btn">
                        <i class="fas fa-paper-plane"></i>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- Success Modal Popup -->
<?php if(!empty($success_message)): ?>
<div class="success-modal-overlay" id="successModal">
    <div class="success-modal-content">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#4CAF50" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <h2>Success!</h2>
        <p><?= htmlspecialchars($success_message) ?></p>
        <p id="countdown" class="countdown">Redirecting in 5 seconds...</p>
        <button class="modal-close-btn" onclick="redirectToDashboard()">Continue to Dashboard</button>
    </div>
</div>

<script>
    let timeLeft = 5;
    const countdownElement = document.getElementById('countdown');
    let countdownInterval;

    function redirectToDashboard() {
        clearInterval(countdownInterval); // Stop countdown if button is clicked
        window.location.href = '<?= ROOT ?>/dashboard'; // Redirect immediately
    }

    countdownInterval = setInterval(() => {
        timeLeft--;
        if (countdownElement) {
            countdownElement.textContent = `Redirecting in ${timeLeft} seconds...`;
        }

        if (timeLeft <= 0) {
            clearInterval(countdownInterval);
            window.location.href = '<?= ROOT ?>/dashboard'; // Redirect after countdown
        }
    }, 1000);

    // Optional: Close modal if clicking outside
    const successModalOverlay = document.getElementById('successModal');
    if (successModalOverlay) {
        successModalOverlay.addEventListener('click', function(event) {
            if (event.target === successModalOverlay) {
                redirectToDashboard();
            }
        });
    }
</script>
<?php endif; ?>


<style>
:root {
    --primary-color: #f1c40f; /* Yellow */
    --primary-dark: #f39c12; /* Orange */
    --primary-light: #f7d774;
    --secondary-color: #2c3e50; /* Dark Blue/Grey */
    --text-dark: #222;
    --text-light: #555; /* Slightly darker for better readability */
    --text-muted: #888;
    --border-color: #e0e0e0;
    --background-light: #fdfdfd; /* Slightly off-white */
    --background-highlight: #fffbeb; /* Very light yellow */
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.08); /* Softer shadow */
    --error-color: #c0392b; /* Darker Red */
    --error-bg: #fdecea;
    --success-color: #27ae60; /* Green */
    --border-radius: 8px;

    /* Progress tracker colors */
    --progress-inactive: #e3e8ec;
    --progress-active: var(--primary-color); /* Use primary yellow */
    --progress-completed: var(--success-color); /* Use success green */
    --progress-line-bg: #e3e8ec;
    --progress-pulse: rgba(241, 196, 15, 0.3); /* Yellow pulse */
}

/* General Styles */
body {
    background-color: var(--background-light);
}

.navigate-icons {
    width: 36px;
    height: 36px;
    transition: transform 0.2s ease;
}
.navigate-icons:hover {
    transform: scale(1.1);
}

/* Error Messages */
.error-messages {
    background: var(--error-bg);
    color: var(--error-color);
    border: 1px solid #f5c6cb; /* Lighter red border */
    border-left: 5px solid var(--error-color);
    border-radius: var(--border-radius);
    padding: 15px 20px;
    margin-bottom: 25px;
    font-size: 0.95rem;
}
.error-messages p {
    margin: 0 0 10px 0;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
}
.error-messages ul {
    margin: 0;
    padding-left: 25px;
}
.error-messages li {
    margin: 6px 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 30px;
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    border: 1px solid var(--border-color);
}
.empty-state-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 20px;
    opacity: 0.8;
}
.empty-state h3 {
    font-size: 1.5rem;
    margin: 0 0 10px;
    color: var(--secondary-color);
}
.empty-state p {
    color: var(--text-light);
    margin-bottom: 25px;
    line-height: 1.6;
}

/* Form Card & Container */
.form-card {
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: visible; /* Changed from hidden */
    padding: 35px; /* Increased padding */
    margin: 0 auto;
    width: 100%;
    box-sizing: border-box;
    border: 1px solid var(--border-color);
}
.service-request-container {
    max-width: 900px; /* Wider container */
    margin: 30px auto 50px;
    padding: 0 15px;
}

/* Progress Tracker */
.progress-tracker-container {
    padding: 20px 15px;
    margin: 0 auto 45px; /* Increased bottom margin */
    max-width: 800px;
}
.progress-tracker {
    display: flex;
    align-items: flex-start; /* Align items to top */
    justify-content: space-between;
    position: relative;
}
.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 0 0 auto; /* Don't grow/shrink */
    width: 100px; /* Give steps a fixed width */
    text-align: center;
}
.step-circle {
    width: 40px; /* Slightly smaller */
    height: 40px;
    border-radius: 50%;
    background: var(--progress-inactive);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.4s ease;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-bottom: 10px; /* Reduced margin */
    border: 3px solid var(--progress-inactive); /* Thicker border */
}
.step-number, .step-icon {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    transition: all 0.3s ease;
}
.step-icon { color: white; } /* Icons always white */
.step-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-color: transparent;
    z-index: -1; /* Behind circle */
}
.step.active .step-circle {
    background: white; /* White background */
    border-color: var(--progress-active); /* Yellow border */
    box-shadow: 0 4px 12px rgba(241, 196, 15, 0.2);
    transform: scale(1.1);
}
.step.active .step-number {
    color: var(--progress-active); /* Yellow number */
}
.step.active .step-pulse {
    animation: pulse 2s infinite;
}
.step.completed .step-circle {
    background: var(--progress-completed); /* Green background */
    border-color: var(--progress-completed);
    box-shadow: 0 4px 12px rgba(39, 174, 96, 0.2);
}
.step.completed .step-icon {
    color: white;
}
.progress-line {
    flex-grow: 1;
    position: relative;
    height: 5px; /* Thicker line */
    background: var(--progress-line-bg);
    margin: 0 -10px; /* Overlap slightly */
    top: 17.5px; /* Vertically center with circle */
    z-index: 1;
}
.progress-line-inner {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0%;
    background: var(--progress-line-bg);
    transition: width 0.5s ease, background 0.5s ease;
}
/* Gradient for line between completed and active */
.step.completed + .progress-line .progress-line-inner.active {
    width: 100%;
    background: linear-gradient(90deg, var(--progress-completed) 0%, var(--progress-active) 100%);
}
/* Solid green line if first step is completed */
.step.completed:first-child + .progress-line .progress-line-inner.active {
     background: var(--progress-completed);
}
.step-label {
    font-size: 0.9rem; /* Slightly smaller */
    font-weight: 500;
    color: var(--text-light);
    text-align: center;
    margin-top: 0; /* Removed top margin */
    transition: all 0.3s ease;
    line-height: 1.3;
}
.step.active .step-label {
    font-weight: 600;
    color: var(--text-dark);
}
.step.completed .step-label {
    color: var(--progress-completed);
}
@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(241, 196, 15, 0.5); }
    70% { box-shadow: 0 0 0 12px rgba(241, 196, 15, 0); }
    100% { box-shadow: 0 0 0 0 rgba(241, 196, 15, 0); }
}

/* Form Sections & Groups */
.section-title {
    font-size: 1.3rem; /* Larger title */
    font-weight: 600;
    margin-bottom: 25px; /* Increased margin */
    color: var(--secondary-color);
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-light);
}
.form-section {
    margin-bottom: 35px; /* Increased margin */
    padding-bottom: 15px; /* Reduced padding */
    border-bottom: none; /* Removed border */
}
.form-section:last-of-type {
    margin-bottom: 20px;
    padding-bottom: 0;
}
.form-group {
    margin-bottom: 25px; /* Increased margin */
}
label.form-label { /* Target specific labels */
    display: block;
    margin-bottom: 10px; /* Increased margin */
    color: var(--text-dark);
    font-weight: 500;
    font-size: 1rem;
}

/* Inputs, Selects, Textarea */
.input-wrapper {
    position: relative;
}
input[type="date"],
textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    font-size: 1rem;
    color: var(--text-dark);
    background-color: #fff; /* White background */
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box; /* Include padding in width */
}
input[type="date"] {
    padding-right: 40px; /* Space for icon */
}
textarea {
    min-height: 120px;
    resize: vertical;
}
.input-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
}
input:focus,
textarea:focus,
.selected-property:focus-within, /* Style wrapper on focus */
.selected-service:focus-within {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(241,196,15,0.2); /* Yellow focus ring */
    outline: none;
}
.input-error {
    color: var(--error-color);
    font-size: 0.85rem; /* Smaller error text */
    margin-top: 8px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.has-error { /* Apply to input/textarea/select wrapper */
    border-color: var(--error-color) !important; /* Force border color */
    background-color: var(--error-bg);
}
input.has-error:focus,
textarea.has-error:focus,
.selected-property.has-error:focus-within,
.selected-service.has-error:focus-within {
     box-shadow: 0 0 0 3px rgba(192, 57, 43, 0.2); /* Red focus ring */
}

/* Form Actions */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    gap: 15px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}
.primary-btn, .secondary-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center; /* Center content */
    gap: 8px;
    padding: 12px 24px;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    min-width: 150px; /* Minimum width */
}
.primary-btn {
    background: var(--primary-color);
    color: var(--secondary-color); /* Dark text on yellow */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
.secondary-btn {
    background-color: #f0f0f0; /* Lighter grey */
    color: var(--text-light);
    border: 1px solid var(--border-color);
}
.primary-btn:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(243, 156, 18, 0.2);
}
.secondary-btn:hover {
    background-color: #e0e0e0; /* Darker grey on hover */
    color: var(--text-dark);
    border-color: #ccc;
}

/* Enhanced Property Selector */
.enhanced-select-wrapper {
    position: relative;
}
.selected-property {
    padding: 10px 15px; /* Adjusted padding */
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff; /* White background */
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.2s ease;
    min-height: 70px; /* Ensure consistent height */
}
.selected-property:hover {
    border-color: var(--primary-light);
}
.selected-property .placeholder {
    display: flex;
    align-items: center;
    color: var(--text-muted);
    gap: 10px;
    font-style: italic;
}
.selected-property .placeholder i {
    color: var(--primary-color);
    font-size: 1.2rem;
}
.selected-property.active { /* When dropdown is open */
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(241,196,15,0.2);
}
.property-dropdown {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    width: 100%;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15); /* Stronger shadow */
    z-index: 100;
    max-height: 300px; /* Slightly reduced height */
    overflow-y: auto;
    display: none;
    border: 1px solid var(--border-color);
    scrollbar-width: thin;
    scrollbar-color: var(--primary-color) #f0f0f0;
}
.property-dropdown::-webkit-scrollbar { width: 6px; }
.property-dropdown::-webkit-scrollbar-track { background: #f0f0f0; border-radius: 6px; }
.property-dropdown::-webkit-scrollbar-thumb { background-color: var(--primary-light); border-radius: 6px; }

.property-option {
    display: flex;
    align-items: center; /* Vertically align items */
    padding: 12px 15px; /* Consistent padding */
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: background-color 0.2s;
}
.property-option:last-child { border-bottom: none; }
.property-option:hover { background-color: var(--background-highlight); }
.property-option.selected { background-color: rgba(241,196,15,0.15); font-weight: 500; }

.property-image {
    width: 70px; /* Fixed width */
    height: 55px; /* Fixed height */
    border-radius: 6px;
    overflow: hidden;
    flex-shrink: 0;
    margin-right: 15px; /* Space between image and text */
    background-color: #eee; /* Placeholder bg */
}
.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.property-details { flex-grow: 1; }
.property-details h4 {
    margin: 0 0 4px 0;
    font-size: 1rem;
    color: var(--text-dark);
    font-weight: 600;
}
.property-address, .property-dates {
    margin: 0 0 3px 0;
    font-size: 0.85rem; /* Smaller text */
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 6px;
}
.property-address i, .property-dates i {
    color: var(--primary-color); /* Icon color */
    width: 12px; /* Align icons */
    text-align: center;
}
.no-properties { padding: 20px; text-align: center; color: var(--text-muted); }
.no-properties i { display: block; font-size: 2rem; margin-bottom: 10px; color: var(--primary-color); }
.hidden-select { position: absolute; left: -9999px; opacity: 0; width: 1px; height: 1px; }

.selected-property-content { /* Content inside the selected display */
    display: flex;
    align-items: center;
    gap: 15px;
    width: 100%;
}
.selected-property-content .property-image {
    width: 70px; /* Match dropdown image size */
    height: 55px;
}

/* Enhanced Service Selector */
.enhanced-service-wrapper { position: relative; }
.selected-service {
    padding: 10px 15px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    background-color: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.2s ease;
    min-height: 70px;
}
.selected-service:hover { border-color: var(--primary-light); }
.selected-service .placeholder {
    display: flex;
    align-items: center;
    color: var(--text-muted);
    gap: 10px;
    font-style: italic;
}
.selected-service .placeholder i { color: var(--primary-color); font-size: 1.2rem; }
.selected-service.active {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(241,196,15,0.2);
}
.service-dropdown {
    position: absolute;
    top: calc(100% + 5px);
    left: 0;
    width: 100%;
    background: white;
    border-radius: var(--border-radius);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    z-index: 100;
    max-height: 450px; /* Increased max height */
    overflow-y: auto;
    display: none;
    border: 1px solid var(--border-color);
    opacity: 0; /* Start hidden for transition */
    transform: translateY(-10px);
    transition: opacity 0.3s ease, transform 0.3s ease;
    scrollbar-width: thin;
    scrollbar-color: var(--primary-color) #f0f0f0;
}
.service-dropdown::-webkit-scrollbar { width: 6px; }
.service-dropdown::-webkit-scrollbar-track { background: #f0f0f0; border-radius: 6px; }
.service-dropdown::-webkit-scrollbar-thumb { background-color: var(--primary-light); border-radius: 6px; }

.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); /* Slightly smaller min width */
    grid-gap: 20px; /* Increased gap */
    padding: 20px;
}
.service-card {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
    display: flex; /* Use flexbox for layout */
    flex-direction: column; /* Stack image and info */
}
.service-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}
.service-card.selected {
    border-color: var(--primary-color);
    box-shadow: 0 6px 18px rgba(241,196,15,0.25);
    background-color: var(--background-highlight);
}
.service-card .service-image {
    height: 150px; /* Fixed height */
    width: 100%;
    overflow: hidden;
    background-color: #eee;
}
.service-card .service-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.service-card:hover .service-image img { transform: scale(1.05); }
.service-card .service-info {
    padding: 15px;
    flex-grow: 1; /* Allow info to take remaining space */
    display: flex;
    flex-direction: column;
}
.service-card h4 {
    margin: 0 0 8px;
    font-size: 1.05rem;
    color: var(--text-dark);
    font-weight: 600;
}
.service-card .service-cost {
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: var(--primary-dark);
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 5px;
}
.service-card .service-description {
    font-size: 0.85rem;
    color: var(--text-light);
    line-height: 1.5; /* Improved line height */
    flex-grow: 1; /* Take available space */
    margin-bottom: 15px;
    /* Removed fixed height - let it grow */
}
.service-card .service-select-btn {
    display: flex;
    align-items: center;
    justify-content: center; /* Center text */
    gap: 8px;
    padding: 8px 12px;
    background: #f0f0f0;
    color: var(--text-light);
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: all 0.2s ease;
    margin-top: auto; /* Push to bottom */
    border: 1px solid var(--border-color);
}
.service-card:hover .service-select-btn,
.service-card.selected .service-select-btn {
    background: var(--primary-color);
    color: var(--secondary-color);
    border-color: var(--primary-color);
}
.no-services { padding: 20px; text-align: center; color: var(--text-muted); }
.no-services i { display: block; font-size: 2rem; margin-bottom: 10px; color: var(--primary-color); }

.selected-service-content { /* Content inside the selected display */
    display: flex;
    align-items: center;
    gap: 15px;
    width: 100%;
}
.selected-service-content .service-image {
    width: 55px; /* Smaller image */
    height: 55px;
    border-radius: 6px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid var(--border-color);
    background-color: #eee;
}
.selected-service-content .service-image img { width: 100%; height: 100%; object-fit: cover; }
.selected-service-content .service-details { flex: 1; }
.selected-service-content h4 { margin: 0 0 4px; color: var(--text-dark); font-size: 1rem; }
.selected-service-content .service-cost { color: var(--primary-dark); font-size: 0.85rem; margin: 0; }

/* Success Modal */
.success-modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px);
    display: flex; justify-content: center; align-items: center;
    z-index: 1000; animation: fadeIn 0.3s ease-out;
}
.success-modal-content {
    background-color: white; padding: 35px; border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); width: 90%; max-width: 420px;
    text-align: center; animation: slideUp 0.4s ease-out;
}
.success-icon { margin-bottom: 20px; }
.success-icon svg { stroke: var(--success-color); }
.success-modal-content h2 { color: var(--success-color); margin-bottom: 15px; font-size: 1.8rem; }
.success-modal-content p { margin-bottom: 15px; color: var(--text-light); font-size: 1rem; line-height: 1.6; }
.countdown { font-size: 0.9rem; color: var(--text-muted); margin-top: 5px; margin-bottom: 25px; }
.modal-close-btn {
    background-color: var(--success-color); color: white; border: none;
    padding: 12px 25px; border-radius: var(--border-radius); cursor: pointer;
    font-weight: 600; transition: background-color 0.2s, transform 0.2s; font-size: 1rem;
}
.modal-close-btn:hover { background-color: #229954; transform: scale(1.05); }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

/* Responsive Adjustments */
@media (max-width: 768px) {
    .form-card { padding: 25px; }
    .service-request-container { max-width: 100%; margin: 20px auto 40px; }
    .section-title { font-size: 1.2rem; }
    .progress-tracker-container { padding: 15px 5px; }
    .step { width: 80px; }
    .step-label { font-size: 0.8rem; }
    .form-actions { flex-direction: column-reverse; }
    .primary-btn, .secondary-btn { width: 100%; }
    .service-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 15px; padding: 15px; }
}
@media (max-width: 576px) {
    .step-label { display: none; }
    .step-circle { margin-bottom: 0; }
    .progress-line { top: 20px; height: 4px; } /* Adjust line position */
    .property-image, .selected-property-content .property-image { width: 50px; height: 40px; margin-right: 10px; }
    .property-details h4, .selected-property-content h4 { font-size: 0.9rem; }
    .property-address, .property-dates, .selected-property-content .service-cost { font-size: 0.75rem; }
    .selected-service-content .service-image { width: 45px; height: 45px; }
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Property Selector ---
    const selectedPropertyDisplay = document.getElementById('selected-property-display');
    const propertyDropdown = document.getElementById('property-dropdown');
    const propertyOptions = document.querySelectorAll('.property-option');
    const hiddenSelect = document.getElementById('property_id');

    // Function to update selected property display
    function updateSelectedPropertyDisplay(selectedOptionElement) {
        if (!selectedOptionElement) {
            selectedPropertyDisplay.innerHTML = `<div class="placeholder"><i class="fas fa-home"></i><span>Select a property</span></div>`;
            return;
        }
        // Clone the content for display
        const propertyDetailsHTML = selectedOptionElement.querySelector('.property-details').innerHTML;
        const propertyImageHTML = selectedOptionElement.querySelector('.property-image').innerHTML;

        selectedPropertyDisplay.innerHTML = `
            <div class="selected-property-content">
                <div class="property-image">${propertyImageHTML}</div>
                <div class="property-details">${propertyDetailsHTML}</div>
            </div>`;
    }

    // Initial setup for property selector based on $old data
    if (hiddenSelect.value) {
        const initiallySelectedOption = document.querySelector(`.property-option[data-value="${hiddenSelect.value}"]`);
        if (initiallySelectedOption) {
            updateSelectedPropertyDisplay(initiallySelectedOption);
            initiallySelectedOption.classList.add('selected');
            selectedPropertyDisplay.classList.remove('has-error'); // Clear error state if pre-selected
        }
    } else {
         updateSelectedPropertyDisplay(null); // Show placeholder
    }

    // Toggle property dropdown
    selectedPropertyDisplay.addEventListener('click', (e) => {
        e.stopPropagation(); // Prevent closing immediately
        const isOpen = propertyDropdown.style.display === 'block';
        propertyDropdown.style.display = isOpen ? 'none' : 'block';
        selectedPropertyDisplay.classList.toggle('active', !isOpen);
    });

    // Handle property selection
    propertyOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Update hidden select value
            hiddenSelect.value = this.dataset.value;

            // Update display
            updateSelectedPropertyDisplay(this);

            // Update selected state in dropdown
            propertyOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');

            // Close dropdown
            propertyDropdown.style.display = 'none';
            selectedPropertyDisplay.classList.remove('active');
            selectedPropertyDisplay.classList.remove('has-error'); // Clear error state on selection

            // Trigger change event for potential listeners
            hiddenSelect.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });

    // --- Service Selector ---
    const selectedServiceDisplay = document.getElementById('selected-service-display');
    const serviceDropdown = document.getElementById('service-dropdown');
    const serviceCards = document.querySelectorAll('.service-card');
    const hiddenServiceType = document.getElementById('service_type');
    const hiddenCostPerHour = document.getElementById('cost_per_hour');
    const hiddenServiceName = document.getElementById('service_name');

    // Function to update selected service display
    function updateSelectedServiceDisplay(selectedCardElement) {
         if (!selectedCardElement) {
            selectedServiceDisplay.innerHTML = `<div class="placeholder"><i class="fa-solid fa-screwdriver-wrench"></i><span>Select a service type</span></div>`;
            return;
        }
        const serviceImageSrc = selectedCardElement.querySelector('.service-image img')?.src || '';
        const serviceName = selectedCardElement.dataset.name || 'N/A';
        const serviceCost = selectedCardElement.dataset.cost || '0';
        const formattedCost = parseFloat(serviceCost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        selectedServiceDisplay.innerHTML = `
            <div class="selected-service-content">
                <div class="service-image">
                    <img src="${serviceImageSrc}" alt="${serviceName}" onerror="this.style.display='none'; this.parentElement.style.backgroundColor='#eee';">
                </div>
                <div class="service-details">
                    <h4>${serviceName}</h4>
                    <div class="service-cost">
                        <i class="fas fa-tag"></i> LKR ${formattedCost}/hour
                    </div>
                </div>
            </div>`;
    }

     // Initial setup for service selector based on $old data
    if (hiddenServiceType.value) {
        const initiallySelectedCard = document.querySelector(`.service-card[data-value="${hiddenServiceType.value}"]`);
        if (initiallySelectedCard) {
            updateSelectedServiceDisplay(initiallySelectedCard);
            initiallySelectedCard.classList.add('selected');
            selectedServiceDisplay.classList.remove('has-error'); // Clear error state if pre-selected
        }
    } else {
        updateSelectedServiceDisplay(null); // Show placeholder
    }


    // Toggle service dropdown
    selectedServiceDisplay.addEventListener('click', function(e) {
        e.stopPropagation();
        const isOpen = serviceDropdown.style.display === 'block';
        if (!isOpen) {
            serviceDropdown.style.display = 'block';
            setTimeout(() => { // Allow display block before starting transition
                serviceDropdown.style.opacity = '1';
                serviceDropdown.style.transform = 'translateY(0)';
            }, 10);
        } else {
            serviceDropdown.style.opacity = '0';
            serviceDropdown.style.transform = 'translateY(-10px)';
            setTimeout(() => { serviceDropdown.style.display = 'none'; }, 300); // Hide after transition
        }
        selectedServiceDisplay.classList.toggle('active', !isOpen);
    });

    // Handle service selection
    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            // Update hidden inputs
            hiddenServiceType.value = this.dataset.value;
            hiddenCostPerHour.value = this.dataset.cost;
            hiddenServiceName.value = this.dataset.name;

            // Update display
            updateSelectedServiceDisplay(this);

            // Update selected state in dropdown
            serviceCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');

            // Close dropdown with animation
            serviceDropdown.style.opacity = '0';
            serviceDropdown.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                serviceDropdown.style.display = 'none';
                selectedServiceDisplay.classList.remove('active');
                selectedServiceDisplay.classList.remove('has-error'); // Clear error state
            }, 300);

            // Trigger change events
            [hiddenServiceType, hiddenCostPerHour, hiddenServiceName].forEach(input => {
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        });
    });

    // --- Close Dropdowns on Outside Click ---
    document.addEventListener('click', function(e) {
        // Close property dropdown
        if (propertyDropdown.style.display === 'block' && !selectedPropertyDisplay.contains(e.target) && !propertyDropdown.contains(e.target)) {
            propertyDropdown.style.display = 'none';
            selectedPropertyDisplay.classList.remove('active');
        }
        // Close service dropdown
        if (serviceDropdown.style.display === 'block' && !selectedServiceDisplay.contains(e.target) && !serviceDropdown.contains(e.target)) {
            serviceDropdown.style.opacity = '0';
            serviceDropdown.style.transform = 'translateY(-10px)';
            setTimeout(() => { serviceDropdown.style.display = 'none'; }, 300);
            selectedServiceDisplay.classList.remove('active');
        }
    });

    // --- Set Min Date for Date Input ---
    const dateInput = document.getElementById('service_date');
    if (dateInput) {
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);
    }
});
</script>

<?php require 'customerFooter.view.php' ?>