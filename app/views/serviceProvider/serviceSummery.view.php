<?php require_once 'serviceproviderHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/dashboard/repairRequests"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <h2>Service Summary</h2>
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

<div class="property-unit-container">
    <div class="left-container-property-unit">
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
    </div>

    <div class="property-details-section">
        <form method="POST" action="">
        <div class="input-group">
                <span class="input-label">Service Type:</span>
                <span class="input-field"><?= htmlspecialchars($service_type) ?></span>
            </div>
            <div class="input-group">
                <span class="input-label">Property Name:</span>
                <span class="input-field"><?= htmlspecialchars($property_name) ?></span>
            </div>
            <div class="input-group">
                <span class="input-label">Status:</span>
                <span class="input-field"><?= htmlspecialchars($status) ?></span>
            </div>
            <div class="input-group">
                <span class="input-label">Earnings:</span>
                <span class="input-field">LKR <?= number_format($earnings, 2) ?></span>
            </div>
            <div class="input-group">
                <span class="input-label">Service Description:</span>
                <span class="input-field"><?= htmlspecialchars($service_provider_description) ?></span>
            </div>
        </form>
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
    
.slide img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the image covers the slide area without distortion */
}
.property-details-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.input-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding: 10px;
}

.input-label {
    flex: 0 0 30%; /* Ensures a fixed width for all labels */
    font-size: 1.1rem; /* Makes the label text larger */
    font-weight: bold; /* Makes the label text bold */
    color: #333; /* Adjust color for better contrast */
}

.input-field {
    flex: 1; /* Takes the remaining space */
    padding: 8px 12px;
    font-size: 1rem;
    color: #555;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 4px;
    word-wrap: break-word; /* Ensures long text wraps correctly */
}
.slider {
    position: relative;
    width: 100%;
    max-width: 800px; /* Set a maximum width for the slider */
    height: 450px; /* Adjust to match image height */
    margin: 20px auto; /* Center the slider on the page */
    overflow: hidden;
    border-radius: 10px; /* Optional: Adds rounded corners */
}

.slides {
    display: flex;
    transition: transform 0.5s ease-in-out;
    height: 100%;
}

.slide {
    min-width: 100%;
    height: 100%; /* Matches slider height */
}

.slide img {
    width: 100%;
    height: 100%; /* Ensure the image covers the slide area */
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
    z-index: 10;
}
.slider-btn:hover {
    background: rgba(0, 0, 0, 0.8); /* Darker background on hover */
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}
</style>

<?php require_once 'serviceproviderFooter.view.php'; ?>
