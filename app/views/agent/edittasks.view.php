<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Edit Tasks</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Serve/taskUpdate" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <!-- Hidden field for service_id -->
            <input type="hidden" name="service_id" value="<?=  $tasks->service_id ?>">

            <label class="input-label">Service Type</label>
            <select name="service_type" class="input-field" required>
                <option value="" disabled>Select Service Type</option>
                <?php foreach ($services as $service): ?>
                    <option value="<?= $service->service_type ?>" <?= $service->name == $tasks->service_type ? 'selected' : '' ?>>
                        <?= $service->name ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label class="input-label">Date</label>
            <input type="text" name="date" value="<?= $tasks->date ?>" class="input-field" required>
            
            <label class="input-label">Cost Per Hour (LKR)</label>
            <input type="text" name="cost_per_hour" value="<?= $tasks->cost_per_hour ?>" class="input-field" required>

            <label class="input-label">Total Hours</label>
            <input type="text" name="total_hours" value="<?= $tasks->total_hours ?>" class="input-field" required>

            <label class="input-label">Status</label>
            <input type="text" name="status" value="<?= $tasks->status ?>" class="input-field" required>
        </div>
        <div class="owner-addProp-form-right">
            

            <label class="input-label">Service Provider Id</label>
            <input type="text" name="service_provider_id" value="<?= $tasks->service_provider_id ?>" class="input-field" required>

            <label class="input-label">Total Cost</label>
            <input type="text" name="total_cost" value="<?= $tasks->total_cost ?>" class="input-field" required>
                        
            <label class="input-label">Service Description</label>
            <textarea name="service_description" class="input-field1" required><?= $tasks->service_description ?></textarea>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Save</button>
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

    function updatePropertyId() {
        const propertyDropdown = document.getElementById('property_name');
        const propertyIdField = document.getElementById('property_id');
        console.log("Selected Property ID:", propertyDropdown.value); // Debugging log
        propertyIdField.value = propertyDropdown.value; // Set the property ID to the selected value
    }

    // Function to update the Property ID field based on the selected Property Name
    function updatePropertyId() {
        const propertyDropdown = document.getElementById('property_name');
        const propertyIdField = document.getElementById('property_id');
        propertyIdField.value = propertyDropdown.value; // Set the property ID to the selected value
    }
    
    function previewNewImage(event) {
        const currentImage = document.getElementById('current-service-image'); // Reference to the current image element

        const file = event.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                currentImage.src = e.target.result; // Replace the current image's source with the new image
            };
            reader.readAsDataURL(file); // Convert the file to a base64 URL
        } else {
            alert('Please upload a valid image file.');
        }
    }

    
</script>

<?php require_once 'agentFooter.view.php'; ?>
