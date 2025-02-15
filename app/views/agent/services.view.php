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
                                    <div class="repair-actions">
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
    let currentPage = 1;
    const listingsPerPage = 6;
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

    let deleteServiceId = null;

function confirmDelete(serviceId) {
    deleteServiceId = serviceId; // Store the ID to delete later
    document.getElementById('deletePopup').style.display = 'flex'; // Show the popup
    document.body.classList.add('popup-active'); // Apply the active class
}

function closePopup() {
    deleteServiceId = null; // Reset the stored ID
    document.getElementById('deletePopup').style.display = 'none'; // Hide the popup
    document.body.classList.remove('popup-active'); // Remove the active class
}

document.getElementById('confirmDelete').addEventListener('click', function () {
    if (deleteServiceId !== null) {
        // Redirect to the delete route
        window.location.href = "<?= ROOT ?>/dashboard/services/delete/" + deleteServiceId;
    }
});

</script>

<?php require_once 'agentFooter.view.php'; ?>