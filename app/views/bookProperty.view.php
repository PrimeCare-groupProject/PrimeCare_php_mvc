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
                        <?php
                            $check_in = $_GET['check_in'] ?? '';
                            $check_out = $_GET['check_out'] ?? '';
                            $p_id = $_GET['p_id'] ?? '';
                        ?>
                        <a href="<?= ROOT ?>/propertyListing/showListingDetail/<?= $p_id ?>?check_in=<?= $check_in ?>&check_out=<?= $check_out ?>"><img src="<?= ROOT ?>/assets/images/backButton.png" class="back-button" alt="back"></a>
                        <p>Book Property</p>
                    </div>
                </div>

                <div class="content-section low-padding" id="content-section" style="margin-top:20px; padding: 20px; background-color: var(--white-color); border-radius: 12px;">
                    <form action="<?= ROOT ?>/propertyListing/bookProperty?p_id=<?= $p_id ?>&check_in=<?= $check_in ?>&check_out=<?= $check_out ?>" method="POST" class="booking-form" style="display: flex; flex-direction: column; gap: 15px; width: 100%">
                        <input type="hidden" name="property_id" value="<?= $p_id ?>">
                        <input type="hidden" id="is_available" name="is_available" value="0">

                        <div id="commercial-section" class="filter-row-instance" style="width: 90%;" >
                            <div class="form-group" style="display: flex; flex-direction: column; flex:1;">
                                <label for="check_in" style="font-weight: bold;">Check-in Date:</label>
                                <input type="date" id="check_in" name="check_in" value="<?= old_date('check_in', $check_in) ?>" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div class="form-group" style="display: flex; flex-direction: column; flex:1;">
                                <label for="check_out" style="font-weight: bold;">Check-out Date:</label>
                                <input type="date" id="check_out" name="check_out" value="<?= old_date('check_out', $check_out) ?>" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div>

                        <div class="filter-row-instance" style="width: 90%;">
                            <div class="form-group" style="display: flex; flex-direction: column; flex:1;">
                                <label for="booking_type" style="font-weight: bold;">Booking Type:</label>
                                <select id="booking_type" name="booking_type" onchange="toggleBookingType(this.value)" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                    <option value="commercial">Commercial</option>
                                    <!-- <option value="monthly">Monthly</option> -->
                                </select>
                            </div>

                            <div class="form-group" style="display: flex; flex-direction: column; flex:1;">
                                <label for="guests" style="font-weight: bold;">Number of Guests:</label>
                                <input type="number" id="guests" name="guests" min="1" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                            <div class="form-group" style="display: flex; flex-direction: column; flex:1;">
                                <label for="special_requests" style="font-weight: bold;">Special Requests:</label>
                                <textarea id="special_requests" name="special_requests" rows="4" placeholder="Enter any special requests" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
                            </div>
                        </div>
                        
<!--                         
                        <div id="monthly-section" style="display: none;">
                            <div class="form-group" style="display: flex; flex-direction: column;">
                                <label for="available_months" style="font-weight: bold;">Available Months:</label>
                                <select id="available_months" name="available_months" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                                    <?php foreach ($available_months ?? [] as $month) : ?>
                                        <option value="<?= $month ?>"><?= $month ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group" style="display: flex; flex-direction: column;">
                                <label for="security_deposit" style="font-weight: bold;">Security Deposit Amount:</label>
                                <input type="number" id="security_deposit" name="security_deposit" min="0" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                            </div>
                        </div> -->
                        <div id="btn-section" class="filter-row-instance" style="width: 90%;" >
                            <button type="submit" name="action" value="check_availability" 
                            style="width: 100px; padding: 5px; border-radius: 12px; background-color: white; color: black; border: 1px solid #ccc; cursor: pointer;">
                                Check Availability
                            </button>
                            <button type="submit" name="action" value="book_now" 
                            style="width: 100px; padding: 5px; border-radius: 12px; background-color: orange; color: white; border: none; cursor: pointer;" 
                            <?= $isAvailable ? '': 'disabled' ?> id="book-now-btn">
                                Book Now
                            </button>
                        </div>
                    </form>
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

    const originalStyles = {
        commercial: { display: document.getElementById('commercial-section').style.display },
        monthly: { display: document.getElementById('monthly-section').style.display }
    };

    function toggleBookingType(value) {
        const commercialSection = document.getElementById('commercial-section');
        const monthlySection = document.getElementById('monthly-section');

        if (value === 'commercial') {
            commercialSection.style.display = 'block';
            monthlySection.style.display = 'none';
        } else if (value === 'monthly') {
            commercialSection.style.display = 'none';
            monthlySection.style.display = 'block';
        } else {
            commercialSection.style.display = 'none';
            monthlySection.style.display = 'none';
        }
    }

    function changeImage(thumbnail) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = thumbnail.src;
    }

    
    document.querySelector('.booking-form').addEventListener('submit', function (event) {
        const checkIn = document.getElementById('check_in').value;
        const checkOut = document.getElementById('check_out').value;

        if (checkIn && checkOut) {
            const formAction = this.getAttribute('action');
            this.setAttribute('action', `${formAction}&check_in=${encodeURIComponent(checkIn)}&check_out=${encodeURIComponent(checkOut)}`);
        }
    });

</script>

</html>
                        
    