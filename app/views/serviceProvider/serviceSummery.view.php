<?php require_once 'serviceproviderHeader.view.php'; ?>

<div class="container">
    <!-- Status Message -->
    <?php if (!empty($_SESSION['status'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['status'] ?>
            <?php $_SESSION['status'] = ''; ?>
        </div>
    <?php endif; ?>

    <!-- Navigation Bar -->
    <div class="user_view-menu-bar">
        <div class="flex-bar-space-between-row">
            <div class="left-content">
                <a href="<?= ROOT ?>/serviceprovider/repairRequests">
                    <img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons">
                </a>
                <div>
                    <h2>Service Summary</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Slider -->
    <?php if (!empty($service_images)): ?>
        <div class="slider">
            <div class="slides">
                <?php foreach ($service_images as $image): ?>
                    <div class="slide">
                        <img src="<?= ROOT ?>/assets/images/uploads/service_logs/<?= htmlspecialchars($image) ?>" alt="Service Image">
                    </div>
                <?php endforeach; ?>
            </div>
            <button onclick="prevSlide()" class="slider-btn prev">&lt;</button>
            <button onclick="nextSlide()" class="slider-btn next">&gt;</button>
        </div>
    <?php endif; ?>

    <!-- Service Details -->
    <div class="property-details-section">
        <div class="detail-group">
            <label class="bolder-text">Service Summary</label>
            <p class="input-field-small more-padding">
                <?= htmlspecialchars($service_type ?? '') ?>
            </p>
        </div>

        <div class="detail-group">
            <label class="bolder-text">Property Information</label>
            <div class="input-group">
                <span class="input-label-small">Name:</span>
                <span class="input-field-small"><?= htmlspecialchars($property_name ?? '') ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">ID:</span>
                <span class="input-field-small"><?= htmlspecialchars($property_id ?? '') ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Status:</span>
                <span class="input-field-small"><?= htmlspecialchars($status ?? '') ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Earnings:</span>
                <span class="input-field-small">$<?= number_format($earnings ?? 0, 2) ?></span>
            </div>
        </div>
    </div>
</div>

<script>
let currentIndex = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

function showSlide(index) {
    if (index >= totalSlides) {
        currentIndex = 0;
    } else if (index < 0) {
        currentIndex = totalSlides - 1;
    } else {
        currentIndex = index;
    }
    
    const translateX = -currentIndex * 100;
    document.querySelector('.slides').style.transform = `translateX(${translateX}%)`;
}

function nextSlide() {
    showSlide(currentIndex + 1);
}

function prevSlide() {
    showSlide(currentIndex - 1);
}

// Auto-slide every 5 seconds
setInterval(nextSlide, 5000);
</script>

<style>
.slider {
    position: relative;
    width: 100%;
    overflow: hidden;
    margin: 20px 0;
}

.slides {
    display: flex;
    transition: transform 0.5s ease-in-out;
}

.slide {
    min-width: 100%;
}

.slide img {
    width: 100%;
    height: auto;
    object-fit: cover;
}

.slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
}

.prev { left: 10px; }
.next { right: 10px; }
</style>

<?php require_once 'serviceproviderFooter.view.php'; ?>