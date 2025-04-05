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

<div class="property-container">
    <!-- Left Section: Image Slider -->
    <?php $images = explode(',', $property->property_images) ?>
    <div class="image-slider">
        <div class="main-image">
            <img id="main-image" src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $images[0] ?>" alt="Property Image">
        </div>
        <div class="thumbnails">
            <?php foreach ($images as $index => $image): ?>
                <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image ?>" alt="Thumbnail 1">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="PL__property-details PL__more_padding">
        <div class="PL__contacts-section">
            <div class="PL__contact">
                <div class="rating-big">
                    <span class="rating-score-big">4.0</span>
                    <span class="stars-big">★★★★☆</span>
                </div>
            </div>
            <div class="PL__pricing">
                <span><?= $property->rental_price ?> LKR</span>
                <small>PER MONTH</small>
            </div>
        </div>
        <h2>Description</h2>
        <p>
            <?= $property->description ?>
        </p>

        <h2>Property Information</h2>
        <table>
            <tr>
                <td>Name:</td>
                <td><?= $property->name ?></td>
            </tr>
            <tr>
                <td>Type:</td>
                <td><?= $property->type ?></td>
            </tr>
            <tr>
                <td>Zip Code:</td>
                <td><?= $property->zipcode ?></td>
            </tr>
            <tr>
                <td>City:</td>
                <td><?= $property->city ?></td>
            </tr>
            <tr>
                <td>State/Province:</td>
                <td><?= $property->state_province ?></td>
            </tr>
            <tr>
                <td>Country:</td>
                <td><?= $property->country ?></td>
            </tr>
            <tr>
                <td>Address:</td>
                <td><?= $property->address ?></td>
            </tr>
            <tr>
                <td>Year Built:</td>
                <td><?= $property->year_built ?></td>
            </tr>
            <!-- <tr>
                                    <td>Units:</td>
                                    <td>4</td>
                                </tr>
                                <tr>
                                    <td>Bedrooms:</td>
                                    <td>2015</td>
                                </tr>
                                <tr>
                                    <td>Size (sq. ft):</td>
                                    <td>6000</td>
                                </tr> 
                            -->
            <tr>
                <td>Floor Plan:</td>
                <td><?= $property->floor_plan ?></td>
            </tr>
            <tr>
                <td>Furniture Description:</td>
                <td><?= $property->furniture_description ?></td>
            </tr>
        </table>

        <h2>Overview</h2>
        <div class="overview overview-more-width">
            <div class="overview-grid-four">
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/bed.png" alt="Bed Icon">
                    <span><?= $property->bedrooms ?> Bedroom</span>
                </div>
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/bathroom.png" alt="Bathroom Icon">
                    <span><?= $property->bathrooms ?> Bathroom</span>
                </div>
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/floor.png" alt="Unit Icon">
                    <span><?= $property->units ?> Units</span>
                </div>
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/size.png" alt="Area Icon">
                    <span><?= $property->size_sqr_ft ?> ft</span>
                </div>
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/furniture.png" alt="Furniture Icon">
                    <span><?= $property->furnished ?></span>
                </div>
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/garage.png" alt="Garage Icon">
                    <span><?= $property->parking; ?> <?= $property->type_of_parking ?></span>
                </div>
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/kitchen.png" alt="kitchen Icon">
                    <span><?= $property->kitchen ?> Kitchen</span>
                </div>
                <div class="PL__overview-item">
                    <img src="<?= ROOT ?>/assets/images/living-room.png" alt="living room Icon">
                    <span><?= $property->living_room ?> Living Room</span>
                </div>
            </div>
        </div>

        <?php if (!empty($property->utilities_included)) {
            $utilities = explode(',', $property->utilities_included);
            shuffle($utilities); // Randomize order
            $alignments = ['center', 'left', 'right'];

            echo "<h2>Utilities Included</h2>";

            // Split into chunks of 3
            $chunks = array_chunk($utilities, 3);

            if (count($utilities) < 3) {
            }

            foreach ($chunks as $chunk) {
                $alignment = $alignments[0];
                shuffle($alignments);

                // Map PHP alignment to CSS class
                $class = 'PL__align-' . $alignment;

                echo "<div class='PL__utilities-row $class'>";
                foreach ($chunk as $utility) {
                    echo "<div class='PL__utility-item'>" . htmlspecialchars(trim($utility)) . "</div>";
                }
                echo "</div>";
            }
        } else {
            echo "<h2>Utilities Included</h2>";
            echo "<p>No Utilities Included</p>";
        }
        ?>

        <?php if (!empty($property->additional_amenities)) {
            $utilities = explode(',', $property->additional_amenities);
            shuffle($utilities); // Randomize order
            $alignments = ['center', 'left', 'right'];

            echo "<h2>Additional Amenities</h2>";

            // Split into chunks of 3
            $chunks = array_chunk($utilities, 3);

            if (count($utilities) < 3) {
            }

            foreach ($chunks as $chunk) {
                $alignment = $alignments[0];
                shuffle($alignments);

                // Map PHP alignment to CSS class
                $class = 'PL__align-' . $alignment;

                echo "<div class='PL__utilities-row $class'>";
                foreach ($chunk as $utility) {
                    echo "<div class='PL__utility-item'>" . htmlspecialchars(trim($utility)) . "</div>";
                }
                echo "</div>";
            }
        }
        ?>

        <?php if (!empty($property->additional_utilities)) {
            echo "<h2>Additional Utilities</h2>";
            echo "<p>";
            echo $property->additional_utilities;
            echo "</p>";
        }
        ?>

        <?php if (!empty($property->security_features)) {
            $utilities = explode(',', $property->security_features);
            shuffle($utilities); // Randomize order
            $alignments = ['center', 'left', 'right'];

            echo "<h2>Security Features</h2>";

            // Split into chunks of 3
            $chunks = array_chunk($utilities, 3);

            if (count($utilities) < 3) {
            }

            foreach ($chunks as $chunk) {
                $alignment = $alignments[0];
                shuffle($alignments);

                // Map PHP alignment to CSS class
                $class = 'PL__align-' . $alignment;

                echo "<div class='PL__utilities-row $class'>";
                foreach ($chunk as $utility) {
                    echo "<div class='PL__utility-item'>" . htmlspecialchars(trim($utility)) . "</div>";
                }
                echo "</div>";
            }
        }
        ?>



        <!-- <h2>Agent Details</h2>
        <div class="PL__owner_details_container">
            
                <img src="<?= get_img($agent->image_url) ?>" alt="Profile Picture" class="PL__agent-picture" id="profile-picture-preview">
                <p><?= $agent->fname . ' ' . $agent->fname ?></p>
                <p><?= $agent->contact ?></p>
            
        </div> -->

        <?php if (!empty($property->special_instructions)) {
            $utilities = explode(',', $property->special_instructions);

            shuffle($utilities); // Randomize order
            $alignments = ['center', 'left', 'right'];

            echo "<h2>Special Instructions</h2>";

            // Split into chunks of 3
            $chunks = array_chunk($utilities, 2);

            foreach ($chunks as $chunk) {
                $alignment = $alignments[0];
                shuffle($alignments);

                // Map PHP alignment to CSS class
                $class = 'PL__align-' . $alignment;

                echo "<div class='PL__utilities-row $class'>";
                foreach ($chunk as $utility) {
                    $cleanText = htmlspecialchars(str_replace('_', ' ', trim($utility)));
                    echo "<div class='PL__utility-item'>" . $cleanText . "</div>";
                }
                echo "</div>";
            }
        }
        ?>

        <?php if (!empty($property->legal_details)) {
            $utilities = explode(',', $property->legal_details);

            shuffle($utilities); // Randomize order
            $alignments = ['center', 'left', 'right'];

            echo "<h2>Legal Details</h2>";

            // Split into chunks of 3
            $chunks = array_chunk($utilities, 2);

            foreach ($chunks as $chunk) {
                $alignment = $alignments[0];
                shuffle($alignments);

                // Map PHP alignment to CSS class
                $class = 'PL__align-' . $alignment;

                echo "<div class='PL__utilities-row $class'>";
                foreach ($chunk as $utility) {
                    $cleanText = htmlspecialchars(str_replace('_', ' ', trim($utility)));
                    echo "<div class='PL__utility-item'>" . $cleanText . "</div>";
                }
                echo "</div>";
            }
        }
        ?>





        <div class="agreement">
            <input type="checkbox" id="agree">
            <label for="agree">By Clicking, I Agree To Terms & Conditions.</label>
        </div>

        <button class="book-btn">Book Property</button>

        <h2>Reviews</h2>
        <div class="review101">
            <div class="reviews-section">
                <div class="review">
                    <div class="review-header">
                        <div class="user-info">
                            <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/673051d1f4182__nimna@gmail.com.jpg" alt="User Image" class="user-img" />
                            <div>
                                <h3 class="user-name">Alexander Rity</h3>
                                <p class="review-date">4 months ago</p>
                            </div>
                        </div>
                        <div class="rating">
                            <span class="rating-score">5.0</span>
                            <span class="stars">★★★★★</span>
                        </div>
                    </div>
                    <p class="review-text">
                        Easy booking, great value! Cozy rooms at a reasonable price in Sheffield's vibrant center.
                        Surprisingly quiet with nearby Traveller’s accommodations. Highly recommended!
                    </p>
                </div>

                <div class="review">
                    <div class="review-header">
                        <div class="user-info">
                            <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/673051d1f4182__nimna@gmail.com.jpg" alt="User Image" class="user-img" />
                            <div>
                                <h3 class="user-name">Emma Creight</h3>
                                <p class="review-date">4 months ago</p>
                            </div>
                        </div>
                        <div class="rating">
                            <span class="rating-score">4.0</span>
                            <span class="stars">★★★★☆</span>
                        </div>
                    </div>
                    <p class="review-text">
                        Effortless booking, unbeatable affordability! Small yet comfortable rooms in the heart of
                        Sheffield's nightlife hub. Surrounded by elegant housing, it's a peaceful gem. Thumbs up!
                    </p>
                </div>
                <a href="#" class="read-more">Read all reviews</a>
            </div>

        </div>
    </div>
</div>

<script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script>
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

<script>
    function changeImage(thumbnail) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = thumbnail.src;
    }
</script>

<?php require_once 'ownerFooter.view.php'; ?>