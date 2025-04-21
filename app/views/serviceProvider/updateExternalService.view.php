<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/serviceprovider/externalServices">
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>Update External Service</h2>
    <button type="button" class="scroll-to-log-btn animated-gradient-btn"
        onclick="document.getElementById('updateFormSection').scrollIntoView({ behavior: 'smooth' });">
        <i class="fas fa-arrow-down"></i> Go to Update Form
    </button>
</div>

<!-- Display errors if any -->
<?php if (isset($_SESSION['errors'])): ?>
    <div class="error-message">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
// Update the variable initialization to ensure variables are always set
$propertyImages = $propertyImages ?? [];
$serviceCompletionImages = $serviceCompletionImages ?? [];

$service_location_image = !empty($propertyImages[0]) ? $propertyImages[0] : 'service_location_default.jpg';
?>

<div class="logs-page-container">
    <div class="logs-content">
        <div class="logs-details-section">
            <div class="logs-details-header">
                <h3>External Service Details</h3>
                <p>Service ID: <?= $service->id ?></p>
            </div>
            <div class="logs-details-body">
                <div class="logs-details-left">
                    <div class="property-image-container">
                        <div class="property-image">
                            <img src="<?= ROOT ?>/assets/images/<?= $service_location_image ?>" alt="Service Location" onerror="this.src='<?= ROOT ?>/assets/images/listing_alt.jpg'">
                        </div>
                        <?php if (count($propertyImages) > 1): ?>
                            <div class="image-navigation">
                                <button class="prev-img" onclick="navigateImages(-1)"><i class="fas fa-chevron-left"></i></button>
                                <div class="image-counter">
                                    <span id="current-image">1</span>/<span><?= count($propertyImages) ?></span>
                                </div>
                                <button class="next-img" onclick="navigateImages(1)"><i class="fas fa-chevron-right"></i></button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="logs-details-right">
                    <div class="detail-row">
                        <span class="detail-label">Service Type:</span>
                        <span class="detail-value"><?= htmlspecialchars($service->service_type) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Location Address:</span>
                        <span class="detail-value"><?= htmlspecialchars($service->property_address) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date Requested:</span>
                        <span class="detail-value"><?= date('F j, Y', strtotime($service->date)) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Rate Per Hour:</span>
                        <span class="detail-value">LKR <?= number_format($service->cost_per_hour, 2) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Service Request Description:</span>
                        <div class="detail-long-text">
                            <?= nl2br(htmlspecialchars($service->property_description)) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="logs-form-section">
            <h3 id="updateFormSection">Update Service Information</h3>
            <form method="post" class="service-update-form" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $service->id ?>">
                
                <div class="owner-addProp-container">
                    <div class="owner-addProp-form-left">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-tools"></i>
                                <h4>Service Information</h4>
                            </div>
    
                            <div class="form-group">
                                <label for="service_provider_description">Work Completion Details <span class="required">*</span></label>
                                <textarea id="service_provider_description" name="service_provider_description" 
                                    class="form-input form-textarea" required placeholder="Describe what work was performed, materials used, etc."><?= htmlspecialchars($service->service_provider_description ?? '') ?></textarea>
                            </div>
                        </div>
                        
                        <!-- Service Documentation Images Upload -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-camera-retro"></i>
                                <h4>Service Documentation</h4>
                            </div>
                            
                            <div class="form-group">
                                <label class="input-label">Upload Proof of Work Images</label>
                                <p class="upload-instruction">Upload images showing the completed work (before/after images are recommended)</p>
                                <div class="owner-addProp-file-upload">
                                    <input type="file" name="service_image[]" id="service_image" class="input-field" multiple accept="image/jpeg, image/png, image/jpg">
                                    <div class="owner-addProp-upload-area">
                                        <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload Icon" class="owner-addProp-upload-logo">
                                        <p class="upload-area-no-margin">Drop your work documentation images here</p>
                                        <button type="button" class="secondary-btn" onclick="document.getElementById('service_image').click()">
                                            Choose Files
                                        </button>
                                    </div>
                                </div>
                                <div id="uploaded-files" class="owner-addProp-uploaded-files"></div>
        
                                <!-- Upload progress container -->
                                <div id="upload-progress-container" style="display: none;">
                                    <div class="progress-info">Uploading files... <span id="upload-percentage">0%</span></div>
                                    <div class="progress-bar-container">
                                        <div id="upload-progress-bar" class="progress-bar"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Display existing service documentation images if any -->
                            <?php 
                            // Use service_completion_images instead of propertyImages with proper error handling
                            $serviceCompletionImages = [];
                            if (!empty($service->service_completion_images)) {
                                $decoded = json_decode($service->service_completion_images, true);
                                if (is_array($decoded)) {
                                    $serviceCompletionImages = $decoded;
                                }
                            }
                            ?>

                            <?php if (!empty($serviceCompletionImages)): ?>
                            <div class="form-group">
                                <label class="input-label">Existing Work Documentation Images</label>
                                <p class="upload-instruction">Service images showing completed work</p>
                                <input type="hidden" name="images_to_remove" id="images-to-remove-input" value="">
                                
                                <div class="existing-images-container">
                                    <?php foreach($serviceCompletionImages as $index => $image): ?>
                                        <div class="existing-image" data-image="<?= htmlspecialchars($image) ?>">
                                            <img src="<?= ROOT ?>/assets/images/<?= htmlspecialchars($image) ?>" alt="Service Documentation <?= $index+1 ?>"
                                                onerror="this.src='<?= ROOT ?>/assets/images/listing_alt.jpg'">
                                            <button type="button" class="remove-image-btn" onclick="toggleImageRemoval(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <?php if(count($serviceCompletionImages) > 0): ?>
                                <div class="selected-images-actions">
                                    <span id="selected-count">0</span> images selected
                                    <button type="button" class="secondary-btn remove-selected-btn" onclick="confirmRemoveSelected()" disabled>
                                        Remove Selected
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
    
                    <div class="owner-addProp-form-right">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-file-invoice-dollar"></i>
                                <h4>Service Billing</h4>
                            </div>
    
                            <!-- Total Hours -->
                            <div class="form-group">
                                <label for="total_hours">Total Hours Worked <span class="required">*</span></label>
                                <input type="number" id="total_hours" name="total_hours" class="form-input" 
                                       value="<?= htmlspecialchars($service->total_hours ?? '') ?>" step="0.5" min="0.5" required>
                            </div>
                            
                            <!-- Additional Charges Section -->
                            <div class="form-group">
                                <label for="additional_charges">Additional Charges (LKR)</label>
                                <input type="number" id="additional_charges" name="additional_charges" class="form-input" 
                                       value="<?= htmlspecialchars($service->additional_charges ?? 0) ?>" step="0.01" min="0">
                            </div>
                                
                            <div class="form-group">
                                <label for="additional_charges_reason">Reason for Additional Charges</label>
                                <textarea id="additional_charges_reason" name="additional_charges_reason" class="form-input"
                                    placeholder="Explain why additional charges are needed (materials, special equipment, etc.)"><?= htmlspecialchars($service->additional_charges_reason ?? '') ?></textarea>
                            </div>
    
                            <!-- Expected Earnings -->
                            <div class="form-group">
                                <label>Total Earnings</label>
                                <div class="earnings-display">
                                    <input type="text" id="expected-earnings" class="form-input highlight-amount" readonly>
                                </div>
                            </div>
    
                            <div class="cost-summary">
                                <div class="cost-item">
                                    <span>Service Cost:</span>
                                    <span id="service-cost">LKR <?= number_format(($service->cost_per_hour ?? 0) * ($service->total_hours ?? 0), 2) ?></span>
                                </div>
                                <div class="cost-item">
                                    <span>Additional Charges:</span>
                                    <span id="additional-cost">LKR <?= number_format($service->additional_charges ?? 0, 2) ?></span>
                                </div>
                                <div class="cost-item total">
                                    <span>Total Cost:</span>
                                    <span id="total-cost">LKR <?= number_format(($service->cost_per_hour ?? 0) * ($service->total_hours ?? 0) + ($service->additional_charges ?? 0), 2) ?></span>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="form-actions">
                                <button type="submit" class="tbtn save-tbtn">Save Changes</button>
                                <button type="submit" name="mark_complete" value="1" class="tbtn complete-tbtn">Mark as Complete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Image navigation functionality
