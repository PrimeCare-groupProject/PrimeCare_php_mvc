<div class="approval-container">
    <div class="approval-left-content">
        <h3>Task 1</h3>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Property ID:</strong></span><span class="input-field-small">P1236</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Service Provider ID:</strong></span><span class="input-field-small">2024/08/23</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Date:</strong></span><span class="input-field-small">No 90 , Colombo , Sri Lanka</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Address:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
        <div class="input-group">
            <div class="input-group-aligned">
                <span class="input-label-aligend"><strong>Description:</strong></span><span class="input-field-small">Anything about the property assigning and other details</span>
            </div>
        </div>
    </div>
    <div class="approval-right-content">
    <a href='<?= ROOT ?>/dashboard/tasks/ongoingtask/<?php echo $_SESSION['repair1']?>/taskremoval'>
        <button class="primary-btn">Remove</button>
    </a>
    <a href='<?= ROOT ?>/dashboard/tasks/ongoingtask/<?php echo $_SESSION['repair1']?>/spreassign'>
        <button class="secondary-btn">Reassign</button>
    </a>
        <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="property" class="approval-right-content-img">
    </div>
</div>