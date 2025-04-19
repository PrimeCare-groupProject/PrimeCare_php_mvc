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
            <form action="<?= ROOT ?>/propertyListing/showListing" method="POST">
                    <div class="PL_form_main-filters">
                        <div class="flex-bar">
                            <img src="<?= ROOT ?>/assets/images/setting.png" alt="see" id="toggleFilters" class="small-icons" style="filter: invert(1); margin-left: 5px;" onclick="toggleFilters()">
                            <div class="search-container">
                                <input type="text" name="searchTerm" id="searchTerm" class="search-input" placeholder="Search Anything..." value="<?= old_value('searchTerm') ?>">
                                <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
                            </div>
                        </div>
                    </div>
                    <div class="PL_form_filters" id="PL_form_filters" style="display: none;">
                        <div class="filter-menu">
                            <div class="filter-row-instance">
                                <!-- Sort By -->
                                <div class="half-of-the-row">
                                    <label>Sort By:
                                        <select id="sort_by" name="sort_by">
                                            <option value="" <?= old_select('sort_by', '') ?>>-- Select --</option>
                                            <option value="price-asc" <?= old_select('sort_by', 'price-asc') ?>>Price Low to High</option>
                                            <option value="price-desc" <?= old_select('sort_by', 'price-desc') ?>>Price High to Low</option>
                                            <option value="newest" <?= old_select('sort_by', 'newest') ?>>Newest</option>
                                            <option value="oldest" <?= old_select('sort_by', 'oldest') ?>>Oldest</option>
                                            <option value="size-asc" <?= old_select('sort_by', 'size-asc') ?>>Size (Small to Large)</option>
                                            <option value="size-desc" <?= old_select('sort_by', 'size-desc') ?>>Size (Large to Small)</option>
                                        </select>
                                    </label>
                                </div>
                                
                                <!-- Price Range -->
                                <div class="half-of-the-row">
                                    <label>Price Range:
                                        <div class="inline-block-inputs">
                                            <input type="number" id="min_price" name="min_price" placeholder="Min" class="short-box" value="<?= old_value('min_price') ?>">
                                            <input type="number" id="max_price" name="max_price" placeholder="Max" class="short-box" value="<?= old_value('max_price') ?>">
                                        </div>
                                    </label>
                                </div>
                                
                                <div class="half-of-the-row">
                                    <label>Check-in Date:
                                        <input type="date" id="check_in_date" name="check_in" placeholder="Check-in Date" value="<?= old_date('check_in', date('Y-m-d')) ?>">
                                    </label>
                                </div>
                                
                                <!-- Check-out Date -->
                                <div class="half-of-the-row">
                                    <label>Check-out Date:
                                        <input type="date" id="check_out_date" name="check_out" placeholder="Check-out Date" value="<?= old_date('check_out', date('Y-m-d', strtotime('+1 day'))) ?>">
                                    </label>
                                </div>

                                <!-- Location Filters -->
                                <div class="half-of-the-row">
                                    <label>Province:
                                        <select id="province" name="province">
                                            <option value="">-- Select Province --</option>
                                            <?php $selectedProvince = old_value('province'); ?>
                                            <script>
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
                                                
                                                // Add provinces to dropdown
                                                const provinces = Object.keys(validLocations);
                                                const provinceSelect = document.getElementById('province');
                                                provinces.forEach(province => {
                                                    const option = document.createElement('option');
                                                    option.value = province;
                                                    option.textContent = province;
                                                    provinceSelect.appendChild(option);
                                                });
                                            </script>
                                        </select>
                                    </label>
                                </div>

                                <div class="half-of-the-row">
                                    <label>District:
                                        <select id="district" name="district">
                                            <option value="">-- Select District --</option>
                                        </select>
                                    </label>
                                </div>

                                <div class="half-of-the-row">
                                    <label>City:
                                        <select id="city" name="city">
                                            <option value="">-- Select City --</option>
                                        </select>
                                    </label>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const provinceSelect = document.getElementById('province');
                                        const districtSelect = document.getElementById('district');
                                        const citySelect = document.getElementById('city');
                                        
                                        // Update districts based on province selection
                                        provinceSelect.addEventListener('change', function() {
                                            const selectedProvince = this.value;
                                            
                                            // Clear district and city dropdowns
                                            districtSelect.innerHTML = '<option value="">-- Select District --</option>';
                                            citySelect.innerHTML = '<option value="">-- Select City --</option>';
                                            
                                            if (selectedProvince) {
                                                const districts = Object.keys(validLocations[selectedProvince]);
                                                districts.forEach(district => {
                                                    const option = document.createElement('option');
                                                    option.value = district;
                                                    option.textContent = district;
                                                    districtSelect.appendChild(option);
                                                });
                                            }
                                        });
                                        
                                        // Update cities based on district selection
                                        districtSelect.addEventListener('change', function() {
                                            const selectedProvince = provinceSelect.value;
                                            const selectedDistrict = this.value;
                                            
                                            // Clear city dropdown
                                            citySelect.innerHTML = '<option value="">-- Select City --</option>';
                                            
                                            if (selectedProvince && selectedDistrict) {
                                                const cities = validLocations[selectedProvince][selectedDistrict];
                                                cities.forEach(city => {
                                                    const option = document.createElement('option');
                                                    option.value = city;
                                                    option.textContent = city;
                                                    citySelect.appendChild(option);
                                                });
                                            }
                                        });
                                        
                                        // Handle form value persistence after submit
                                        const oldProvince = "<?= old_value('province') ?>";
                                        const oldDistrict = "<?= old_value('district') ?>";
                                        const oldCity = "<?= old_value('city') ?>";
                                        
                                        if (oldProvince) {
                                            provinceSelect.value = oldProvince;
                                            provinceSelect.dispatchEvent(new Event('change'));
                                            
                                            setTimeout(() => {
                                                if (oldDistrict) {
                                                    districtSelect.value = oldDistrict;
                                                    districtSelect.dispatchEvent(new Event('change'));
                                                    
                                                    setTimeout(() => {
                                                        if (oldCity) {
                                                            citySelect.value = oldCity;
                                                        }
                                                    }, 50);
                                                }
                                            }, 50);
                                        }
                                    });
                                </script>
                                
                                <!-- Apply Filters Button -->
                                <div class="half-of-the-row" style="display: flex; flex-direction: column; justify-content: space-between; align-items: center;">
                                    <button type="submit" 
                                        style="width: 100px; padding: 5px; border-radius: 12px; background-color: orange; color: white; border: none; cursor: pointer; ">
                                        Apply Filters
                                    </button>
                                    <button type="button" onclick="resetFilters()" 
                                        style="width: 100px; padding: 5px; border-radius: 12px; background-color: white; color: black; border: 1px solid #ccc; cursor: pointer;">
                                        Reset Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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

    <script src="<?= ROOT ?>/assets/js/propertyListings/listings.js"></script>
    <script src="<?= ROOT ?>/assets/js/loader.js"></script>
    <script>
   
    </script>
</body>

</html>