let currentImageIndex = 0;
const propertyImagesArray = <?= json_encode($propertyImages ?? []) ?>;
const serviceCompletionImagesArray = <?= json_encode($serviceCompletionImages ?? []) ?>;

// Choose which array to use for navigation (property images in this case)
const imagesToNavigate = propertyImagesArray && propertyImagesArray.length ? propertyImagesArray : [];

function navigateImages(direction) {
    if (!imagesToNavigate || !imagesToNavigate.length) return;
    
    const newIndex = currentImageIndex + direction;
    
    if (newIndex >= 0 && newIndex < imagesToNavigate.length) {
        currentImageIndex = newIndex;
        updateDisplayedImage();
    }
}

function updateDisplayedImage() {
    const imageElement = document.querySelector('.property-image img');
    const currentElement = document.getElementById('current-image');
    
    if (!imageElement || !currentElement || !imagesToNavigate[currentImageIndex]) return;
    
    imageElement.src = `<?= ROOT ?>/assets/images/${imagesToNavigate[currentImageIndex]}`;
    currentElement.textContent = currentImageIndex + 1;
}

// Enhanced file upload preview with animated progress bar
document.getElementById('service_image').addEventListener('change', function(e) {
    const container = document.getElementById('uploaded-files');
    const progressContainer = document.getElementById('upload-progress-container');
    const progressBar = document.getElementById('upload-progress-bar');
    const percentageText = document.getElementById('upload-percentage');
    
    container.innerHTML = '';
    
    if (this.files.length > 0) {
        // Show progress container
        progressContainer.style.display = 'block';
        
        // Start with 0% progress
        progressBar.style.width = '0%';
        percentageText.textContent = '0%';
        
        // Create file previews
        for (let i = 0; i < this.files.length; i++) {
            const file = this.files[i];
            const div = document.createElement('div');
            div.className = 'uploaded-file';
            
            // Add file icon based on type
            const icon = document.createElement('span');
            icon.innerHTML = '📷 ';
            div.appendChild(icon);
            
            // Add file name
            const fileName = document.createElement('span');
            fileName.textContent = file.name;
            fileName.style.marginLeft = '5px';
            div.appendChild(fileName);
            
            container.appendChild(div);
        }
        
        // Animate progress bar (simulation)
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                
                // Simulate completion delay
                setTimeout(() => {
                    // Add completion animation to file items
                    const fileItems = container.querySelectorAll('.uploaded-file');
                    fileItems.forEach(item => {
                        item.classList.add('upload-complete');
                    });
                    
                    // Update text to show completion
                    percentageText.textContent = 'Complete!';
                    
                    // Hide progress bar after a delay
                    setTimeout(() => {
                        progressContainer.style.display = 'none';
                    }, 2000);
                }, 500);
            }
            
            progressBar.style.width = progress + '%';
            percentageText.textContent = Math.round(progress) + '%';
        }, 200);
    }
});

