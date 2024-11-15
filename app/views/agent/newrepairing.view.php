<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks/newtask'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>New <?php echo $_SESSION['repair']?> Task</h2>
</div>

<form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">Task Type</label>
            <input type="text" name="reapirType" placeholder="Type of task" class="input-field" required>

            <label class="input-label-aligned">
                <label class="input-label">Property</label>
                <select class="input-field-small" name="serviceProvider" required>
                    <option value="notassign">Select SP</option>
                    <?php foreach ($properties as $property): ?>
                        <option value="<?= $property->id ?>"><?= $property->name ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            
            <label class="input-label">Description about task</label>
            <textarea name="description" placeholder="Write About task" class="input-field" required></textarea>

            <label class="input-label">Upload Inventory Image*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>
        </div>

        <div class="owner-addProp-form-right">
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
        </div>
    </div>
</form>

<?php require_once 'agentFooter.view.php'; ?>