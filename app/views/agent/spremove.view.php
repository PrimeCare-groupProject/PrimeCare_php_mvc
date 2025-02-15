<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/ManageProviders/serviceproviders/removeserviceprovider'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Service Providers</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">

            <div class="serPro">
            <img src="<?= ROOT ?>/assets/images/serProimg.png" alt="Back" class="serProimg">
            <h3>Mr Service</h3>
            <h3>Provider</h3>
            </div>

            <label class="input-label">First Name</label>
            <input type="text" name="firstname" value="Bimsara" class="input-field" readonly>

            <label class="input-label">Last Name</label>
            <input type="text" name="lastname" value="Imash" class="input-field" readonly>

            <label class="input-label">Date Of Birth</label>
            <input type="text" name="date" value="2022/12/07" class="input-field" readonly>

            <label class="input-label">Gender</label>
            <input type="text" name="gender" value="Male" class="input-field" readonly>

            <label class="input-label">Contact Number</label>
            <input type="text" name="primarycontactnumber" value="0783118863" class="input-field" readonly>

            <label class="input-label">Contact Number(Secondary)</label>
            <input type="text" name="secondarycontactnumber" value="0777612225" class="input-field" readonly>

            <label class="input-label">Email Address</label>
            <input type="email" name="email" value="Bimsa2021@gmail.com" class="input-field" readonly>

        </div>

        <div class="owner-addProp-form-right">

            <label class="input-label">Bank Account Number</label>
            <input type="text" name="email" value="2090993808980290" class="input-field" readonly>

            <label class="input-label">Address</label>
            <input type="text" name="address" value="No 12,Temple road, Galle." class="input-field" readonly>

            <label class="input-label">Marital Status</label>
            <input type="text" name="maritalstatus" value="Unmarried" class="input-field" readonly>

            <label class="input-label">NIC Number</label>
            <input type="text" name="NIC Number" value="202235293687" class="input-field" readonly>

            <!--<label class="input-label">Upload Profile Image</label>
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
            -->

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Edit</button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'agentFooter.view.php'; ?>