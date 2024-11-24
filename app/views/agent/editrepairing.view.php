<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/repairings'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Edit Repair</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/update" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <!-- Hidden field for service_id -->
            <input type="hidden" name="service_id" value="<?= isset($service1) ? htmlspecialchars($service1->service_id) : '' ?>">

            <!-- Hidden field for service_id -->
            <input type="hidden" name="service_img" value="<?= isset($service1) ? htmlspecialchars($service1->service_img) : '' ?>">

            <label class="input-label">Repair Name</label>
            <!-- Populate the input field with the actual name -->
            <input type="text" name="name" value="<?= isset($service1) ? htmlspecialchars($service1->name) : '' ?>" class="input-field" required>

            <label class="input-label">Cost Of Hour</label>
            <!-- Populate the cost per hour field (assuming $service1->cost_per_hour exists) -->
            <input type="text" name="cost_per_hour" value="<?= isset($service1) ? htmlspecialchars($service1->cost_per_hour) : '' ?>" class="input-field" required>
            
            <label class="input-label">Description About The Repair</label>
            <!-- Populate the description textarea with the actual description -->
            <textarea name="description" class="input-field1" required><?= isset($service1) ? htmlspecialchars($service1->description) : '' ?></textarea>

            <?php
        // Assuming $service is an object containing the service data
        $currentImage = isset($service->service_img) ? ROOT . '/' . $service->service_img : ROOT . '/assets/images/default_service.png';
        ?>

        <label class="input-label">Upload Service Image</label>
        <div class="owner-addProp-file-upload">
            <!-- Display the current image -->
            <div style="margin-bottom: 10px;">
                <img 
                    src="<?= $currentImage ?>" 
                    alt="Current Service Image" 
                    style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;"
                >
            </div>

            <!-- Input for uploading a new image -->
            <input 
                type="file" 
                name="service_img" 
                id="service_img" 
                class="input-field" 
                accept=".png, .jpg, .jpeg" 
                onchange="previewNewImage(event)"
                required
            >
            
            <div id="new-image-preview" style="margin-top: 10px;"></div>
        </div>

            <!-- Image preview container -->
            <div id="image-preview-container" style="display: flex; gap: 10px; margin-top: 10px;"></div>

            
            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Save</button>
            </div>
        </div>
    </div>
</form>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message">
        <?= $_SESSION['flash_message']; ?>
        <?php unset($_SESSION['flash_message']); ?> <!-- Clear the message after displaying -->
    </div>
<?php endif; ?>

<script>
    function previewNewImage(event) {
        const previewContainer = document.getElementById('new-image-preview');
        previewContainer.innerHTML = ''; // Clear the preview container

        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'New Image Preview';
                img.style.width = '150px';
                img.style.height = '150px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '5px';
                img.style.border = '1px solid #ccc';
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file); // Convert the file to a base64 URL
        } else {
            alert('Please upload a valid image file.');
        }
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>