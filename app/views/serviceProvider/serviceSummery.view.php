<?php require_once 'serviceproviderHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/serviceprovider/repairRequests"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <h2>Service Summary</h2>
        </div>
        <div class="status-badge <?= strtolower($status) ?>">
            <?= htmlspecialchars($status) ?>
        </div>
    </div>
</div>

<?php if (isset($_SESSION['errors'])): ?>
    <div class="error-message">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <?= $error ?><br>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="summary-container">
    <!-- Image Slider Section -->
    <div class="summary-section images-section">
        <div class="section-header">
            <i class="fas fa-images"></i>
            <h3>Service Documentation</h3>
        </div>
        
        <div class="slider">
            <div class="slides">
                <?php if (!empty($service_images)): ?>
                    <?php foreach ($service_images as $index => $image): ?>
                        <div class="slide" id="slide-<?= $index ?>">
                            <img src="<?= ROOT ?>/assets/images/uploads/service_logs/<?= htmlspecialchars($image) ?>" alt="Service Image <?= $index + 1 ?>">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="slide no-images">
                        <i class="fas fa-camera"></i>
                        <p>No images available for this service</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($service_images) && count($service_images) > 1): ?>
                <button onclick="prevSlide()" class="slider-btn prev"><i class="fas fa-chevron-left"></i></button>
                <button onclick="nextSlide()" class="slider-btn next"><i class="fas fa-chevron-right"></i></button>
                <div class="slider-dots">
                    <?php for($i = 0; $i < count($service_images); $i++): ?>
                        <span class="dot <?= ($i === 0) ? 'active' : '' ?>" data-index="<?= $i ?>" onclick="showSlide(<?= $i ?>)"></span>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="summary-columns">
        <!-- Service Details Section -->
        <div class="summary-section details-section">
            <div class="section-header">
                <i class="fas fa-tools"></i>
                <h3>Service Details</h3>
            </div>
            
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Service Type</span>
                    <span class="info-value"><?= htmlspecialchars($service_type) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Date</span>
                    <span class="info-value"><?= htmlspecialchars($date ?? 'Not specified') ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Property</span>
                    <span class="info-value"><?= htmlspecialchars($property_name) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Property ID</span>
                    <span class="info-value"><?= htmlspecialchars($property_id ?? 'Not specified') ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Total Hours</span>
                    <span class="info-value"><?= htmlspecialchars($total_hours ?? 'Not specified') ?> hours</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Service Provider</span>
                    <span class="info-value"><?= htmlspecialchars($service_provider_name ?? $_SESSION['user']->fname . ' ' . $_SESSION['user']->lname) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Financial Summary Section -->
        <div class="summary-section financial-section">
            <div class="section-header">
                <i class="fas fa-file-invoice-dollar"></i>
                <h3>Financial Summary</h3>
            </div>
            
            <div class="info-card">
                <div class="info-row">
                    <span class="info-label">Hourly Rate</span>
                    <span class="info-value">LKR <?= number_format($cost_per_hour ?? 0, 2) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Labor Cost</span>
                    <span class="info-value">LKR <?= number_format(($total_hours ?? 0) * ($cost_per_hour ?? 0), 2) ?></span>
                </div>
                
                <?php if (!empty($additional_charges)): ?>
                <div class="info-row additional-charges">
                    <span class="info-label">Additional Charges</span>
                    <span class="info-value">LKR <?= number_format($additional_charges, 2) ?></span>
                </div>
                
                <div class="info-row reason">
                    <span class="info-label">Charges Reason</span>
                    <span class="info-value"><?= htmlspecialchars($additional_charges_reason ?? 'Not specified') ?></span>
                </div>
                <?php endif; ?>
                
                <div class="info-row total">
                    <span class="info-label">Total Earnings</span>
                    <span class="info-value">LKR <?= number_format($earnings, 2) ?></span>
                </div>

                <div class="info-row">
                    <span class="info-label">Usual Service Cost</span>
                    <span class="info-value">LKR <?= number_format($service_details->usual_cost ?? (($service_details->cost_per_hour ?? 0) * ($service_details->total_hours ?? 0)), 2) ?></span>
                </div>

                <?php if (!empty($service_details->additional_charges)): ?>
                <div class="info-row additional-charges">
                    <span class="info-label">Additional Charges</span>
                    <span class="info-value">LKR <?= number_format($service_details->additional_charges, 2) ?></span>
                </div>

                <div class="info-row reason">
                    <span class="info-label">Charges Reason</span>
                    <span class="info-value"><?= htmlspecialchars($service_details->additional_charges_reason ?? 'Not specified') ?></span>
                </div>
                <?php endif; ?>

                <div class="info-row total">
                    <span class="info-label">Total Cost</span>
                    <span class="info-value">LKR <?= number_format(
                        ($usual_cost ?? 0) + ($additional_charges ?? 0), 2
                    ) ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Description Section -->
    <div class="summary-section description-section">
        <div class="section-header">
            <i class="fas fa-clipboard-list"></i>
            <h3>Service Description</h3>
        </div>
        
        <div class="info-card">
            <div class="description">
                <?php if (!empty($service_provider_description)): ?>
                    <?= nl2br(htmlspecialchars($service_provider_description)) ?>
                <?php else: ?>
                    <p class="no-description">No description provided for this service.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Image slider functionality
