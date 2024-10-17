<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/ownerdashboard/propertylisting"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Name of Property</h2>
                <p><span>Maintained By: </span>Agent's Name</p>
            </div>
        </div>
        <div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/bars.png" alt="Print" class="small-icons align-to-right">
                <span class="tooltip-text">Financial Report</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/caution.png" alt="Problem" class="small-icons align-to-right">
                <span class="tooltip-text">Report a Problem</span>
            </div>
        </div>
    </div>
</div>

<div class="property-unit-container">

    <div class="left-container-of-property-unit">
        <div class="slider">
            <div class="slides">

                <div class="slide">
                    <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Slide 1">
                </div>

                <div class="slide">
                    <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Slide 2">
                </div>

                <div class="slide">
                    <img src="<?= ROOT ?>/assets/images/listing_alt2.jpg" alt="Slide 3">
                </div>
            </div>


            <button class="prev" onclick="prevSlide()">&#10094;</button>
            <button class="next" onclick="nextSlide()">&#10095;</button>
        </div>


        <div class="reviews-section">
            <label class="bolder-text">Reviews</label>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <?php require __DIR__ . '/../components/reviewfiled1.php'; ?>
            <?php endfor; ?>
        </div>
    </div>


    <div class="property-details-section">
        <label class="bolder-text">Description</label>
        <p class="input-field-small more-padding">
            Welcome to Oceanview Retreat, an exquisite beachfront property located in the vibrant city of Miami, Florida. Situated along the pristine shores of the Atlantic Ocean, this luxurious estate offers a truly unparalleled coastal living experience. With breathtaking panoramic views of the ocean and direct access to a private white sandy beach, Oceanview Retreat is a haven for relaxation and rejuvenation. Immerse yourself in the soothing sounds of the waves and indulge in the serenity of the surroundings.
        </p>

        <label class="bolder-text">Property Information</label>

        <div class="input-group">
            <span class="input-label-small">Name:</span><span class="input-field-small">Own Property</span>
        </div>

        <div class="input-group">
            <span class="input-label-small">Type:</span><span class="input-field-small">Oceanview Retreat</span>
        </div>

        <div class="input-group">
            <span class="input-label-small">Address:</span><span class="input-field-small">Oceanview Retreat , Colombo , Sri lanka</span>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Zip Code:</span><span class="input-field-small">80140</span>
            </div>
            <div class="input-group">
                <span class="input-label-small">City:</span><span class="input-field-small">Colombo</span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">State/Province:</span><span class="input-field-small">Western</span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Country:</span><span class="input-field-small">Sri Lanka</span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Year Built:</span><span class="input-field-small">2015</span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Monthly Rent:</span><span class="input-field-small">LKR 20000</span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Units:</span><span class="input-field-small">4</span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Size(square feet):</span><span class="input-field-small">1000</span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Bedrooms:</span><span class="input-field-small">4</span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Bathrooms:</span><span class="input-field-small">2</span>
            </div>
        </div>

        <span class="input-label-small">Floor Plan:</span>
        <p class="input-field-small more-padding">
            Welcome to Oceanview Retreat, an exquisite beachfront property located in the vibrant city of Miami, Florida. Situated along the pristine shores of the Atlantic Ocean, this luxurious estate offers a truly unparalleled coastal living experience.
        </p>

        <div class="flex-buttons-space-between">
            <button class="secondary-btn">Edit Property</button>
            <button class="secondary-btn">Delete Property</button>
        </div>

    </div>

</div>



</div>


<script>
    let currentIndex = 0;

    function showSlide(index) {
        const slides = document.querySelector('.slides');
        const totalSlides = document.querySelectorAll('.slide').length;

        if (index >= totalSlides) {
            currentIndex = 0;
        } else if (index < 0) {
            currentIndex = totalSlides - 1;
        } else {
            currentIndex = index;
        }

        const translateX = -currentIndex * 100;
        slides.style.transform = `translateX(${translateX}%)`;
    }

    function nextSlide() {
        showSlide(currentIndex + 1);
    }

    function prevSlide() {
        showSlide(currentIndex - 1);
    }
</script>

<?php require_once 'ownerFooter.view.php'; ?>