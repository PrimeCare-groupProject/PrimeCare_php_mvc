<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/propertylisting/propertyunitowner/<?= $property->property_id ?>'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>Update details on <span style="color: var(--green-color);"><?= $property->name ?><span></h2>
</div>

<form method="POST" action="<?= ROOT ?>/property/update/<?= $property->property_id ?>" enctype="multipart/form-data">
    <!-- <form method="POST" action="<?= ROOT ?>/property/updateTemp/<?= $property->property_id ?>" enctype="multipart/form-data"> -->
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Name Of Property*</label>
            <input type="text" name="name" placeholder="Enter Property Name" class="input-field" value="<?= $property->name ?>" required>

            <label class="input-label">Type*</label>
            <select name="type" class="input-field" id="property-type" required onchange="toggleRentField()" value="<?= $property->type ?>">
                <option value="shortTerm">Short Term</option>
                <option value="monthly">Monthly</option>
                <option value="serviceOnly">Service Only</option>
            </select>

            <label class="input-label">Description*</label>
            <textarea name="description" placeholder="Write About Property" class="input-field" required><?= $property->description ?></textarea>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Year Built*</label>
                    <input type="text" name="year_built" placeholder="Enter Property Year Built" class="input-field" value="<?= $property->year_built ?>" required>
                </div>
                <div class="input-group-group" id="rent-basis-field" style="display: none;">
                    <label class="input-label">Monthly Rent In LKR*</label>
                    <input type="number" name="rental_price" placeholder="Enter Rent" value="<?= $property->rental_price ?>" class="input-field">
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Furnished</label>
                    <select name="furnished" class="input-field" value="<?= $property->furnished ?>">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Parking</label>
                    <select name="parking" class="input-field" value="<?= $property->parking ?>">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>

            <div id="uploaded-files" class="owner-addProp-uploaded-files">
                <!-- Uploaded files will be displayed here -->
            </div>


            <label class="input-label">Upload New Property Images (Max 6)*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_images[]" id="property_images" class="input-field" multiple
                    accept=".png, .jpg, .jpeg" data-max-files="6" onchange="handleFileSelect(event)">
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
                    <input type="text" name="country" placeholder="Enter Property Country" class="input-field" value="<?= $property->country ?>" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">State*</label>
                    <input type="text" name="state_province" placeholder="Enter Property State" class="input-field" value="<?= $property->state_province ?>" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">City*</label>
                    <input type="text" name="city" placeholder="Enter Property City" class="input-field" value="<?= $property->city ?>" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Address*</label>
                    <input type="text" name="address" placeholder="Enter Address" class="input-field" value="<?= $property->address ?>" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Zip Code*</label>
                    <input type="text" name="zipcode" placeholder="Enter Property Zip Code" class="input-field" value="<?= $property->zipcode ?>" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">No Of Units Of Property*</label>
                    <input type="number" name="units" placeholder="Enter No Of Property Units" class="input-field" value="<?= $property->units ?>" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Size Of Property In Square Roots*</label>
                    <input type="number" name="size_sqr_ft" placeholder="Enter Property Size" class="input-field" value="<?= $property->size_sqr_ft ?>" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Bedrooms*</label>
                    <input type="number" name="bedrooms" placeholder="Enter No Of Bedrooms" class="input-field" value="<?= $property->bedrooms ?>" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Bathrooms*</label>
                    <input type="number" name="bathrooms" placeholder="Enter No of Bathrooms" class="input-field" value="<?= $property->bathrooms ?>" required>
                </div>
            </div>

            <div class="input-group-group">
                <label class="input-label">Floor Plan</label>
                <textarea name="floor_plan" placeholder="Enter a Description About The Property" class="input-field"><?= $property->floor_plan ?></textarea>
            </div>

            <label class="input-label">Upload New Property Ownership Details*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_documents" id="ownership_details" accept=".pdf" data-max-files="6" onchange="handleFileSelectForDocs(event)">
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
                <button type="submit" class="primary-btn">Update</button>
            </div>

            <div class="errors" style="display: <?= !empty($property->errors) ? 'block' : 'none'; ?>">
                <p><?= $property->errors['type'] ??
                        $property->errors['name'] ??
                        $property->errors['description'] ??
                        $property->errors['year_built'] ??
                        $property->errors['rental_price'] ??
                        $property->errors['country'] ??
                        $property->errors['state_province'] ??
                        $property->errors['city'] ??
                        $property->errors['address'] ??
                        $property->errors['zipcode'] ??
                        $property->errors['units'] ??
                        $property->errors['size_sqr_ft'] ??
                        $property->errors['bedrooms'] ??
                        $property->errors['bathrooms'] ??
                        $property->errors['update'] ??
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