<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks/inventorymanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>New Inventory</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Inventory Type</label>
            <input type="text" name="reapirType" placeholder="Type of the Inventory" class="input-field" required>

            <label class="input-label">Inventory Name</label>
            <input type="text" name="name" placeholder="Enter Inventory Name" class="input-field" required>

            <label class="input-label">Description about Inventory</label>
            <textarea name="description" placeholder="Write About Inventory" class="input-field" required></textarea>

            <label class="input-label">Upload Inventory Image*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>
            <div id="uploaded-files" class="owner-addProp-uploaded-files">
                <!-- Uploaded files will be displayed here -->
            </div>
        </div>

        <div class="owner-addProp-form-right">
            <label class="input-label">Property ID:</label>
            <input type="text" name="address" placeholder="Enter the Property ID" class="input-field" required>

            <label class="input-label">Property Name:</label>
            <input type="text" name="Property Name" placeholder="Enter the Name" class="input-field" required>

            <label class="input-label">Date:</label>
            <input type="date" name="date" placeholder="Enter the Date" class="input-field" required>

            <label class="input-label">Price:</label>
            <input type="text" name="serviceID" placeholder="Enter the service ID" class="input-field" required>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'agentFooter.view.php'; ?>