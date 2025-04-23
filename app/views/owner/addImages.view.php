<?php require_once 'ownerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/dashboard/propertyListing/propertyunitowner/<?= $property->property_id ?>">
                <img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons">
            </a>
            <div>
                <h2>Add Images: <span style="color: var(--green-color);"><?= $property->name ?></span></h2>
            </div>
        </div>
    </div>
</div>

<?php $images = explode(',', $property->property_images) ?>

<form method="POST" action="<?= ROOT ?>/dashboard/propertylisting/changeImages/<?= $property->property_id ?>" enctype="multipart/form-data">
    <div class="owner-addProp-form">
        <label class="input-label">Current Property Images</label>
        <div class="owner-addProp-uploaded-files">
            <?php if (!empty($images)) : ?>
                <?php foreach ($images as $index => $image) : ?>
                    <div class="img-preview" style="position: relative;">
                        <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image ?>" alt="Property Image" class="preview-img">
                        <div style="position: absolute; top: 5px; right: 5px;">
                            <input type="checkbox" name="remove_images[]" class="checked_red" value="<?= $image ?>"></input>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No images currently uploaded.</p>
            <?php endif; ?>
        </div>

        <label class="input-label">Upload New Images (Max total 6)*</label>
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

        <div style="margin-top: 20px; justify-content: center; display: flex;">
            <button type="submit" class="primary-btn">Update Images</button>
        </div>
    </div>
</form>


<script>
    function handleFileSelect(event) {
        const maxFiles = 6;
        const files = event.target.files;
        const previewContainer = document.getElementById('preview-container');

        const checkboxes = document.querySelectorAll('.owner-addProp-uploaded-files input[type="checkbox"]');
        let remainingExisting = 0;
        checkboxes.forEach(cb => {
            if (!cb.checked) remainingExisting++;
        });

        previewContainer.innerHTML = '';

        if ((files.length + remainingExisting) > maxFiles) {
            const imgContainer = document.createElement('div');
            imgContainer.className = 'img-preview-error';
            imgContainer.style.width = '100%';
            imgContainer.innerHTML = `<p class="image-preview-error-p">You can only have a maximum of ${maxFiles} images in total (including existing ones).</p>`;
            previewContainer.appendChild(imgContainer);
            event.target.value = '';
            return;
        }

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'img-preview';
                imgContainer.innerHTML = `
                <img src="${e.target.result}" alt="Selected Image" class="preview-img">
            `;
                previewContainer.appendChild(imgContainer);
            };
            reader.readAsDataURL(file);
        });
    }
</script>


<?php require_once 'ownerFooter.view.php'; ?>