<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/preInspection'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>PreInspection Update</h2>
</div>

<form method="POST" action="preInspectUpdate" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">

            <img src="<?= ROOT ?>/assets/images/booking1.png" alt="Back" class="bookingimg">
            <div class="bookingcol">
                <img src="<?= ROOT ?>/assets/images/booking2.png" alt="Back" class="booking1img">
                <img src="<?= ROOT ?>/assets/images/booking2.png" alt="Back" class="booking1img">
            </div>

            <label class="input-label">Property ID</label>
            <input type="text" name="property_id" value="<?= $preinspect->property_id ?>" class="input-field" readonly>

            <label class="input-label">Property Name</label>
            <input type="text" name="property_name" value="<?= $preinspect->name ?>" class="input-field" readonly>

            <label class="input-label">Description</label>
            <textarea name="city" class="input-field description-box" rows="7" readonly><?= $preinspect->description ?></textarea>

            <label class="input-label">Country</label>
            <input type="text" name="country" value="<?= $preinspect->country ?>" class="input-field" readonly>

            <label class="input-label">Province</label>
            <input type="text" name="province" value="<?= $preinspect->state_province ?>" class="input-field" readonly>

            <label class="input-label">City</label>
            <input type="text" name="city" value="<?= $preinspect->city ?>" class="input-field" readonly>

            <label class="input-label">Zip Code</label>
            <input type="text" name="zipcode" value="<?= $preinspect->zipcode ?>" class="input-field" readonly>

            <label class="input-label">Address</label>
            <textarea name="address" class="input-field description-box" rows="2" readonly><?= $preinspect->address ?></textarea>

            <label class="input-label">Type</label>
            <input type="text" name="type" value="<?= $preinspect->type ?>" class="input-field" readonly>
        </div>

        <div class="owner-addProp-form-right">

            <label class="input-label">Year Built</label>
            <input type="text" name="year Built" value="<?= $preinspect->year_built ?>" class="input-field" readonly>

            <label class="input-label">Rent (LKR)</label>
            <input type="text" name="Rent" value="<?= $preinspect->rental_price ?>" class="input-field" readonly>

            <label class="input-label">Units</label>
            <input type="text" name="units" value="<?= $preinspect->units ?>" class="input-field" readonly>

            <label class="input-label">Size Square Feet</label>
            <input type="text" name="sizesquarefeet" value="<?= $preinspect->size_sqr_ft ?>" class="input-field" readonly>

            <label class="input-label">Bedrooms</label>
            <input type="text" name="bedrooms" value="<?= $preinspect->bedrooms ?>" class="input-field" readonly>

            <label class="input-label">Bathrooms</label>
            <input type="text" name="bathrooms" value="<?= $preinspect->bathrooms ?>" class="input-field" readonly>

            <label class="input-label">Parking</label>
            <input type="text" name="parking" value="<?= $preinspect->parking ?>" class="input-field" readonly>

            <label class="input-label">Furnished</label>
            <input type="text" name="furnished" value="<?= $preinspect->furnished ?>" class="input-field" readonly>

            <label class="input-label">Floor Plan</label>
            <textarea name="floorplan" class="input-field description-box" rows="7" readonly><?= $preinspect->floor_plan ?></textarea>

            <label class="input-label">Owner ID</label>
            <input type="text" name="ownerid" value="<?= $user->pid ?>" class="input-field" readonly>

            <label class="input-label">Owner Name</label>
            <input type="text" name="ownername" value="<?= $user->fname ?> <?= $user->lname ?>" class="input-field" readonly>

            <label class="input-label">User Name</label>
            <input type="text" name="username" value="<?= $user->username ?>" class="input-field" readonly>

            <label class="input-label">Email</label>
            <input type="email" name="email" value="<?= $user->email ?>" class="input-field" readonly>

            <label class="input-label">Mobile No.</label>
            <input type="text" name="mobileNo" value="<?= $user->contact ?>" class="input-field" readonly>

            <div class="buttons-to-right">
                <button type="submit" name="action" value="accept" class="primary-btn">Accept</button>
                <button type="submit" name="action" value="reject" class="secondary-btn">Reject</button>
            </div>
        </div>
    </div>
</form>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let descriptionBox = document.querySelector(".description-box");

        if (descriptionBox) {
            descriptionBox.style.height = "auto"; // Reset height to auto
            descriptionBox.style.height = descriptionBox.scrollHeight + "px"; // Adjust height based on content
        }
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>