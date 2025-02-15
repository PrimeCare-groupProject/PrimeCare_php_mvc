<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/services'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Edit Tasks</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/taskupdate" enctype="multipart/form-data">
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
