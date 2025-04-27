<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>New Task</h2>
</div>

<form method="POST" action="<?= ROOT ?>/tasks/create" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
        <label class="input-label">Task Type</label>
        <select name="service_type" id="service_type" class="input-field" required>
            <option value="" disabled selected>Select Task Type</option>
            <?php foreach ($services as $service): ?>
                <option value="<?= $service->name ?>"><?= $service->name ?></option>
            <?php endforeach; ?>
        </select>
            
            <label class="input-label">Date</label>
            <input type="date" name="date" placeholder="date" class="input-field" required>
            <!--<textarea name="description" placeholder="Write About task" class="input-field" required></textarea>-->

            <label class="input-label">Property Name:</label>
            <select name="property_name" id="property_name" class="input-field" onchange="updatePropertyId()" required>
                <option value="" disabled selected>Select Property</option>
                <?php foreach ($properties as $property): ?>
                    <option value="<?= $property->property_id ?>"><?= $property->name ?></option>
                <?php endforeach; ?>
            </select>

            <label class="input-label">Property ID:</label>
            <input type="text" name="property_id" id="property_id" placeholder="Property ID" class="input-field" readonly required>

            <label class="input-label">Cost Per Hour</label>
            <input type="text" name="cost_per_hour" placeholder="Cost Per Hour" class="input-field" required>

            <label class="input-label">Total Hours</label>
            <input type="text" name="total_hours" placeholder="Total Hours" class="input-field" required>

            <label class="input-label">Service Provider Name:</label>
            <select name="service_provider_name" id="service_provider_name" class="input-field" onchange="updateServiceProviderId()" required>
                <option value="" disabled selected>Select Service Provider</option>
                <?php if (!empty($serpro)): ?>
                    <?php foreach ($serpro as $provider): ?>
                        <option value="<?= $provider->pid ?>"><?= $provider->fname ?> <?= $provider->lname ?></option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option value="" disabled>No Service Providers Available</option>
                <?php endif; ?>
            </select>

            <label class="input-label">Service Provider ID:</label>
            <input type="text" name="service_provider_id" id="service_provider_id" placeholder="Service Provider ID" class="input-field" readonly required>

            <label class="input-label">Upload Service Image</label>
                <div class="owner-addProp-file-upload">
                    <input type="file" name="service_images[]" id="service_images" class="input-field" multiple accept=".png, .jpg, .jpeg" data-max-files="6" onchange="previewImages(event)" required>
                    <div class="owner-addProp-upload-area">
                        <img src="<?= ROOT ?>/assets/images/upload.png" alt="Upload" class="owner-addProp-upload-logo">
                        <p class="upload-area-no-margin">Drop your files here</p>
                        <button type="button" class="primary-btn" onclick="document.getElementById('service_images').click()">Choose File</button>
                    </div>
                </div>

            <!-- Image preview container -->
            <div id="image-preview-container" style="display: flex; gap: 10px; margin-top: 10px;"></div>
            

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
            </div>
        </div>

        <!--<div class="owner-addProp-form-right">
            <label class="input-label-aligned">
                <label class="input-label">Service Provider</label>
                <select class="input-field-small" name="serviceProvider" required>
                <option value="notassign">Select SP</option>
                <option value="ag1">Service Provider 1</option>
                <option value="ag2">Service Provider 2</option>
                <option value="ag3">Service Provider 3</option>
                <option value="ag4">Service Provider 4</option>
                </select>
            </label>
            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
            </div>
        </div>-->
    </div>
</form>

<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="flash-message">
        <?= $_SESSION['flash_message']; ?>
        <?php unset($_SESSION['flash_message']); ?> <!-- Clear the message after displaying -->
    </div>
<?php endif; ?>

<script>
    // Function to update the Service Provider ID field based on the selected Service Provider Name
    function updateServiceProviderId() {
        const serviceProviderDropdown = document.getElementById('service_provider_name');
        const serviceProviderIdField = document.getElementById('service_provider_id');
        serviceProviderIdField.value = serviceProviderDropdown.value; // Set the Service Provider ID to the selected value
    }

    function previewImages(event) {
        const files = event.target.files;
        const container = document.getElementById('image-preview-container');
        container.innerHTML = ''; // Clear previous previews

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                container.appendChild(img);
            };

            reader.readAsDataURL(file);
        }
    }

    // Function to validate numeric input
    function validateNumericInput(event) {
        const inputField = event.target;
        const value = inputField.value;

        // Check if the value is not numeric
        if (isNaN(value) || value.includes(" ")) {
            inputField.value = ""; // Clear the invalid input
            alert("Please enter a valid numeric value."); // Show an alert message
        }
    }

    // Attach the validation function to the relevant input fields
    document.addEventListener("DOMContentLoaded", function () {
        const propertyIdField = document.querySelector('input[name="property_id"]');
        const costPerHourField = document.querySelector('input[name="cost_per_hour"]');
        const totalHoursField = document.querySelector('input[name="total_hours"]');

        // Add event listeners for real-time validation
        propertyIdField.addEventListener("input", validateNumericInput);
        costPerHourField.addEventListener("input", validateNumericInput);
        totalHoursField.addEventListener("input", validateNumericInput);
    });
    // Function to validate numeric input
    function validateNumericInput(event) {
        const inputField = event.target;
        const value = inputField.value;
        const errorMessage = inputField.nextElementSibling; // Assume the error message is the next sibling element

        // Check if the value is not numeric
        if (isNaN(value) || value.includes(" ")) {
            inputField.value = ""; // Clear the invalid input
            errorMessage.textContent = "Please enter a valid numeric value."; // Show an error message
            errorMessage.style.color = "red";
        } else {
            errorMessage.textContent = ""; // Clear the error message
        }
    }

    // Attach the validation function to the relevant input fields
    document.addEventListener("DOMContentLoaded", function () {
        const propertyIdField = document.querySelector('input[name="property_id"]');
        const costPerHourField = document.querySelector('input[name="cost_per_hour"]');
        const totalHoursField = document.querySelector('input[name="total_hours"]');

        // Add event listeners for real-time validation
        propertyIdField.addEventListener("input", validateNumericInput);
        costPerHourField.addEventListener("input", validateNumericInput);
        totalHoursField.addEventListener("input", validateNumericInput);

        // Add error message elements after each field
        [propertyIdField, costPerHourField, totalHoursField].forEach(field => {
            const errorMessage = document.createElement("span");
            field.parentNode.insertBefore(errorMessage, field.nextSibling);
        });
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>