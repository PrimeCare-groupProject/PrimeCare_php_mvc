<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/repairings'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>New Repair</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/create" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Repair Name</label>
            <input type="text" name="name" placeholder="Enter repair name" class="input-field" required>

            <label class="input-label">Cost Of Hour</label>
            <input type="text" name="cost_per_hour" placeholder="Enter repair name" class="input-field" required>
            
            <label class="input-label">Description About The Repair</label>
            <textarea name="description" placeholder="description" class="input-field1" required></textarea>

            <label class="input-label">Upload Service Images (Max 6)*</label>
                <div class="owner-addProp-file-upload">
                    <input type="file" name="service_images[]" id="service_images" class="input-field" multiple accept=".png, .jpg, .jpeg" data-max-files="6" onchange="previewImages(event)" required>
                    <div class="owner-addProp-upload-area">
                        <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload" class="owner-addProp-upload-logo">
                        <p class="upload-area-no-margin">Drop your files here</p>
                        <button type="button" class="primary-btn" onclick="document.getElementById('service_images').click()">Choose File</button>
                    </div>
                </div>

            <!-- Image preview container -->
            <div id="image-preview-container" style="display: flex; gap: 10px; margin-top: 10px;"></div>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
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
    function previewImages(event) {
        const files = event.target.files;
        const container = document.getElementById('image-preview-container');
        container.innerHTML = ''; // Clear previous previews

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                container.appendChild(img);
            };

            reader.readAsDataURL(file);
        }
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>