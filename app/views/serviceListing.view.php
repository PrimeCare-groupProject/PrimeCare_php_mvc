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
                <form action="<?= ROOT ?>/propertyListing" method="POST">
                    <div class="PL_form_main-filters">
                        <div class="flex-bar2">
                            <a href="<?= ROOT ?>/home"><img src="<?= ROOT ?>/assets/images/backButton.png" class="back-button" alt="back"></a>
                            <p>Services</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>





        <div class="content-section" id="content-section">
            <div class="listing-items">
                <?php for ($i = 0; $i < 5; $i++): ?>
                    <div class="PL_property-card-home">
                        <a href="<?= ROOT ?>/"><img src="<?= ROOT ?>/assets/images/pool.jpg" alt="property" class="property-card-image"></a>
                        <div class="content-section-of-card">
                            <div class="address-home">
                                Plumbing Services
                            </div>
                            <div class="name">
                                All kinds of plumbing services
                            </div>
                            <div class="price">
                                Rs. 500 /Hour
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script>
</body>

</html>