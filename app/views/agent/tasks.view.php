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
<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (!empty($tasks)): ?>
            <?php foreach ($tasks as $task): ?>
                <div class="property-card">
                    <div class="property-image">
                        <img src="<?= ROOT ?>/assets/images/DoorRepair.jpg" alt="services">
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
                                    <a href="<?=ROOT?>/dashboard/services/editservices/" class="delete1-btn"><img src="<?= ROOT ?>/assets/images/edit.png" class="property-info-img" /></a>
                                    <a href="javascript:void(0);" class="edit-btn" >
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
<!-- Pagination Buttons -->
<div class="pagination">
    <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
    <span class="current-page">1</span>
    <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
</div>

<?php require_once 'agentFooter.view.php'; ?>
