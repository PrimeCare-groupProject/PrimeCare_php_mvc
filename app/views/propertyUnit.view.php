<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/propertylisting.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/loader.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/flash_messages.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>PrimeCare</title>
</head>

<body>
    <?php
    if (isset($_SESSION['flash'])) {
        flash_message();
    }

    $query_string = '';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $query_string = $_SERVER['QUERY_STRING'];
    }
    ?>
    <div class="PL__navigation-bar">
        <div class="PL__top-navigations">
            <ul>
                <li><a href="<?= ROOT ?>/home"><img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare" class="header-logo-png"></a></li>
                <li>
                    <?php if (isset($_SESSION['user'])) : ?>
                        <button class="header__button" onclick="window.location.href = '<?= ROOT ?>/dashboard/profile'">
                            <img src="<?= get_img($_SESSION['user']->image_url) ?>" alt="Profile" class="header_profile_picture">
                            Profile
                        </button>
                    <?php else : ?>
                        <button class="header__button" onclick="window.location.href = '<?= ROOT ?>/login'">
                            Sign In | Log In
                        </button>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
        <div class="PL_filter-section">
            <div class="PL__filter">
                <div class="PL_form_main-filters">
                    <div class="flex-bar2">
                        <a href="<?= ROOT ?>/propertyListing/showListing"><img src="<?= ROOT ?>/assets/images/backButton.png" class="back-button" alt="back"></a>
                        <p><?= $property->name ?></p>
                    </div>
                </div>

                <div class="content-section low-padding" id="content-section">
                    <div class="property-container">
                        <!-- Left Section: Image Slider -->
                        <?php $images = explode(',', $property->property_images) ?>

                        <div class="image-slider">
                            <div class="main-image">
                                <img id="main-image" src="<?= get_img($images[0], 'property') ?>" alt="Property Image">
                            </div>

                            <div class="thumbnails">
                                <?php foreach ($images as $index => $image): ?>
                                    <img onclick="changeImage(this)" src="<?= get_img($image, 'property') ?>" alt="Thumbnail 1">
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="PL__property-details">
                            <div class="PL__contacts-section">
                                <div class="PL__contact">
                                    <div class="rating-big">
                                        <?php $this_Ratings = getPropertyRatings($property->property_id) ?>
                                        <span class="rating-score"><?= $this_Ratings ?></span>
                                        <span class="stars">
                                            <?php
                                            $rating = $this_Ratings; // Example: 3.5
                                            $fullStars = floor($rating);
                                            $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                            $emptyStars = 5 - $fullStars - $halfStar;

                                            for ($i = 0; $i < $fullStars; $i++) {
                                                echo '<i class="fas fa-star"></i>'; // Full star
                                            }
                                            if ($halfStar) {
                                                echo '<i class="fas fa-star-half-alt"></i>'; // Half star
                                            }
                                            for ($i = 0; $i < $emptyStars; $i++) {
                                                echo '<i class="far fa-star"></i>'; // Empty star
                                            }

                                            ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="PL__pricing">
                                    <span><?= $property->rental_price ?> LKR</span>
                                    <small><?= strtoupper($property->rental_period) ?></small>
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
                                <tr>
                                    <td>Floor Plan:</td>
                                    <td><?= $property->floor_plan ?></td>
                                </tr>
                            </table>

                            <h2>Overview</h2>
                            <div class="overview" style="width: auto;">
                                <div class="overview-grid">
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
                                        <span><?= $property->parking ?></span>
                                    </div>
                                </div>
                            </div>

                            <h2 style="margin-top:20px;">Booking Summary</h2>
                            <div class="content-section low-padding" id="content-section" style="height:fit-content; margin-top: -20px; padding: 20px; background-color: var(--white-color); border-radius: 12px;">
                                <div class="booking-summary" style="width: 100%; margin-bottom: 20px; padding: 15px; background: #f8f8f8; border-radius: 8px;">
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="font-weight: bold; padding: 8px 10px;">Check-in:</td>
                                            <td><?= date('M d, Y', strtotime($bookingSummary['check_in'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; padding: 8px 10px;">Check-out:</td>
                                            <td><?= date('M d, Y', strtotime($bookingSummary['check_out'])) ?></td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight: bold; padding: 8px 10px;">Number of Days:</td>
                                            <td><?= $bookingSummary['days'] ?> days</td>
                                        </tr>
                                        <?php if ($property->rental_period == 'Monthly'): ?>
                                            <tr>
                                                <td style="font-weight: bold; padding: 8px 10px;">Rental Period:</td>
                                                <td>Monthly (<?= $bookingSummary['months'] ?> months)</td>
                                            </tr>
                                        <?php endif; ?>
                                        <tr style="border-top: 1px solid #ddd;">
                                            <td style="font-weight: bold; padding: 12px 10px;">Total Price:</td>
                                            <td style="font-weight: bold; color: #FF6B00;">LKR <?= number_format($bookingSummary['total_price'], 2) ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <form action="<?= ROOT ?>/propertyListing/showListingDetail/<?= esc($property->property_id) ?>" method="POST" class="booking-form" style="display: flex; flex-direction: column; gap: 15px; width: 100%">
                                    <input type="hidden" name="action" value="update_booking">
                                    <input type="hidden" name="p_id" value="<?= esc($property->property_id) ?>">
                                    <input type="hidden" name="rental_period" value="<?= esc($_GET['rental_period'] ?? $property->rental_period) ?>">
                                    <input type="hidden" name="period_duration" value="<?= esc($_GET['period_duration'] ?? '') ?>">
                                    <input type="hidden" name="query_string" value="<?= esc($query_string) ?>">
                                    <div class="filter-row-instance" style="width: 100%; display: flex; gap: 15px;">
                                        <div class="form-group" style="display: flex; flex-direction: column; flex:1;">
                                            <label for="check_in" style="font-weight: bold;">Check-in Date:</label>
                                            <input type="date" id="check_in" name="check_in" value="<?= $bookingSummary['check_in'] ?>" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                        </div>
                                        <div class="form-group" style="display: flex; flex-direction: column; flex:1;">
                                            <label for="check_out" style="font-weight: bold;">Check-out Date:</label>
                                            <input type="date" id="check_out" name="check_out" value="<?= $bookingSummary['check_out'] ?>" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                        </div>
                                    </div>
                                    <button type="submit" style="width: 200px; padding: 10px; border-radius: 12px; background-color: orange; color: white; border: none; cursor: pointer; align-self: center; margin-top: 10px;">
                                        Update Booking Details
                                    </button>
                                </form>
                            </div>

                            <div class="agreement">
                                <input type="checkbox" id="agree" onchange="toggleBookButton()">
                                <label for="agree">By Clicking, I Agree To
                                    <a href="<?= ROOT ?>/termsAndConditions">
                                        Terms & Conditions.
                                    </a>
                                </label>
                            </div>

                            <form action="<?= ROOT ?>/propertyListing/bookProperty" method="POST">
                                <input type="hidden" name="p_id" value="<?= esc($property->property_id) ?>">
                                <input type="hidden" name="check_in" value="<?= $bookingSummary['check_in'] ?>">
                                <input type="hidden" name="check_out" value="<?= $bookingSummary['check_out'] ?>">
                                <input type="hidden" name="rental_period" value="<?= esc($property->rental_period) ?>">
                                <input type="hidden" name="period_duration" value="<?= $bookingSummary['months'] ?? '' ?>">

                                <?php if (!empty($currentBookingStatus)): ?>
                                    <div class="booking-status-info" style="margin-bottom:10px;">
                                        <strong>Current Booking Status:</strong>
                                        <span><?= esc($currentBookingStatus) ?></span>
                                    </div>
                                    <button type="submit" class="book-btn" id="book-btn" style="width: 100%;" disabled>
                                        Booking Already Requested (<?= esc($currentBookingStatus) ?>)
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="book-btn" id="book-btn" style="width: 100%;" disabled>
                                        Please check the box to proceed payment.
                                    </button>
                                <?php endif; ?>
                            </form>


                            <?php if ($reviews): ?>
                                <h2>Reviews</h2>
                                <div class="review101">
                                    <div class="reviews-section">
                                        <?php foreach ($reviews as $review): ?>
                                            <?php
                                            $userDetails = getUserDetails($review->person_id);
                                            ?>
                                            <div class="review">
                                                <div class="review-header">
                                                    <div class="user-info">
                                                        <img src="<?= getImageByUserID($review->person_id) ?>" alt="User Image" class="user-img" />
                                                        <div>
                                                            <h3 class="user-name"><?= $userDetails->fname . ' ' . $userDetails->lname ?></h3>
                                                            <p class="review-date"><?= covertTimeToReadableForm($review->created_at) ?></p>
                                                        </div>
                                                    </div>
                                                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

                                                    <div class="rating">
                                                        <span class="rating-score"><?= $review->rating ?></span>
                                                        <span class="stars">
                                                            <?php
                                                            $rating = $review->rating; // Example: 3.5
                                                            $fullStars = floor($rating);
                                                            $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                                            $emptyStars = 5 - $fullStars - $halfStar;

                                                            for ($i = 0; $i < $fullStars; $i++) {
                                                                echo '<i class="fas fa-star"></i>'; // Full star
                                                            }
                                                            if ($halfStar) {
                                                                echo '<i class="fas fa-star-half-alt"></i>'; // Half star
                                                            }
                                                            for ($i = 0; $i < $emptyStars; $i++) {
                                                                echo '<i class="far fa-star"></i>'; // Empty star
                                                            }

                                                            ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="review-text">
                                                    <?= $review->message ?>
                                                </p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>

                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script>
<script src="<?= ROOT ?>/assets/js/loader.js"></script>
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

    function changeImage(thumbnail) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = thumbnail.src;
    }

    function toggleBookButton() {
        const agreeCheckbox = document.getElementById('agree');
        const bookButton = document.getElementById('book-btn');
        <?php if (!empty($currentBookingStatus)): ?>
            bookButton.disabled = true;
            bookButton.textContent = 'Booking Already Requested (<?= esc($currentBookingStatus) ?>)';
        <?php else: ?>
            bookButton.disabled = !agreeCheckbox.checked;
            bookButton.textContent = agreeCheckbox.checked ? 'Book Property' : 'Please check the box to proceed payment.';
        <?php endif; ?>
    }
</script>

<script>

</script>

</html>