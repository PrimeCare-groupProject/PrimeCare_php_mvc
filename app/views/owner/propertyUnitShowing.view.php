<?php require_once 'ownerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/dashboard/propertyListing"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2><?= $property->name ?></h2>
                <p><span>Maintained By: </span><?= $agent->fname . ' ' . $agent->lname ?></p>
            </div>
        </div>
        <div class="right-content">
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/engineering.png" alt="Print" class="small-icons align-to-right color_financial" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/repairlisting?property_name=<?= urlencode($property->name) ?>&property_id=<?= urlencode($property->property_id) ?>'">
                <span class="tooltip-text">Get Service</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/house-owner.png" alt="Print" class="small-icons align-to-right color_financial" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/financialreportunit/<?= $property->property_id ?>'">
                <span class="tooltip-text">Tenants</span>
            </div>
            <!-- Add Track Maintenance button here -->
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/maintenance.png" alt="Track" class="small-icons align-to-right color_info" onclick="window.location.href='<?= ROOT ?>/dashboard/trackOrder/<?= $property->property_id ?>'">
                <span class="tooltip-text">Track Maintenance</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/rating.png" alt="Print" class="small-icons align-to-right color_purple" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/review/<?= $property->property_id ?>'">
                <span class="tooltip-text">Reviews</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/financial.png" alt="Print" class="small-icons align-to-right color_financial" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/financialreportunit/<?= $property->property_id ?>'">
                <span class="tooltip-text">Financial Report</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/alert.png" alt="Problem" class="small-icons align-to-right color_alert" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/reportproblem/<?= $property->property_id ?>'">
                <span class="tooltip-text">Report a Problem</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/support.png" alt="contact" class="small-icons align-to-right color_contact" onclick="toggleContactPopup(event)">
                <span class="tooltip-text">Contact the Agent</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/edit_icon.png" alt="edit" class="small-icons align-to-right color_edit" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/updateproperty/<?= $property->property_id ?>'">
                <span class="tooltip-text">Edit Details</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/image.png" alt="edit" class="small-icons align-to-right color_edit" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/addImages/<?= $property->property_id ?>'">
                <span class="tooltip-text">Add Images</span>
            </div>
            <div class="tooltip-container">
                <img src="<?= ROOT ?>/assets/images/delete_black.png" alt="edit" class="small-icons align-to-right color_caution" onclick="window.location.href='<?= ROOT ?>/dashboard/propertylisting/deleteView/<?= $property->property_id ?>'">
                <span class="tooltip-text">Remove</span>
            </div>
        </div>
        <div id="contactPopup" class="PopupMessage__contact-popup PopupMessage__hidden">
            <div class="PL__owner_details_container">
                <img src="<?= get_img($agent->image_url) ?>" alt="Profile Picture" class="PL__agent-picture" id="profile-picture-preview">
                <div class="PL__popup_column_content">
                    <p><?= $agent->fname . ' ' . $agent->fname ?></p>
                    <p class="PL__light-color"><?= $agent->email ?></p>
                    <p class="PL__contact_button" id="agentContact" onclick="copyContactToClipboard()"><?= $agent->contact ?></p>
                </div>
            </div>
        </div>


        <script>
            function toggleContactPopup(event) {
                event.stopPropagation(); // Prevent click from reaching document
                const popup = document.getElementById('contactPopup');
                popup.classList.toggle('PopupMessage__hidden');
            }

            // Close popup if clicking outside
            document.addEventListener('click', function(e) {
                const popup = document.getElementById('contactPopup');
                if (!popup.classList.contains('PopupMessage__hidden')) {
                    popup.classList.add('PopupMessage__hidden');
                }
            });

            // Prevent closing when clicking inside the popup
            document.getElementById('contactPopup').addEventListener('click', function(e) {
                e.stopPropagation();
            });

            function copyContactToClipboard() {
                const contact = document.getElementById('agentContact').innerText;
                navigator.clipboard.writeText(contact).then(() => {
                    console.log("Phone number copied to clipboard!");
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                });
            }
        </script>


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

        <?php
        if ($property->purpose == 'Rent') {
            echo "
                <div class='PL__contacts-section'>
                    <div class='PL__contact'>
                        <div class='rating-big'>
                            <span class='rating-score-big'>4.0</span>
                            <span class='stars-big'>★★★★☆</span>
                        </div>
                    </div>
                    <div class='PL__pricing'>
                        <span> " . $property->rental_price . " LKR</span>
                        <small>PER MONTH</small>
                    </div>
                </div>
                ";
        } else {
            if ($property->status == 'Pending' && $property->advance_paid == 'Not_Paid') {
        ?>
                <div class='PL__contacts-section'>
                    <div class='PL__contact'>
                        <div class='rating-big'>
                            <button class='primary-btn' onclick="window.location.href='<?= ROOT ?>/dashboard/payAdvance/<?= $property->property_id ?>'">Pay Advance</button>
                        </div>
                    </div>
                    <div class='PL__pricing'>
                        <span><?= number_format(findAdvancePrice($property->rental_price), 2) ?> LKR</span>
                    </div>
                </div>
            <?php
            } elseif($property->rental_price > 0 && $property->advance_paid == 'Paid') {
            ?>
                <div class='PL__contacts-section'>
                    <div class='PL__contact'>
                        <div class='rating-big'>
                            <button class='primary-btn'>Make Payment</button>
                        </div>
                    </div>
                    <div class='PL__pricing'>
                        <span><?= $property->rental_price ?> LKR</span>
                    </div>
                </div>
        <?php
            } 
        }

        ?>


        <h2>Description</h2>
        <p>
            <?= $property->description ?>
        </p>

        <h2>Property Information</h2>
        <table>
            <tr>
                <td>Name:</td>
                <td class="PL__table_data"><?= $property->name ?></td>
            </tr>
            <tr>
                <td>Type:</td>
                <td class="PL__table_data"><?= $property->type ?></td>
            </tr>
            <tr>
                <td>Zip Code:</td>
                <td class="PL__table_data"><?= $property->zipcode ?></td>
            </tr>
            <tr>
                <td>City:</td>
                <td class="PL__table_data"><?= $property->city ?></td>
            </tr>
            <tr>
                <td>State/Province:</td>
                <td class="PL__table_data"><?= $property->state_province ?></td>
            </tr>
            <tr>
                <td>Country:</td>
                <td class="PL__table_data"><?= $property->country ?></td>
            </tr>
            <tr>
                <td>Address:</td>
                <td class="PL__table_data"><?= $property->address ?></td>
            </tr>
            <tr>
                <td>Purpose:</td>
                <td class="PL__table_data"><?= $property->purpose ?></td>
            </tr>

            <?php if ($property->purpose == 'Rent') { ?>
                <tr>
                    <td>Year Built:</td>
                    <td class="PL__table_data"><?= $property->year_built ?></td>
                </tr>
                <tr>
                    <td>Floor Plan:</td>
                    <td class="PL__table_data"><?= $property->floor_plan ?></td>
                </tr>
                <tr>
                    <td>Furniture Description:</td>
                    <td class="PL__table_data"><?= $property->furniture_description ?></td>
                </tr>
            <?php } ?>

        </table>

        <?php if ($property->purpose == 'Rent') { ?>

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

        <?php } ?>

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

        <!-- 
        <?php
        if ($property->purpose == 'Rent') {
            echo "<div class='agreement'>
            <input type='checkbox' id='agree'>
            <label for='agree'>By Clicking, I Agree To Terms & Conditions.</label>
        </div>

        <button class='book-btn'>Book Property</button>";
        }
        ?>


        <?php if ($property->purpose == 'Rent') { ?>

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

        <?php } ?> -->


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