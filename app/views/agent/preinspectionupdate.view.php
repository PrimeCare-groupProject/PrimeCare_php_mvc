<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/preInspection'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>PreInspection Update</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">

            <img src="<?= ROOT ?>/assets/images/booking1.png" alt="Back" class="bookingimg">
            <div class="bookingcol">
                <img src="<?= ROOT ?>/assets/images/booking2.png" alt="Back" class="booking1img">
                <img src="<?= ROOT ?>/assets/images/booking2.png" alt="Back" class="booking1img">
            </div>

            <label class="input-label">Property ID</label>
            <input type="text" name="propertyname" value="<?= $preinspect->property_id ?>" class="input-field" readonly>

            <label class="input-label">Property Name</label>
            <input type="text" name="province" value="<?= $preinspect->name ?>" class="input-field" readonly>

            <label class="input-label">Description</label>
            <input type="text" name="city" value="<?= $preinspect->description ?>" class="input-field" readonly>

            <label class="input-label">Country</label>
            <input type="text" name="country" value="<?= $preinspect->country ?>" class="input-field" readonly>

            <label class="input-label">Province</label>
            <input type="text" name="country" value="<?= $preinspect->state_province ?>" class="input-field" readonly>

            <label class="input-label">City</label>
            <input type="text" name="country" value="<?= $preinspect->city ?>" class="input-field" readonly>

            <label class="input-label">Zip Code</label>
            <input type="text" name="country" value="<?= $preinspect->zipcode ?>" class="input-field" readonly>

            <label class="input-label">Address</label>
            <input type="text" name="country" value="<?= $preinspect->address ?>" class="input-field" readonly>

            <label class="input-label">Type</label>
            <input type="text" name="country" value="<?= $preinspect->type ?>" class="input-field" readonly>
        </div>

        <div class="owner-addProp-form-right">

        <label class="input-label">Year Built</label>
            <input type="text" name="address" value="<?= $preinspect->year_built ?>" class="input-field" readonly>

            <label class="input-label">Rent Price</label>
            <input type="text" name="customername" value="<?= $preinspect->rent_on_basis ?>" class="input-field" readonly>

            <label class="input-label">Units</label>
            <input type="text" name="customerId" value="<?= $preinspect->units ?>" class="input-field" readonly>

            <label class="input-label">Size Square Feet</label>
            <input type="text" name="email" value="<?= $preinspect->size_sqr_ft ?>" class="input-field" readonly>

            <label class="input-label">Bedrooms</label>
            <input type="text" name="mobilenumber" value="<?= $preinspect->bedrooms ?>" class="input-field" readonly>

            <label class="input-label">Bathrooms</label>
            <input type="text" name="startingDate" value="<?= $preinspect->bathrooms ?>" class="input-field" readonly>

            <label class="input-label">Parking</label>
            <input type="text" name="endDate" value="<?= $preinspect->parking ?>" class="input-field" readonly>

            <label class="input-label">Furnished</label>
            <input type="text" name="rental" value="<?= $preinspect->furnished ?>" class="input-field" readonly>

            <label class="input-label">Floor Plan</label>
            <input type="text" name="Paymentstatus" value="<?= $preinspect->floor_plan ?>" class="input-field" readonly>

            <label class="input-label">Owner ID</label>
            <input type="text" name="Paymentstatus" value="<?= $user->pid ?>" class="input-field" readonly>

            <label class="input-label">Owner Name</label>
            <input type="text" name="Paymentstatus" value="<?= $user->fname ?> <?= $user->lname ?>" class="input-field" readonly>

            <label class="input-label">User Name</label>
            <input type="text" name="Paymentstatus" value="<?= $user->username ?>" class="input-field" readonly>

            <label class="input-label">Email</label>
            <input type="email" name="Paymentstatus" value="<?= $user->email ?>" class="input-field" readonly>

            <label class="input-label">Mobile No.</label>
            <input type="text" name="Paymentstatus" value="<?= $user->contact ?>" class="input-field" readonly>
            
            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Accept</button>
                <button type="submit" class="secondary-btn">Reject</button>
            </div>
        </div>
    </div>
</form>



<?php require_once 'agentFooter.view.php'; ?>