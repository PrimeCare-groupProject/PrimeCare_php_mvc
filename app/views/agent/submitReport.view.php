<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/preInspection'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>PreInspection submission on: <span style="color: var(--green-color);"><?= $property->name ?></span></h2>
</div>


<form method="POST" action="<?= ROOT ?>/dashboard/preInspection/submitPreInspection/<?= $property->property_id ?>" enctype="multipart/form-data">
    <div class="owner-addProp-form">
        <h3 class="form-headers no-top-border" style="align-self: center;">PreInspection Details</h3>
        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Property ID*</label>
                <input type="text" name="property_id" placeholder="Enter Property ID" class="input-field" value="<?= $property->property_id ?>" required>
            </div>
            <div class="input-group-group">
                <label class="input-label">Name Of Property*</label>
                <input type="text" name="name" placeholder="Enter Property Name" class="input-field" value="<?= $property->name ?>" required>
            </div>
        </div>


        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Provided Details*</label>
                <select name="provided_details" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="True">True</option>
                    <option value="False">False</option>
                    <option value="Not_Checked">Not Cheched</option>
                </select>
            </div>
            <div class="input-group-group">
                <label class="input-label">Title Deed*</label>
                <select name="title_deed" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="True">True</option>
                    <option value="False">False</option>
                    <option value="Not_Checked">Not Cheched</option>
                </select>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Utility Bills*</label>
                <select name="utility_bills" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="True">True</option>
                    <option value="False">False</option>
                    <option value="Not_Checked">Not Cheched</option>
                </select>
            </div>
            <div class="input-group-group">
                <label class="input-label">Owner ID Copy*</label>
                <select name="owner_id_copy" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="True">True</option>
                    <option value="False">False</option>
                    <option value="Not_Checked">Not Cheched</option>
                </select>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Lease Agreement*</label>
                <select name="lease_agreement" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="True">True</option>
                    <option value="False">False</option>
                    <option value="Not_Checked">Not Cheched</option>
                </select>
            </div>
            <div class="input-group-group">
                <label class="input-label">Property Condition*</label>
                <select name="property_condition" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="Good">Good</option>
                    <option value="Average">Average</option>
                    <option value="Poor">Poor</option>
                    <option value="Not_Checked">Not Cheched</option>
                </select>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group-group">
                <label class="input-label">Owner Present*</label>
                <select name="owner_present" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="True">True</option>
                    <option value="False">False</option>
                    <option value="Not_Checked">Not Cheched</option>
                </select>
            </div>
            <div class="input-group-group">
                <label class="input-label">Recommendations*</label>
                <select name="recommendation" class="input-field">
                    <option value="">-- Select Purpose --</option>
                    <option value="Approved">Approved</option>
                    <option value="Requires_Fixes">Requires Fixes</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Not_Checked">Not Checked</option>
                </select>
            </div>
        </div>

        <label class="input-label">Maintenance Issues</label>
        <textarea name="Maintenance_issues" placeholder="Any Maintenance Issues" class="input-field"></textarea>

        <label class="input-label">Note</label>
        <textarea name="notes" placeholder="Any Notes.." class="input-field"></textarea>

        <label class="input-label">Upload Document Details*</label>
        <div class="owner-addProp-file-upload">
            <input type="file" name="property_document" id="ownership_details" accept=".pdf" onchange="handleFileSelectForDocs(event)" required>
            <div class="owner-addProp-upload-area">
                <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                <p class="upload-area-no-margin">Drop your files here</p>
                <button type="button" class="primary-btn" onclick="document.getElementById('ownership_details').click()">Choose File</button>
            </div>
        </div>
        <div id="uploaded-files" class="owner-addProp-uploaded-files">
            <!-- Uploaded files will be displayed here -->
        </div>
        <div id="preview-container-docs" class="owner-addProp-uploaded-files">
            <!-- Preview area for selected images -->
        </div>

        <hr>
        <div class="items-inline">
            <input type="checkbox" name="terms" required />
            <p>By Clicking, I Agree To Terms & Conditions.</p>
        </div>
        <div class="buttons-to-right">
            <button type="submit" class="primary-btn">Submit</button>
        </div>
</form>

<script>
    ROOT = "<?= ROOT ?>";
</script>

<script src="<?= ROOT ?>/assets/js/property/addproperty.js"></script>

<?php require_once 'agentFooter.view.php'; ?>