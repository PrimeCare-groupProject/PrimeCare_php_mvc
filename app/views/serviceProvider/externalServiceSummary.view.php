<?php require_once 'serviceproviderHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/serviceprovider/externalServices"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <h2>External Service Summary</h2>
        </div>
        <div class="status-badge <?= strtolower($service->status) ?>">
            <?= htmlspecialchars(ucfirst($service->status)) ?>
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
            <h3>Property Images</h3>
        </div>
        
        <div class="slider">
            <div class="slides">
                <?php if (!empty($propertyImages)): ?>
                    <?php foreach ($propertyImages as $index => $image): ?>
                        <div class="slide" id="slide-<?= $index ?>">
                            <img src="<?= ROOT ?>/assets/images/<?= htmlspecialchars($image) ?>" alt="Property Image <?= $index + 1 ?>"
                                 onerror="this.src='<?= ROOT ?>/assets/images/listing_alt.jpg'">
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="slide no-images">
                        <i class="fas fa-camera"></i>
                        <p>No images available for this property</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($propertyImages) && count($propertyImages) > 1): ?>
                <button onclick="prevSlide()" class="slider-tbtn prev"><i class="fas fa-chevron-left"></i></button>
                <button onclick="nextSlide()" class="slider-tbtn next"><i class="fas fa-chevron-right"></i></button>
                <div class="slider-dots">
                    <?php for($i = 0; $i < count($propertyImages); $i++): ?>
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
                    <span class="info-value"><?= htmlspecialchars($service->service_type) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Date</span>
                    <span class="info-value"><?= date('F j, Y', strtotime($service->date)) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Property Address</span>
                    <span class="info-value"><?= htmlspecialchars($service->property_address) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Total Hours</span>
                    <span class="info-value"><?= htmlspecialchars($service->total_hours ?? '0') ?> hours</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Customer</span>
                    <span class="info-value"><?= htmlspecialchars($customer->fname ?? '') . ' ' . htmlspecialchars($customer->lname ?? 'Not specified') ?></span>
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
                <!-- Usual Cost -->
                <div class="info-row">
                    <span class="info-label">Cost per Hour</span>
                    <span class="info-value">LKR <?= number_format($service->cost_per_hour ?? 0, 2) ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Usual Cost</span>
                    <span class="info-value">LKR <?= number_format($service->usual_cost ?? 0, 2) ?></span>
                </div>
                
                <!-- Additional Charges -->
                <?php if (!empty($service->additional_charges) && $service->additional_charges > 0): ?>
                <div class="info-row additional-charges">
                    <span class="info-label">Additional Charges</span>
                    <span class="info-value">LKR <?= number_format($service->additional_charges, 2) ?></span>
                </div>
                
                <div class="info-row reason">
                    <span class="info-label">Charges Reason</span>
                    <span class="info-value"><?= htmlspecialchars($service->additional_charges_reason ?? 'Not specified') ?></span>
                </div>
                <?php else: ?>
                <div class="info-row no-additional">
                    <span class="info-label">Additional Charges</span>
                    <span class="info-value">LKR 0.00</span>
                </div>
                <?php endif; ?>
                
                <!-- Total Earnings -->
                <div class="info-row total">
                    <span class="info-label">Total Earnings</span>
                    <span class="info-value highlight-total">LKR <?= number_format($service->total_cost ?? 0, 2) ?></span>
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
            <div class="info-row">
                <span class="info-label">Customer Description</span>
            </div>
            <div class="description">
                <?= nl2br(htmlspecialchars($service->property_description ?? 'No description provided.')) ?>
            </div>
            
            <div class="info-row" style="margin-top:20px;">
                <span class="info-label">Service Provider Report</span>
            </div>
            <div class="description">
                <?php if (!empty($service->service_provider_description)): ?>
                    <?= nl2br(htmlspecialchars($service->service_provider_description)) ?>
                <?php else: ?>
                    <p class="no-description">No service report provided.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="<?= ROOT ?>/serviceprovider/externalServices" class="tbtn back-tbtn">Back to List</a>
        <button class="tbtn print-tbtn" onclick="printSummary()">Print Summary</button>
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
    
    // Update active dot
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

// Initialize slider
if (totalSlides > 0) {
    showSlide(0);
    
    // Auto-slide every 5 seconds if there are multiple images
    if (totalSlides > 1) {
        setInterval(nextSlide, 5000);
    }
}

