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
    <form method="POST">
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
            <div class="PL_filter-section">
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
                            <option value="price-asc">Price Low to High</option>
                            <option value="price-desc">Price High to Low</option>
                            <option value="newest">Newest</option>
                            <option value="oldest">Oldest</option>
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Availability</h3>
                        <div>
                            <label style="display:block; margin-bottom:5px;">Check-in</label>
                            <input type="date" id="propCheckInDate" name="check_in" style="width:100%; padding:8px; margin-bottom:10px; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div>
                            <label style="display:block; margin-bottom:5px;">Check-out</label>
                            <input type="date" id="propCheckOutDate" name="check_out" style="width:100%; padding:8px; margin:0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        </div>
                    </div>

                    <!-- Property Type -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Property Type</h3>
                        <select id="propTypeSelect" name="property_type" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value="">Any Type</option>
                            <option value="House">House</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Villa">Villa</option>
                            <option value="Studio">Studio</option>
                            <option value="Farmhouse">Farmhouse</option>
                        </select>
                    </div>

                    
                    <!-- Price Range -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Price Range</h3>
                        <div>
                            <input type="number" id="propMinPriceInput" name="min_price" placeholder="Min Price" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        </div>
                        <div>
                            <input type="number" id="propMaxPriceInput" name="max_price" placeholder="Max Price" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        </div>
                    </div>
                    
                    <!-- Location -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Location</h3>
                        <select id="propProvinceSelect" name="province" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value="">-- Select Province --</option>
                            <!-- Provinces will be populated via JavaScript -->
                        </select>
                        <select id="propDistrictSelect" name="district" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value="">-- Select District --</option>
                        </select>
                        <select id="propCitySelect" name="city" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value="">-- Select City --</option>
                        </select>
                    </div>
                    
                    <!-- Rooms -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Rooms</h3>
                        <input type="number" id="propBedroomsInput" name="bedrooms" placeholder="Min Bedrooms" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        <input type="number" id="propBathroomsInput" name="bathrooms" placeholder="Min Bathrooms" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        <input type="number" id="propKitchenInput" name="kitchens" placeholder="Min Kitchens" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        <input type="number" id="propLivingRoomInput" name="living_rooms" placeholder="Min Living Rooms" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                    </div>
                    
                    <!-- Furnishing -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Furnishing</h3>
                        <select id="propFurnishedSelect" name="furnishing" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value="">Any</option>
                            <option value="Fully Furnished">Fully Furnished</option>
                            <option value="Semi-Furnished">Semi-Furnished</option>
                            <option value="Unfurnished">Unfurnished</option>
                        </select>
                    </div>
                    
                    <!-- Parking -->
                    <div style="margin-bottom:20px;">
                        <h3 style="margin-top:0; color:#333; font-size:16px; border-bottom:1px solid #ddd; padding-bottom:5px;">Parking</h3>
                        <div style="margin:5px 0;">
                            <input type="checkbox" id="propParkingCheck" name="has_parking" style="margin-right:5px;">
                            <label for="propParkingCheck">Has Parking</label>
                        </div>
                        <input type="number" id="propParkingSlotsInput" name="parking_slots" placeholder="Min Parking Slots" min="0" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                        <select id="propParkingTypeSelect" name="parking_type" style="width:100%; padding:8px; margin:5px 0; box-sizing:border-box; border:1px solid #ddd; border-radius:4px;">
                            <option value="">Any Parking Type</option>
                            <option value="Covered Garage">Covered Garage</option>
                            <option value="Open Parking">Open Parking</option>
                            <option value="Street Parking">Street Parking</option>
                            <option value="Carport">Carport</option>
                            <option value="Underground Parking">Underground Parking</option>
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
                            <a href="<?= ROOT ?>/propertyListing/showListingDetail/<?= $property->property_id ?>?check_in=<?= $_POST['check_in'] ?? '' ?>&check_out=<?= $_POST['check_out'] ?? '' ?>">
                                <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= explode(',', $property->property_images)[0] ?>" alt="property" class="property-card-image">
                            </a>
                            <div class="content-section-of-card">
                                <div class="address">
                                <?= $property->address ?>
                                </div>
                                <div class="name">
                                <?= $property->name ?>
                                </div>
                                <div class="price">
                                <?= $property->rental_price ?> /Month
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
                <p>No properties found.</p>
            <?php endif; ?>
                </div>
                <!-- Pagination Buttons -->
                <div class="pagination">
                    <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
                    <span class="current-page">1</span>
                    <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
                </div>
            </div>
        </div>

        <script>
            // Place this above your DOMContentLoaded event or in your main JS file
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
                // Populate province select using the same data structure from the main form
                const propProvinceSelect = document.getElementById('propProvinceSelect');
                const propDistrictSelect = document.getElementById('propDistrictSelect');
                const propCitySelect = document.getElementById('propCitySelect');
                
                // Set default dates
                const checkInInput = document.getElementById('propCheckInDate');
                const checkOutInput = document.getElementById('propCheckOutDate');
                const today = new Date();
                checkInInput.value = today.toISOString().split('T')[0];
                const tomorrow = new Date();
                tomorrow.setDate(today.getDate() + 1);
                checkOutInput.value = tomorrow.toISOString().split('T')[0];

                // Ensure checkout is always at least 1 day after checkin
                checkInInput.addEventListener('change', function() {
                    const checkInDate = new Date(this.value);
                    let minCheckOutDate = new Date(checkInDate);
                    minCheckOutDate.setDate(checkInDate.getDate() + 1);
                    checkOutInput.min = minCheckOutDate.toISOString().split('T')[0];

                    // If current checkout is before min, update it
                    if (new Date(checkOutInput.value) <= checkInDate) {
                        checkOutInput.value = minCheckOutDate.toISOString().split('T')[0];
                    }
                });

                // Set initial min for checkout
                checkOutInput.min = tomorrow.toISOString().split('T')[0];
                
                // Add provinces
                const provinces = Object.keys(validLocations);
                provinces.forEach(province => {
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
                        const districts = Object.keys(validLocations[selectedProvince]);
                        districts.forEach(district => {
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
                        const cities = validLocations[selectedProvince][selectedDistrict];
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city;
                            option.textContent = city;
                            propCitySelect.appendChild(option);
                        });
                    }
                });
            });
            
            function resetPropertyFilters() {
                document.getElementById('propSearchInput').value = '';
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

            function resetFilters() {
                document.getElementById('searchTerm').value = '';
                document.getElementById('propSortBySelect').selectedIndex = 0;
                document.getElementById('propMinPriceInput').value = '';
                document.getElementById('propMaxPriceInput').value = '';
                document.getElementById('propCheckInDate').value = new Date().toISOString().split('T')[0];
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                document.getElementById('propCheckOutDate').value = tomorrow.toISOString().split('T')[0];
                document.getElementById('propProvinceSelect').selectedIndex = 0;
                document.getElementById('propDistrictSelect').innerHTML = '<option value="">-- Select District --</option>';
                document.getElementById('propCitySelect').innerHTML = '<option value="">-- Select City --</option>';
                document.getElementById('propTypeSelect').selectedIndex = 0;
                document.getElementById('propBedroomsInput').value = '';
                document.getElementById('propBathroomsInput').value = '';
                document.getElementById('propKitchenInput').value = '';
                document.getElementById('propLivingRoomInput').value = '';
                document.getElementById('propFurnishedSelect').selectedIndex = 0;
                document.getElementById('propParkingCheck').checked = false;
                document.getElementById('propParkingSlotsInput').value = '';
                document.getElementById('propParkingTypeSelect').selectedIndex = 0;
            }
        </script>

    </form>
    <script>
        function toggleFilters() {
            const filters = document.getElementById('PL_form_filters');
            filters.style.display = filters.style.display === 'none' ? 'block' : 'none';
        }
        
        function togglePropertyFilters() {
            const panel = document.getElementById('filterSidebar');
            const btn = document.getElementById('collapseFilterBtn');
            panel.style.marginLeft = (panel.style.marginLeft === '0px' || panel.style.marginLeft === '0') ? '-20%' : '0';
            btn.style.left = (panel.style.marginLeft === '0px' || panel.style.marginLeft === '0') ? '20%' : '0';
        }
    </script>
    <script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script>
    <script src="<?= ROOT ?>/assets/js/loader.js"></script>

</html>