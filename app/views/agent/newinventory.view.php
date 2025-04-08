<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/inventory'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>New Inventory</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Inventory/create" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Inventory Name:</label>
            <input type="text" name="inventory_name" placeholder="Enter the Inventory Name" class="input-field" required>

            <label class="input-label">Inventory Type:</label>
            <input type="text" name="inventory_type" placeholder="Enter the Inventory Type" class="input-field" required>

            <label class="input-label">Date:</label>
            <input type="date" name="date"  class="input-field" required>

            <label class="input-label">Unit Price:</label>
            <input type="text" name="unit_price" placeholder="Enter Unit Price" class="input-field" required>

            <label class="input-label">Quantity:</label>
            <input type="text" name="quantity" placeholder="Enter the Quantity " class="input-field" required>

            <label class="input-label">Description:</label>
            <textarea name="description" placeholder="description" class="input-field1" required></textarea>

        </div>

        <div class="owner-addProp-form-right">
        <label class="input-label">Upload Service Image</label>
                <div class="owner-addProp-file-upload">
                    <input type="file" name="inventory_image[]" id="inventory_image" class="input-field" multiple accept=".png, .jpg, .jpeg" data-max-files="6" onchange="previewImages(event)" required>
                    <div class="owner-addProp-upload-area">
                        <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload" class="owner-addProp-upload-logo">
                        <p class="upload-area-no-margin">Drop your files here</p>
                        <button type="button" class="primary-btn" onclick="document.getElementById('inventory_image').click()">Choose File</button>
                    </div>
                </div>

            <!-- Image preview container -->
            <div id="image-preview-container" style="display: flex; gap: 10px; margin-top: 10px;"></div>

            <label class="input-label">Seller Name:</label>
            <input type="text" name="seller_name" placeholder="Enter the Seller Name" class="input-field" required>

            <label class="input-label">Seller Address:</label>
            <input type="text" name="seller_address" placeholder="Enter the Seller Address" class="input-field" required>

            <label class="input-label">Property ID:</label>
            <input type="text" name="property_id" placeholder="Enter the Property ID" class="input-field" required>

            <label class="input-label">Property Name:</label>
            <input type="text" name="property_name" placeholder="Enter the Property Name" class="input-field" required>

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