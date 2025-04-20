<?php require 'serviceproviderHeader.view.php' ?>

<!-- Add FontAwesome  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<?php
// Ensure $property_image is just the filename
if (!empty($property_image)) {
    $property_image = basename(str_replace('\\', '/', $property_image));
}
?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/serviceprovider/repairRequests">
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>Service Completion Log</h2>
    <button type="button" class="scroll-to-log-btn animated-gradient-btn"
        onclick="document.getElementById('addLogSection').scrollIntoView({ behavior: 'smooth' });">
        <i class="fas fa-arrow-down"></i> Go to Add Log Form
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

<div class="logs-page-container">
    <div class="logs-content">
        <div class="logs-details-section">
            <div class="logs-details-header">
                <h3>Service Request Details</h3>
                <p>Service ID: <?= htmlspecialchars($service_id) ?></p>
            </div>
            <div class="logs-details-body">
                <div class="logs-details-left">
                    <!-- Property Image -->
                    <div class="property-image-container">
                        <div class="property-image">
                            <?php if (!empty($property_image)): ?>
                                <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= htmlspecialchars($property_image) ?>"
                                    alt="Property Image" onerror="this.src='<?= ROOT ?>/assets/images/listing_alt.jpg'">
                            <?php else: ?>
                                <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Property Image">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="logs-details-right">
                    <div class="detail-row">
                        <span class="detail-label">Service Type:</span>
                        <span class="detail-value"><?= htmlspecialchars($service_type) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Property Name:</span>
                        <span class="detail-value"><?= htmlspecialchars($property_name) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Property Address:</span>
                        <span class="detail-value"><?= htmlspecialchars($property_address) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Requester:</span>
                        <span class="detail-value"><?= htmlspecialchars($requester_name) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Contact:</span>
                        <span class="detail-value"><?= htmlspecialchars($requester_contact) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Service Description:</span>
                        <div class="detail-long-text">
                            <?= nl2br(htmlspecialchars($service_description)) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="logs-form-section">
            <h3 id="addLogSection">Complete Service Log</h3>
            <form method="POST" action="<?= ROOT ?>/serviceprovider/addLogs" enctype="multipart/form-data" id="logForm">
                <!-- Service ID (hidden) -->
                <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">
                <!-- Hidden input for images to remove (will be used if editing existing logs) -->
                <input type="hidden" name="images_to_remove" id="images-to-remove-input" value="">
                
                <div class="owner-addProp-container">
                    <div class="owner-addProp-form-left">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-tools"></i>
                                <h4>Service Information</h4>
                            </div>
    
                            <!-- Repair Type -->
                            <div class="form-group">
                                <label class="input-label">Repair Type</label>
                                <input type="text" name="service_type" value="<?= htmlspecialchars($service_type) ?>" class="form-input highlight-field" readonly>
                            </div>
    
                            <!-- Property Name -->
                            <div class="form-group">
                                <label class="input-label">Property Name</label>
                                <input type="text" name="property_name" value="<?= htmlspecialchars($property_name) ?>" class="form-input highlight-field" readonly>
                            </div>
    
                            <!-- Description -->
                            <div class="form-group">
                                <label class="input-label">Completion Report <span class="required">*</span></label>
                                <textarea name="description" class="form-input form-textarea" required
                                    placeholder="Provide detailed description of the repair work done"></textarea>
                            </div>
                        </div>
                        
                        <!-- Service Documentation Images Upload -->
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-camera-retro"></i>
                                <h4>Service Documentation</h4>
                            </div>
                            
                            <div class="form-group">
                                <label class="input-label">Upload Repair Images <span class="required">*</span></label>
                                <p class="upload-instruction">Upload images showing the completed work (before/after images are recommended)</p>
                                <div class="owner-addProp-file-upload">
                                    <input type="file" name="property_image[]" id="property_image" class="form-input" multiple required accept="image/jpeg, image/png, image/jpg">
                                    <div class="owner-addProp-upload-area">
                                        <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload Icon" class="owner-addProp-upload-logo">
                                        <p class="upload-area-no-margin">Drop your work documentation images here</p>
                                        <button type="button" class="secondary-btn" onclick="document.getElementById('property_image').click()">
                                            Choose Files
                                        </button>
                                    </div>
                                </div>
                                <div id="uploaded-files" class="owner-addProp-uploaded-files"></div>
        
                                <!-- Upload progress container -->
                                <div id="upload-progress-container" style="display: none;">
                                    <div class="progress-info">Uploading files... <span id="upload-percentage">0%</</span></div>
                                    <div class="progress-bar-container">
                                        <div id="upload-progress-bar" class="progress-bar"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Replace the existing images container with this version -->
                            <div class="form-group">
                                <label class="input-label">Existing Documentation Images</label>
                                <p class="upload-instruction">Select images to remove before saving</p>
                                <div class="existing-images-container">
                                    <?php if (isset($existing_images) && !empty($existing_images)): ?>
                                        <?php foreach($existing_images as $index => $image): ?>
                                            <div class="existing-image" data-image="<?= htmlspecialchars($image) ?>">
                                                <img src="<?= ROOT ?>/assets/images/uploads/service_logs/<?= htmlspecialchars($image) ?>" 
                                                    alt="Service Documentation <?= $index+1 ?>"
                                                    onerror="this.src='<?= ROOT ?>/assets/images/listing_alt.jpg'">
                                                
                                                <!-- Simplified button with inline styles for maximum visibility -->
                                                <button type="button" onclick="toggleImageRemoval(this)" 
                                                    style="position: absolute; top: 5px; right: 5px; background-color: #e74c3c; 
                                                    color: white; width: 25px; height: 25px; border-radius: 50%; border: none; 
                                                    font-weight: bold; cursor: pointer; z-index: 1000; display: flex; 
                                                    align-items: center; justify-content: center;">
                                                    X
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div style="padding: 20px; text-align: center; background: #f8f9fa; border-radius: 4px;">
                                            <p>No existing images found</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if(isset($existing_images) && !empty($existing_images)): ?>
                            <div class="selected-images-actions">
                                <span><strong id="selected-count">0</strong> images selected for removal</span>
                                <button type="button" class="secondary-btn remove-selected-btn" onclick="confirmRemoveSelected()" disabled>
                                    Remove Selected
                                </button>
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
    
                            <!-- Property ID -->
                            <div class="form-group">
                                <label class="input-label">Property ID</label>
                                <input type="text" name="property_id" value="<?= htmlspecialchars($property_id) ?>" class="form-input highlight-field" readonly>
                            </div>
    
                            <!-- Total Hours -->
                            <div class="form-group">
                                <label class="input-label">Total Hours <span class="required">*</span></label>
                                <input type="number" id="total-hours" name="total_hours" class="form-input" step="0.5" min="0.5" required
                                    placeholder="Enter total hours spent on repair">
                            </div>
                            
                            <!-- Hourly Rate (hidden) -->
                            <input type="hidden" id="hourly-rate" value="<?= htmlspecialchars($hourly_rate ?? 2500) ?>">
                            
                            <!-- Additional Charges Section -->
                            <div class="form-group">
                                <label class="input-label">Additional Charges (LKR)</label>
                                <input type="number" id="additional-charges" name="additional_charges" class="form-input" step="0.01" min="0" value="0"
                                    placeholder="Enter any additional charges">
                            </div>
                                
                            <div class="form-group">
                                <label class="input-label">Reason for Additional Charges</label>
                                <textarea id="charges-reason" name="additional_charges_reason" class="form-input" 
                                    placeholder="Explain what the additional charges are for (materials, extra services, etc.)"><?= htmlspecialchars($additional_charges_reason ?? '') ?></textarea>
                            </div>
    
                            <!-- Expected Earnings -->
                            <div class="form-group">
                                <label class="input-label">Expected Earnings</label>
                                <div class="earnings-display">
                                    <input type="text" id="expected-earnings" class="form-input highlight-amount" readonly>
                                </div>
                            </div>
    
                            <div class="cost-summary">
                                <div class="cost-item">
                                    <span>Service Cost:</span>
                                    <span id="service-cost">LKR 0.00</span>
                                </div>
                                <div class="cost-item">
                                    <span>Additional Charges:</span>
                                    <span id="additional-cost">LKR 0.00</span>
                                </div>
                                <div class="cost-item total">
                                    <span>Total Cost:</span>
                                    <span id="total-cost">LKR 0.00</span>
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="form-actions">
                                <button type="submit" name="save_operation" value="1" class="tbtn save-changes-btn">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <button type="submit" name="complete_service" value="1" class="tbtn save-tbtn">
                                    <i class="fas fa-check-circle"></i> Complete Service
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Image preview and removal functionality
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('property_image');
    const uploadArea = document.querySelector('.owner-addProp-upload-area');
    const uploadedFilesContainer = document.getElementById('uploaded-files');
    const progressContainer = document.getElementById('upload-progress-container');
    const progressBar = document.getElementById('upload-progress-bar');
    const percentageText = document.getElementById('upload-percentage');
    let selectedFiles = []; // To track selected files
    
    // File selection change handler
    fileInput.addEventListener('change', function(e) {
        uploadedFilesContainer.innerHTML = '';
        
        if (this.files.length > 0) {
            // Show progress container
            progressContainer.style.display = 'block';
            progressBar.style.width = '0%';
            percentageText.textContent = '0%';
            
            // Track files
            selectedFiles = Array.from(this.files);
            
            // Simulate upload progress
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    
                    // Simulate completion delay
                    setTimeout(() => {
                        percentageText.textContent = 'Complete!';
                        setTimeout(() => {
                            progressContainer.style.display = 'none';
                        }, 1000);
                    }, 500);
                }
                
                progressBar.style.width = progress + '%';
                percentageText.textContent = Math.round(progress) + '%';
            }, 100);
            
            // Process each file
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                createFilePreview(file, i);
            }
        }
    });
    
    function createFilePreview(file, index) {
        // Create container
        const container = document.createElement('div');
        container.className = 'uploaded-file-preview';
        container.dataset.index = index;
        
        // Create preview area with image
        const previewArea = document.createElement('div');
        previewArea.className = 'preview-image-container';
        
        // Create image element
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            img.className = 'preview-image';
            
            // Use FileReader to get image data
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
            
            previewArea.appendChild(img);
        } else {
            // For non-image files
            const iconSpan = document.createElement('span');
            iconSpan.className = 'file-icon';
            iconSpan.innerHTML = '📄';
            previewArea.appendChild(iconSpan);
        }
        
        container.appendChild(previewArea);
        
        // Add remove button at the top right
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'remove-upload-btn';
        removeBtn.innerHTML = '✕';
        removeBtn.onclick = function() {
            removeUploadedFile(container, index);
        };
        container.appendChild(removeBtn);
        
        // Create file info area at the bottom
        const infoArea = document.createElement('div');
        infoArea.className = 'file-info';
        
        // Add file name
        const fileName = document.createElement('span');
        fileName.className = 'file-name';
        fileName.textContent = file.name;
        infoArea.appendChild(fileName);
        
        // Add file size
        const fileSize = document.createElement('span');
        fileSize.className = 'file-size';
        fileSize.textContent = formatFileSize(file.size);
        infoArea.appendChild(fileSize);
        
        container.appendChild(infoArea);
        
        // Add to DOM
        uploadedFilesContainer.appendChild(container);
    }
    
    // Function to remove a file from the upload list
    function removeUploadedFile(element, fileIndex) {
        // Add animation class
        element.classList.add('file-removing');
        
        // Remove after animation completes
        setTimeout(() => {
            element.remove();
            
            // Remove from selectedFiles array
            selectedFiles = selectedFiles.filter((_, index) => index !== fileIndex);
            
            // Update all indexes after removal
            const containers = uploadedFilesContainer.querySelectorAll('.uploaded-file-preview');
            containers.forEach((container, newIndex) => {
                container.dataset.index = newIndex;
                container.querySelector('.remove-upload-btn').onclick = function() {
                    removeUploadedFile(container, newIndex);
                };
            });
            
            // Show message if no files left
            if (uploadedFilesContainer.children.length === 0) {
                const noFilesMsg = document.createElement('div');
                noFilesMsg.className = 'no-files-message';
                noFilesMsg.textContent = 'No files selected for upload';
                noFilesMsg.style.padding = '15px';
                noFilesMsg.style.textAlign = 'center';
                noFilesMsg.style.color = '#999';
                noFilesMsg.style.fontStyle = 'italic';
                uploadedFilesContainer.appendChild(noFilesMsg);
                
                // Clear file input
                fileInput.value = '';
            }
            
            // Update the file input object using DataTransfer
            updateFileInput();
        }, 300);
    }
    
    // Function to update the file input with the current selection
    function updateFileInput() {
        // Create a new DataTransfer object
        const dataTransfer = new DataTransfer();
        
        // Add remaining files
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        
        // Update the file input
        fileInput.files = dataTransfer.files;
    }
    
    // Helper function to format file size
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
    }
    
    // Drag and drop functionality
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.backgroundColor = '#fef9e7'; 
        this.style.borderColor = '#f1c40f';     
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.backgroundColor = '';
        this.style.borderColor = '';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.backgroundColor = '';
        this.style.borderColor = '';
        
        // Trigger file input change with the dropped files
        fileInput.files = e.dataTransfer.files;
        
        // Manually trigger change event
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    });

    // Image removal functionality
    const imagesToRemove = new Set();

    window.toggleImageRemoval = function(btn) {
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
    };

    window.confirmRemoveSelected = function() {
        if (imagesToRemove.size === 0) return;
        
        const confirmMessage = `Are you sure you want to remove ${imagesToRemove.size} selected image${imagesToRemove.size > 1 ? 's' : ''}?`;
        
        if (confirm(confirmMessage)) {
            // Images will be removed when the form is submitted
            alert(`${imagesToRemove.size} image${imagesToRemove.size > 1 ? 's' : ''} will be removed when you save changes`);
        }
    };
});

