<?php require_once 'agentHeader.view.php'; $booking = $bookings[0];?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/bookings/history'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Booking History Details</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Booking/update" enctype="multipart/form-data">
<?php foreach ($images as $image): ?>
    <?php if ($booking->property_id == $image->property_id): ?>
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">

            <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image->image_url ?>" alt="Back" class="bookingimg">

            <input type="hidden" name="booking_id" value="<?=  $booking->booking_id ?>">

            <label class="input-label">Property Name</label>
            <input type="text" name="propertyname" value="<?=  $booking->name ?>" class="input-field" readonly>

            <label class="input-label">Province</label>
            <input type="text" name="province" value="<?= $booking->state_province?>" class="input-field" readonly>

            <label class="input-label">Country</label>
            <input type="text" name="country" value="<?= $booking->country?>" class="input-field" readonly>

            <label class="input-label">City</label>
            <input type="text" name="city" value="<?= $booking->city?>" class="input-field" readonly>

            <label class="input-label">Address</label>
            <input type="text" name="address" value="<?= $booking->address?>" class="input-field" readonly>

            <label class="input-label">Customer Name</label>
            <input type="text" name="customername" value="<?= $booking->fname?> <?= $booking->lname?>" class="input-field" readonly>

        </div>

        <div class="owner-addProp-form-right">

            <label class="input-label">Customer ID</label>
            <input type="text" name="customerId" value="<?= $booking->pid?>" class="input-field" readonly>

            <label class="input-label">Email</label>
            <input type="email" name="email" value="<?= $booking->email?>" class="input-field" readonly>

            <label class="input-label">Mobile Number</label>
            <input type="email" name="mobilenumber" value="<?= $booking->contact?>" class="input-field" readonly>

            <label class="input-label">Booked Date</label>
            <input type="email" name="bookedDate" value="<?= $booking->booked_date?>" class="input-field" readonly>

            <label class="input-label">Lease Starting Date</label>
            <input type="email" name="startingDate" value="<?= $booking->start_date?>" class="input-field" readonly>

            <label class="input-label">Renting Period (Months)</label>
            <input type="email" name="renting_period" value="<?= $booking->renting_period?>" class="input-field" readonly>

            <label class="input-label">Rental (LKR)</label>
            <input type="email" name="rental" value="<?= $booking->price?>" class="input-field" readonly>

            <label class="input-label">Payment Status</label>
            <input type="email" name="Paymentstatus" value="<?= $booking->payment_status?>" class="input-field" readonly>

            <label class="input-label">Confirmed Status</label>
            <input type="email" name="AcceptStatus" value="<?= $booking->accept_status?>" class="input-field" readonly>

            <label class="input-label">Confirmed Date</label>
            <input type="email" name="Accepteddate" value="<?= $booking->accepted_date?>" class="input-field" readonly>

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
        </div>
    </div>
    <?php endif; ?>
    <?php endforeach; ?>
</form>

<?php require_once 'agentFooter.view.php'; ?>