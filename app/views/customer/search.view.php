<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Search</h2>
    <div class="view-filter-button">
        <img src="<?= ROOT ?>/assets/images/arrow-down.png" alt="see" class="filter-menu-btn" id="filter-menu-btn" onclick="toggleMenu()">
    </div>
</div>

<form>

    <div class="filter-menu-container hide-items" id="hided-menu">
        <div class="filter-menu">
            <div class="filter-row-instance">
                <div class="half-of-the-row">
                    <label>Location:
                        <input type="text" placeholder="Location">
                    </label>
                </div>
                <div class="half-of-the-row">
                    <label>Property Type:
                        <select>
                            <option value="">Select Type</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                        </select>
                    </label>
                </div>
                <div class="half-of-the-row">
                    <label>Status:
                        <select>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Under Maintenance</option>
                        </select>
                    </label>
                </div>
                <div class="half-of-the-row">
                    <label>Sort By:
                        <select>
                            <option value="price-asc">Price Low to High</option>
                            <option value="price-desc">Price High to Low</option>
                            <option value="newest">Newest</option>
                        </select>
                    </label>
                </div>
            </div>
    
            <div class="filter-row-instance">
                <div class="half-of-the-row">
                    <label>Price Range:
                        <input type="number" placeholder="Min Price">
                        <input type="number" placeholder="Max Price">
                    </label>
                </div>
                <div class="half-of-the-row">
                    <label>Rooms:
                        <input type="number" placeholder="Min Rooms">
                        <input type="number" placeholder="Max Rooms">
                    </label>
                </div>
            </div>
    
            <div class="filter-row-instance">
                <button type="button" onclick="applyFilters()" class="primary-btn">Apply Filters</button>
            </div>
        </div>
    </div>
</form>

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (!empty($properties)): ?>
            <?php foreach ($properties as $property): ?>
                <div class="property-card">
                    <div class="property-image">
                        <!-- <a href="<?= ROOT ?>/dashboard/propertyunit/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= explode( ',' , $property->property_images)[0] ?>" alt=""></a> -->
                        <a href="<?= ROOT ?>/property/propertyUnit/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= explode( ',' , $property->property_images)[0] ?>" alt=""></a>
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
                                    <span class="border-button-for-rent">RS.<?= $property->rent_on_basis ?></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="property-description"><img src="<?= ROOT ?>/assets/images/location.png" class="property-info-img" /><?= $property->address ?></p>
                        </div>
                        <div>
                            <p class="property-description"><?= $property->description ?></p>
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

<script src="<?= ROOT ?>/assets/js/customer/filterMenu.js"></script>

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

<?php require 'customerFooter.view.php' ?>