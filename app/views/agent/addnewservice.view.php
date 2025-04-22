<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/services'>
        <button class="back-btn-new"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>New Service</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/create" enctype="multipart/form-data" class="service-form-v2">
    <div class="service-form-container">
        <div class="service-form-header">
            <h2><i class="fas fa-tools"></i> Register New Service</h2>
            <p class="service-form-subtitle">Provide details about your professional service</p>
        </div>

        <div class="service-form-body">
            <div class="service-form-group">
                <label class="service-form-label">
                    <span>Service Name</span>
                    <div class="service-input-wrapper">
                        <i class="fas fa-tag input-icon"></i>
                        <input type="text" name="name" placeholder="e.g. Electrical Repair" class="service-form-input" required>
                    </div>
                </label>
            </div>

            <div class="service-form-group">
                <label class="service-form-label">
                    <span>Hourly Rate (LKR)</span>
                    <div class="service-input-wrapper">
                        <i class="fas fa-money-bill-wave input-icon"></i>
                        <input type="number" name="cost_per_hour" placeholder="e.g. 3500" class="service-form-input" required>
                    </div>
                </label>
            </div>

            <div class="service-form-group full-width">
                <label class="service-form-label">
                    <span>Service Description</span>
                    <textarea name="description" placeholder="Describe your service expertise, qualifications, and what clients can expect..." class="service-form-textarea" rows="4" required></textarea>
                    <i class="fas fa-align-left textarea-icon"></i>
                </label>
            </div>

            <div class="service-form-group full-width">
                <label class="service-form-label">
                    <span>Service Images (Max 6)</span>
                    <div class="service-upload-container">
                        <input type="file" name="service_images[]" id="service_images_v2" class="service-file-input" multiple accept=".png, .jpg, .jpeg" data-max-files="6" onchange="previewServiceImages(event)" required>
                        <div class="service-upload-area" id="drop-zone">
                            <div class="upload-content">
                                <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload" class="upload-icon">
                                <p class="upload-text">Drag & drop files here or</p>
                                <button type="button" class="service-upload-btn" onclick="document.getElementById('service_images_v2').click()">
                                    <i class="fas fa-folder-open"></i> Browse Files
                                </button>
                                <p class="upload-hint">Supports: JPG, PNG (Max 6 files)</p>
                            </div>
                        </div>
                    </div>
                </label>
                <div id="service-image-preview" class="service-preview-grid"></div>
            </div>

            <div class="service-form-actions">
                <button type="submit" class="service-submit-btn">
                    <i class="fas fa-check-circle"></i> Register Service
                </button>
            </div>
        </div>
    </div>
</form>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="service-flash-message">
        <?= $_SESSION['flash_message']; ?>
        <?php unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<script>
function previewServiceImages(event) {
    const files = event.target.files;
    const container = document.getElementById('service-image-preview');
    container.innerHTML = '';

    for (let i = 0; i < Math.min(files.length, 6); i++) {
        const file = files[i];
        if (!file.type.match('image.*')) continue;

        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.classList.add('service-preview-img');
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
}

// Drag and drop functionality
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('service_images_v2');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('highlight');
});

['dragleave', 'dragend'].forEach(type => {
    dropZone.addEventListener(type, () => {
        dropZone.classList.remove('highlight');
    });
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('highlight');
    
    if (e.dataTransfer.files.length) {
        fileInput.files = e.dataTransfer.files;
        const event = new Event('change');
        fileInput.dispatchEvent(event);
    }
});
</script>
<?php require_once 'agentFooter.view.php'; ?>