// Cost calculation functionality
document.addEventListener('DOMContentLoaded', function() {
    const totalHoursInput = document.getElementById('total_hours');
    const additionalChargesInput = document.getElementById('additional_charges');
    const additionalReasonInput = document.getElementById('additional_charges_reason');
    const serviceCostDisplay = document.getElementById('service-cost');
    const additionalCostDisplay = document.getElementById('additional-cost');
    const totalCostDisplay = document.getElementById('total-cost');
    const expectedEarningsInput = document.getElementById('expected-earnings');
    
    const costPerHour = <?= $service->cost_per_hour ?? 0 ?>;
    
    function updateCosts() {
        const hours = parseFloat(totalHoursInput.value) || 0;
        const additionalCharges = parseFloat(additionalChargesInput.value) || 0;
        
        const serviceCost = hours * costPerHour;
        const totalCost = serviceCost + additionalCharges;
        
        serviceCostDisplay.textContent = `LKR ${serviceCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        additionalCostDisplay.textContent = `LKR ${additionalCharges.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        totalCostDisplay.textContent = `LKR ${totalCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        expectedEarningsInput.value = totalCost.toLocaleString('en-US');
        
        // Make reason field required if there are additional charges
        if (additionalCharges > 0) {
            additionalReasonInput.setAttribute('required', '');
            additionalReasonInput.classList.add('required-field');
        } else {
            additionalReasonInput.removeAttribute('required');
            additionalReasonInput.classList.remove('required-field');
        }
    }
    
    totalHoursInput.addEventListener('input', updateCosts);
    additionalChargesInput.addEventListener('input', updateCosts);
    
    // Initialize costs
    updateCosts();
});

// Add drag and drop functionality
const uploadArea = document.querySelector('.owner-addProp-upload-area');
const fileInput = document.getElementById('service_image');

uploadArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    uploadArea.style.backgroundColor = '#fef9e7'; 
    uploadArea.style.borderColor = '#f1c40f';     
});

uploadArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    uploadArea.style.backgroundColor = '';
    uploadArea.style.borderColor = '';
});

uploadArea.addEventListener('drop', function(e) {
    e.preventDefault();
    uploadArea.style.backgroundColor = '';
    uploadArea.style.borderColor = '';
    
    // Trigger file input change with the dropped files
    fileInput.files = e.dataTransfer.files;
    
    // Manually trigger change event
    const event = new Event('change');
    fileInput.dispatchEvent(event);
});

// Image removal functionality
const imagesToRemove = new Set();

function toggleImageRemoval(btn) {
    const imageContainer = btn.parentElement;
    const imagePath = imageContainer.dataset.image;
    
    if (imageContainer.classList.contains('marked-for-removal')) {
        // Unmark for removal
        imageContainer.classList.remove('marked-for-removal');
        imagesToRemove.delete(imagePath);
    } else {
        // Mark for removal
        imageContainer.classList.add('marked-for-removal');
        imagesToRemove.add(imagePath);
    }
    
    // Update selected count
    const selectedCount = document.getElementById('selected-count');
    if (selectedCount) {
        selectedCount.textContent = imagesToRemove.size;
        
        // Update remove button state
        const removeBtn = document.querySelector('.remove-selected-btn');
        if (removeBtn) {
            removeBtn.disabled = imagesToRemove.size === 0;
        }
    }
    
    // Update hidden input with images to remove
    document.getElementById('images-to-remove-input').value = Array.from(imagesToRemove).join(',');
}

function confirmRemoveSelected() {
    if (imagesToRemove.size === 0) return;
    
    const confirmMessage = `Are you sure you want to remove ${imagesToRemove.size} selected image${imagesToRemove.size > 1 ? 's' : ''}?`;
    
    if (confirm(confirmMessage)) {
        // Images will be removed when the form is submitted
        // The controller will handle the actual deletion
        alert(`${imagesToRemove.size} image${imagesToRemove.size > 1 ? 's' : ''} will be removed when you save changes`);
    }
}

// Submit form handling
document.querySelector('.service-update-form').addEventListener('submit', function() {
    // Update the hidden input with final list of images to remove
    document.getElementById('images-to-remove-input').value = Array.from(imagesToRemove).join(',');
});
</script>

<style>
/* Error message styling */
.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px 15px;
    margin: 0 auto 20px;
    border-radius: 5px;
    max-width: 1200px;
}

/* Container styling */
.logs-page-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 15px;
    box-sizing: border-box;
}

.logs-content {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    width: 100%;
}

/* Details section styling */
.logs-details-section {
    padding: 0;
    border-bottom: 1px solid #e0e0e0;
}

.logs-details-header {
    background-color: #f9f9f9;
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logs-details-header h3 {
    margin: 0;
    color: #333;
    font-size: 20px;
}

.logs-details-body {
    display: flex;
    flex-wrap: wrap;
    padding: 20px;
}

.logs-details-left {
    width: 300px;
    flex-shrink: 0;
    margin-right: 30px;
}

.logs-details-right {
    flex: 1;
    min-width: 0; /* Prevent flex item from overflowing */
}

/* Service location image styling */
.property-image-container {
    width: 100%;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.property-image {
    height: 250px;
    overflow: hidden;
    position: relative;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.property-image img:hover {
    transform: scale(1.05);
}

.image-navigation {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    background: #f0f0f0;
}

.image-counter {
    margin: 0 10px;
    font-size: 14px;
    color: #555;
}

.prev-img, .next-img {
    background: none;
    border: none;
    color: #555;
    cursor: pointer;
    font-size: 16px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.prev-img:hover, .next-img:hover {
    background: #ddd;
}

/* Detail rows styling */
.detail-row {
    margin-bottom: 15px;
}

.detail-label {
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
    display: block;
}

.detail-value {
    color: #333;
    word-break: break-word;
}

.detail-long-text {
    background: #f9f9f9;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    white-space: pre-line;
    max-height: 120px;
    overflow-y: auto;
    font-size: 14px;
    line-height: 1.5;
    width: 100%;
    box-sizing: border-box;
}

/* Form section styling */
.logs-form-section {
    padding: 20px;
}

.logs-form-section h3 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #333;
    font-size: 20px;
}

.logs-form-section h4 {
    margin-bottom: 15px;
    color: #555;
}

/* Upload styling */
.owner-addProp-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
    width: 100%;
    box-sizing: border-box;
}

.owner-addProp-form-left, 
.owner-addProp-form-right {
    flex: 1;
    min-width: 300px;
    width: calc(50% - 10px);
    box-sizing: border-box;
}

.owner-addProp-file-upload {
    position: relative;
}

.owner-addProp-file-upload input[type="file"] {
    position: absolute;
    width: 0;
    height: 0;
    opacity: 0;
}

.upload-instruction {
    color: #666;
    font-size: 14px;
    margin-bottom: 10px;
    font-style: italic;
}

.owner-addProp-upload-area {
    border: 2px dashed #ddd;
    border-radius: 6px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #fff;
}

.owner-addProp-upload-area:hover {
    border-color: #f1c40f;
    background-color: #fef9e7;
}

.owner-addProp-upload-logo {
    width: 60px;
    margin-bottom: 10px;
    opacity: 0.7;
}

.upload-area-no-margin {
    margin: 5px 0;
}

.owner-addProp-uploaded-files {
    margin-top: 15px;
}

.uploaded-file {
    display: flex;
    align-items: center;
    padding: 10px;
    margin-bottom: 8px;
    background: #f9f9f9;
    border-radius: 4px;
    border-left: 3px solid #f1c40f;
}

.upload-complete {
    animation: fadeInYellow 0.5s forwards;
}

@keyframes fadeInYellow {
    0% { background-color: #f9f9f9; }
    100% { background-color: #fef9e7; }
}

/* Progress bar styling */
.progress-bar-container {
    width: 100%;
    height: 8px;
    background-color: #f0f0f0;
    border-radius: 4px;
    margin: 10px 0;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #f1c40f, #f39c12);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #555;
}

/* Existing images styling */
.existing-images-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.existing-image {
    height: 100px;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 2px solid transparent;
    transition: all 0.2s ease;
    position: relative;
}

.existing-image:hover {
    transform: scale(1.05);
    border-color: #f1c40f;
}

.existing-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-image-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(0, 0, 0, 0.5);
    border: none;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.remove-image-btn:hover {
    background: rgba(0, 0, 0, 0.7);
}

.selected-images-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 14px;
    color: #555;
}

.selected-images-actions .secondary-btn {
    padding: 6px 12px;
    font-size: 14px;
}

/* Form inputs styling */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #444;
}

.form-input {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 15px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-input:focus {
    border-color: #f1c40f;
    outline: none;
    box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.2);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.required {
    color: #e74c3c;
}

.required-field {
    border-color: #e74c3c !important;
}

/* Cost summary styling */
.cost-summary {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    box-sizing: border-box;
}

.cost-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.cost-item.total {
    border-top: 1px solid #ddd;
    margin-top: 8px;
    padding-top: 12px;
    font-weight: 600;
    font-size: 18px;
}

/* Button styling */
.form-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    width: 100%;
    box-sizing: border-box;
}

.tbtn {
    padding: 12px 20px;
    border-radius: 4px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    font-size: 16px;
    flex: 1;
    text-align: center;
    transition: all 0.3s;
}

.save-tbtn {
    background-color: #f1c40f;
    color: #333;
}

.save-tbtn:hover {
    background-color: #f39c12;
    color: white;
}

.complete-tbtn {
    background-color: #2ecc71;
    color: white;
}

.complete-tbtn:hover {
    background-color: #27ae60;
}

.earnings-display {
    position: relative;
}

.earnings-display::before {
    content: "LKR";
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #f39c12; 
    font-weight: bold;
}

#expected-earnings {
    padding-left: 45px;
}

.highlight-amount {
    font-weight: bold;
    font-size: 18px;
    color: #f39c12;
    background-color: #fef9e7;
    text-align: right;
}

.secondary-btn {
    background-color: #f0f0f0;
    color: #333;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.3s;
}

.secondary-btn:hover {
    background-color: #e0e0e0;
}

.scroll-to-log-btn {
    margin-left: 20px;
    padding: 8px 18px;
    background: linear-gradient(270deg, #f1c40f, #f39c12, #f1c40f, #f7d774);
    background-size: 400% 400%;
    color: #222;
    border: none;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
    animation: gradientMove 2.5s ease infinite;
}

.scroll-to-log-btn:hover {
    color: #fff;
}

@keyframes gradientMove {
    0% {background-position:0% 50%;}
    50% {background-position:100% 50%;}
    100% {background-position:0% 50%;}
}

.info-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 20px;
    margin-bottom: 25px;
    border-top: 3px solid #f1c40f;
    width: 100%;
    box-sizing: border-box;
}

.info-card-header {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f0f0f0;
}

.info-card-header i {
    margin-right: 10px;
    color: #f1c40f;
    font-size: 1.2em;
}

.info-card-header h4 {
    margin: 0;
    color: #2c3e50;
}

/* Fix back button link */
.user_view-menu-bar a {
    display: inline-flex;
    align-items: center;
    text-decoration: none;
}

/* Responsive adjustments */
@media (max-width: 992px) {
    .owner-addProp-form-left, 
    .owner-addProp-form-right {
        width: 100%;
        min-width: 100%;
    }
}

@media (max-width: 768px) {
    .logs-details-body {
        flex-direction: column;
    }
    
    .logs-details-left {
        width: 100%;
        margin-right: 0;
        margin-bottom: 20px;
    }
    
    .form-actions {
        flex-direction: column;
    }
}

/* Add these styles for image removal functionality */
.existing-image {
    position: relative;
}

.remove-image-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
}

.existing-image:hover .remove-image-btn {
    opacity: 1;
}

.remove-image-btn i {
    color: #e74c3c;
    font-size: 12px;
}

.marked-for-removal {
    border: 2px solid #e74c3c;
    opacity: 0.6;
}

.marked-for-removal img {
    filter: grayscale(50%);
}

.selected-images-actions {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.remove-selected-btn {
    background-color: #e74c3c;
    color: white;
}

.remove-selected-btn:hover:not(:disabled) {
    background-color: #c0392b;
}

.remove-selected-btn:disabled {
    background-color: #f0f0f0;
    color: #999;
    cursor: not-allowed;
}
</style>

<?php require 'serviceproviderFooter.view.php' ?>