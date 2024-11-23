<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/property/"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Property name</h2>
                <p><span>Maintained By: </span>Agent's Name</p>
            </div>
        </div>
    </div>
</div>
<div class="RP__container">
    <div class="RP_side-containers">
        <h2>Report your Problem</h2>
        <img src="<?= ROOT ?>/assets/images/reportform.jpg" alt="Report about property">
    </div>
    <div class="RP_side-containers">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="problem">What is the problem?</label>
                <textarea name="problem" id="problem" cols="30" rows="3" class="input-field" required></textarea>
            </div>
            <div>
                <label for="urgency">How urgent is the problem?</label>
                <select name="urgency" id="urgency" class="input-field" required>
                    <option value="1">Urgent</option>
                    <option value="2">Not Urgent</option>
                </select>
            </div>
            <div>
                <label for="location">Where is the problem?</label>
                <input type="text" name="location" id="location" class="input-field" required>
            </div>
            <label>Upload Reports (Max 6)*</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="reports_images[]" id="reports_images" class="input-field" multiple
                    accept=".png, .jpg, .jpeg" data-max-files="6" onchange="handleFileSelect(event)" required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('reports_images').click()">Choose File</button>
                </div>
            </div>
            <div id="preview-container" class="owner-addProp-uploaded-files">
                <!-- Preview area for selected images -->
            </div>
            <div class="to-flex-end">
                <button type="submit" class="primary-btn" style="align-self: flex-end;">Submit</button>
            </div>
        </form>
    </div>
</div>

<script>
    const ROOT = '<?= ROOT ?>';
</script>
<script src="<?= ROOT ?>/assets/js/reports/addreports.js"></script>
<?php require_once 'customerFooter.view.php'; ?>