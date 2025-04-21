<?php require 'customerHeader.view.php' ?>

<style>
    .listing-the-property {
        margin-bottom: 80px; 
        padding-bottom: 40px; 
    }
    
    .pagination {
        margin-top: 30px;
        margin-bottom: 60px; 
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        position: relative; 
        z-index: 10; 
    }
    
    .property-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 320px; 
    }
    
    body {
        padding-bottom: 70px;
        min-height: 100vh;
        position: relative; 
    }
    
    .bottom-spacer {
        height: 50px;
        width: 100%;
        clear: both;
    }
    
    footer {
        position: relative;
        margin-top: 60px;
    }

    .property-card-service-item{
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        background-color: var(--white-color);
        border-radius: 24px;
        
        box-shadow: var(--box-shadow);
        overflow: hidden;
        max-width: 100%;
        margin: 5px;
        cursor: pointer;
    }
</style>

<div class="user_view-menu-bar">
    <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
    <h2>Repairs</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" id="service-search" class="search-input" placeholder="Search Anything...">
            <button id="search-btn" class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
    </div>
</div>

<div class="listing-the-property">
    <div class="property-listing-grid">
        <?php if(!empty($services)): ?>
            <?php foreach($services as $service): ?>
                <div class="property-card-service-item"
                     onclick="window.location.href='<?= ROOT ?>/dashboard/requestServiceExternal?service_id=<?= $service->service_id ?>'">
                    <div class="property-image">
                        <?php 
                        $imgPath = ROOT . "/assets/images/repairimages/" . $service->service_img;
                        $placeholderPath = ROOT . "/assets/images/service_placeholder.jpg";
                        ?>
                        <img src="<?= !empty($service->service_img) ? $imgPath : $placeholderPath ?>" 
                             alt="<?= $service->name ?>" 
                             onerror="this.src='<?= $placeholderPath ?>'">
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
                        <p class="service-cost">$<?= number_format($service->cost_per_hour, 2) ?> per hour</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-services-message">
                <p>No repair services available at this time.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination Buttons -->
    <div class="pagination">
        <button class="prev-page" disabled><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get search elements
        const searchInput = document.getElementById('service-search');
        const searchBtn = document.getElementById('search-btn');
        
        // Function to perform search
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const serviceItems = document.querySelectorAll('.property-card-service-item');
            
            let resultsFound = false;
            
            serviceItems.forEach(item => {
                // Search in name and description
                const name = item.querySelector('h3').textContent.toLowerCase();
                const description = item.querySelector('.property-description').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || description.includes(searchTerm)) {
                    item.style.display = 'flex';
                    resultsFound = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            const noResultsElement = document.querySelector('.no-results-message');
            if (!resultsFound && searchTerm !== '') {
                if (!noResultsElement) {
                    const noResults = document.createElement('div');
                    noResults.className = 'no-results-message';
                    noResults.innerHTML = '<p>No services found matching your search.</p>';
                    document.querySelector('.property-listing-grid').appendChild(noResults);
                }
            } else if (noResultsElement) {
                noResultsElement.remove();
            }
            
            // Reset pagination after search
            resetPagination();
        }
        
        // Reset pagination to first page
        function resetPagination() {
            currentPage = 1;
            document.querySelector('.current-page').textContent = currentPage;
            document.querySelector('.prev-page').disabled = true;
            
            // Count visible items
            const visibleItems = document.querySelectorAll('.property-card-service-item[style*="display: flex"]').length;
            const totalVisiblePages = Math.ceil(visibleItems / listingsPerPage);
            
            document.querySelector('.next-page').disabled = (totalVisiblePages <= 1);
            
            // Show/hide pagination based on results
            document.querySelector('.pagination').style.display = (totalVisiblePages > 1) ? 'flex' : 'none';
            
            showPage(currentPage);
        }
        
        // Handle search button click
        if (searchBtn) {
            searchBtn.addEventListener('click', performSearch);
        }
        
        // Handle Enter key in search input
        if (searchInput) {
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }
        
        // Pagination functionality
        const listingsPerPage = 9;
        const listings = document.querySelectorAll('.property-card-service-item');
        const totalPages = Math.ceil(listings.length / listingsPerPage);
        let currentPage = 1;
        
        document.querySelector('.pagination').style.display = (totalPages > 1) ? 'flex' : 'none';
        
        function showPage(page) {
            let visibleCount = 0;
            let visibleOnThisPage = 0;
            
            listings.forEach((listing, index) => {
                // Only consider listings that aren't filtered out by search
                if (window.getComputedStyle(listing).display !== 'none') {
                    visibleCount++;
                    if (visibleCount > (page-1) * listingsPerPage && visibleCount <= page * listingsPerPage) {
                        listing.style.display = 'flex';
                        visibleOnThisPage++;
                    } else {
                        listing.style.display = 'none';
                    }
                }
            });
            
            document.querySelector('.current-page').textContent = page;
            document.querySelector('.prev-page').disabled = page === 1;
            
            const totalVisiblePages = Math.ceil(visibleCount / listingsPerPage);
            document.querySelector('.next-page').disabled = page >= totalVisiblePages;
        }
        
        document.querySelector('.next-page').addEventListener('click', () => {
            if (!document.querySelector('.next-page').disabled) {
                currentPage++;
                showPage(currentPage);
                document.querySelector('.listing-the-property').scrollIntoView({behavior: 'smooth'});
            }
        });
        
        document.querySelector('.prev-page').addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
                document.querySelector('.listing-the-property').scrollIntoView({behavior: 'smooth'});
            }
        });
        
        // Initialize pagination
        showPage(currentPage);
    });
</script>

<?php require 'customerFooter.view.php' ?>