// Cost calculation functionality
document.addEventListener('DOMContentLoaded', function() {
    const totalHoursInput = document.getElementById('total-hours');
    const additionalChargesInput = document.getElementById('additional-charges');
    const chargesReasonInput = document.getElementById('charges-reason');
    const serviceCostDisplay = document.getElementById('service-cost');
    const additionalCostDisplay = document.getElementById('additional-cost');
    const totalCostDisplay = document.getElementById('total-cost');
    const expectedEarningsInput = document.getElementById('expected-earnings');
    const hourlyRateInput = document.getElementById('hourly-rate');
    
    function updateCosts() {
        const hours = parseFloat(totalHoursInput.value) || 0;
        const additionalCharges = parseFloat(additionalChargesInput.value) || 0;
        const hourlyRate = parseFloat(hourlyRateInput.value) || 2500;
        
        const serviceCost = hours * hourlyRate;
        const totalCost = serviceCost + additionalCharges;
        
        serviceCostDisplay.textContent = `LKR ${serviceCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        additionalCostDisplay.textContent = `LKR ${additionalCharges.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        totalCostDisplay.textContent = `LKR ${totalCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        expectedEarningsInput.value = totalCost.toLocaleString('en-US');
        
        // Make reason field required if there are additional charges
        if (additionalCharges > 0) {
            chargesReasonInput.setAttribute('required', '');
            chargesReasonInput.classList.add('required-field');
        } else {
            chargesReasonInput.removeAttribute('required');
            chargesReasonInput.classList.remove('required-field');
        }
    }
    
    totalHoursInput.addEventListener('input', updateCosts);
    additionalChargesInput.addEventListener('input', updateCosts);
    
    // Initialize costs
    updateCosts();
});

// Add drag and drop functionality
const uploadArea = document.querySelector('.owner-addProp-upload-area');
const fileInput = document.getElementById('property_image');

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
document.getElementById('logForm').addEventListener('submit', function(event) {
    // Update the hidden input with final list of images to remove
    document.getElementById('images-to-remove-input').value = Array.from(imagesToRemove).join(',');
    
    // Validate additional charges reason
    const additionalCharges = parseFloat(document.getElementById('additional-charges').value) || 0;
    const chargesReason = document.getElementById('charges-reason').value.trim();
    
    if (additionalCharges > 0 && chargesReason === '') {
        alert('Please provide a reason for the additional charges.');
        document.getElementById('charges-reason').focus();
        event.preventDefault();
        return false;
    }
    
    // Let the form submit naturally - no need to add hidden inputs
    // The clicked button will include its own name/value in the form data
});

// Add this to your existing JavaScript
function saveChanges() {
    // Update the hidden input with final list of images to remove
    document.getElementById('images-to-remove-input').value = Array.from(imagesToRemove).join(',');
    
    // Set a flag to indicate this is a save operation, not a complete operation
    const saveOperationInput = document.createElement('input');
    saveOperationInput.type = 'hidden';
    saveOperationInput.name = 'save_operation';
    saveOperationInput.value = '1';
    document.getElementById('logForm').appendChild(saveOperationInput);
    
    // Submit the form
    document.getElementById('logForm').submit();
}

// Style the Save Changes button
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .save-changes-btn {
            background-color: #3498db;
            color: white;
            margin-right: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .save-changes-btn:hover {
            background-color: #2980b9;
        }
        
        .save-changes-btn i, .save-tbtn i {
            margin-right: 8px;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    `;
    document.head.appendChild(style);
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

/* Property image styling */
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
    position: relative; /* For positioning the remove button */
    transition: all 0.3s ease;
}

.remove-upload-btn {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    opacity: 0.8;
    transition: opacity 0.2s, transform 0.2s;
}

.remove-upload-btn:hover {
    opacity: 1;
    transform: translateY(-50%) scale(1.1);
}

.file-removing {
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s ease;
}

.no-files-message {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
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
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 15px;
}

.existing-image {
    position: relative !important;
    height: 150px !important; /* Increased height for better visibility */
    width: 100% !important; /* Take full width */
    border-radius: 6px !important;
    overflow: hidden !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
    border: 2px solid transparent !important;
    transition: all 0.2s ease !important;
}

/* Make the button always visible */
.existing-image button {
    opacity: 1 !important; /* Always visible */
    background: rgba(231, 76, 60, 0.9) !important;
}

/* Updated styles for the selected images actions bar */
.selected-images-actions {
    margin-top: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-radius: 4px;
    border-left: 3px solid #e74c3c;
}

.uploaded-file-preview {
    display: flex;
    flex-direction: column; 
    padding: 15px;
    margin-bottom: 12px;
    background-color: #f9f9f9;
    border-radius: 6px;
    border-top: 3px solid #f1c40f; 
    position: relative;
    transition: all 0.3s ease;
    animation: slideIn 0.3s forwards;
    width: 100%;
}

.preview-image-container {
    width: 100%;
    height: 180px; 
    border-radius: 4px;
    overflow: hidden;
    margin-right: 0;
    margin-bottom: 12px; 
    flex-shrink: 0;
    background-color: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Update file info for vertical layout */
.file-info {
    display: flex;
    justify-content: space-between; /* Put filename and size side by side */
    width: 100%;
    padding-top: 8px;
}

/* Update remove button position for vertical layout */
.remove-upload-btn {
    position: absolute;
    right: 10px;
    top: 10px; /* Position at top instead of middle */
    transform: none; /* Remove the Y translation */
    z-index: 10;
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

.form-input, .input-field {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 15px;
    transition: border-color 0.3s;
    box-sizing: border-box;
}

.form-input:focus, .input-field:focus {
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

.highlight-field {
    background-color: #fef9e7;
    color: #495057;
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
.remove-image-btn {
    position: absolute !important;
    top: 5px !important;
    right: 5px !important;
    background-color: rgba(255, 255, 255, 0.9) !important;
    border: 1px solid #e74c3c !important;
    border-radius: 50% !important;
    width: 28px !important;
    height: 28px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    opacity: 1 !important;
    z-index: 100 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.2) !important;
}

.remove-image-btn i {
    color: #e74c3c !important;
    font-size: 14px !important;
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

/* New styles for uploaded images display */
.uploaded-file-preview {
    display: flex;
    flex-direction: column; /* Changed to column layout */
    padding: 15px;
    margin-bottom: 12px;
    background-color: #f9f9f9;
    border-radius: 6px;
    border-top: 3px solid #f1c40f; /* Changed from border-left to border-top */
    position: relative;
    transition: all 0.3s ease;
    animation: slideIn 0.3s forwards;
    width: 100%;
}

@keyframes slideIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.preview-image-container {
    width: 100%;
    height: 180px; /* Make image taller */
    border-radius: 4px;
    overflow: hidden;
    margin-right: 0;
    margin-bottom: 12px; /* Add margin below image */
    flex-shrink: 0;
    background-color: #eee;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-icon {
    font-size: 32px;
    color: #666;
}

.file-info {
    display: flex;
    justify-content: space-between; /* Put filename and size side by side */
    width: 100%;
    padding-top: 8px;
}

.file-name {
    font-weight: 500;
    color: #333;
    margin-bottom: 3px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: calc(100% - 40px);
}

.file-size {
    color: #777;
    font-size: 12px;
}

.remove-upload-btn {
    position: absolute;
    right: 10px;
    top: 10px; /* Position at top instead of middle */
    transform: none; /* Remove the Y translation */
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 12px;
    opacity: 0.8;
    transition: all 0.2s;
}

.remove-upload-btn:hover {
    opacity: 1;
    transform: scale(1.1);
    background-color: #c0392b;
}

.file-removing {
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s ease;
}

.existing-images-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 15px;
}

@media (max-width: 992px) {
    .existing-images-container {
        grid-template-columns: repeat(3, 1fr); /* 3 items per row on smaller screens */
    }
}

@media (max-width: 768px) {
    .existing-images-container {
        grid-template-columns: repeat(2, 1fr); /* 2 items per row on mobile */
    }
}

.existing-image {
    position: relative !important;
    height: 150px !important; /* Increased height for better visibility */
    width: 100% !important; /* Take full width */
    border-radius: 6px !important;
    overflow: hidden !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1) !important;
    border: 2px solid transparent !important;
    transition: all 0.2s ease !important;
}

.existing-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.existing-image:hover img {
    transform: scale(1.05);
}

.existing-image button {
    position: absolute;
    top: 6px;
    right: 6px;
    background: rgba(231, 76, 60, 0.85);
    color: white;
    border: none;
    border-radius: 50%;
    width: 26px;
    height: 26px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s, transform 0.2s;
    z-index: 10;
}

.existing-image:hover button {
    opacity: 1;
}

.existing-image button:hover {
    transform: scale(1.1);
    background: rgba(192, 57, 43, 0.95);
}
</style>

<?php require 'serviceproviderFooter.view.php' ?>