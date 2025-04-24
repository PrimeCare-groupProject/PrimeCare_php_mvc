<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/dashboard/requestServiceOccupied"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
    <h2>Select Service for Request</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" id="service-search" class="search-input" placeholder="Search services...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
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
                <div class="property-card service-item" 
                     data-name="<?= htmlspecialchars(strtolower($service->name)) ?>" 
                     data-description="<?= htmlspecialchars(strtolower($service->description)) ?>">
                    <div class="property-image">
                        <?php 
                        $imgPath = ROOT . "/assets/images/repairimages/" . ($service->service_img ?? '');
                        $placeholderPath = ROOT . "/assets/images/service_placeholder.jpg";
                        ?>
                        <img src="<?= !empty($service->service_img) ? $imgPath : $placeholderPath ?>" 
                             alt="<?= htmlspecialchars($service->name) ?>" 
                             onerror="this.src='<?= $placeholderPath ?>'">
                        <div class="image-overlay"></div>
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <h3 class="service-title"><?= htmlspecialchars($service->name) ?></h3>
                            </div>
                        </div>
                        <div class="property-description-container">
                            <p class="property-description"><?= htmlspecialchars($service->description) ?></p>
                        </div>
                        <div class="service-cost-container">
                            <p class="service-cost">$<?= number_format($service->cost_per_hour, 2) ?> per hour</p>
                        </div>
                        <div class="property-actions">
                            <a href="<?= ROOT ?>/dashboard/serviceRequest?type=<?= urlencode($service->name) ?>&cost_per_hour=<?= $service->cost_per_hour ?>&property_id=<?= $property->property_id ?>&property_name=<?= urlencode($property->name) ?>" class="select-button">
                                <span class="button-text">Select</span>
                                <div class="button-glow"></div>
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

<style>
/* Selected Property Banner */
.selected-property-banner {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, #ffcc00 80%);
    color: #333;
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 15px rgba(255, 204, 0, 0.2);
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
    background: rgba(255, 255, 255, 0.3);
    padding: 4px 12px;
    border-radius: 20px;
}

.property-address {
    display: flex;
    align-items: center;
    font-size: 14px;
}

/* Property Listing Grid */
.property-listing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    padding: 20px 0;
}

/* Card Styling */
.property-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 204, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.property-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 204, 0, 0.2),
                0 0 15px 2px rgba(255, 204, 0, 0.15);
}

/* Image Styling */
.property-image {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.property-card:hover .property-image img {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(255,204,0,0.2) 100%);
    z-index: 1;
    opacity: 0.2;
    transition: opacity 0.3s ease;
}

.property-card:hover .image-overlay {
    opacity: 0.3;
}

/* Content Styling */
.property-details {
    padding: 20px;
}

.service-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
}

.property-description-container {
    height: 60px;
    overflow: hidden;
    margin-bottom: 15px;
}

.property-description {
    color: #64748b;
    line-height: 1.6;
    font-size: 14px;
}

.service-cost-container {
    margin-bottom: 15px;
}

.service-cost {
    font-weight: 700;
    color: #333;
    background-color: rgba(255, 204, 0, 0.2);
    display: inline-block;
    padding: 5px 15px;
    border-radius: 20px;
}

/* Button Styling */
.property-actions {
    display: flex;
    justify-content: center;
}

.select-button {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, #ffcc00, #ffb700);
    color: #222;
    border-radius: 50px;
    border: 1px solid rgba(255, 204, 0, 0.3);
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    width: 100%;
    box-shadow: 0 4px 15px rgba(255, 204, 0, 0.3),
                inset 0 0 5px rgba(255, 255, 255, 0.5);
    letter-spacing: 0.5px;
}

.select-button:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.3), transparent);
    border-radius: 50px 50px 0 0;
}

.select-button:hover {
    box-shadow: 0 6px 20px rgba(255, 204, 0, 0.5),
                inset 0 0 10px rgba(255, 255, 255, 0.8),
                0 0 15px rgba(255, 204, 0, 0.3);
    transform: translateY(-2px);
    background: linear-gradient(135deg, #ffcc00, #ffb700, #ffa500);
}

.button-text {
    z-index: 1;
    position: relative;
    letter-spacing: 0.5px;
    background: linear-gradient(to right, #444, #111);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    font-weight: 700;
}

.button-glow {
    position: absolute;
    width: 100px;
    height: 100px;
    background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.select-button:hover .button-glow {
    animation: glowing 1.5s infinite;
    opacity: 0.7;
}

/* Pagination */
.pagination {
    display: none;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 30px;
}

.prev-page, .next-page {
    background: white;
    border: 1px solid #ddd;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.prev-page:hover, .next-page:hover {
    background: #f9f9f9;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.prev-page:disabled, .next-page:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.current-page {
    font-weight: bold;
    font-size: 16px;
}

/* No services message */
.no-services-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    color: #666;
}

/* Search input */
.search-input {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 20px;
    width: 200px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #ffcc00;
    box-shadow: 0 0 10px rgba(255, 204, 0, 0.3);
    width: 220px;
}

/* Animations */
@keyframes glowing {
    0% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 0.7;
    }
    50% {
        transform: translate(-50%, -50%) scale(1.2);
        opacity: 0.3;
    }
    100% {
        transform: translate(-50%, -50%) scale(0.8);
        opacity: 0.7;
    }
}
</style>

<script>
    // Service search functionality
    document.getElementById('service-search').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const services = document.querySelectorAll('.service-item');
        
        services.forEach(service => {
            const name = service.dataset.name;
            const description = service.dataset.description;
            
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
    
    // Only initialize pagination if we have more than one page
    if (totalPages > 1) {
        document.querySelector('.pagination').style.display = 'flex';
    }

    function showPage(page) {
        // Hide all listings
        listings.forEach((listing, index) => {
            if (index >= (page-1) * listingsPerPage && index < page * listingsPerPage) {
                listing.style.display = 'block';
            } else {
                listing.style.display = 'none';
            }
        });

        // Update pagination display
        document.querySelector('.current-page').textContent = page;
        
        // Enable/disable pagination buttons
        document.querySelector('.prev-page').disabled = page === 1;
        document.querySelector('.next-page').disabled = page === totalPages;
    }

    document.querySelector('.next-page').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
            // Scroll to top of the listing
            document.querySelector('.listing-the-property').scrollIntoView({behavior: 'smooth'});
        }
    });

    document.querySelector('.prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
            // Scroll to top of the listing
            document.querySelector('.listing-the-property').scrollIntoView({behavior: 'smooth'});
        }
    });

    // Add glow effect to buttons
    document.querySelectorAll('.select-button').forEach(button => {
        // Create glowing effect on mousemove
        button.addEventListener('mousemove', function(e) {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const glow = button.querySelector('.button-glow');
            glow.style.left = `${x}px`;
            glow.style.top = `${y}px`;
        });
    });

    // Initial page load
    showPage(currentPage);
</script>

<?php require 'customerFooter.view.php' ?>