let currentIndex = 0;
const slides = document.querySelectorAll('.slide');
const dots = document.querySelectorAll('.dot');
const totalSlides = slides.length;

function showSlide(index) {
    if (index >= totalSlides) {
        currentIndex = 0;
    } else if (index < 0) {
        currentIndex = totalSlides - 1;
    } else {
        currentIndex = index;
    }
    
    // Add smooth scrolling effect
    document.querySelector('.slides').style.transition = 'transform 0.5s ease-in-out';
    
    // Update the transform property of the slides container
    const translateX = -currentIndex * 100;
    document.querySelector('.slides').style.transform = `translateX(${translateX}%)`;
    
    // Simply toggle active class without animations
    document.querySelectorAll('.dot').forEach((dot, i) => {
        if (i === currentIndex) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function nextSlide() {
    showSlide(currentIndex + 1);
}

function prevSlide() {
    showSlide(currentIndex - 1);
}

// Initialize the slider
if (totalSlides > 0) {
    showSlide(0);
    
    // Auto-slide every 5 seconds if there are multiple images
    if (totalSlides > 1) {
        setInterval(nextSlide, 5000);
    }
}
</script>

<style>
/* General styling */
.summary-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Status badge styling */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.done {
    background-color: #d4edda;
    color: #155724;
}

.pending {
    background-color: #fff3cd;
    color: #856404;
}

.ongoing {
    background-color: #cce5ff;
    color: #004085;
}

/* Section styling */
.summary-section {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    overflow: hidden;
}

.section-header {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #f0f0f0;
    background-color: #f9f9f9;
}

.section-header i {
    font-size: 1.4em;
    margin-right: 12px;
    color: #f1c40f;
}

.section-header h3 {
    margin: 0;
    color: #2c3e50;
    font-size: 18px;
    font-weight: 600;
}

/* Layout for columns */
.summary-columns {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 768px) {
    .summary-columns {
        grid-template-columns: 1fr;
    }
}

/* Info card styling */
.info-card {
    padding: 20px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #555;
}

.info-value {
    color: #333;
}

/* Special styling for financial items */
.info-row.additional-charges {
    background-color: #fef9e7;
    padding: 10px;
    border-radius: 4px;
    margin-top: 5px;
}

.info-row.reason {
    margin-bottom: 10px;
    border-bottom: 2px dashed #f0f0f0;
    padding-bottom: 15px;
}

.info-row.total {
    margin-top: 10px;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.info-row.total .info-label,
.info-row.total .info-value {
    font-weight: 700;
    font-size: 1.2em;
    color: #f39c12;
}

/* Description styling */
.description {
    line-height: 1.6;
    color: #444;
    white-space: pre-wrap;
}

.no-description {
    color: #999;
    font-style: italic;
}

/* Enhanced slider styling */
.images-section {
    margin-bottom: 25px;
}

.slider {
    position: relative;
    width: 100%;
    height: 450px;
    overflow: hidden;
    border-radius: 0 0 10px 10px;
}

.slides {
    display: flex;
    transition: transform 0.5s ease-in-out;
    height: 100%;
}

.slide {
    min-width: 100%;
    height: 100%;
    position: relative;
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    opacity: 0.7;
    transition: opacity 0.3s;
}

.slider-btn:hover {
    opacity: 1;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}

/* Fixed dot navigation styling */
.slider-dots {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 8px;
    background-color: rgba(0, 0, 0, 0.3);
    padding: 8px 12px;
    border-radius: 20px;
    backdrop-filter: blur(2px);
    z-index: 10;
}

/* Reset dot styling to ensure consistent shape */
.dot {
    display: block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.6);
    cursor: pointer;
    transition: background-color 0.3s ease;
    border: none;
    padding: 0;
    margin: 0;
    box-sizing: border-box;
}

.dot:hover {
    background-color: rgba(255, 255, 255, 0.9);
}

/* Consolidated active dot styles */
.dot.active {
    background-color: #f1c40f;
    box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.5);
    width: 10px;
    height: 10px;
    transition: all 0.3s ease;
}

/* Simpler animation that doesn't affect size or shape */
@keyframes dotGlow {
    0% { box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.3); }
    50% { box-shadow: 0 0 0 4px rgba(241, 196, 15, 0.5); }
    100% { box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.3); }
}

.dot.active {
    animation: dotGlow 2s infinite;
}

/* Ensure dots are visible on any background */
.slider-dots:hover {
    background-color: rgba(0, 0, 0, 0.5);
}

/* No images placeholder */
.slide.no-images {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f9f9f9;
    color: #999;
}
</style>

<?php require_once 'serviceproviderFooter.view.php'; ?>
