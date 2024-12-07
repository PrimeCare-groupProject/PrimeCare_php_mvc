<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/dashboard/repairRequests"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons"></a>
    <h2>ADD Logs</h2>
</div>

<form method="POST" action="<?= ROOT ?>/serviceprovider/addLogs" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <!-- Service ID (hidden) -->
            <input type="hidden" name="service_id" value="<?= htmlspecialchars($service_id) ?>">

            <!-- Repair Type -->
            <label class="input-label">Repair Type</label>
            <input type="text" name="service_type" value="<?= htmlspecialchars($service_type) ?>" class="input-field" readonly>

            <!-- Property Name -->
            <label class="input-label">Property Name</label>
            <input type="text" name="property_name" value="<?= htmlspecialchars($property_name) ?>" class="input-field" readonly>

            <!-- Description -->
            <label class="input-label">Description about Repair</label>
            <textarea name="description" class="input-field" required
                placeholder="Provide detailed description of the repair work done"></textarea>

            <!-- Upload Images -->
            <label class="input-label">Upload Repair Images*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload Icon" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">
                        Choose File
                    </button>
                </div>
            </div>
            <div id="uploaded-files" class="owner-addProp-uploaded-files">
                <!-- Uploaded files preview will be shown here -->
            </div>
        </div>

        <div class="owner-addProp-form-right">
            <!-- Property ID -->
            <label class="input-label">Property ID</label>
            <input type="text" name="property_id" value="<?= htmlspecialchars($property_id) ?>" class="input-field" readonly>

            <!-- Total Hours -->
            <label class="input-label">Total Hours</label>
            <input type="number" name="total_hours" class="input-field" step="0.5" min="0.5" required
                placeholder="Enter total hours spent on repair">

            <!-- Earnings (if needed) -->
            <label class="input-label">Expected Earnings</label>
            <input type="text" value="<?= htmlspecialchars($earnings) ?>" class="input-field" readonly>

            <!-- Submit Button -->
            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit Log</button>
            </div>
        </div>
    </div>
</form>


<script>
// Add preview functionality for uploaded files
document.getElementById('property_image').addEventListener('change', function(e) {
    const container = document.getElementById('uploaded-files');
    container.innerHTML = '';
    
    for (let i = 0; i < this.files.length; i++) {
        const file = this.files[i];
        const div = document.createElement('div');
        div.className = 'uploaded-file';
        div.textContent = file.name;
        container.appendChild(div);
    }
});
</script>

<?php require 'serviceproviderFooter.view.php' ?>