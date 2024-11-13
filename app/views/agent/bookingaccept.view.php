<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/bookings'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Bookings</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">

            <img src="<?= ROOT ?>/assets/images/booking1.png" alt="Back" class="bookingimg">
            <div class="bookingcol">
                <img src="<?= ROOT ?>/assets/images/booking2.png" alt="Back" class="booking1img">
                <img src="<?= ROOT ?>/assets/images/booking2.png" alt="Back" class="booking1img">
            </div>

            <label class="input-label">Property Name</label>
            <input type="text" name="propertyname" value="Ocean View Cottage" class="input-field" readonly>

            <label class="input-label">Province</label>
            <input type="text" name="province" value="South" class="input-field" readonly>

            <label class="input-label">Country</label>
            <input type="text" name="country" value="Sri Lanka" class="input-field" readonly>

            <label class="input-label">City</label>
            <input type="text" name="city" value="Galle" class="input-field" readonly>
        </div>

        <div class="owner-addProp-form-right">

        <label class="input-label">Address</label>
            <input type="text" name="address" value="No.7,Temple Road,Galle." class="input-field" readonly>

            <label class="input-label">Customer Name</label>
            <input type="text" name="customername" value="Nihala Weerasighe" class="input-field" readonly>

            <label class="input-label">Customer ID</label>
            <input type="text" name="customerId" value="C1234" class="input-field" readonly>

            <label class="input-label">Email</label>
            <input type="email" name="email" value="bimsa@gmail.com" class="input-field" readonly>

            <label class="input-label">Mobile Number</label>
            <input type="email" name="mobilenumber" value="0783118863" class="input-field" readonly>

            <label class="input-label">Lease Starting Date</label>
            <input type="email" name="startingDate" value="2024/12/08" class="input-field" readonly>

            <label class="input-label">Lease End Date</label>
            <input type="email" name="endDate" value="2025/01/08" class="input-field" readonly>

            <label class="input-label">Rental (LKR)</label>
            <input type="email" name="rental" value="200000" class="input-field" readonly>

            <label class="input-label">Payment Status</label>
            <input type="email" name="Paymentstatus" value="Paid" class="input-field" readonly>
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
                <button type="submit" class="primary-btn">Accept</button>
                <button type="submit" class="secondary-btn">Reject</button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'agentFooter.view.php'; ?>