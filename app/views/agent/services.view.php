<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/serviceManagement/'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
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


    let deleteServiceId = null;

    function confirmDelete(serviceId) {
        deleteServiceId = serviceId;
        document.getElementById('deletePopup').style.display = 'flex';
        document.body.classList.add('popup-active');
    }

    function closePopup() {
        deleteServiceId = null;
        document.getElementById('deletePopup').style.display = 'none';
        document.body.classList.remove('popup-active');
    }
  
document.addEventListener('DOMContentLoaded', function() {
    // Constants and variables
    const searchInput = document.querySelector('.search-input');
    const searchBtn = document.querySelector('.search-btn');
    const propertyGrid = document.querySelector('.property-listing-grid');
    const paginationContainer = document.querySelector('.pagination');
    const prevPageBtn = document.querySelector('.prev-page');
    const nextPageBtn = document.querySelector('.next-page');
    const originalCards = Array.from(propertyGrid.querySelectorAll('#specific-page'));
    const listingsPerPage = 9;
    let currentPage = 1;
    let filteredCards = [];

    // Initialize
    initPagination();

    function initPagination() {
        filteredCards = [...originalCards];
        updateDisplay();
        setupEventListeners();
    }

    function performSearch() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        
        if (searchTerm === '') {
            filteredCards = [...originalCards];
        } else {
            filteredCards = originalCards.filter(card => {
                const name = card.querySelector('h2')?.textContent.toLowerCase() || '';
                const description = card.querySelector('.limited-paragraph')?.textContent.toLowerCase() || '';
                const price = card.querySelector('.tag-teal')?.textContent.toLowerCase() || '';
                
                return name.includes(searchTerm) || 
                       description.includes(searchTerm) || 
                       price.includes(searchTerm);
            });
        }
        
        currentPage = 1;
        updateDisplay();
    }

    function updateDisplay() {
        // Clear and repopulate grid
        propertyGrid.innerHTML = '';
        
        if (filteredCards.length > 0) {
            filteredCards.forEach(card => propertyGrid.appendChild(card.cloneNode(true)));
        } else {
            showNoResultsMessage();
        }
        
        updatePaginationUI();
        showCurrentPage();
    }

    function showNoResultsMessage() {
        propertyGrid.innerHTML = `
            <div style="width: 100%; text-align: center; padding: 40px 0; grid-column: 1 / -1;">
                <div style="margin-bottom: 15px; opacity: 0.5;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No matching services found</h3>
                <p style="font-size: 14px; color: #777; margin: 8px 0 0 0;">
                    No services match your search.
                </p>
            </div>
        `;
    }

    function showCurrentPage() {
        const listings = propertyGrid.querySelectorAll('#specific-page');
        const startIdx = (currentPage - 1) * listingsPerPage;
        const endIdx = startIdx + listingsPerPage;
        
        listings.forEach((listing, index) => {
            listing.style.display = (index >= startIdx && index < endIdx) ? 'block' : 'none';
        });
        
        document.querySelector('.current-page').textContent = currentPage;
    }

    function updatePaginationUI() {
        const totalItems = filteredCards.length;
        const totalPages = Math.ceil(totalItems / listingsPerPage);
        
        paginationContainer.style.display = totalPages > 1 ? 'flex' : 'none';
        prevPageBtn.disabled = currentPage <= 1;
        nextPageBtn.disabled = currentPage >= totalPages;
    }

    function goToNextPage() {
        const totalPages = Math.ceil(filteredCards.length / listingsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            showCurrentPage();
            updatePaginationUI();
        }
    }

    function goToPrevPage() {
        if (currentPage > 1) {
            currentPage--;
            showCurrentPage();
            updatePaginationUI();
        }
    }

    function setupEventListeners() {
        // Remove existing listeners to prevent duplicates
        searchBtn.removeEventListener('click', performSearch);
        searchInput.removeEventListener('keyup', handleSearchKeyup);
        nextPageBtn.removeEventListener('click', goToNextPage);
        prevPageBtn.removeEventListener('click', goToPrevPage);
        
        // Add fresh listeners
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keyup', handleSearchKeyup);
        nextPageBtn.addEventListener('click', goToNextPage);
        prevPageBtn.addEventListener('click', goToPrevPage);
    }

    function handleSearchKeyup(e) {
        if (e.key === 'Enter') performSearch();
    }

    document.getElementById('confirmDelete').addEventListener('click', function() {
        if (deleteServiceId !== null) {
            window.location.href = "<?= ROOT ?>/dashboard/services/delete/" + deleteServiceId;
        }
    });
});

</script>

<?php require_once 'agentFooter.view.php'; ?>