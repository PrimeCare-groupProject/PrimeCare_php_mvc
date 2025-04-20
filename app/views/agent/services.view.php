<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>services</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/services/addnewservice'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new service</span>
        </div>
    </div>
</div>

<div class="blur-container">
<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (!empty($services)): ?>
            <?php foreach ($services as $service): ?>
                    <div id="specific-page">
                        <div style="height: 96%;" class="property-card">
                                <div class="property-image">
                                    <img src="<?= ROOT ?>/<?= $service->service_img ?>" alt="services">
                                </div>
                                <div class="card1-body">
                                    <h2>
                                        <?= $service->name ?>
                                    </h2>
                                    <p class="limited-paragraph">
                                        <?= $service->description ?>
                                    </p>
                                    <div class="repair-actionsnew">
                                        <span class="tag tag-teal"><?= $service->cost_per_hour ?> LKR</span>
                                            <div>
                                                <a href="<?=ROOT?>/dashboard/services/editservices/<?= $service->service_id ?>" class="delete1-btn"><img src="<?= ROOT ?>/assets/images/edit.png" class="property-info-img" /></a>
                                                <a href="javascript:void(0);" class="edit-btn" onclick="confirmDelete(<?= $service->service_id ?>)">
                                                    <img src="<?= ROOT ?>/assets/images/delete.png" class="property-info-img" />
                                                </a>
                                            </div>
                                    </div>
                                </div>
                        </div>
                    </div>
            <?php endforeach; ?> 
        <?php else: ?>
            <p>No Repairings found.</p>
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

<div id="deletePopup" class="popup-overlay" style="display: none;">
    <div class="popup-content">
        <h3>Are you sure you want to delete this item?</h3>
        <p>This action cannot be undone. Please confirm.</p>
        <div class="popup-buttons">
            <button id="confirmDelete" class="confirm-btn">Delete</button>
            <button onclick="closePopup()" class="cancel-btn">Cancel</button>
        </div>
    </div>
</div>


<script>
// Add this script to your services view file
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    const propertyGrid = document.querySelector('.property-listing-grid');
    const originalCards = Array.from(propertyGrid.querySelectorAll('#specific-page')); // Store original cards
    
    function performSearch() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        
        if (searchTerm === '') {
            // If search is empty, restore original cards
            propertyGrid.innerHTML = '';
            originalCards.forEach(card => propertyGrid.appendChild(card.cloneNode(true)));
            updatePagination();
            return;
        }
        
        // Filter cards
        const filteredCards = originalCards.filter(card => {
            const name = card.querySelector('h2')?.textContent.toLowerCase() || '';
            const description = card.querySelector('.limited-paragraph')?.textContent.toLowerCase() || '';
            const price = card.querySelector('.tag-teal')?.textContent.toLowerCase() || '';
            
            return name.includes(searchTerm) || 
                   description.includes(searchTerm) || 
                   price.includes(searchTerm);
        });
        
        // Update grid
        propertyGrid.innerHTML = '';
        if (filteredCards.length > 0) {
            filteredCards.forEach(card => propertyGrid.appendChild(card.cloneNode(true)));
            updatePagination();
        } else {
            // Show "no results" message
            propertyGrid.innerHTML = `
                <div style="width: 100%; text-align: center; padding: 40px 0;">
                    <div style="margin-bottom: 15px; opacity: 0.5;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </div>
                    <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No matching services found</h3>
                    <p style="font-size: 14px; color: #777; margin: 8px 0 0 0;">
                        No services match your search for "${searchTerm}".
                    </p>
                </div>
            `;
            // Hide pagination when no results
            document.querySelector('.pagination').style.display = 'none';
        }
    }
    
    function updatePagination() {
        const listings = document.querySelectorAll('.property-listing-grid #specific-page');
        const totalPages = Math.ceil(listings.length / listingsPerPage);
        document.querySelector('.pagination').style.display = totalPages > 1 ? 'flex' : 'none';
        currentPage = 1;
        showPage(currentPage);
    }
    
    // Event listeners
    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
});
</script>

<?php require_once 'agentFooter.view.php'; ?>