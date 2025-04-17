<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/serviceprovider/repairRequests"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons"></a>
    <h2>Service Completion Log</h2>
</div>

<div class="logs-page-container">
    <div class="section-header">
        <h3>Complete Service Details</h3>
        <p>Please provide information about the completed repair service</p>
    </div>

    <form method="POST" action="<?= ROOT ?>/serviceprovider/addLogs" enctype="multipart/form-data" id="logForm">
        <div class="owner-addProp-container">
            <div class="owner-addProp-form-left">
                <!-- Service ID (hidden) -->
                <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">

                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-tools"></i>
                        <h4>Service Information</h4>
                    </div>

                    <!-- Repair Type -->
                    <div class="form-group">
                        <label class="input-label">Repair Type</label>
                        <input type="text" name="service_type" value="<?= htmlspecialchars($service_type) ?>" class="input-field highlight-field" readonly>
                    </div>

                    <!-- Property Name -->
                    <div class="form-group">
                        <label class="input-label">Property Name</label>
                        <input type="text" name="property_name" value="<?= htmlspecialchars($property_name) ?>" class="input-field highlight-field" readonly>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="input-label">Completion Report <span class="required-field">*</span></label>
                        <textarea name="description" class="input-field textarea-field" required
                            placeholder="Provide detailed description of the repair work done"></textarea>
                    </div>
                </div>

                <!-- Upload Images -->
                <div class="info-card">
                    <div class="info-card-header">
                        <i class="fas fa-images"></i>
                        <h4>Service Documentation</h4>
                    </div>
                    
                    <div class="form-group">
                        <label class="input-label">Upload Repair Images <span class="required-field">*</span></label>
                        <div class="owner-addProp-file-upload">
                            <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                            <div class="owner-addProp-upload-area">
                                <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload Icon" class="owner-addProp-upload-logo">
                                <p class="upload-area-no-margin">Drop your files here</p>
                                <button type="button" class="secondary-btn" onclick="document.getElementById('property_image').click()">
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
                        <input type="text" name="property_id" value="<?= htmlspecialchars($property_id) ?>" class="input-field highlight-field" readonly>
                    </div>

                    <!-- Total Hours -->
                    <div class="form-group">
                        <label class="input-label">Total Hours <span class="required-field">*</span></label>
                        <input type="number" id="total-hours" name="total_hours" class="input-field" step="0.5" min="0.5" required
                            placeholder="Enter total hours spent on repair">
                    </div>
                    
                    <!-- Hourly Rate (hidden) -->
                    <input type="hidden" id="hourly-rate" value="<?= htmlspecialchars($hourly_rate ?? 2500) ?>">
                    
                    <!-- Additional Charges Section -->
                    <div class="form-group">
                        <label class="input-label">Additional Charges (LKR)</label>
                        <input type="number" id="additional-charges" name="additional_charges" class="input-field" step="0.01" min="0" value="0"
                            placeholder="Enter any additional charges">
                    </div>
                        
                    <div class="form-group">
                        <label class="input-label">Reason for Additional Charges</label>
                        <textarea name="additional_charges_reason" id="charges-reason" class="input-field" 
                            placeholder="Explain what the additional charges are for (materials, extra services, etc.)"><?= htmlspecialchars($additional_charges_reason ?? '') ?></textarea>
                    </div>

                    <!-- Expected Earnings -->
                    <div class="form-group">
                        <label class="input-label">Expected Earnings</label>
                        <div class="earnings-display">
                            <input type="text" id="expected-earnings" class="input-field highlight-amount" readonly>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="buttons-to-right">
                        <button type="submit" class="primary-btn">Complete Service</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Enhanced styling for logs page */
.logs-page-container {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

/* Enhanced section header styling */
.section-header {
    margin-bottom: 30px;
    padding-bottom: 15px;
    position: relative;
    border-bottom: 1px solid #f0f0f0;
    background: white;
    border-radius: 15px;
}

.section-header::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -1px;
    margin-left: 20px;
    width: 180px;
    border-radius: 15px;
    height: 3px;
    background-color: #f1c40f; /* Yellow accent line */
}

.section-header h3 {
    margin-bottom: 8px;
    color: #2c3e50;
    font-size: 24px;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.section-header p {
    color: #7f8c8d;
    margin: 0;
    font-size: 15px;
    line-height: 1.5;
    max-width: 80%;
}

.info-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 20px;
    margin-bottom: 25px;
    border-top: 3px solid #f1c40f; /* Yellow accent border */
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
    color: #f1c40f; /* Changed from blue to yellow */
    font-size: 1.2em;
}

.info-card-header h4 {
    margin: 0;
    color: #2c3e50;
}

.form-group {
    margin-bottom: 18px;
}

.input-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #34495e;
}

.input-field {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.input-field:focus {
    border-color: #f1c40f; /* Changed from blue to yellow */
    box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.2); /* Changed to yellow with transparency */
    outline: none;
}

