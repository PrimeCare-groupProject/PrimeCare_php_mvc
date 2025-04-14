<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/dashboard/search"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2><?= $property->name ?></h2>
                <p><span>Maintained By: </span>Agent's Name</p>
            </div>
        </div>
        <div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/caution.png" alt="Problem" class="small-icons align-to-right" onclick="window.location.href='<?= ROOT ?>/dashboard/reportProblem/<?= $property->property_id ?>'">
                <span class="tooltip-text">Report a Problem</span>
            </div>
        </div>
    </div>
</div>

<div class="property-unit-container">

    <div class="left-container-of-property-unit">
        <div class="slider">
            <?php $images = explode(',', $property->property_images) ?>
            <div class="slides">
                <?php foreach ($images as $index => $image): ?>
                    <div class="slide">
                        <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image ?>" alt="Slide 1">
                    </div>
                <?php endforeach; ?>
            </div>


            <button class="prev" onclick="prevSlide()">&#10094;</button>
            <button class="next" onclick="nextSlide()">&#10095;</button>
        </div>

        <div class="custom-reviews-container">
    <label class="custom-title">Reviews</label>
    <?php 
        $hasReview = false;
        if (!empty($reviews)): 
            foreach ($reviews as $review):
                if ($review->property_id == $property->property_id): 
                    $hasReview = true;
    ?>
        <div class="custom-review-card">
            <div class="custom-review-author">
                <label><?= $review->customer_name ?></label>
            </div>
            <div class="custom-review-rating">
                <?php
                    $stars = intval($review->rating);
                    for ($i = 1; $i <= 5; $i++) {
                        echo $i <= $stars ? '★' : '☆';
                    }
                ?>
                (<?= $review->rating ?>/5)
            </div>
            <div class="custom-review-text">
                <span><?= $review->description ?></span>
            </div>
        </div>
    <?php 
                endif;
            endforeach;
        endif;

        if (!$hasReview): 
    ?>
        <p class="custom-no-reviews">No Reviews found.</p>
    <?php endif; ?>
</div>

    </div>


    <div class="property-details-section">
        <div class="input-group">
            <span class="input-field-small-for-rent">Monthly Rent: LKR <?= $property->rental_price ?></span>
        </div>

        <label class="bolder-text">Description</label>
        <p class="input-field-small more-padding">
            <?= $property->description ?>
        </p>

        <label class="bolder-text">Property Information</label>

        <div class="input-group">
            <span class="input-label-small">Name:</span><span class="input-field-small"><?= $property->name ?></span>
        </div>

        <div class="input-group">
            <span class="input-label-small">Type:</span><span class="input-field-small"><?= $property->type ?></span>
        </div>

        <div class="input-group">
            <span class="input-label-small">Address:</span><span class="input-field-small"><?= $property->address ?></span>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Zip Code:</span><span class="input-field-small"><?= $property->zipcode ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">City:</span><span class="input-field-small"><?= $property->city ?></span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Province:</span><span class="input-field-small"><?= $property->state_province ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Country:</span><span class="input-field-small"><?= $property->country ?></span>
            </div>
        </div>

        <div class="input-group">
            <span class="input-label-small">Size:</span><span class="input-field-small"><?= $property->size_sqr_ft ?> square feet</span>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Units:</span><span class="input-field-small"><?= $property->units ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Year Built:</span><span class="input-field-small"><?= $property->year_built ?></span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Bedrooms:</span><span class="input-field-small"><?= $property->bedrooms ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Bathrooms:</span><span class="input-field-small"><?= $property->bathrooms ?></span>
            </div>
        </div>

        <span class="input-label-small">Floor Plan:</span>
        <p class="input-field-small more-padding"><?= $property->floor_plan ?></p>

        <div class="flex-buttons-space-between">
            <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/bookProperty/<?= $property->property_id ?>'">Book the property</button>
            <!--Should be fixed later when property table done-->
            <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/repairlisting?property_name=<?= urlencode($property->property_name ?? 'Oceanview Retreat') ?>&property_id=<?= urlencode($_GET['id'] ?? '') ?>'">Request Repair</button>
        </div>

        <div class="custom-review-form-wrapper">
        <h3 class="custom-review-form-title">Leave a Review</h3>

        <form method="POST" action="<?= ROOT ?>/Review/review" enctype="multipart/form-data">
            <input type="hidden" name="property_id" value="<?= $property->property_id ?>">

            <div class="custom-review-form-group">
                <label for="reviewer_name" class="custom-review-label">Your Name</label>
                <input type="text" id="reviewer_name" name="reviewer_name" class="custom-review-input" required>
            </div>

            <div class="custom-review-form-group">
                <label for="rating" class="custom-review-label">Rating</label>
                <select id="rating" name="rating" class="custom-review-select" required>
                    <option value="5">🌟🌟🌟🌟🌟 - Excellent</option>
                    <option value="4">🌟🌟🌟🌟 - Good</option>
                    <option value="3">🌟🌟🌟 - Average</option>
                    <option value="2">🌟🌟 - Fair</option>
                    <option value="1">🌟 - Poor</option>
                </select>
            </div>

            <div class="custom-review-form-group">
                <label for="review" class="custom-review-label">Your Review</label>
                <textarea id="review" name="review" rows="4" class="custom-review-textarea" required></textarea>
            </div>

            <button type="submit" class="custom-review-submit-btn">Submit Review</button>
        </form>
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

<?php require_once 'customerFooter.view.php'; ?>