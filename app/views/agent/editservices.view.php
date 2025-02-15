<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/services'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Edit Services</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/update" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <!-- Hidden field for service_id -->
            <input type="hidden" name="service_id" value="<?= isset($service1) ? htmlspecialchars($service1->service_id) : '' ?>">

            <!-- Hidden field for service_id -->
            <input type="hidden" name="service_img" value="<?= isset($service1) ? htmlspecialchars($service1->service_img) : '' ?>">

            <label class="input-label">Repair Name</label>
            <input type="text" name="name" value="<?= isset($service1) ? htmlspecialchars($service1->name) : '' ?>" class="input-field" required>

            <label class="input-label">Cost Of Hour</label>
            <input type="text" name="cost_per_hour" value="<?= isset($service1) ? htmlspecialchars($service1->cost_per_hour) : '' ?>" class="input-field" required>
            
            <label class="input-label">Description About The Repair</label>
            <textarea name="description" class="input-field1" required><?= isset($service1) ? htmlspecialchars($service1->description) : '' ?></textarea>

            <?php
                $currentImage = isset($service1->service_img) ? ROOT . '/' . $service1->service_img : ROOT . '/assets/images/default_service.png';
            ?>

            <label class="input-label">Upload Service Image</label>
            <div class="owner-addProp-file-upload">
                <!-- Display the current image with an overlay -->
                <div style="position: relative; margin-bottom: 10px;">
                    <img 
                        src="<?= $currentImage ?>" 
                        id="current-service-image" 
                        alt="Current Service Image" 
                        style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px; border: 1px solid #ccc;"
                    >
                    <!-- Overlay with "Edit Image" text -->
                    <div 
                        style="
                            position: absolute; 
                            top: 0; 
                            left: 0; 
                            width: 100%; 
                            height: 100%; 
                            display: flex; 
                            align-items: center; 
                            justify-content: center; 
                            background-color: rgba(0, 0, 0, 0.5); 
                            color: white; 
                            font-size: 18px; 
                            font-weight: bold; 
                            border-radius: 5px;
                        "
                    >
                        Edit Image
                    </div>
                </div>

                <input 
                    type="file" 
                    name="service_images" 
                    id="service_images" 
                    class="input-field" 
                    accept=".png, .jpg, .jpeg" 
                    onchange="previewNewImage(event)"
                    required
                >
                
                <div id="new-image-preview" style="margin-top: 10px;"></div>
            </div>

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
        const currentImage = document.getElementById('current-service-image'); // Reference to the current image element

        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                currentImage.src = e.target.result; // Replace the current image's source with the new image
            };
            reader.readAsDataURL(file); // Convert the file to a base64 URL
        } else {
            alert('Please upload a valid image file.');
        }
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>
