<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/services/serviceproviders'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Add Service Provider</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">First Name</label>
            <input type="text" name="firstname" placeholder="Enter First Name" class="input-field" required>

            <label class="input-label">Last Name</label>
            <input type="text" name="lastname" placeholder="Enter Last Name" class="input-field" required>

            <label class="input-label">Date Of Birth</label>
            <input type="date" name="date" placeholder="Enter the Date" class="input-field" required>

            <label class="input-label">Gender</label>
            <input type="text" name="gender" placeholder="Male/Female" class="input-field" required>

            <label class="input-label">Contact Number</label>
            <input type="text" name="primarycontactnumber" placeholder="Enter Primary Contact Number" class="input-field" required>

            <label class="input-label">Contact Number(Secondary)</label>
            <input type="text" name="secondarycontactnumber" placeholder="Enter Secondary Contact Number" class="input-field" required>

            <label class="input-label">Email Address</label>
            <input type="email" name="email" placeholder="Enter Email Address" class="input-field" required>

            <label class="input-label">Bank Account Number</label>
            <input type="text" name="email" placeholder="Enter Bank Account Number" class="input-field" required>

            <label class="input-label">Address</label>
            <textarea name="address" placeholder="Enter the address" class="input-field" required></textarea>

        </div>

        <div class="owner-addProp-form-right">
            <label class="input-label">Marital Status</label>
            <input type="text" name="maritalstatus" placeholder="Married/Unmarried" class="input-field" required>

            <label class="input-label">NIC Number</label>
            <input type="text" name="NIC Number" placeholder="Enter the NIC Number" class="input-field" required>

            <label class="input-label">Upload Profile Image</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>

            <label class="input-label">Resume/CV</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'agentFooter.view.php'; ?>