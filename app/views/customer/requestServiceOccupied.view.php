<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Select Property for Request</h2>
    <div class="gap"></div>
    <div class="PL__filter_container">
    </div>
</div>

<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (is_array($properties) && !empty($properties)): ?>
            <?php foreach ($properties as $property): ?>
                <!-- Make entire card clickable -->
                <div class="property-card futuristic-card" onclick="redirectToRepairListingOcc(<?= $property->property_id ?>)">
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
                            <!-- Enhanced select button with animated icon -->
                            <a href="<?= ROOT ?>/dashboard/repairListingOcc/<?= $property->property_id ?>" class="select-button" onclick="event.stopPropagation();">
                                <span class="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 18l6-6-6-6"></path>
                                    </svg>
                                </span>
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
    cursor: pointer;
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
    border-radius: 0px 16px 0px 16px;
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

/* Enhanced Button Styling */
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
    padding: 12px 24px;
    background: linear-gradient(135deg, #ffcc00, #ffb700);
    color: #333;
    border-radius: 50px;
    border: none;
    font-weight: 600;
    font-size: 15px;
    cursor: pointer;
    overflow: hidden;
    transition: all 0.3s ease;
    text-decoration: none;
    width: 100%;
    max-width: 200px;
    box-shadow: 0 4px 10px rgba(255, 204, 0, 0.3);
    gap: 10px;
}

.select-button .icon {
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.25);
    width: 24px;
    height: 24px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.select-button .icon svg {
    width: 12px;
    height: 12px;
    stroke: #333;
    transition: all 0.3s ease;
}

.select-button:hover {
    box-shadow: 0 5px 15px rgba(255, 204, 0, 0.4);
    transform: translateY(-2px);
    background: linear-gradient(135deg, #ffcc00, #ffb700);
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

/* Remove the glow effect */
.button-glow {
    display: none;
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

@keyframes bounceRight {
    0%, 100% { transform: translateX(0); }
    50% { transform: translateX(3px); }
}
</style>

<script>
    // Function to handle property selection and redirect
    function redirectToRepairListingOcc(propertyId) {
        window.location.href = '<?= ROOT ?>/dashboard/repairListingOcc/' + propertyId;
    }
    
    // Add glow effect to regular select buttons
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