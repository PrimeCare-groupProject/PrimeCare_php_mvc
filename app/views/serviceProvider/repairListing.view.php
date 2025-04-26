<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard'><img src="<?= ROOT ?>/assets/images/backButton.png" alt="back" class="navigate-icons"></a>
    <h2>Service Type Overview</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" id="serviceSearch" class="search-input" placeholder="Search Anything...">
            <button class="search-btn" onclick="searchServices()"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
    </div>
</div>

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (empty($services)): ?>
            <p class="no-services">No services available at the moment.</p>
        <?php else: ?>
            <?php foreach ($services as $service): ?>
                <div class="property-card" onclick="window.location.href='<?= ROOT ?>/serviceprovider/serviceOverview?service_id=<?= $service->service_id ?>'">
                    <div class="property-image">
                        <?php
                        // Debug what's actually in the service_img field
                        $debug_img = !empty($service->service_img) ? htmlspecialchars($service->service_img) : 'empty';
                        
                        if (!empty($service->service_img)) {
                            // Use the service_img field directly as it already contains the path
                            $imagePath = ROOT . "/" . $service->service_img;
                        } else {
                            $imagePath = ROOT . "/assets/images/service_placeholder.jpg";
                        }
                        ?>
                        <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($service->name) ?>">
                        
                        <!-- Temporary debug info
                        <div style="font-size: 10px; color: #888; position: absolute; bottom: 0; background: rgba(255,255,255,0.7); padding: 2px; width: 100%;">
                            Image: <?= $debug_img ?>
                        </div> -->
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
                        <div class="service-price">
                            <span>LKR <?= number_format($service->cost_per_hour, 2) ?> per hour</span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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
    const cards = document.querySelectorAll('.property-listing-grid .property-card');
    const totalPages = Math.ceil(cards.length / listingsPerPage);

    function showPage(page) {
        // Hide all listings
        cards.forEach((card, index) => {
            card.style.display = 'none';
        });

        // Show listings for the current page
        const start = (page - 1) * listingsPerPage;
        const end = start + listingsPerPage;

        for (let i = start; i < end && i < cards.length; i++) {
            cards[i].style.display = 'block';
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
    
    function searchServices() {
        const searchInput = document.getElementById('serviceSearch').value.toLowerCase();
        cards.forEach(card => {
            const serviceName = card.querySelector('h3').textContent.toLowerCase();
            const serviceDesc = card.querySelector('.property-description').textContent.toLowerCase();
            
            if (serviceName.includes(searchInput) || serviceDesc.includes(searchInput)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Initial page load
    showPage(currentPage);
</script>

<style>
    .service-price {
        margin-top: 10px;
        font-weight: bold;
        color: #FFC107;

    }
    
    .no-services {
        grid-column: span 3;
        text-align: center;
        padding: 30px;
        font-style: italic;
        color: #777;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .property-details{
        padding: 20px;
    }
</style>

<?php require 'serviceproviderFooter.view.php' ?>