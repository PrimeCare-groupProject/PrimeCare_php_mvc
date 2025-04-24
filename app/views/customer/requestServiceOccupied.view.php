<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Select Property for Request</h2>
    <div class="gap"></div>
    <div class="PL__filter_container">
        <!-- <div class="search-container">
            <div class="futuristic-search">
                <input type="text" id="propertySearch" class="search-input" placeholder="Search properties...">
                <div class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
            </div>
        </div> -->
    </div>
</div>

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (is_array($properties) && !empty($properties)): ?>
            <?php foreach ($properties as $property): ?>
                <div class="property-card futuristic-card">
                    <?php
                    $images = !empty($property->property_images) ? explode(',', $property->property_images) : [];
                    $firstImage = !empty($images) ? $images[0] : 'default.png';
                    ?>
                    <div class="property-image">
                        <div class="image-overlay"></div>
                        <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $firstImage ?>" alt="Property Image">
                        <div class="property-badge">Occupied</div>
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <h3 class="property-title"><?= $property->name ?? 'Unnamed Property' ?></h3>
                            </div>
                        </div>
                        <div class="property-meta">
                            <p class="property-description"><img src="<?= ROOT ?>/assets/images/location.png" class="property-info-img" /><?= $property->address ?? 'No address provided' ?></p>
                        </div>
                        <div class="property-description-container">
                            <p class="property-description">
                                <?= $property->description ?? 'No description available' ?>
                            </p>
                        </div>
                        <div class="property-actions">
                            <a href="<?= ROOT ?>/dashboard/createServiceRequest/<?= $property->property_id ?>" class="select-button">
                                <span class="button-text">Select</span>
                                <div class="button-glow"></div>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-properties-message">
                <div class="message-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <path d="M15 3v18"></path>
                        <path d="M9 9h6"></path>
                    </svg>
                </div>
                <h3>No occupied properties found</h3>
                <p>You currently don't have any occupied properties. You need to book a property before you can request services for it.</p>
                <a href="<?= ROOT ?>/dashboard/search" class="primary-btn">Browse Properties</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Layout */
.property-listing-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 25px;
    padding: 20px;
}

/* Card Styling */
.futuristic-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 204, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.futuristic-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(255, 204, 0, 0.2),
                0 0 15px 2px rgba(255, 204, 0, 0.15);
}

.futuristic-card:hover .image-overlay {
    opacity: 0.3;
}

.futuristic-card:hover .property-badge {
    background: yellow;
}

/* Image Styling */
.property-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.futuristic-card:hover .property-image img {
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

.property-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.7) 0%, #ffcc00 80%);
    color: #333;
    padding: 6px 18px 6px 12px;
    border-radius: 0px 16px 0px 16px ;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(0,0,0,0.2);

}

/* Content Styling */
.property-details {
    padding: 20px;
}

.property-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
}

.profile-details-items {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.property-meta {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid rgba(0,0,0,0.07);
}

.property-info-img {
    width: 16px;
    height: 16px;
    margin-right: 8px;
    vertical-align: middle;
}

.property-description-container {
    height: 60px;
    overflow: hidden;
    margin-bottom: 20px;
    position: relative;
}

.property-description {
    color: #64748b;
    line-height: 1.6;
    font-size: 14px;
}

/* Button Styling */
.property-actions {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.select-button {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 24px;
    background: #ffcc00;
    color: white;
    border-radius: 30px;
    border: none;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    width: 100%;
    box-shadow: 0 4px 10px rgba(255, 204, 0, 0.3);
}

.select-button:hover {
    box-shadow: 0 6px 15px rgba(255, 204, 0, 0.4);
    transform: translateY(-2px);
    background: #ffd633;
}

.button-text {
    z-index: 1;
    position: relative;
    letter-spacing: 0.5px;
    color: #333;
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

/* Search Box Styling */
.futuristic-search {
    position: relative;
    width: 100%;
}

.search-input {
    width: 250px;
    padding: 12px 20px;
    padding-right: 40px;
    border-radius: 30px;
    border: 1px solid rgba(255, 204, 0, 0.2);
    background-color: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
    font-size: 14px;
    box-shadow: 0 2px 8px rgba(255, 204, 0, 0.1);
}

.search-input:focus {
    outline: none;
    border-color: #ffcc00;
    box-shadow: 0 2px 12px rgba(255, 204, 0, 0.2);
    width: 280px;
}

.search-icon {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #ffcc00;
}

/* Empty State Styling */
.no-properties-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 18px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(10px);
}

.message-icon {
    color: #ffcc00;
    margin-bottom: 20px;
    opacity: 0.7;
}

.no-properties-message h3 {
    margin-bottom: 15px;
    color: #333;
    font-weight: 600;
}

.no-properties-message p {
    margin-bottom: 25px;
    color: #64748b;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
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
    // Enhanced search functionality
    document.getElementById('propertySearch').addEventListener('input', function() {
        let searchValue = this.value.toLowerCase();
        let properties = document.querySelectorAll('.property-card');
        
        properties.forEach(property => {
            let name = property.querySelector('.property-title').innerText.toLowerCase();
            let address = property.querySelector('.property-meta .property-description').innerText.toLowerCase();
            let description = property.querySelector('.property-description-container .property-description').innerText.toLowerCase();
            
            if (name.includes(searchValue) || address.includes(searchValue) || description.includes(searchValue)) {
                property.style.display = 'block';
            } else {
                property.style.display = 'none';
            }
        });
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
</script>

<?php require 'customerFooter.view.php' ?>