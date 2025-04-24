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
    
    .property-card-service-item {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 320px;
        background-color: var(--white-color);
        border-radius: 24px;
        box-shadow: var(--box-shadow);
        overflow: hidden;
        max-width: 100%;
        margin: 5px;
        cursor: pointer;
    }
/*     
    .property-card-service-item:hover {
    } */
    
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
    
    /* Selected Property Banner */
    .selected-property-banner {
        background: white;
        color: #333;
        padding: 15px 20px;
        margin-top: 10px;
        margin-bottom: 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* box-shadow: 0 4px 15px rgba(255, 204, 0, 0.2); */
        margin-left: 7px;
        margin-right: 7px;
    }
    
    .property-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .property-label {
        font-weight: 600;
    }
    
    .property-value {
        font-weight: 700;
        background-color: rgba(255, 204, 0, 0.15);
        padding: 4px 12px;
        border-radius: 20px;
        color: #ffb700;
    }
    
    .property-address {
        display: flex;
        align-items: center;
        font-size: 14px;
    }
    
    .service-cost {
        font-weight: 600;
        color: #333;
        background-color: rgba(255, 204, 0, 0.15);
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        margin-top: 5px;
    }
    
    /* Button styling */
    .select-button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        background: linear-gradient(135deg, #ffcc00, #ffb700);
        color: #333;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        width: auto;
        max-width: 160px;
        box-shadow: 0 3px 8px rgba(255, 204, 0, 0.2);
        gap: 8px;
        /* margin: 5px auto 0; */
        margin-bottom: 15px;
    }
    
    .select-button .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.25);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .select-button .icon svg {
        width: 10px;
        height: 10px;
        stroke: #333;
    }
    
    .select-button:hover {
        box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
        transform: translateY(-2px);
    }
    
    .select-button:hover .icon {
        transform: translateX(3px);
        background: rgba(255, 255, 255, 0.4);
    }
    
    .select-button:hover .icon svg {
        animation: bounceRight 0.8s infinite;
    }
    
    .button-text {
        z-index: 1;
        position: relative;
        letter-spacing: 0.5px;
        color: #333;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    
    .select-button:hover .button-text {
        transform: translateX(3px);
    }
    
    @keyframes bounceRight {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(3px); }
    }
    
    /* Hide glow */
    .button-glow {
        display: none;
    }

    /* Service Card Styling - Matching External Repair Listing */
    .property-card {
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 320px; 
    }

    .property-card-service-item {
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

    /* .property-card-service-item:hover {
    } */

    .property-image {
        height: 200px;
        overflow: hidden;
    }

    .property-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .property-details {
        padding: 15px;
    }

    .property-details h3 {
        margin-bottom: 10px;
        font-size: 18px;
        font-weight: 600;
        color: #333;
    }

    .property-description {
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .service-cost {
        font-weight: 600;
        color: #333;
        background-color: rgba(255, 204, 0, 0.15);
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        margin-top: 5px;
    }

    .property-actions {
        margin-top: auto;
        padding: 10px 0;
        display: flex;
        justify-content: center;
    }

    .select-button {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        background: linear-gradient(135deg, #ffcc00, #ffb700);
        color: #333;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        width: auto;
        max-width: 160px;
        box-shadow: 0 3px 8px rgba(255, 204, 0, 0.2);
        gap: 8px;
        margin: 5px auto 0;
    }

    .select-button .icon {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.25);
        width: 20px;
        height: 20px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .select-button .icon svg {
        width: 10px;
        height: 10px;
        stroke: #333;
    }

    .property-listing-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        padding: 10px 10;
    }
</style>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/dashboard/requestServiceOccupied"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
    <h2>Select Service for Request</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" id="service-search" class="search-input" placeholder="Search services...">
            <button id="search-btn" class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
    </div>
</div>

<div class="listing-the-property">
    <!-- Selected Property Banner -->
    <?php if (!empty($property)): ?>
    <div class="selected-property-banner">
        <div class="property-info">
            <span class="property-label">Selected Property:</span>
            <span class="property-value"><?= htmlspecialchars($property->name ?? 'Unknown Property') ?></span>
        </div>
        <div class="property-address">
            <img src="<?= ROOT ?>/assets/images/location.png" class="property-info-img" />
            <?= htmlspecialchars($property->address ?? 'No address available') ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Dynamic Service Listings -->
    <div class="property-listing-grid">
        <?php if(!empty($services)): ?>
            <?php foreach($services as $service): ?>
                <div class="property-card-service-item" 
                     data-name="<?= htmlspecialchars(strtolower($service->name)) ?>" 
                     data-description="<?= htmlspecialchars(strtolower($service->description)) ?>"
                     onclick="window.location.href='<?= ROOT ?>/dashboard/serviceRequest?type=<?= urlencode($service->name) ?>&cost_per_hour=<?= $service->cost_per_hour ?>&property_id=<?= $property->property_id ?>&property_name=<?= urlencode($property->name) ?>'">
                    <div class="property-image">
                        <?php 
                        $imgPath = ROOT . "/" . ($service->service_img ?? '');
                        $placeholderPath = ROOT . "/assets/images/uploads/service_images/service_placeholder.jpg";
                        ?>
                        <img src="<?= !empty($service->service_img) ? $imgPath : $placeholderPath ?>" 
                             alt="<?= htmlspecialchars($service->name) ?>" 
                             onerror="this.src='<?= $placeholderPath ?>'">
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <h3><?= htmlspecialchars($service->name) ?></h3>
                            </div>
                        </div>
                        <p class="property-description">
                            <?= htmlspecialchars($service->description) ?>
                        </p>
                        <p class="service-cost">$<?= number_format($service->cost_per_hour, 2) ?> per hour</p>
                        
                        <div class="property-actions">
                            <a href="<?= ROOT ?>/dashboard/serviceRequest?type=<?= urlencode($service->name) ?>&cost_per_hour=<?= $service->cost_per_hour ?>&property_id=<?= $property->property_id ?>&property_name=<?= urlencode($property->name) ?>" class="select-button" onclick="event.stopPropagation();">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 18l6-6-6-6"></path>
                                    </svg>
                                </span>
                                <span class="button-text">Select</span>
                            </a>
                        </div>
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
                const name = item.dataset.name;
                const description = item.dataset.description;
                
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
        
        // Add animation effects to buttons
        document.querySelectorAll('.select-button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent card click when button is clicked
            });
        });
        
        // Initialize pagination
        showPage(currentPage);
    });
</script>

<?php require 'customerFooter.view.php' ?>