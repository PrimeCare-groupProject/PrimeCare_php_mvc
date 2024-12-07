<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <!-- <img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"> -->
    <div class="gap"></div>
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
        <?php foreach ($services as $service) : ?>
            <div class="property-card">
                <div class="property-image">
                    <a href="<?= ROOT ?>/serve/serviceUnit/<?= $service->service_id ?>">
                        <img src="<?= ROOT ?>/<?= $service->service_img ?>" alt="services">
                    </a>
                </div>
                <div class="property-details">
                    <div class="profile-details-items">
                        <div>
                            <h3><?= $service->name ?></h3>
                        </div>
                    </div>
                    <p class="property-description">
                        <?= $service->description ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
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

<?php require 'customerFooter.view.php' ?>