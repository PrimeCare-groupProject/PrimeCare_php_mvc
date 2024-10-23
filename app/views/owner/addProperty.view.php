<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/propertylisting'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>ADD PROPERTIES</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Type*</label>
            <input type="text" name="type" placeholder="Own Property" class="input-field" required>

            <label class="input-label">Name Of Property*</label>
            <input type="text" name="name" placeholder="Enter Property Name" class="input-field" required>

            <label class="input-label">Description*</label>
            <textarea name="description" placeholder="Write About Property" class="input-field" required></textarea>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">Year Built*</label>
                    <input type="text" name="year" placeholder="Enter Property Year Built" class="input-field" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Monthly Rent In LKR*</label>
                    <input type="text" name="rent" placeholder="Enter Rent" class="input-field" required>
                </div>
            </div>

            <label class="input-label">Upload Property Image*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>
            <div id="uploaded-files" class="owner-addProp-uploaded-files">
                <!-- Uploaded files will be displayed here -->
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
                    <input type="text" name="state" placeholder="Enter Property State" class="input-field" required>
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
                    <input type="text" name="zip" placeholder="Enter Property Zip Code" class="input-field" required>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label class="input-label">No Of Units Of Property*</label>
                    <input type="text" name="units" placeholder="Enter No Of Property Units" class="input-field" required>
                </div>
                <div class="input-group-group">
                    <label class="input-label">Size Of Property In Square Roots*</label>
                    <input type="text" name="size" placeholder="Enter Property Size" class="input-field" required>
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
                <input type="file" name="ownership_details" id="ownership_details" required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('ownership_details').click()">Choose File</button>
                </div>
            </div>

            <div class="items-inline">
                <input type="checkbox" name="terms" required />
                <p>By Clicking, I Agree To Terms & Conditions.</p>
            </div>
            <div class="buttons-to-right"> 
                <button type="submit" class="primary-btn">Save</button>
            </div>
        </div>
    </div>
</form>

<script src="scripts.js"></script>
<?php require_once 'ownerFooter.view.php'; ?>