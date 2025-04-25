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
    <!-- <form disabled> -->
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

            <form action="<?= ROOT ?>/propertyListing/showListing" method="POST">
            <!-- Replace the nested form with a div -->
            <div class="PL_filter-section" style="width: 80vw;">
                <div class="PL__filter">
                    <div class="PL_form_main-filters">
                        <div class="flex-bar" style="justify-content: end;">
                            <img src="<?= ROOT ?>/assets/images/setting.png" alt="see" id="toggleFilters" class="small-icons" style="filter: invert(1); margin-left: 5px; display:none;" onclick="toggleFilters()">
                            <div class="search-container">
                                <input type="text" name="searchTerm" id="searchTerm" class="search-input" placeholder="Search Anything..." value="<?= old_value('searchTerm') ?>">
                                <button type="submit" class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Property Filter Panel with Inline Styles -->
            <div style="margin-top:0px; display:flex; transition:all 0.3s; position:fixed; top:0; left:0; width:100%; height:100%; z-index:50; pointer-events:none;">
                <!-- Filter Panel -->
                <div id="filterSidebar" style="width:20%; padding:15px; background:#f5f5f5; background-color:#e5e7eb; border-right:1px solid #ddd; height:100vh; overflow-y:auto; box-sizing:border-box; transition:all 0.3s; margin-left:-20%; pointer-events:auto;">
                    <h2 style="margin-top:0;">Property Filters</h2>
                    
                    <!-- Sort By -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Sort By</h3>
                        <select id="propSortBySelect" name="sort_by" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value="">-- Select --</option>
                            <option value="price-asc"<?= old_select('sort_by', 'price-asc') ?>>Price Low to High</option>
                            <option value="price-desc"<?= old_select('sort_by', 'price-desc') ?>>Price High to Low</option>
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Availability</h3>
                        <div>
                            <label style="display:block; margin-bottom:5px;">Type</label>
                            <div style="display:flex; gap:10px;">
                                <select id="propRentalPeriodSelect" name="rental_period" onchange="togglePeriodDuration(this.value)" style="flex:1; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                                    <option value="" selected<?= old_select('rental_period', '') ?>>Any Period</option>
                                    <option value="Monthly"<?= old_select('rental_period', 'Monthly') ?> selected>Monthly</option>
                                    <option value="Daily"<?= old_select('rental_period', 'Daily') ?>  >Daily</option>
                                </select>
                                <input type="number" id="periodDuration" name="period_duration" placeholder="No. of .." min="1" oninput="updateCheckoutDate()" style="width:100%; flex:1; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px; " value="<?= old_value('period_duration') ?>">
                            </div>
                        </div>
                        
                        <div>
                            <label style="display:block; margin-bottom:5px;">Check-in</label>
                            <input type="date" id="propCheckInDate" name="check_in" style="width:100%; padding:8px; margin-bottom:10px; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_date('check_in') ?>">
                        </div>
                        <div>
                            <label style="display:block; margin-bottom:5px;">Check-out</label>
                            <input type="date" id="propCheckOutDate" name="check_out" style="width:100%; padding:8px; margin:0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_date('check_out') ?>">
                        </div>
                    </div>

                    <!-- Property Type -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Property Type</h3>
                        <select id="propTypeSelect" name="property_type" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value=""<?= old_select('property_type', '') ?>>Any Type</option>
                            <option value="House"<?= old_select('property_type', 'House') ?>>House</option>
                            <option value="Apartment"<?= old_select('property_type', 'Apartment') ?>>Apartment</option>
                            <option value="Villa"<?= old_select('property_type', 'Villa') ?>>Villa</option>
                            <option value="Studio"<?= old_select('property_type', 'Studio') ?>>Studio</option>
                            <option value="Farmhouse"<?= old_select('property_type', 'Farmhouse') ?>>Farmhouse</option>
                        </select>
                    </div>

                    
                    <!-- Price Range -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Price Range</h3>
                        <div>
                            <input type="number" id="propMinPriceInput" name="min_price" placeholder="Min Price Per Unit" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_value('min_price') ?>">
                        </div>
                        <div>
                            <input type="number" id="propMaxPriceInput" name="max_price" placeholder="Max Price Per Unit" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_value('max_price') ?>">
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Location</h3>
                        <select id="propProvinceSelect" name="province" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value=""<?= old_select('province', '') ?>>-- Select Province --</option>
                            <!-- Provinces will be populated via JavaScript -->
                        </select>
                        <select id="propDistrictSelect" name="district" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value=""<?= old_select('district', '') ?>>-- Select District --</option>
                        </select>
                        <select id="propCitySelect" name="city" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value=""<?= old_select('city', '') ?>>-- Select City --</option>
                        </select>
                    </div>
                    
                    <!-- Rooms -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Rooms</h3>
                        <input type="number" id="propBedroomsInput" name="bedrooms" placeholder="Min Bedrooms" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_value('bedrooms') ?>">
                        <input type="number" id="propBathroomsInput" name="bathrooms" placeholder="Min Bathrooms" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_value('bathrooms') ?>">
                        <input type="number" id="propKitchenInput" name="kitchens" placeholder="Min Kitchens" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_value('kitchens') ?>">
                        <input type="number" id="propLivingRoomInput" name="living_rooms" placeholder="Min Living Rooms" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_value('living_rooms') ?>">
                    </div>
                    
                    <!-- Furnishing -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Furnishing</h3>
                        <select id="propFurnishedSelect" name="furnishing" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value=""<?= old_select('furnishing', '') ?>>Any</option>
                            <option value="Fully Furnished"<?= old_select('furnishing', 'Fully Furnished') ?>>Fully Furnished</option>
                            <option value="Semi-Furnished"<?= old_select('furnishing', 'Semi-Furnished') ?>>Semi-Furnished</option>
                            <option value="Unfurnished"<?= old_select('furnishing', 'Unfurnished') ?>>Unfurnished</option>
                        </select>
                    </div>
                    
                    <!-- Parking -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Parking</h3>
                        <div style="margin:5px 0;">
                            <input type="checkbox" id="propParkingCheck" name="has_parking" style="margin-right:5px;"<?= old_check('has_parking', 'on') ?>>
                            <label for="propParkingCheck">Has Parking</label>
                        </div>
                        <input type="number" id="propParkingSlotsInput" name="parking_slots" placeholder="Min Parking Slots" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;" value="<?= old_value('parking_slots') ?>">
                        <select id="propParkingTypeSelect" name="parking_type" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value=""<?= old_select('parking_type', '') ?>>Any Parking Type</option>
                            <option value="Covered Garage"<?= old_select('parking_type', 'Covered Garage') ?>>Covered Garage</option>
                            <option value="Open Parking"<?= old_select('parking_type', 'Open Parking') ?>>Open Parking</option>
                            <option value="Street Parking"<?= old_select('parking_type', 'Street Parking') ?>>Street Parking</option>
                            <option value="Carport"<?= old_select('parking_type', 'Carport') ?>>Carport</option>
                            <option value="Underground Parking"<?= old_select('parking_type', 'Underground Parking') ?>>Underground Parking</option>
                        </select>
                    </div>
                    
                    <!-- Buttons -->
                    <div style="display:flex; gap:10px;">
                        <button type="submit" style="flex:1; background:#ff8800; color:white; border:none; padding:10px 15px; cursor:pointer; border-radius:4px;">Apply Filters</button>
                        <button type="button" onclick="resetPropertyFilters()" style="flex:1; background:white; color:black; border:1px solid #ddd; padding:10px 15px; cursor:pointer; border-radius:4px;">Reset</button>
                    </div>
                </div>
            </div>

            <!-- Toggle Button -->
            <button id="collapseFilterBtn" onclick="togglePropertyFilters()" type="button"
                style="margin-top:70px; position:fixed; top:10px; left:0; z-index:1000; background:#14213d; color:white; border:none; padding:10px; cursor:pointer; border-radius:0 35px 35px 0; transition:all 0.3s;">
            <img src="<?= ROOT ?>/assets/images/setting.png" style="height:24px; width:24px; margin-right:5px; cursor:pointer; position:relative; color:white; filter:invert(1);">
            </button>
            
            </form>


            <div class="content-section" id="content-section">
                <div class="listing-items">
                <?php if (!empty($properties)): ?>
                    <?php foreach($properties as $property): ?>
                        <div class="PL_property-card">
                            <?php

                            $detail_url = ROOT . "/propertyListing/showListingDetail/" . $property->property_id;
                            if (!empty($query_string)) {
                                $detail_url .= '?' . $query_string;
                            }
                            ?>
                            <a href="<?= $detail_url ?>">
                                <?php 
                                    $property_images = explode(',', $property->property_images);
                                    $first_image = !empty($property_images[0]) ? $property_images[0] : "";
                                ?>
                                <img src="<?= get_img($first_image, 'property') ?>" alt="property" class="property-card-image">
                            </a>
                            <div class="content-section-of-card">
                                <div class="address">
                                <?= $property->address ?>
                                </div>
                                <div class="name">
                                <?= $property->name ?>
                                </div>
                                <div class="price">
                                <?= $property->rental_price ?> /<?= $property->rental_period ?>
                                </div>
                            </div>
                            <div class="units-diplays">
                                <div class="unit-display__item">
                                    <div class="unit-display__item__icon">
                                        <img src="<?= ROOT ?>/assets/images/bed.png" alt="beds" class="unit-display__item__icon__image">
                                    </div>
                                    <div class="unit-display__item__text">
                                        <div class="unit-display__item__text__number">
                                        <?= $property->bedrooms ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="unit-display__item">
                                    <div class="unit-display__item__icon">
                                        <img src="<?= ROOT ?>/assets/images/bathroom.png" alt="baths" class="unit-display__item__icon__image">
                                    </div>
                                    <div class="unit-display__item__text">
                                        <div class="unit-display__item__text__number">
                                        <?= $property->bathrooms ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="unit-display__item">
                                    <div class="unit-display__item__icon">
                                        <img src="<?= ROOT ?>/assets/images/size.png" alt="area" class="unit-display__item__icon__image">
                                    </div>
                                    <div class="unit-display__item__text">
                                        <div class="unit-display__item__text__number">
                                        <?= $property->size_sqr_ft ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                </div>
                <p style="text-align: center;">No properties found.</p>
            <?php endif; ?>
                <!-- Pagination Buttons -->
                <!-- <div class="pagination">
                    <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
                    <span class="current-page">1</span>
                    <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
                </div> -->
            </div>
        </div>

    <!-- </form> -->

    <script>
            // Location data and filter logic
            const validLocations = {
                'Western': {
                    'Colombo': ['Colombo', 'Dehiwala-Mount Lavinia', 'Moratuwa', 'Battaramulla', 'Sri Jayawardenepura Kotte', 'Nugegoda', 'Maharagama', 'Kesbewa', 'Kaduwela', 'Kolonnawa', 'Homagama', 'Piliyandala', 'Boralesgamuwa', 'Malabe', 'Pita Kotte', 'Avissawella', 'Padukka', 'Hanwella'],
                    'Gampaha': ['Negombo', 'Gampaha', 'Ja-Ela', 'Wattala', 'Ragama', 'Kadawatha', 'Minuwangoda', 'Kiribathgoda', 'Hendala', 'Mabole', 'Katunayake', 'Kandana', 'Nittambuwa', 'Kirindiwela', 'Biyagama', 'Peliyagoda'],
                    'Kalutara': ['Kalutara', 'Beruwala', 'Panadura', 'Aluthgama', 'Wadduwa', 'Matugama', 'Bandaragama', 'Horana', 'Ingiriya', 'Bulathsinhala', 'Agalawatta']
                },
                'Central': {
                    'Kandy': ['Kandy', 'Peradeniya', 'Gampola', 'Katugastota', 'Nawalapitiya', 'Pilimathalawa', 'Wattegama', 'Akurana', 'Digana', 'Gelioya', 'Kundasale', 'Galagedara', 'Hanguranketa', 'Ampitiya'],
                    'Matale': ['Matale', 'Dambulla', 'Sigiriya', 'Ukuwela', 'Rattota', 'Galewela', 'Nalanda', 'Palapathwela', 'Aluvihare', 'Yatawatta'],
                    'Nuwara Eliya': ['Nuwara Eliya', 'Hatton', 'Talawakele', 'Maskeliya', 'Pussellawa', 'Kotagala', 'Lindula', 'Ragala', 'Walapane', 'Hanguranketha']
                },
                'Southern': {
                    'Galle': ['Galle', 'Hikkaduwa', 'Unawatuna', 'Ambalangoda', 'Karapitiya', 'Baddegama', 'Bentota', 'Balapitiya', 'Elpitiya', 'Imaduwa', 'Batapola', 'Ahangama', 'Ahungalla'],
                    'Matara': ['Matara', 'Weligama', 'Dickwella', 'Akuressa', 'Kamburupitiya', 'Hakmana', 'Deniyaya', 'Mirissa', 'Devinuwara', 'Malimboda', 'Morawaka'],
                    'Hambantota': ['Hambantota', 'Tangalle', 'Tissamaharama', 'Kataragama', 'Ambalantota', 'Beliatta', 'Weeraketiya', 'Lunugamvehera', 'Sooriyawewa', 'Angunukolapelessa']
                },
                'Northern': {
                    'Jaffna': ['Jaffna', 'Nallur', 'Chavakachcheri', 'Point Pedro', 'Karainagar', 'Velanai', 'Valvettithurai', 'Kopay', 'Kaithady', 'Manipay', 'Tellippalai', 'Chunnakam', 'Uduvil'],
                    'Kilinochchi': ['Kilinochchi', 'Pallai', 'Paranthan', 'Karachchi', 'Mulankavil', 'Pooneryn', 'Kandavalai'],
                    'Mullaitivu': ['Mullaitivu', 'Puthukudiyiruppu', 'Oddusuddan', 'Thunukkai', 'Mallavi', 'Mankulam'],
                    'Vavuniya': ['Vavuniya', 'Cheddikulam', 'Nedunkeni', 'Omanthai'],
                    'Mannar': ['Mannar', 'Adampan', 'Nanattan', 'Musali', 'Madhu']
                },
                'Eastern': {
                    'Trincomalee': ['Trincomalee', 'Kinniya', 'Mutur', 'Kantale', 'Nilaveli', 'China Bay', 'Seruwila', 'Thampalakamam', 'Kuchchaveli', 'Gomarankadawala'],
                    'Batticaloa': ['Batticaloa', 'Eravur', 'Valachchenai', 'Kalkudah', 'Oddamavadi', 'Vakarai', 'Kattankudy', 'Chenkalady', 'Araipattai'],
                    'Ampara': ['Ampara', 'Kalmunai', 'Sammanthurai', 'Dehiattakandiya', 'Uhana', 'Pottuvil', 'Akkaraipattu', 'Sainthamaruthu', 'Thirukkovil', 'Nintavur', 'Addalachchenai', 'Mahaoya']
                },
                'North Western': {
                    'Kurunegala': ['Kurunegala', 'Kuliyapitiya', 'Maho', 'Polgahawela', 'Pannala', 'Narammala', 'Nikaweratiya', 'Wariyapola', 'Ibbagamuwa', 'Alawwa', 'Giriulla', 'Bingiriya'],
                    'Puttalam': ['Puttalam', 'Chilaw', 'Wennappuwa', 'Anamaduwa', 'Nattandiya', 'Dankotuwa', 'Kalpitiya', 'Marawila', 'Madampe', 'Arachchikattuwa', 'Norochcholai']
                },
                'North Central': {
                    'Anuradhapura': ['Anuradhapura', 'Kekirawa', 'Medawachchiya', 'Mihintale', 'Thambuttegama', 'Eppawala', 'Kahatagasdigiliya', 'Galenbindunuwewa', 'Horowpothana', 'Kebithigollewa', 'Rambewa', 'Thalawa'],
                    'Polonnaruwa': ['Polonnaruwa', 'Kaduruwela', 'Hingurakgoda', 'Medirigiriya', 'Dimbulagala', 'Manampitiya', 'Lankapura', 'Elahera', 'Bakamuna', 'Jayanthipura']
                },
                'Uva': {
                    'Badulla': ['Badulla', 'Bandarawela', 'Ella', 'Hali-Ela', 'Welimada', 'Mahiyanganaya', 'Diyatalawa', 'Haputale', 'Passara', 'Lunugala', 'Uva-Paranagama', 'Kandaketiya'],
                    'Monaragala': ['Monaragala', 'Wellawaya', 'Bibile', 'Buttala', 'Kataragama', 'Siyambalanduwa', 'Thanamalvila', 'Badalkumbura', 'Madulla']
                },
                'Sabaragamuwa': {
                    'Ratnapura': ['Ratnapura', 'Balangoda', 'Embilipitiya', 'Pelmadulla', 'Kuruwita', 'Eheliyagoda', 'Kalawana', 'Kahawatta', 'Rakwana', 'Opanayaka', 'Godakawela', 'Nivithigala'],
                    'Kegalle': ['Kegalle', 'Mawanella', 'Warakapola', 'Rambukkana', 'Galigamuwa', 'Deraniyagala', 'Yatiyantota', 'Ruwanwella', 'Dehiowita', 'Aranayaka', 'Hemmathagama']
                }
            };

            document.addEventListener('DOMContentLoaded', function() {
                // Province/District/City population
                const propProvinceSelect = document.getElementById('propProvinceSelect');
                const propDistrictSelect = document.getElementById('propDistrictSelect');
                const propCitySelect = document.getElementById('propCitySelect');
                const checkInInput = document.getElementById('propCheckInDate');
                const checkOutInput = document.getElementById('propCheckOutDate');
                const today = new Date();

                // Set default dates if not set
                if (!checkInInput.value) {
                    checkInInput.value = today.toISOString().split('T')[0];
                }
                if (!checkOutInput.value) {
                    const tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);
                    checkOutInput.value = tomorrow.toISOString().split('T')[0];
                }

                // Ensure checkout is always at least 1 day after checkin
                checkInInput.addEventListener('change', function() {
                    const checkInDate = new Date(this.value);
                    let minCheckOutDate = new Date(checkInDate);
                    minCheckOutDate.setDate(checkInDate.getDate() + 1);
                    checkOutInput.min = minCheckOutDate.toISOString().split('T')[0];
                    if (new Date(checkOutInput.value) <= checkInDate) {
                        checkOutInput.value = minCheckOutDate.toISOString().split('T')[0];
                    }
                });

                // Set initial min for checkout
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);
                checkOutInput.min = tomorrow.toISOString().split('T')[0];

                // Add provinces
                Object.keys(validLocations).forEach(province => {
                    const option = document.createElement('option');
                    option.value = province;
                    option.textContent = province;
                    propProvinceSelect.appendChild(option);
                });

                // Update district select when province changes
                propProvinceSelect.addEventListener('change', function() {
                    const selectedProvince = this.value;
                    propDistrictSelect.innerHTML = '<option value="">-- Select District --</option>';
                    propCitySelect.innerHTML = '<option value="">-- Select City --</option>';
                    if(selectedProvince) {
                        Object.keys(validLocations[selectedProvince]).forEach(district => {
                            const option = document.createElement('option');
                            option.value = district;
                            option.textContent = district;
                            propDistrictSelect.appendChild(option);
                        });
                    }
                });

                // Update city select when district changes
                propDistrictSelect.addEventListener('change', function() {
                    const selectedProvince = propProvinceSelect.value;
                    const selectedDistrict = this.value;
                    propCitySelect.innerHTML = '<option value="">-- Select City --</option>';
                    if(selectedProvince && selectedDistrict) {
                        validLocations[selectedProvince][selectedDistrict].forEach(city => {
                            const option = document.createElement('option');
                            option.value = city;
                            option.textContent = city;
                            propCitySelect.appendChild(option);
                        });
                    }
                });
            });

            function resetPropertyFilters() {
                document.getElementById('searchTerm').value = '';
                document.getElementById('propTypeSelect').selectedIndex = 0;
                document.getElementById('propProvinceSelect').selectedIndex = 0;
                document.getElementById('propDistrictSelect').innerHTML = '<option value="">-- Select District --</option>';
                document.getElementById('propCitySelect').innerHTML = '<option value="">-- Select City --</option>';
                document.getElementById('propCheckInDate').value = new Date().toISOString().split('T')[0];
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('propCheckOutDate').value = tomorrow.toISOString().split('T')[0];
                document.getElementById('propBedroomsInput').value = '';
                document.getElementById('propBathroomsInput').value = '';
                document.getElementById('propKitchenInput').value = '';
                document.getElementById('propLivingRoomInput').value = '';
                document.getElementById('propFurnishedSelect').selectedIndex = 0;
                document.getElementById('propParkingCheck').checked = false;
                document.getElementById('propParkingSlotsInput').value = '';
                document.getElementById('propParkingTypeSelect').selectedIndex = 0;
                document.getElementById('propMinPriceInput').value = '';
                document.getElementById('propMaxPriceInput').value = '';
                document.getElementById('propSortBySelect').selectedIndex = 0;
            }

            // return date diff depending on the period type
            function getDateDiff(start, end, period) {
                const startDate = new Date(start);
                const endDate = new Date(end);
                if (period === 'Monthly') {
                    let months = (endDate.getFullYear() - startDate.getFullYear()) * 12;
                    months += endDate.getMonth() - startDate.getMonth();
                    // If endDate's day is greater than startDate's, count as an extra month
                    if (endDate.getDate() > startDate.getDate()) months++;
                    return months;
                } else if (period === 'Daily') {
                    const diffTime = endDate - startDate;
                    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                }
                return 0;
            }

            function updateCheckoutDate() {
                const checkInInput = document.getElementById('propCheckInDate');
                const checkOutInput = document.getElementById('propCheckOutDate');
                const period = document.getElementById('propRentalPeriodSelect').value;
                const duration = parseInt(document.getElementById('periodDuration').value) || 0;
                const checkInDate = new Date(checkInInput.value);

                if (checkInInput.value && duration > 0) {
                    let checkOutDate = new Date(checkInDate);
                    if (period === 'Monthly') {
                        checkOutDate.setMonth(checkOutDate.getMonth() + duration);
                    } else if (period === 'Daily') {
                        checkOutDate.setDate(checkOutDate.getDate() + duration);
                    }
                    checkOutInput.value = checkOutDate.toISOString().split('T')[0];
                }
            }

            function updatePeriodDuration() {
                const checkInInput = document.getElementById('propCheckInDate');
                const checkOutInput = document.getElementById('propCheckOutDate');
                const period = document.getElementById('propRentalPeriodSelect').value;
                const durationInput = document.getElementById('periodDuration');
                if (checkInInput.value && checkOutInput.value) {
                    const duration = getDateDiff(checkInInput.value, checkOutInput.value, period);
                    durationInput.value = duration > 0 ? duration : '';
                }
            }

            function togglePropertyFilters() {
                const panel = document.getElementById('filterSidebar');
                const btn = document.getElementById('collapseFilterBtn');
                panel.style.marginLeft = (panel.style.marginLeft === '0px' || panel.style.marginLeft === '0') ? '-20%' : '0';
                btn.style.left = (panel.style.marginLeft === '0px' || panel.style.marginLeft === '0') ? '20%' : '0';
            }

            function togglePeriodDuration(value) {
                const durationField = document.getElementById('periodDuration');
                durationField.style.display = value ? 'block' : 'none';
                durationField.placeholder = value === 'Monthly' ? 'No. of months' : 'No. of days';
                durationField.value = '';
                updateCheckoutDate();
            }
        </script>
    <script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script>
    <script src="<?= ROOT ?>/assets/js/loader.js"></script>

</html>