<div class="user_view-menu-bar">
    <h2>properties</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <!-- <button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button> -->
        <div class="tooltip-container">
            <button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button>
            <span class="tooltip-text">Add new property</span>
        </div>
    </div>
</div>

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>
        <?php require __DIR__ . '/../components/propertyListingComponent.php'; ?>

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