// Print functionality
function printSummary() {
    const originalContents = document.body.innerHTML;
    
    // Create a print-friendly version of the summary
    let printContent = `
        <div style="max-width: 800px; margin: 0 auto; font-family: Arial, sans-serif;">
            <h1 style="text-align: center; color: #2c3e50;">External Service Summary</h1>
            <div style="text-align: right; margin-bottom: 20px; color: #666;">
                <p>Date: ${new Date().toLocaleDateString()}</p>
                <p>Status: ${document.querySelector('.status-badge').textContent.trim()}</p>
            </div>
            
            <div style="margin-bottom: 30px;">
                <h2 style="color: #f1c40f; border-bottom: 2px solid #f1c40f; padding-bottom: 8px;">Service Details</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0; width: 40%;">Service Type:</td>
                        <td style="padding: 8px 0;">${document.querySelectorAll('.info-value')[0].textContent}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Date:</td>
                        <td style="padding: 8px 0;">${document.querySelectorAll('.info-value')[1].textContent}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Property Address:</td>
                        <td style="padding: 8px 0;">${document.querySelectorAll('.info-value')[2].textContent}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Total Hours:</td>
                        <td style="padding: 8px 0;">${document.querySelectorAll('.info-value')[3].textContent}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Customer:</td>
                        <td style="padding: 8px 0;">${document.querySelectorAll('.info-value')[4].textContent}</td>
                    </tr>
                </table>
            </div>
            
            <div style="margin-bottom: 30px;">
                <h2 style="color: #f1c40f; border-bottom: 2px solid #f1c40f; padding-bottom: 8px;">Financial Summary</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0; width: 40%;">Cost per Hour:</td>
                        <td style="padding: 8px 0;">${document.querySelectorAll('.info-value')[5].textContent}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Usual Cost:</td>
                        <td style="padding: 8px 0;">${document.querySelectorAll('.info-value')[6].textContent}</td>
                    </tr>
    `;
    
    // Check if additional charges exist
    const additionalChargesElement = document.querySelector('.additional-charges');
    if (additionalChargesElement) {
        printContent += `
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Additional Charges:</td>
                        <td style="padding: 8px 0;">${document.querySelector('.additional-charges .info-value').textContent}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Charges Reason:</td>
                        <td style="padding: 8px 0;">${document.querySelector('.reason .info-value').textContent}</td>
                    </tr>
        `;
    } else {
        printContent += `
                    <tr>
                        <td style="font-weight: bold; padding: 8px 0;">Additional Charges:</td>
                        <td style="padding: 8px 0;">LKR 0.00</td>
                    </tr>
        `;
    }
    
    // Add total
    printContent += `
                    <tr style="font-size: 1.2em;">
                        <td style="font-weight: bold; padding: 12px 0; border-top: 2px solid #ddd;">Total Earnings:</td>
                        <td style="font-weight: bold; padding: 12px 0; border-top: 2px solid #ddd;">${document.querySelector('.total .info-value').textContent}</td>
                    </tr>
                </table>
            </div>
            
            <div>
                <h2 style="color: #f1c40f; border-bottom: 2px solid #f1c40f; padding-bottom: 8px;">Service Description</h2>
                <p style="font-weight: bold; margin-bottom: 5px;">Customer Description:</p>
                <div style="margin-bottom: 20px; padding: 10px; background: #f9f9f9; border-radius: 4px;">
                    ${document.querySelectorAll('.description')[0].innerHTML}
                </div>
                <p style="font-weight: bold; margin-bottom: 5px;">Service Provider Report:</p>
                <div style="padding: 10px; background: #f9f9f9; border-radius: 4px;">
                    ${document.querySelectorAll('.description')[1].innerHTML}
                </div>
            </div>
            
            <div style="margin-top: 40px; border-top: 1px dashed #ddd; padding-top: 20px; text-align: center;">
                <p style="font-size: 0.9em; color: #777;">This document was generated on ${new Date().toLocaleString()}</p>
                <p style="font-size: 0.9em; color: #777;">PrimeCare External Services</p>
            </div>
        </div>
    `;
    
    // Replace page content with print-friendly version
    document.body.innerHTML = printContent;
    
    // Print the page
    window.print();
    
    // Restore original content
    document.body.innerHTML = originalContents;
    
    // Reinitialize slider and functionality
    if (totalSlides > 0) {
        showSlide(currentIndex);
    }
}
</script>

<style>
/* General styling */
.summary-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    margin-bottom: 15px;
}

.flex-bar-space-between-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.left-content {
    display: flex;
    align-items: center;
}

/* Status badge styling */
.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-right: 20px;
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

.paid {
    background-color: #d1ecf1;
    color: #0c5460;
}

.rejected {
    background-color: #f8d7da;
    color: #721c24;
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
    border-bottom: 1px solid #f0f0f0;
}

.info-row.total .info-label,
.info-row.total .info-value {
    font-weight: 700;
    font-size: 1.1em;
    color: #2c3e50;
}

.highlight-total {
    color: #2c3e50 !important;
    font-weight: 700;
    font-size: 1.2em;
}

.info-row.no-additional {
    color: #888;
    font-style: italic;
}

/* Description styling */
.description {
    line-height: 1.6;
    color: #444;
    white-space: pre-wrap;
    background: #f9f9f9;
    padding: 15px;
    border-radius: 4px;
    margin-top: 10px;
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
    background-color: #f0f0f0;
}

.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.slider-tbtn {
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

.slider-tbtn:hover {
    opacity: 1;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}

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

.dot.active {
    background-color: #f1c40f;
    box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.5);
    animation: dotGlow 2s infinite;
}

@keyframes dotGlow {
    0% { box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.3); }
    50% { box-shadow: 0 0 0 4px rgba(241, 196, 15, 0.5); }
    100% { box-shadow: 0 0 0 2px rgba(241, 196, 15, 0.3); }
}

.slide.no-images {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background-color: #f9f9f9;
    color: #999;
}

.slide.no-images i {
    font-size: 48px;
    margin-bottom: 15px;
    opacity: 0.5;
}

/* Action buttons */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 20px;
}

.tbtn {
    padding: 12px 25px;
    border-radius: 6px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
}

.back-tbtn {
    background: #f1c40f;
    color: #222;
}

.back-tbtn:hover {
    background: #f39c12;
    color: #000;
}

.print-tbtn {
    background: #3498db;
    color: white;
}

.print-tbtn:hover {
    background: #2980b9;
}

/* Error message styling */
.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px 15px;
    margin: 15px auto;
    border-radius: 5px;
    max-width: 1200px;
}

/* Print-specific styles */
@media print {
    .user_view-menu-bar,
    .action-buttons,
    .navigate-icons {
        display: none !important;
    }
    
    .summary-container {
        padding: 0;
        box-shadow: none;
    }
    
    .summary-section {
        page-break-inside: avoid;
        box-shadow: none;
        border: 1px solid #ddd;
        margin-bottom: 15px;
    }
}
</style>

<?php require_once 'serviceproviderFooter.view.php'; ?>