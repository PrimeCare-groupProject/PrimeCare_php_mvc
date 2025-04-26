<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/serviceprovider/serviceOverview?service_id=<?= $service->service_id ?>'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Apply for <?= htmlspecialchars($service->name) ?> Service</h2>
</div>

<div class="application-container">
    <div class="service-info-card">
        <h3>Service Details</h3>
        <div class="service-details">
            <p><strong>Service Name:</strong> <?= htmlspecialchars($service->name) ?></p>
            <p><strong>Rate:</strong> LKR <?= number_format($service->cost_per_hour, 2) ?> per hour</p>
            <p><strong>Description:</strong> <?= htmlspecialchars($service->description) ?></p>
        </div>
    </div>
    
    <div class="application-form-card">
        <h3>Submit Your Application</h3>
        
        <form action="<?= ROOT ?>/serviceprovider/submitServiceApplication" method="POST" enctype="multipart/form-data" id="applicationForm">
            <input type="hidden" name="service_id" value="<?= $service->service_id ?>">
            
            <div class="form-group">
                <label for="proof_document">Upload Proof Document:</label>
                <p class="form-hint">Please upload a document that proves your qualification for this service (certification, license, portfolio, etc.)</p>
                
                <div class="upload-container">
                    <div class="file-upload-box" id="dropArea">
                        <input type="file" name="proof_document" id="proof_document" class="file-input" accept=".pdf,.jpg,.jpeg,.png" required>
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <div class="upload-text">
                            <span class="primary-text">Choose a file or drag it here</span>
                            <span class="secondary-text">Allowed formats: PDF, JPG, JPEG, PNG (Max: 5MB)</span>
                        </div>
                    </div>
                    
                    <div class="file-preview" id="filePreview">
                        <div class="preview-header">
                            <div class="file-info">
                                <i class="preview-icon"></i>
                                <div class="file-details">
                                    <span class="file-name">No file selected</span>
                                    <span class="file-size"></span>
                                </div>
                            </div>
                            <button type="button" class="remove-btn" id="removeFile">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" id="uploadProgress"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="submit-btn" id="submitBtn">
                    <span class="btn-text">Submit Application</span>
                    <!-- <div class="loading-spinner"></div> -->
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Main container styles */
    .application-container {
        max-width: 1000px;
        margin: 20px auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        padding: 20px;
    }
    
    /* Card styles */
    .service-info-card,
    .application-form-card {
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 28px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .service-info-card:hover,
    .application-form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 25px rgba(0,0,0,0.12);
    }
    
    /* Headings */
    .service-info-card h3,
    .application-form-card h3 {
        color: #333;
        margin-top: 0;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 20px;
        font-size: 20px;
        font-weight: 600;
        position: relative;
    }
    
    .service-info-card h3:after,
    .application-form-card h3:after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 60px;
        height: 3px;
        background: #FFD600;
        border-radius: 3px;
    }
    
    /* Service details */
    .service-details p {
        margin: 14px 0;
        line-height: 1.6;
        color: #444;
        display: flex;
        align-items: baseline;
    }
    
    .service-details p strong {
        min-width: 120px;
        color: #333;
    }
    
    /* Form elements */
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
        font-size: 16px;
    }
    
    .form-hint {
        font-size: 14px;
        color: #666;
        margin-bottom: 12px;
        line-height: 1.5;
    }
    
    /* File upload styling */
    .upload-container {
        margin-top: 15px;
        position: relative;
    }
    
    .file-upload-box {
        border: 2px dashed #ccc;
        border-radius: 10px;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s;
        background-color: #f9f9f9;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .file-upload-box:hover {
        border-color: #FFD600;
        background-color: #f5f7ff;
    }
    
    .file-upload-box.dragover {
        border-color: #FFD600;
        background-color: #f0f4ff;
    }
    
    .file-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    
    .upload-icon {
        font-size: 40px;
        color: #FFD600;
        margin-bottom: 15px;
    }
    
    .upload-text {
        display: flex;
        flex-direction: column;
    }
    
    .primary-text {
        font-size: 16px;
        font-weight: 500;
        color: #333;
        margin-bottom: 6px;
    }
    
    .secondary-text {
        font-size: 12px;
        color: #777;
    }
    
    /* File preview area */
    .file-preview {
        margin-top: 15px;
        background-color: #f5f7ff;
        border-radius: 10px;
        padding: 15px;
        display: none;
    }
    
    .file-preview.active {
        display: block;
        animation: fadeIn 0.3s ease-out;
    }
    
    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .file-info {
        display: flex;
        align-items: center;
    }
    
    .preview-icon {
        width: 40px;
        height: 40px;
        background-color: #e1e6ff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        color: #FFD600;
        font-size: 20px;
    }
    
    .preview-icon.pdf:before {
        content: '\f1c1';
        font-family: 'Font Awesome 5 Free';
    }
    
    .preview-icon.image:before {
        content: '\f1c5';
        font-family: 'Font Awesome 5 Free';
    }
    
    .file-details {
        display: flex;
        flex-direction: column;
    }
    
    .file-name {
        font-weight: 500;
        color: #333;
        font-size: 14px;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .file-size {
        color: #777;
        font-size: 12px;
    }
    
    .remove-btn {
        background: none;
        border: none;
        color: #777;
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        transition: all 0.2s;
    }
    
    .remove-btn:hover {
        background-color: #e0e0e0;
        color: #d32f2f;
    }
    
    /* Progress bar */
    .progress-container {
        height: 6px;
        background-color: #e0e0e0;
        border-radius: 3px;
        margin-top: 10px;
        overflow: hidden;
    }
    
    .progress-bar {
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, #FFD600, #6a8aff);
        border-radius: 3px;
        transition: width 0.3s ease;
    }
    
    /* Submit button */
    .form-actions {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
    }
    
    .submit-btn {
        background: linear-gradient(135deg, #FFD600, #FFB300);
        color: #333;
        border: none;
        border-radius: 8px;
        padding: 12px 30px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 214, 0, 0.3);
        background: linear-gradient(135deg, #FFC107, #FFA000);
    }
    
    .submit-btn:active {
        transform: translateY(0);
    }
    
    .btn-text {
        position: relative;
        z-index: 1;
    }
    
    .submit-btn.loading .btn-text {
        visibility: hidden;
    }
    
    /* .loading-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 24px;
        height: 24px;
        border: 3px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s linear infinite;
        display: none;
    } */
    
    /* .submit-btn.loading .loading-spinner {
        display: block;
    } */
    
    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* @keyframes spin {
        to {
            transform: translate(-50%, -50%) rotate(360deg);
        }
    } */
    
    /* Responsive design */
    @media (max-width: 768px) {
        .application-container {
            grid-template-columns: 1fr;
        }
        
        .service-details p strong {
            min-width: 100px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('proof_document');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.querySelector('.file-name');
    const fileSize = document.querySelector('.file-size');
    const removeFileBtn = document.getElementById('removeFile');
    const progressBar = document.getElementById('uploadProgress');
    const previewIcon = document.querySelector('.preview-icon');
    const form = document.getElementById('applicationForm');
    const submitBtn = document.getElementById('submitBtn');
    const uploadIcon = document.querySelector('.upload-icon');
    
    // Fix: Make entire drop area clickable to open file dialog
    dropArea.addEventListener('click', function(e) {
        // Don't trigger if clicking on the input itself to avoid double events
        if (e.target !== fileInput) {
            fileInput.click();
        }
    });
    
    // Specifically make the upload icon clickable
    uploadIcon.addEventListener('click', function(e) {
        e.stopPropagation(); // Prevent double triggering
        fileInput.click();
    });
    
    // Handle drag and drop events
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight() {
        dropArea.classList.add('dragover');
    }
    
    function unhighlight() {
        dropArea.classList.remove('dragover');
    }
    
    // Handle file drop
    dropArea.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        
        if (file) {
            handleFile(file);
            fileInput.files = dt.files; // Set the file input value to the dropped file
        }
    }
    
    // Handle file selection via input
    fileInput.addEventListener('change', function() {
        if (this.files[0]) {
            handleFile(this.files[0]);
        }
    });
    
    function handleFile(file) {
        // Update preview
        filePreview.classList.add('active');
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Set appropriate icon based on file type
        if (file.type.includes('pdf')) {
            previewIcon.className = 'preview-icon pdf';
        } else if (file.type.includes('image')) {
            previewIcon.className = 'preview-icon image';
        }
        
        // Simulate upload progress
        simulateUpload();
    }
    
    function formatFileSize(bytes) {
        if (bytes < 1024) {
            return bytes + ' B';
        } else if (bytes < 1048576) {
            return (bytes / 1024).toFixed(2) + ' KB';
        } else {
            return (bytes / 1048576).toFixed(2) + ' MB';
        }
    }
    
    // Simulate upload progress
    function simulateUpload() {
        let width = 0;
        progressBar.style.width = '0%';
        
        const interval = setInterval(() => {
            if (width >= 100) {
                clearInterval(interval);
            } else {
                width += 5;
                progressBar.style.width = width + '%';
            }
        }, 50);
    }
    
    // Remove file button
    removeFileBtn.addEventListener('click', function(e) {
        e.stopPropagation(); 
        e.preventDefault(); 
        
        // Add visual feedback before removing
        const fileInfoEl = this.closest('.preview-header').querySelector('.file-info');
        
        // Animate the removal
        fileInfoEl.style.opacity = '0.5';
        progressBar.style.width = '0%';
        
        // Create removal animation
        filePreview.classList.add('removing');
        
        // Delay actual removal to show animation
        setTimeout(() => {
            // Clear file input
            fileInput.value = '';
            
            // Reset the UI
            filePreview.classList.remove('active', 'removing');
            fileName.textContent = 'No file selected';
            fileSize.textContent = '';
            
            // Reset any validation states/messages if they exist
            const errorElement = document.querySelector('.file-error');
            if (errorElement) {
                errorElement.remove();
            }
            
            // Return focus to the drop area
            dropArea.focus();
            
            // Visual feedback that upload area is ready again
            dropArea.classList.add('highlight');
            setTimeout(() => {
                dropArea.classList.remove('highlight');
            }, 600);
            
        }, 300);
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        if (!fileInput.files[0]) {
            e.preventDefault();
            alert('Please select a file to upload');
            return;
        }
        
        // Show loading state
        submitBtn.classList.add('loading');
    });
});
</script>

<?php require 'serviceproviderFooter.view.php' ?>