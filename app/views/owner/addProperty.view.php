<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/property/propertylisting'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>ADD PROPERTIES</h2>
</div>

<form method="POST" action="<?= ROOT ?>/property/create" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Name Of Property*</label>
            <input type="text" name="name" placeholder="Enter Property Name" class="input-field" required>

            <!-- <label class="input-label">Type*</label>
            <select name="type" class="input-field" required>
                <option value="short_term">Short Term</option>
                <option value="monthly">Monthly</option>
                <option value="service_only">Service Only</option>
            </select> -->
            <label class="input-label">Type*</label>
            <select name="type" class="input-field" id="property-type" required onchange="toggleRentField()">
                <option value="shortTerm">Short Term</option>
                <option value="monthly">Monthly</option>
                <option value="serviceOnly">Service Only</option>
            </select>

            <label class="input-label">Description*</label>
            <textarea name="description" placeholder="Write About Property" class="input-field" required></textarea>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Year Built*</label>
                    <input type="text" name="year_built" placeholder="Enter Property Year Built" class="input-field" required>
                </div>
                <!-- <div class="input-group-group">
                    <label class="input-label">Monthly Rent In LKR*</label>
                    <input type="text" name="rent_on_basis" placeholder="Enter Rent" class="input-field" required>
                </div> -->
                <div class="input-group-group" id="rent-basis-field" style="display: none;">
                    <label class="input-label">Monthly Rent In LKR*</label>
                    <input type="number" name="rent_on_basis" placeholder="Enter Rent" class="input-field" value="0">
                </div>
            </div>

            <!-- <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Status*</label>
                    <input type="text" name="status" placeholder="Enter the status" class="input-field" required>
                    <select name="status" class="input-field" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="under_maintenance">Under Maintenance</option>
                        <option value="sold">Sold</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div> -->

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Furnished</label>
                    <select name="furnished" class="input-field">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Parking</label>
                    <select name="parking" class="input-field">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

            <!-- <label class="input-label">Upload Property Image*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div> -->
            <div id="uploaded-files" class="owner-addProp-uploaded-files">
                <!-- Uploaded files will be displayed here -->
            </div>


            <label class="input-label">Upload Property Images (Max 6)*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_images[]" id="property_images" class="input-field" multiple
                    accept=".png, .jpg, .jpeg" data-max-files="6" onchange="handleFileSelect(event)" required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_images').click()">Choose File</button>
                </div>
            </div>
            <div id="preview-container" class="owner-addProp-uploaded-files">
                <!-- Preview area for selected images -->
            </div>





        </div>

        <div class="owner-addProp-form-right">
            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Country*</label>
                    <input type="text" name="country" placeholder="Enter Property Country" class="input-field" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">State*</label>
                    <input type="text" name="state_province" placeholder="Enter Property State" class="input-field" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">City*</label>
                    <input type="text" name="city" placeholder="Enter Property City" class="input-field" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Address*</label>
                    <input type="text" name="address" placeholder="Enter Address" class="input-field" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Zip Code*</label>
                    <input type="text" name="zipcode" placeholder="Enter Property Zip Code" class="input-field" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">No Of Units Of Property*</label>
                    <input type="text" name="units" placeholder="Enter No Of Property Units" class="input-field" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Size Of Property In Square Roots*</label>
                    <input type="text" name="size_sqr_ft" placeholder="Enter Property Size" class="input-field" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Bedrooms*</label>
                    <input type="text" name="bedrooms" placeholder="Enter No Of Bedrooms" class="input-field" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Bathrooms*</label>
                    <input type="text" name="bathrooms" placeholder="Enter No of Bathrooms" class="input-field" required>
                </div>
            </div>

            <div class="input-group-group">
                <label class="input-label">Floor Plan</label>
                <textarea name="floor_plan" placeholder="Enter a Description About The Property" class="input-field"></textarea>
            </div>

            <label class="input-label">Upload Property Ownership Details*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_documents" id="ownership_details" accept=".pdf" data-max-files="6" onchange="handleFileSelectForDocs(event)" required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('ownership_details').click()">Choose File</button>
                </div>
            </div>
            <div id="preview-container-docs" class="owner-addProp-uploaded-files">
                <!-- Preview area for selected images -->
            </div>

            <div class="items-inline">
                <input type="checkbox" name="terms" required />
                <p>By Clicking, I Agree To Terms & Conditions.</p>
            </div>
            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Save</button>
            </div>

            <div class="errors" style="display: <?= !empty($property->errors) ? 'block' : 'none'; ?>">
                <p><?= $property->errors['type'] ??
                        $property->errors['name'] ??
                        $property->errors['description'] ??
                        $property->errors['year_built'] ??
                        $property->errors['rent_on_basis'] ??
                        $property->errors['country'] ??
                        $property->errors['state_province'] ??
                        $property->errors['city'] ??
                        $property->errors['address'] ??
                        $property->errors['zipcode'] ??
                        $property->errors['units'] ??
                        $property->errors['size_sqr_ft'] ??
                        $property->errors['bedrooms'] ??
                        $property->errors['bathrooms'] ??
                        $property->errors['insert'] ??
                        $property->errors['media']  ?>
                </p>
            </div>
        </div>
    </div>
</form>

<script>
    var ROOT = '<?= ROOT ?>';
    function toggleRentField() {
        const typeSelect = document.getElementById('property-type');
        const rentBasisField = document.getElementById('rent-basis-field');

        if (typeSelect.value === 'monthly') {
            rentBasisField.style.display = 'block';
        } else {
            rentBasisField.style.display = 'none';
        }
    }

    // Initialize display based on current selection
    document.addEventListener('DOMContentLoaded', toggleRentField);
</script>


<script src="<?= ROOT ?>/assets/js/property/addproperty.js"></script>
<?php require_once 'ownerFooter.view.php'; ?>