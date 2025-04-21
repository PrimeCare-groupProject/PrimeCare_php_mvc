<div class="property-container">
    <?php $images = explode(',', $property->property_images) ?>
    <?php 
        if(empty($images[0])) {
            $images[0] = 'property.png'; // Set a default image if none are available
        }   
    ?>
    <div class="image-slider">
        <div class="main-image">
            <img id="main-image" src="<?= get_img($images[0] , 'property') ?>" alt="Property Image">
        </div>
        <div class="thumbnails">
            <?php foreach ($images as $index => $image): ?>
                <!-- <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image ?>" alt="Thumbnail 1"> -->
                <img onclick="changeImage(this)" src="<?= get_img($image , 'property') ?>" alt="Thumbnail 1">
                <?php endforeach; ?>
        </div>
    </div>

    <div class="PL__property-details PL__more_padding">

        <div class='PL__contacts-section'>
            <div class='PL__contact'>
                <div class='rating-big'>
                    <span class='rating-score-big'>4.0</span>
                    <span class='stars-big'>★★★★☆</span>
                </div>
            </div>
            <div class='PL__pricing'>
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

        <h2>Owner Details</h2>
        <table>
            <tr>
                <td>Name:</td>
                <td class="PL__table_data"><?= $property->owner_name ?></td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td class="PL__table_data"><?= $property->owner_phone ?></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td class="PL__table_data"><?= $property->owner_email ?></td>
            </tr>
        </table>

    </div>
</div>