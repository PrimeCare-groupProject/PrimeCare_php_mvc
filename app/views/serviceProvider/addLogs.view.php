<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    <h2>ADD Logs</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Repair Type</label>
            <input type="text" name="reapirType" placeholder="Type of the Repair" class="input-field" required>

            <label class="input-label">Name of Property</label>
            <input type="text" name="name" placeholder="Enter Property Name" class="input-field" required>

            <label class="input-label">Description about Repair</label>
            <textarea name="description" placeholder="Write About Repair" class="input-field" required></textarea>

            <label class="input-label">Upload Property Image*</label>
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
            <label class="input-label">Address</label>
            <input type="text" name="address" placeholder="Enter the Address" class="input-field" required>

            <label class="input-label">Date</label>
            <input type="date" name="date" placeholder="Repair Date" class="input-field" required>

            <label class="input-label">Service ID</label>
            <input type="text" name="serviceID" placeholder="Enter the service ID" class="input-field" required>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
            </div>
        </div>
    </div>
</form>

<script src="scripts.js"></script>

<?php require 'serviceproviderFooter.view.php' ?>