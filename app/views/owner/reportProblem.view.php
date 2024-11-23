<?php require_once 'ownerHeader.view.php'; ?>

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
        <form action="" method="post">
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
            <div class="owner-addProp-file-upload">
                <!-- <label for="image">Upload an image</label>
                <input type="file" name="image" id="image" class="input-field" required> -->
                <input type="file" name="reports_images" id="reports_images" data-max-files="3" onchange="handleFileSelectForDocs(event)" required>
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

<script src="<?= ROOT ?>/assets/js/property/addreports.js"></script>
<?php require_once 'ownerFooter.view.php'; ?>