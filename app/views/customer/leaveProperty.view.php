<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/dashboard/occupiedproperties"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
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
       
    </div>

    <div class="property-details-section">
        <div class="input-group">
            <span class="input-field-small-for-rent">Monthly Rent: LKR <?= $property->rent_on_basis ?></span>
        </div>

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

        <div class="flex-buttons-space-between">
            <button class="primary-btn red-solid">Confirm Cancellation</button>
        </div>

    </div>
</div>

<div class="RP__container">
    <div class="RP_side-containers">
        <h2>Give a Feedback</h2>
        <img src="<?= ROOT ?>/assets/images/givefeedback.jpg" alt="Report about property">
    </div>
    <div class="RP_side-containers">
        <form action="" method="post" enctype="multipart/form-data">
            <div>
                <label for="review">Write your review</label>
                <textarea name="review" id="review" cols="30" rows="3" class="input-field" required></textarea>
            </div>
            <div>
                <label for="score">Review Score</label>
                <select name="score" id="score" class="input-field" required>
                    <option value="1">1 - Poor</option>
                    <option value="2">2 - Fair</option>
                    <option value="3">3 - Good</option>
                    <option value="4">4 - Very Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>
            <div>
                <label for="location">Thoughts about our service?</label>
                <input type="text" name="location" id="location" class="input-field" required>
            </div>
            <div class="to-flex-end">
                <button type="submit" class="primary-btn" style="align-self: flex-end;">Submit</button>
            </div>
        </form>
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