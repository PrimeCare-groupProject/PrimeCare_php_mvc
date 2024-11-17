<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>repairings</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/repairings/addnewrepair'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new property</span>
        </div>
    </div>
</div>
<!-- 
<div class="success-msg-container">
    <p class="success-msg"><?= $property->success['insert'] ?? '' ?></p>
</div> -->

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                <!--<div class="property-card">
                    <div class="property-image">
                        <img src="<?= ROOT ?>/assets/images/repairimages/concreterepairing.png" alt="services">
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <h3><?= $service->name ?></h3>
                                <div class="property-info">
                                    <span><img src="<?= ROOT ?>/assets/images/building-plan.png" class="property-info-img" /><?= $property->units ?> Unit</span>
                                    <span><img src="<?= ROOT ?>/assets/images/double-bed.png" class="property-info-img" /><?= $property->bedrooms ?> Rooms</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="property-description"><img src="<?= ROOT ?>/assets/images/location.png" class="property-info-img" /><?= $property->address ?></p>
                        </div>
                        <div>
                            <p class="property-description">
                            <?= $property->description ?>
                            </p>
                        </div>
                        <div class="property-actions">
                            <a href="#" class="change-status">change Pending</a>
                            <div>
                                <a href="<?=ROOT?>/dashboard/updateRepairing/<?= $property->property_id ?>" class="delete-btn"><img src="<?= ROOT ?>/assets/images/edit.png" class="property-info-img" /></a>
                                <a href="<?= ROOT ?>/property/delete/<?= $property->property_id ?>" class="edit-btn"><img src="<?= ROOT ?>/assets/images/delete.png" class="property-info-img" /></a>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="repair-card">
                    <div class="repair-image">
                        <img src="<?= ROOT ?>/assets/images/repairimages/deckrepairing.png" alt="services">
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <span class="no-underline"><h3><?= $service->name ?></h3></span>
                            </div>
                        </div>
                        <div>
                            <p class="property-description">
                            <?= $service->description ?>
                            </p>
                        </div>
                        <div>
                            <p class="property-description"> Cost Per Hour : 
                            <?= $service->cost_per_hour ?>
                            </p>
                        </div>
                        <div class="property-actions">
                            <a href="#" class="change-status">change Pending</a>
                            <div>
                                <a href="<?=ROOT?>/dashboard/repairings/editrepairing" class="delete-btn"><img src="<?= ROOT ?>/assets/images/edit.png" class="property-info-img" /></a>
                                <a href="<?= ROOT ?>/dashboard/repairings/delete/<?= $service->service_id ?>" class="edit-btn"><img src="<?= ROOT ?>/assets/images/delete.png" class="property-info-img" /></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?> 
        <?php else: ?>
            <p>No Repairings found.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination Buttons -->
    <div class="pagination">
        <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
    </div>
</div>

<script>
    let currentPage = 1;
    const listingsPerPage = 9;
    const listings = document.querySelectorAll('.property-listing-grid .property-component');
    const totalPages = Math.ceil(listings.length / listingsPerPage);

    function showPage(page) {
        // Hide all listings
        listings.forEach((listing, index) => {
            listing.style.display = 'none';
        });

        // Show listings for the current page
        const start = (page - 1) * listingsPerPage;
        const end = start + listingsPerPage;

        for (let i = start; i < end && i < listings.length; i++) {
            listings[i].style.display = 'block';
        }

        // Update pagination display
        document.querySelector('.current-page').textContent = page;
    }

    document.querySelector('.next-page').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    });

    document.querySelector('.prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    });

    // Initial page load
    showPage(currentPage);
</script>

<?php require_once 'agentFooter.view.php'; ?>