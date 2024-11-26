<?php require 'ownerHeader.view.php' ?>

<div class="user_view-menu-bar">
<a href='<?= ROOT ?>/property/propertylisting'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Repairs</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
    </div>
</div>

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Door Lock Repair and Replacement&cost_per_hour=1000&estimated_hours=2'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/DoorRepair.jpg" alt="Door Lock Repair">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Door Lock Repair and Replacement</h3>
                    </div>
                </div>
                <p class="property-description">
                    Fixes or replaces faulty door locks to ensure secure and smooth access, covering issues from jammed locks to broken keys and misaligned mechanisms.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Plumbing Leak Repair and Maintenance&cost_per_hour=1500&estimated_hours=3'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/Plumbing.jpg" alt="Plumbing Repair">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Plumbing Leak Repair and Maintenance</h3>
                    </div>
                </div>
                <p class="property-description">
                    Addresses water leaks, pipe bursts, and fixture malfunctions, preventing water damage, mold growth, and ensuring efficient water flow throughout the property.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=HVAC Maintenance&cost_per_hour=2000&estimated_hours=4'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/ac.jpg" alt="HVAC Maintenance">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>HVAC Maintenance</h3>
                    </div>
                </div>
                <p class="property-description">
                    Regularly cleans and services HVAC units for optimal temperature control and air quality, ensuring efficient operation and extending equipment lifespan.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Electrical System Inspection and Maintenance&cost_per_hour=1800&estimated_hours=3'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/elect.jpg" alt="Electrical Maintenance">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Electrical System Inspection and Maintenance</h3>
                    </div>
                </div>
                <p class="property-description">
                    Provides routine inspections, repairs, and upgrades for outlets, wiring, and circuit breakers to maintain safe and efficient electrical systems.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Gardening and Landscaping Services&cost_per_hour=1200&estimated_hours=5'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/gardening.png " alt="Gardening Services">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Gardening and Landscaping Services</h3>
                    </div>
                </div>
                <p class="property-description">
                    Offers regular lawn care, plant trimming, soil treatment, and seasonal planting to enhance the property's curb appeal and maintain a vibrant, healthy landscape.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Window and Glass Repair&cost_per_hour=1300&estimated_hours=2'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/glass.jpg" alt="Window Repair">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Window and Glass Repair</h3>
                    </div>
                </div>
                <p class="property-description">
                    Repairs cracked or broken windows, restores seals, and improves insulation, keeping the property secure, energy-efficient, and visually appealing.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Painting and Surface Touch-Ups&cost_per_hour=1100&estimated_hours=6'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/paint.png" alt="Painting Services">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Painting and Surface Touch-Ups</h3>
                    </div>
                </div>
                <p class="property-description">
                    Refreshes interior and exterior paint, covering scuffs, chips, and fading to keep the property looking fresh and well-maintained.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Roof and Gutter Cleaning&cost_per_hour=1600&estimated_hours=4'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/roof.jpg" alt="Roof Cleaning">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Roof and Gutter Cleaning</h3>
                    </div>
                </div>
                <p class="property-description">
                    Removes debris and clogs from roofs and gutters to prevent water damage and ensure effective drainage, particularly during heavy rainfall seasons.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Pool Cleaning and Maintenance&cost_per_hour=1400&estimated_hours=3'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/pool.jpg" alt="Pool Maintenance">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Pool Cleaning and Maintenance</h3>
                    </div>
                </div>
                <p class="property-description">
                    Regularly cleans and balances pool chemicals, checks equipment like filters and pumps, and performs minor repairs, ensuring a safe and enjoyable swimming environment.
                </p>
            </div>
        </div>

        <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Flooring and Carpet Repair&cost_per_hour=1700&estimated_hours=4'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/floor.jpg" alt="Flooring Repair">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Flooring and Carpet Repair</h3>
                    </div>
                </div>
                <p class="property-description">
                    Fixes issues like loose tiles, cracks, and carpet stains to maintain a clean, safe, and comfortable living space for occupants.
                </p>
            </div>
        </div>
            <div class="property-card" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/servicerequest?type=Pest Control and Extermination&cost_per_hour=1900&estimated_hours=3'">
            <div class="property-image">
                <img src="<?= ROOT ?>/assets/images/fest.jpg" alt="pest Maintenance">
            </div>
            <div class="property-details">
                <div class="profile-details-items">
                    <div>
                        <h3>Pest Control and Extermination</h3>
                    </div>
                </div>
                <p class="property-description">
                Provides routine inspections and extermination services for pests such as rodents, termites, and insects, keeping the property safe and pest-free.
                </p>
            </div>
        </div>
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

<?php require 'ownerFooter.view.php' ?>