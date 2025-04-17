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
                <form action="<?= ROOT ?>/propertyListing" method="POST">
                    <div class="PL_form_main-filters">
                        <div class="flex-bar">
                            <img src="<?= ROOT ?>/assets/images/setting.png" alt="see" id="toggleFilters" class="small-icons" style="filter: invert(1); margin-left: 5px;" onclick="toggleFilters()">
                            <div class="search-container">
                                <input type="text" class="search-input" placeholder="Search Anything...">
                                <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
                            </div>
                        </div>
                    </div>
                    <div class="PL_form_filters" id="PL_form_filters" style="display: none;">
                        <div class="filter-menu">
                            <div class="filter-row-instance">
                                <div class="half-of-the-row">
                                    <label>Type:
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
                                <div class="half-of-the-row">
                                    <label>Price Range:
                                        <div class="inline-block-inputs">
                                            <input type="number" placeholder="Min" class="short-box">
                                            <input type="number" placeholder="Max" class="short-box">
                                        </div>
                                    </label>
                                </div>
                                <div class="half-of-the-row">
                                    <label>Rooms:
                                        <input type="number" placeholder="Rooms">
                                    </label>
                                </div>
                                <div class="half-of-the-row" style="display: none;">
                                    <button type="button" onclick="applyFilters()" class="primary-btn">Apply Filters</button>
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
                    <?php foreach ($properties as $property): ?>
                        <?php
                        $images = explode(',', $property->property_images);
                        $firstImage = $images[0] ?? 'default.png'; // Fallback to a default image if none exist
                        ?>

                        <div class="PL_property-card">
                            <a href="<?= ROOT ?>/propertyListing/showListingDetail/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $firstImage ?>" alt="property" class="property-card-image"></a>
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

    <script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script>
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
</body>

</html>