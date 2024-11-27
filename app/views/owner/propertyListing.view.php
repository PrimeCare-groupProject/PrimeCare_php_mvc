<?php require_once 'ownerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <h2>properties</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <!-- <button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button> -->
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/propertylisting/addproperty'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new property</span>
        </div>
    </div>
</div>


<div class="errors" style="display: <?= !empty($status) ? 'block' : 'none'; ?>; background-color: #b5f9a2;">
    <?php if (!empty($status)): ?>
        <p><?= $status;  ?></p>
    <?php endif; ?>
    <?php $_SESSION['status'] = '' ?>
</div>

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (!empty($properties)): ?>
            <?php foreach ($properties as $property): ?>
                <div class="property-card">
                    <div class="property-image">
                        <!-- <a href="<?= ROOT ?>/property/propertyUnitOwner/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= explode(',', $property->property_images)[0] ?>" alt="Property Image"></a> -->
                        <a href="<?= ROOT ?>/property/propertyUnitOwner/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= explode(',', $property->property_images)[0] ?>" alt="Property Image"></a>
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <h3><?= $property->name ?></h3>
                                <div class="property-info">
                                    <span><img src="<?= ROOT ?>/assets/images/building-plan.png" class="property-info-img" /><?= $property->units ?> Unit</span>
                                    <span><img src="<?= ROOT ?>/assets/images/double-bed.png" class="property-info-img" /><?= $property->bedrooms ?> Rooms</span>
                                </div>
                            </div>
                            <div>
                                <div class="property-status">
                                    <span class="border-button"><?= $property->status ?></span>
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
                                <a href="<?= ROOT ?>/dashboard/updateProperty/<?= $property->property_id ?>" class="delete-btn"><img src="<?= ROOT ?>/assets/images/edit.png" class="property-info-img" /></a>
                                <a href="<?= ROOT ?>/property/dropProperty/<?= $property->property_id ?>" class="edit-btn"><img src="<?= ROOT ?>/assets/images/delete.png" class="property-info-img" /></a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No properties found.</p>
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

<?php require_once 'ownerFooter.view.php'; ?>