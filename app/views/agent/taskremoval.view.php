<?php require_once 'agentHeader.view.php'; ?>
<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/tasks'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Task Removal</h2>
</div>
<div class="repair-container">
    <div>
        <h3>Service Place Information</h3>
    </div>
    <div class="serPro">
        <img src="<?= ROOT ?>/assets/images/serProimg.png" alt="Back" class="serProimg">
        <h3>Mr Service</h3>
        <h3>Provider</h3>

    </div>
    <div class="repair-left-content">
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Property ID:</strong></span><span class="input-field-small">P1236</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Property Name:</strong></span><span class="input-field-small">2024/08/23</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Province:</strong></span><span class="input-field-small">No 90 , Colombo , Sri Lanka</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Country:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>City:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Id:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Address:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Repair Type:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>SP ID:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>SP Name:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="serPro1">
            <a href='<?= ROOT ?>/dashboard/tasks/ongoingtask/<?php echo $_SESSION['repair1']?>/taskremoval'>
                <button class="primary-btn">Remove</button>
            </a>
            <a href='<?= ROOT ?>/dashboard/tasks/ongoingtask/<?php echo $_SESSION['repair1']?>'>
                <button class="secondary-btn">Cancel</button>
            </a>
        </div>
</div>
<?php require_once 'agentFooter.view.php'; ?>