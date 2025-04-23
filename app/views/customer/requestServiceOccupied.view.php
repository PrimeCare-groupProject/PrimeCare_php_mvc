<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/dashboard"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
    <h2>Request Repair Service</h2>
</div>

<div class="service-request-container">
    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><i class="fas fa-exclamation-circle"></i> <?= $error ?></li>
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
            <p>You don't have any active rental properties at the moment.</p>
            <a href="<?= ROOT ?>/properties" class="primary-btn">
                <i class="fas fa-search"></i>
                Browse Properties
            </a>
        </div>
    <?php else: ?>
        <div class="form-card">
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
                <!-- Rest of the form remains the same -->
                <div class="form-section">
                    <h3 class="section-title"><i class="fas fa-home" style="color:#f1c40f"></i> Property Selection</h3>
                    <!-- Property selection part remains the same -->
                    <div class="form-group">
                        <label for="property_id">Choose your property</label>
                        <div class="select-wrapper">
                            <select name="property_id" id="property_id" required>
                                <option value="">-- Select a Property --</option>
                                <?php foreach ($activeBookings as $booking): ?>
                                    <option value="<?= $booking->property_id ?>" <?= (isset($old['property_id']) && $old['property_id'] == $booking->property_id) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($booking->property_name) ?> (<?= htmlspecialchars($booking->property_address ?? 'No address') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                        <?php if (isset($errors['property_id'])): ?>
                            <div class="input-error"><?= $errors['property_id'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="section-title"><i class="fa-solid fa-screwdriver-wrench" style="color:#f1c40f"></i> Service Information</h3>
                    
                    <div class="form-group">
                        <label for="service_time">Time of service needed</label>
                        <div class="input-wrapper">
                            <input 
                                type="datetime-local" 
                                name="service_time" 
                                id="service_time"
                                value="<?= isset($old['service_time']) ? htmlspecialchars($old['service_time']) : '' ?>"
                                required
                                class="<?= isset($errors['service_time']) ? 'has-error' : '' ?>"
                            >
                            <i class="fas fa-calendar-alt input-icon"></i>
                        </div>
                        <?php if (isset($errors['service_time'])): ?>
                            <div class="input-error"><?= $errors['service_time'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="service_type">Type of service needed</label>
                        <div class="select-wrapper">
                            <select name="service_type" id="service_type" required>
                                <option value="">-- Select Service Type --</option>
                                <?php if (!empty($serviceTypes)): ?>
                                    <?php foreach ($serviceTypes as $service): ?>
                                        <option value="<?= $service->service_id ?>" 
                                            data-cost="<?= $service->cost_per_hour ?>"
                                            <?= (isset($old['service_type']) && $old['service_type'] == $service->service_id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($service->name) ?> (LKR <?= number_format($service->cost_per_hour, 2) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <i class="fas fa-chevron-down select-arrow"></i>
                        </div>
                        <?php if (isset($errors['service_type'])): ?>
                            <div class="input-error"><?= $errors['service_type'] ?></div>
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
                        ><?= isset($old['service_description']) ? htmlspecialchars($old['service_description']) : '' ?></textarea>
                        <?php if (isset($errors['service_description'])): ?>
                            <div class="input-error"><?= $errors['service_description'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>

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

<style>
:root {
    --primary-color: #f1c40f;
    --primary-dark: #f39c12;
    --primary-light: #f7d774;
    --secondary-color: #2c3e50;
    --text-dark: #222;
    --text-light: #444;
    --text-muted: #888;
    --border-color: #e0e0e0;
    --background-light: #fafbfc;
    --background-highlight: #f7f7e7;
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
    --shadow-md: 0 2px 16px rgba(241,196,15,0.10);
    --error-color: #b30000;
    --error-bg: #ffeaea;
    --border-radius: 8px;
    
    /* New progress tracker colors */
    --progress-inactive: #e3e8ec;
    --progress-active: #4cc9f0;
    --progress-completed: #4cc9f0;
    --progress-line-bg: #e3e8ec;
    --progress-pulse: rgba(76, 201, 240, 0.3);
}

.navigate-icons {
    width: 36px;
    height: 36px;
}

.error-messages {
    background: var(--error-bg);
    color: var(--error-color);
    border: 1px solid #ffb3b3;
    border-radius: var(--border-radius);
    padding: 12px 18px;
    margin-bottom: 20px;
}

.error-messages ul {
    margin: 0;
    padding-left: 20px;
}

.error-messages li {
    margin: 5px 0;
}

.empty-state {
    text-align: center;
    padding: 60px 30px;
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
}

.empty-state-icon {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin: 0 0 10px;
    color: var(--secondary-color);
}

.empty-state p {
    color: var(--text-light);
    margin-bottom: 20px;
}

.form-card {
    background: #fff;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: visible;
    padding: 28px;
    margin: 0 auto;
    width: 100%;
    box-sizing: border-box;
}

.service-request-container {
    max-width: 90%;
    margin: 30px auto 50px;
    padding: 0 15px;
    overflow: hidden;
}

/* New modern progress tracker styles */
.progress-tracker-container {
    padding: 20px 15px;
    margin: 0 auto 40px;
    max-width: 800px;
}

.progress-tracker {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-circle {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    background: var(--progress-inactive);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: all 0.4s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-bottom: 14px;
    border: 2px solid transparent;
}

.step-number, .step-icon {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--text-light);
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.step-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background-color: transparent;
    z-index: 1;
}

.step.active .step-circle {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-color: var(--primary-light);
    box-shadow: 0 5px 15px rgba(241, 196, 15, 0.3);
    transform: scale(1.1);
}

.step.active .step-number {
    color: white;
    font-weight: 700;
}

.step.active .step-pulse {
    animation: pulse 2s infinite;
}

.step.completed .step-circle {
    background: var(--progress-completed);
    border-color: var(--progress-completed);
    box-shadow: 0 5px 15px rgba(76, 201, 240, 0.3);
}

.step.completed .step-icon {
    color: white;
}

.progress-line {
    flex-grow: 1;
    position: relative;
    height: 4px;
    background: var(--progress-line-bg);
    margin: 0 15px;
    margin-bottom: 36px;
    z-index: 1;
}

.progress-line-inner {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 0%;
    background: var(--progress-line-bg);
    transition: width 0.5s ease;
}

.progress-line-inner.active {
    width: 100%;
    background: linear-gradient(90deg, var(--progress-completed) 0%, var(--primary-color) 100%);
}

.step-label {
    font-size: 0.95rem;
    font-weight: 500;
    color: var(--text-light);
    text-align: center;
    margin-top: 5px;
    transition: all 0.3s ease;
}

.step.active .step-label {
    font-weight: 600;
    color: var(--text-dark);
}

.step.completed .step-label {
    color: var(--progress-completed);
}

/* Pulse animation for active step */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(241, 196, 15, 0.7);
    }
    70% {
        box-shadow: 0 0 0 15px rgba(241, 196, 15, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(241, 196, 15, 0);
    }
}

.section-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: var(--secondary-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-section {
    margin-bottom: 30px;
    padding-bottom: 25px;
    border-bottom: 1px solid var(--border-color);
}

.form-section:last-of-type {
    margin-bottom: 20px;
    padding-bottom: 15px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    color: var(--text-dark);
    font-weight: 500;
}

.select-wrapper, .input-wrapper {
    position: relative;
}

select, input[type="datetime-local"] {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    font-size: 1rem;
    color: var(--text-dark);
    background-color: var(--background-light);
    transition: border-color 0.2s, box-shadow 0.2s;
}

select {
    appearance: none;
    padding-right: 40px;
    cursor: pointer;
}

.select-arrow, .input-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none;
}

select:focus, 
input:focus,
textarea:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(241,196,15,0.15);
    outline: none;
}

textarea {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    resize: vertical;
    min-height: 120px;
    font-size: 1rem;
    background-color: var(--background-light);
    transition: border-color 0.2s, box-shadow 0.2s;
}

.input-error {
    color: var(--error-color);
    font-size: 0.9rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
}

.has-error {
    border-color: var(--error-color);
    background-color: var(--error-bg);
}

.form-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    gap: 15px;
    padding-bottom: 10px;
}

.primary-btn, .secondary-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.primary-btn {
    background: linear-gradient(90deg, var(--primary-color) 60%, var(--primary-light) 100%);
    color: var(--text-dark);
    box-shadow: 0 2px 8px rgba(241,196,15,0.1);
}

.secondary-btn {
    background-color: #f5f5f5;
    color: var(--text-light);
    border: 1px solid var(--border-color);
}

.primary-btn:hover {
    background: linear-gradient(90deg, var(--primary-dark) 60%, var(--primary-color) 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(241,196,15,0.2);
}

.secondary-btn:hover {
    background-color: #ebebeb;
    color: var(--text-dark);
}

@media (max-width: 768px) {
    .form-card {
        padding: 20px;
    }

    .service-request-container {
        margin: 20px auto 40px;
    }
    
    .form-section {
        margin-bottom: 25px;
        padding-bottom: 20px;
    }

    .progress-tracker-container {
        padding: 15px 10px;
        margin-bottom: 30px;
    }
    
    .step-circle {
        width: 40px;
        height: 40px;
    }
    
    .step-label {
        font-size: 0.85rem;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .primary-btn, .secondary-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .step-label {
        display: none;
    }
    
    .step-circle {
        margin-bottom: 0;
    }
    
    .progress-line {
        margin-bottom: 0;
    }
}
</style>

<?php require 'customerFooter.view.php' ?>