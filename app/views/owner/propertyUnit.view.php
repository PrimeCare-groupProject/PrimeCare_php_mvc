<?php require_once 'ownerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/property/propertyListing"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2><?= $property->name ?></h2>
                <p><span>Maintained By: </span>Agent's Name</p>
            </div>
        </div>
        <div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/bars.png" alt="Print" class="small-icons align-to-right" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/financialreportunit/<?= $property->property_id ?>'">
                <span class="tooltip-text">Financial Report</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/caution.png" alt="Problem" class="small-icons align-to-right" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/reportproblem/<?= $property->property_id ?>'">
                <span class="tooltip-text">Report a Problem</span>
            </div>
        </div>
    </div>
</div>

<div class="errors" style="display: <?= !empty($status) ? 'block' : 'none'; ?>; background-color: #b5f9a2;">
    <?php if (!empty($status)): ?>
        <p><?= $status;  ?></p>
    <?php endif; ?>
    <?php $_SESSION['status'] = '' ?>
</div>


<div class="property-unit-container">

    <div class="left-container-of-property-unit">
        <div class="slider">
            <div class="slides">
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
                <span class="input-label-small">State/Province:</span><span class="input-field-small"><?= $property->state_province ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Country:</span><span class="input-field-small"><?= $property->country ?></span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Year Built:</span><span class="input-field-small"><?= $property->year_built ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Monthly Rent:</span><span class="input-field-small"><?= $property->rental_price ?></span>
            </div>
        </div>

        <div class="input-group">
            <div class="input-group">
                <span class="input-label-small">Units:</span><span class="input-field-small"><?= $property->units ?></span>
            </div>
            <div class="input-group">
                <span class="input-label-small">Size(square feet):</span><span class="input-field-small"><?= $property->size_sqr_ft ?></span>
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
            <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/updateproperty/<?= $property->property_id ?>'">Edit Property</button>
            <!-- <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/updateproperty/<?= $property->property_id ?>'">Edit Property</button> -->
            <!--Should be fixed later when property table done-->
            <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/repairlisting?property_name=<?= urlencode($property->name) ?>&property_id=<?= urlencode($property->property_id) ?>'">Request Repair</button>
            <!-- <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/property/delete/<?= $property->property_id ?>'">Remove Property</button> -->
            <button class="secondary-btn" onclick="window.location.href='<?= ROOT ?>/property/dropProperty/<?= $property->property_id ?>'">Remove Property</button>
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