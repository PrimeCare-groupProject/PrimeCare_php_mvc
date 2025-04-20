<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
    <h2>Repairs</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" id="service-search" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
    </div>
</div>

<div class="listing-the-property">
    <div class="property-listing-grid">
        <?php if(!empty($services)): ?>
            <?php foreach($services as $service): ?>
                <div class="property-card service-item"
                     onclick="window.location.href='<?= ROOT ?>/customer/requestServiceExternal?service_type=<?= urlencode($service->name) ?>&cost_per_hour=<?= $service->cost_per_hour ?>'">
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
    // Service search functionality
    document.getElementById('service-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const services = document.querySelectorAll('.service-item');
        
        services.forEach(service => {
            const name = service.querySelector('h3').textContent.toLowerCase();
            const description = service.querySelector('.property-description').textContent.toLowerCase();
            
            if (name.includes(searchTerm) || description.includes(searchTerm)) {
                service.style.display = 'block';
            } else {
                service.style.display = 'none';
            }
        });

        // Reset pagination after search
        currentPage = 1;
        showPage(currentPage);
    });

    // Pagination functionality
    const listingsPerPage = 9;
    const listings = document.querySelectorAll('.property-listing-grid .property-card');
    const totalPages = Math.ceil(listings.length / listingsPerPage);
    let currentPage = 1;
    
    if (totalPages > 1) {
        document.querySelector('.pagination').style.display = 'flex';
    } else {
        document.querySelector('.pagination').style.display = 'none';
    }

    function showPage(page) {
        listings.forEach((listing, index) => {
            if (index >= (page-1) * listingsPerPage && index < page * listingsPerPage) {
                listing.style.display = 'block';
            } else {
                listing.style.display = 'none';
            }
        });

        document.querySelector('.current-page').textContent = page;
        document.querySelector('.prev-page').disabled = page === 1;
        document.querySelector('.next-page').disabled = page === totalPages;
    }

    document.querySelector('.next-page').addEventListener('click', () => {
        if (currentPage < totalPages) {
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

    showPage(currentPage);
</script>

<?php require 'customerFooter.view.php' ?>