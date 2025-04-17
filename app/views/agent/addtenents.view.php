<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/dashboard/manageProviders/managetenents">
        <button class="back-btn">
            <img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons">
        </button>
    </a>
    <h2>Add Tenant</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Tenants/create" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <!-- LEFT SIDE -->
        <div class="owner-addProp-form-left">

            <label class="input-label">Tenant Full Name:</label>
            <input type="text" name="tenant_name" placeholder="Enter full name" class="input-field" required>

            <label class="input-label">Age:</label>
            <input type="number" name="age" placeholder="Enter age" class="input-field" required>

            <label class="input-label">Country:</label>
            <input type="text" name="country" placeholder="Enter country" class="input-field" required>

            <label class="input-label">Province:</label>
            <input type="text" name="province" placeholder="Enter province/state" class="input-field" required>

            <label class="input-label">City:</label>
            <input type="text" name="city" placeholder="Enter city" class="input-field" required>

            <label class="input-label">Address:</label>
            <textarea name="address" placeholder="Enter full address" class="input-field1" required></textarea>

            <!-- Rental and Lease Details -->
            <label class="input-label">Rental Amount:</label>
            <input type="number" name="rental_amount" placeholder="Enter rental amount" class="input-field" required>

            <label class="input-label">Renting Period (in months):</label>
            <input type="number" name="renting_period" placeholder="Enter renting period (months)" class="input-field" required>

            <label class="input-label">Leasing Start Date:</label>
            <input type="date" name="leasing_start_date" class="input-field" required>

            <label class="input-label">Booked Date:</label>
            <input type="date" name="booked_date" class="input-field" required>

        </div>

        <!-- RIGHT SIDE -->
        <div class="owner-addProp-form-right">

            <label class="input-label">NIC / Passport Number:</label>
            <input type="text" name="nic" placeholder="Enter NIC or Passport Number" class="input-field" required>

            <label class="input-label">Phone Number:</label>
            <input type="text" name="phone" placeholder="Enter phone number" class="input-field" required>

            <label class="input-label">Email:</label>
            <input type="email" name="email" placeholder="Enter email address" class="input-field" required>

            <label class="input-label">Related Property ID:</label>
            <input type="text" name="property_id" placeholder="Enter related Property ID" class="input-field" required>

            <label class="input-label">Upload Tenant Photo:</label>
            <input type="file" name="tenant_image" class="input-field" accept="image/*">

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Add Tenant</button>
            </div>
        </div>
    </div>
</form>

<?php require_once 'agentFooter.view.php'; ?>
