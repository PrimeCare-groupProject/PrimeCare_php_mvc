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
    // Add this at the beginning of your script
    // Add IDs to search elements
    document.querySelector('.search-input').id = 'repair-search';
    document.querySelector('.search-btn').id = 'search-btn';
    
    let currentPage = 1;
    const listingsPerPage = 9;
    const listings = document.querySelectorAll('.property-listing-grid .property-card');
    let totalPages = Math.ceil(listings.length / listingsPerPage);
    
    // Add this search function
    function performSearch() {
        const searchTerm = document.getElementById('repair-search').value.toLowerCase().trim();
        let visibleListings = [];
        
        // Filter listings based on search term
        listings.forEach((listing, index) => {
            const title = listing.querySelector('h3').textContent.toLowerCase();
            const description = listing.querySelector('.property-description').textContent.toLowerCase();
            
            if (searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm)) {
                visibleListings.push(listing);
            }
        });
        
        // Update total pages based on search results
        totalPages = Math.ceil(visibleListings.length / listingsPerPage);
        
        // Reset to first page when searching
        currentPage = 1;
        
        // Show no results message if needed
        const noResultsMessage = document.querySelector('.no-results-message');
        if (visibleListings.length === 0 && searchTerm !== '') {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.className = 'no-results-message';
                message.textContent = 'No repairs found matching your search.';
                message.style.textAlign = 'center';
                message.style.padding = '20px';
                message.style.color = '#666';
                document.querySelector('.property-listing-grid').appendChild(message);
            }
            // Hide pagination if no results
            document.querySelector('.pagination').style.display = 'none';
        } else {
            // Remove no results message if it exists
            if (noResultsMessage) {
                noResultsMessage.remove();
            }
            // Show pagination if we have results and more than one page
            document.querySelector('.pagination').style.display = totalPages > 1 ? 'flex' : 'none';
        }
        
        // Hide all listings first
        listings.forEach(listing => {
            listing.style.display = 'none';
        });
        
        // Show matched listings for current page only
        const start = 0; // Always start at first page after search
        const end = Math.min(listingsPerPage, visibleListings.length);
        
        for (let i = start; i < end; i++) {
            visibleListings[i].style.display = 'block';
        }
        
        // Update pagination display
        document.querySelector('.current-page').textContent = totalPages > 0 ? 1 : 0;
        document.querySelector('.prev-page').disabled = true;
        document.querySelector('.next-page').disabled = totalPages <= 1;
        
        // Store visible listings for pagination
        window.searchResults = visibleListings;
    }
    
    // Update the showPage function to work with search results
    function showPage(page) {
        const resultsToShow = window.searchResults || listings;
        
        // Hide all listings
        listings.forEach(listing => {
            listing.style.display = 'none';
        });
        
        // Show listings for the current page
        const start = (page - 1) * listingsPerPage;
        const end = start + listingsPerPage;
        
        for (let i = start; i < end && i < resultsToShow.length; i++) {
            resultsToShow[i].style.display = 'block';
        }
        
        // Update pagination display
        document.querySelector('.current-page').textContent = page;
    }
    
    // Add search event listeners
    document.getElementById('search-btn').addEventListener('click', performSearch);
    document.getElementById('repair-search').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Update navigation event listeners to work with search results
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