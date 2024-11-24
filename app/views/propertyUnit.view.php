<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/propertylisting.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>PrimeCare</title>
</head>

<body>
    <div class="PL__navigation-bar">
        <div class="PL__top-navigations">
            <ul>
                <li><a href="<?= ROOT ?>/home"><img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare" class="header-logo-png"></a></li>
                <li><?php
                    if (isset($_SESSION['user'])) {
                        echo "<button class='header__button' onClick=\"window.location.href = 'dashboard/profile'\">";
                        echo "<img src='" . get_img($_SESSION['user']->image_url) . "' alt='Profile' class='header_profile_picture'>";
                        echo "Profile";
                    } else {
                        echo "<button class='header__button' onClick=\"window.location.href = 'login'\">";
                        echo "Sign In | Log In";
                    }

                    ?></li>
            </ul>
        </div>
        <div class="PL_filter-section">
            <div class="PL__filter">
                <div class="PL_form_main-filters">
                    <div class="flex-bar2">
                        <a href="<?= ROOT ?>/propertyListing/showListing"><img src="<?= ROOT ?>/assets/images/backButton.png" class="back-button" alt="back"></a>
                        <p>OceanVilla Resort</p>
                    </div>
                </div>
                <div class="content-section low-padding" id="content-section">
                    <div class="property-container">
                        <!-- Left Section: Image Slider -->
                        <div class="image-slider">
                            <div class="main-image">
                                <img id="main-image" src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Property Image">
                            </div>
                            <div class="thumbnails">
                                <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Thumbnail 1">
                                <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/listing_alt2.jpg" alt="Thumbnail 2">
                                <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/booking1.png" alt="Thumbnail 3">
                                <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="Thumbnail 1">
                                <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/listing_alt2.jpg" alt="Thumbnail 2">
                                <img onclick="changeImage(this)" src="<?= ROOT ?>/assets/images/booking1.png" alt="Thumbnail 3">
                            </div>
                        </div>

                        <div class="PL__property-details">
                            <div class="PL__contacts-section">
                                <div class="PL__contact">
                                    <div class="rating-big">
                                        <span class="rating-score-big">4.0</span>
                                        <span class="stars-big">★★★★☆</span>
                                    </div>
                                </div>
                                <div class="PL__pricing">
                                    <span>225,000.00 LKR</span>
                                    <small>PER MONTH</small>
                                </div>
                            </div>
                            <h2>Description</h2>
                            <p>
                                Welcome to Oceanview Retreat, an exquisite beachfront property located in the vibrant city of Miami, Florida. Situated along the pristine shores of the Atlantic Ocean, this luxurious estate offers a truly unparalleled coastal living experience. With breathtaking panoramic views of the ocean and direct access to a private white sandy beach, Oceanview Retreat is a haven for relaxation and rejuvenation. Immerse yourself in the soothing sounds of the waves and indulge in the serenity of the surroundings.
                            </p>

                            <h2>Property Information</h2>
                            <table>
                                <tr>
                                    <td>Name:</td>
                                    <td>Oceanview Retreat</td>
                                </tr>
                                <tr>
                                    <td>Type:</td>
                                    <td>Residence</td>
                                </tr>
                                <tr>
                                    <td>Zip Code:</td>
                                    <td>11320</td>
                                </tr>
                                <tr>
                                    <td>City:</td>
                                    <td>Colombo</td>
                                </tr>
                                <tr>
                                    <td>State/Province:</td>
                                    <td>Florida</td>
                                </tr>
                                <tr>
                                    <td>Country:</td>
                                    <td>America</td>
                                </tr>
                                <tr>
                                    <td>Year Built:</td>
                                    <td>2015</td>
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
                                <tr>
                                    <td>Monthly Rent (LKR):</td>
                                    <td>20000</td>
                                </tr> -->
                                <tr>
                                    <td>Floor Plan:</td>
                                    <td>The house contains 7 units, 4 rooms, kitchen, washroom, and dining area</td>
                                </tr>
                            </table>

                            <h2>Overview</h2>
                            <div class="overview">
                                <div class="overview-grid">
                                    <div class="overview-item">
                                        <img src="<?= ROOT ?>/assets/images/bed.png" alt="Bed Icon">
                                        <span>3 Bedroom</span>
                                    </div>
                                    <div class="overview-item">
                                        <img src="<?= ROOT ?>/assets/images/bathroom.png" alt="Bathroom Icon">
                                        <span>7 Bathroom</span>
                                    </div>
                                    <div class="overview-item">
                                        <img src="<?= ROOT ?>/assets/images/floor.png" alt="Unit Icon">
                                        <span>7 Units</span>
                                    </div>
                                    <div class="overview-item">
                                        <img src="<?= ROOT ?>/assets/images/size.png" alt="Area Icon">
                                        <span>3,943 ft</span>
                                    </div>
                                    <div class="overview-item">
                                        <img src="<?= ROOT ?>/assets/images/furniture.png" alt="Furniture Icon">
                                        <span>All Furniture</span>
                                    </div>
                                    <div class="overview-item">
                                        <img src="<?= ROOT ?>/assets/images/garage.png" alt="Garage Icon">
                                        <span>1 Car Garage</span>
                                    </div>
                                </div>
                            </div>

                            <div class="agreement">
                                <input type="checkbox" id="agree">
                                <label for="agree">By Clicking, I Agree To Terms & Conditions.</label>
                            </div>

                            <button class="book-btn">Book Property</button>

                            <h2>Reviews</h2>
                            <div class="add-review-section">
                                <textarea placeholder="Write a review..."></textarea>
                                <div class="review-buttons">
                                    <button class="post-btn">Post</button>
                                    <button class="cancel-btn">Cancel</button>
                                </div>
                            </div>

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
</body>

</html>