.highlight-field {
    background-color: #fef9e7; /* Very light yellow background */
    color: #495057;
}

.highlight-amount {
    font-weight: bold;
    font-size: 18px;
    color: #f39c12; /* Yellow/orange for amounts */
    background-color: #fef9e7;
    text-align: right;
}

.textarea-field {
    min-height: 120px;
    resize: vertical;
}

.required-field {
    color: #e74c3c;
}

.owner-addProp-upload-area {
    border: 2px dashed #ddd;
    border-radius: 6px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.owner-addProp-upload-area:hover {
    border-color: #f1c40f; /* Changed from blue to yellow */
    background-color: #fef9e7; /* Light yellow background */
}

.owner-addProp-upload-logo {
    width: 60px;
    margin-bottom: 10px;
    opacity: 0.7;
}

.secondary-btn {
    background-color: #f0f0f0;
    color: #333;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    margin-top: 10px;
    transition: background-color 0.3s;
}

.secondary-btn:hover {
    background-color: #e0e0e0;
}

.primary-btn {
    background-color: #f1c40f; /* Changed from blue to yellow */
    color: #333; /* Darker text for better contrast against yellow */
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
    transition: background-color 0.3s;
}

.primary-btn:hover {
    background-color: #f39c12; /* Darker yellow on hover */
}

/* Progress bar styling */
.progress-bar-container {
    width: 100%;
    height: 8px;
    background-color: #f0f0f0;
    border-radius: 4px;
    margin: 10px 0 15px 0;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #f1c40f, #f39c12); /* Yellow gradient */
    border-radius: 4px;
    transition: width 0.3s ease;
}

.progress-info {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
}

.uploaded-file {
    display: flex;
    align-items: center;
    padding: 10px;
    margin-bottom: 8px;
    background: #f9f9f9;
    border-radius: 4px;
    border-left: 3px solid #f1c40f; /* Changed from green to yellow */
    transition: all 0.3s ease;
}

.upload-complete {
    animation: fadeInYellow 0.5s forwards; /* Changed animation name */
}

@keyframes fadeInYellow {
    0% { background-color: #f9f9f9; }
    100% { background-color: #fef9e7; } /* Light yellow completion */
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
    /* height: 20px; */
    color: #f39c12; 
    font-weight: bold;
}

#expected-earnings {
    padding-left: 45px;
}

/* Additional enhanced styling for section-header */
@media (min-width: 768px) {
    .section-header {
        display: flex;
        flex-direction: column;
        padding-left: 15px;
        padding-bottom: 20px;
    }
    
    .section-header h3 {
        font-size: 28px;
        position: relative;
    }
    
    .section-header h3::before {
        content: '';
        position: absolute;
        left: -15px;
        top: 8px;
        height: 20px;
        width: 5px;
        background-color: #f1c40f;
        border-radius: 3px;
    }
}
</style>

<script>
// Enhanced file upload preview with animated progress bar
document.getElementById('property_image').addEventListener('change', function(e) {
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

// Real-time earnings calculation
document.addEventListener('DOMContentLoaded', function() {
    const totalHoursInput = document.getElementById('total-hours');
    const additionalChargesInput = document.getElementById('additional-charges');
    const hourlyRateInput = document.getElementById('hourly-rate');
    const expectedEarningsInput = document.getElementById('expected-earnings');
    const chargesReasonInput = document.getElementById('charges-reason');
    
    function calculateEarnings() {
        const hours = parseFloat(totalHoursInput.value) || 0;
        const additionalCharges = parseFloat(additionalChargesInput.value) || 0;
        const hourlyRate = parseFloat(hourlyRateInput.value) || 2500; // Default to 2500 LKR if not set
        
        const hourlyTotal = hours * hourlyRate;
        const totalEarnings = hourlyTotal + additionalCharges;
        
        // Format with commas as thousands separators
        expectedEarningsInput.value = totalEarnings.toLocaleString('en-US');
        
        // Toggle reason field required state based on additional charges
        if (additionalCharges > 0) {
            chargesReasonInput.setAttribute('required', 'required');
            chargesReasonInput.classList.add('required-field-input');
        } else {
            chargesReasonInput.removeAttribute('required');
            chargesReasonInput.classList.remove('required-field-input');
        }
    }
    
    // Add event listeners for real-time updates
    totalHoursInput.addEventListener('input', calculateEarnings);
    additionalChargesInput.addEventListener('input', calculateEarnings);
    
    // Initial calculation
    calculateEarnings();
    
    // Add form validation for additional charges reason
    document.getElementById('logForm').addEventListener('submit', function(e) {
        const additionalCharges = parseFloat(additionalChargesInput.value) || 0;
        const reason = chargesReasonInput.value.trim();
        
        if (additionalCharges > 0 && reason === '') {
            e.preventDefault();
            alert('Please provide a reason for the additional charges.');
            chargesReasonInput.focus();
        }
    });
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
</script>

<?php require 'serviceproviderFooter.view.php' ?>