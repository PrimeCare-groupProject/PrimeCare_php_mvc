<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/propertylisting.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>PrimeCare</title>
</head>

<body>
    <div class="PL__navigation-bar">
        <div class="PL__top-navigations">
            <ul>
                <li><a href="<?= ROOT ?>/home"><img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare" class="header-logo-png"></a></li>
                <li><?php
                    if (isset($_SESSION['user'])) {
                        echo "<button class='header__button' onClick=\"window.location.href = 'dashboard/profile'\">";
                        echo "<img src='" . get_img($_SESSION['user']->image_url) . "' alt='Profile' class='header_profile_picture'>";
                        echo "Profile";
                    } else {
                        echo "<button class='header__button' onClick=\"window.location.href = 'login'\">";
                        echo "Sign In | Log In";
                    }

                    ?></li>
            </ul>
        </div>
        <div class="PL_filter-section">
            <div class="PL__filter">
            <form action="<?= ROOT ?>/propertyListing/showListing" method="POST">
                    <div class="PL_form_main-filters">
                        <div class="flex-bar">
                            <img src="<?= ROOT ?>/assets/images/setting.png" alt="see" id="toggleFilters" class="small-icons" style="filter: invert(1); margin-left: 5px;" onclick="toggleFilters()">
                            <div class="search-container">
                                <input type="text" name="searchTerm" id="searchTerm" class="search-input" placeholder="Search Anything..." value="<?= old_value('searchTerm') ?>">
                                <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
                            </div>
                        </div>
                    </div>
                    <div class="PL_form_filters" id="PL_form_filters" style="display: none;">
                        <div class="filter-menu">
                            <div class="filter-row-instance">
                                <!-- Sort By -->
                                <div class="half-of-the-row">
                                    <label>Sort By:
                                        <select id="sort_by" name="sort_by">
                                            <option value="" <?= old_select('sort_by', '') ?>>-- Select --</option>
                                            <option value="price-asc" <?= old_select('sort_by', 'price-asc') ?>>Price Low to High</option>
                                            <option value="price-desc" <?= old_select('sort_by', 'price-desc') ?>>Price High to Low</option>
                                            <option value="newest" <?= old_select('sort_by', 'newest') ?>>Newest</option>
                                            <option value="oldest" <?= old_select('sort_by', 'oldest') ?>>Oldest</option>
                                            <option value="size-asc" <?= old_select('sort_by', 'size-asc') ?>>Size (Small to Large)</option>
                                            <option value="size-desc" <?= old_select('sort_by', 'size-desc') ?>>Size (Large to Small)</option>
                                        </select>
                                    </label>
                                </div>
                                
                                <!-- Price Range -->
                                <div class="half-of-the-row">
                                    <label>Price Range:
                                        <div class="inline-block-inputs">
                                            <input type="number" id="min_price" name="min_price" placeholder="Min" class="short-box" value="<?= old_value('min_price') ?>">
                                            <input type="number" id="max_price" name="max_price" placeholder="Max" class="short-box" value="<?= old_value('max_price') ?>">
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="half-of-the-row">
                                    <label>Check-in Date:
                                        <input type="date" id="check_in_date" name="check_in" placeholder="Check-in Date" value="<?= old_date('check_in', date('Y-m-d')) ?>">
                                    </label>
                                </div>
                                
                                <!-- Check-out Date -->
                                <div class="half-of-the-row">
                                    <label>Check-out Date:
                                        <input type="date" id="check_out_date" name="check_out" placeholder="Check-out Date" value="<?= old_date('check_out', date('Y-m-d')) ?>">
                                    </label>
                                </div>

                                <!-- Location Filters -->
                                <div class="half-of-the-row">
                                    <label>City:
                                        <input type="text" id="city" name="city" placeholder="City" value="<?= old_value('city') ?>">
                                    </label>
                                </div>

                                <div class="half-of-the-row">
                                    <label>State/Province:
                                        <input type="text" id="state" name="state" placeholder="State/Province" value="<?= old_value('state') ?>">
                                    </label>
                                </div>
                                
                                <!-- Apply Filters Button -->
                                <div class="half-of-the-row" style="display: flex; flex-direction: column; justify-content: space-between; align-items: center;">
                                    <button type="submit" 
                                        style="width: 100px; padding: 5px; border-radius: 12px; background-color: orange; color: white; border: none; cursor: pointer; ">
                                        Apply Filters
                                    </button>
                                    <button type="button" onclick="resetFilters()" 
                                        style="width: 100px; padding: 5px; border-radius: 12px; background-color: white; color: black; border: 1px solid #ccc; cursor: pointer;">
                                        Reset Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="content-section" id="content-section">
            <div class="listing-items">
            <?php if (!empty($properties)): ?>
                <?php foreach($properties as $property): ?>
                    <div class="PL_property-card">
                        <a href="<?= ROOT ?>/propertyListing/showListingDetail/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= explode( ',' , $property->property_images)[0] ?>" alt="property" class="property-card-image"></a>
                        <div class="content-section-of-card">
                            <div class="address">
                            <?= $property->address ?>
                            </div>
                            <div class="name">
                            <?= $property->name ?>
                            </div>
                            <div class="price">
                            <?= $property->rental_price ?> /Month
                            </div>
                        </div>
                        <div class="units-diplays">
                            <div class="unit-display__item">
                                <div class="unit-display__item__icon">
                                    <img src="<?= ROOT ?>/assets/images/bed.png" alt="beds" class="unit-display__item__icon__image">
                                </div>
                                <div class="unit-display__item__text">
                                    <div class="unit-display__item__text__number">
                                    <?= $property->bedrooms ?>
                                    </div>
                                </div>
                            </div>
                            <div class="unit-display__item">
                                <div class="unit-display__item__icon">
                                    <img src="<?= ROOT ?>/assets/images/bathroom.png" alt="baths" class="unit-display__item__icon__image">
                                </div>
                                <div class="unit-display__item__text">
                                    <div class="unit-display__item__text__number">
                                    <?= $property->bathrooms ?>
                                    </div>
                                </div>
                            </div>
                            <div class="unit-display__item">
                                <div class="unit-display__item__icon">
                                    <img src="<?= ROOT ?>/assets/images/size.png" alt="area" class="unit-display__item__icon__image">
                                </div>
                                <div class="unit-display__item__text">
                                    <div class="unit-display__item__text__number">
                                    <?= $property->size_sqr_ft ?>
                                    </div>
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
    </div>

    <!-- <script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script> -->
    <script>
        const toggleButton = document.getElementById('toggleFilters');
        const filters = document.getElementById('PL_form_filters');

        toggleButton.addEventListener('click', function() {
            if(filters.style.display === 'none' || filters.style.display === '') {
                filters.style.display = 'block';
            } else {
                filters.style.display = 'none';
            }
        });

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
        function resetFilters() {
            // Reset dropdown selections
            document.getElementById('sort_by').selectedIndex = 0;
            
            // Reset price range inputs
            document.getElementById('min_price').value = '';
            document.getElementById('max_price').value = '';
            
            // Reset date inputs
            document.getElementById('check_in_date').value = '';
            document.getElementById('check_out_date').value = '';
            
            // Reset location inputs
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            
            // Reset search term
            document.getElementById('searchTerm').value = '';
            
            // Optionally refresh the listing
            // applyFilters(); // Uncomment this if you want to refresh listings after reset
        }
    </script>
</body>

</html>