<?php require_once 'agentHeader.view.php'; ?>
<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Tasks</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/tasks/newtask'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new task</span>
        </div>
    </div>
</div>
<div class="blur-container">
    <div class="listing-the-property">
        <!-- Property Listings -->
        <div class="property-listing-grid">
            <?php if (!empty($tasks)): ?>
                <?php foreach ($tasks as $task): ?>
                    <div class="property-card">
                        <div class="property-image">
                        <img src="<?= ROOT ?>/<?= $task->service_img ?>" alt="services">
                        </div>
                        <div class="taskdetails">
                            <div class="profile-details-items">
                                <div class="card1-body">
                                    <h3><?= $task->service_type ?></h3>
                                </div>
                            </div>
                            <div class="repair-actionsnew">
                                    <div class="booking1">
                                        <img src="<?= ROOT ?>/assets/images/location.png" class="taskimg" />
                                        <span class="tag tag-teal"><?= $task->property_name ?></span>
                                    </div>
                                    <div>
                                        <a href="<?=ROOT?>/dashboard/tasks/edittasks/<?= $task->service_id?>" class="delete1-btn"><img src="<?= ROOT ?>/assets/images/edit.png" class="property-info-img" /></a>
                                        <a href="javascript:void(0);" class="edit-btn" onclick="confirmDelete(<?= $task->service_id ?>)">
                                            <img src="<?= ROOT ?>/assets/images/delete.png" class="property-info-img" />
                                        </a>
                                    </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?> 
            <?php else: ?>
                <p>No Tasks found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Pagination Buttons -->
<div class="pagination">
    <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
    <span class="current-page">1</span>
    <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
</div>

<div id="deletePopup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <h3>Are you sure you want to delete this item?</h3>
        <p>This action cannot be undone. Please confirm.</p>
        <div class="popup-buttons">
            <button id="confirmDelete" class="confirm-btn">Delete</button>
            <button onclick="closePopup()" class="cancel-btn">Cancel</button>
        </div>
    </div>
</div>

<script>
    let deleteServiceId = null;

function confirmDelete(serviceId) {
    deleteServiceId = serviceId; // Store the ID to delete later
    document.getElementById('deletePopup').style.display = 'flex'; // Show the popup
    document.body.classList.add('popup-active'); // Apply the active class
}

function closePopup() {
    deleteServiceId = null; // Reset the stored ID
    document.getElementById('deletePopup').style.display = 'none'; // Hide the popup
    document.body.classList.remove('popup-active'); // Remove the active class
}

document.getElementById('confirmDelete').addEventListener('click', function () {
    if (deleteServiceId !== null) {
        // Redirect to the delete route
        window.location.href = "<?= ROOT ?>/dashboard/tasks/delete/" + deleteServiceId;
    }
});
</script>

<?php require_once 'agentFooter.view.php'; ?>
