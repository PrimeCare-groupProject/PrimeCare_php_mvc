<!DOCTYPE html>
<html>
<head>
<style>
    /* Main container styles */
    body {
        margin: 0;
        font-family: Arial, sans-serif;
    }
    
    .PropFilt_container {
        margin-top: 100px;
        display: flex;
        transition: all 0.3s;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 100;
    }

    /* Filter panel styles */
    .PropFilt_sidebar {
        width: 15%;
        padding: 15px;
        background: #f5f5f5;
        background-color: var(--secondary-color);
        border-right: 1px solid #ddd;
        height: 100vh;
        overflow-y: auto;
        box-sizing: border-box;
        transition: all 0.3s;
    }
    
    .PropFilt_sidebar.hidden {
        margin-left: -20%;
    }
    
    /* Toggle button styles */
    .PropFilt_collapse_btn {
        margin-top: 90px;
        position: fixed;
        top: 10px;
        left: 15%;
        z-index: 1000;
        background: #14213d;
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        border-radius: 0 35px 35px 0;
        transition: all 0.3s;
    }
    
    .PropFilt_collapse_btn.hidden {
        left: 0;
    }
    
    /* Filter section styles */
    .PropFilt_category {
        margin-bottom: 20px;
    }
    
    .PropFilt_category h3 {
        margin-top: 0;
        color: #333;
        font-size: 16px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 5px;
    }
    
    select, input[type="text"], input[type="number"] {
        width: 100%;
        padding: 8px;
        margin: 5px 0;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .PropFilt_checkbox_container {
        display: flex;
        flex-direction: column;
    }
    
    .PropFilt_checkbox_row {
        margin: 5px 0;
    }
    
    .PropFilt_action_btn {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 4px;
        width: 100%;
    }
    
    .PropFilt_action_btn:hover {
        background: #45a049;
    }

    .PropFilt_icon {
        height: 24px;
        width: 24px;
        margin-right: 5px;
        cursor: pointer;
        position: relative;
        color: white;
        filter: invert(1);
    }
</style>
<title>Property Filter</title>
</head>
<body>

<div class="PropFilt_container">
    <!-- Filter Panel -->
    <div class="PropFilt_sidebar" id="filterSidebar">
        <h2>Property Filters</h2>
        
        <!-- Search Term -->
        <div class="PropFilt_category">
            <h3>Search</h3>
            <input type="text" id="propSearchInput" placeholder="Search properties...">
        </div>
        
        <!-- Property Type -->
        <div class="PropFilt_category">
            <h3>Property Type</h3>
            <select id="propTypeSelect">
                <option value="">Any Type</option>
                <option value="House">House</option>
                <option value="Apartment">Apartment</option>
                <option value="Villa">Villa</option>
                <option value="Studio">Studio</option>
                <option value="Farmhouse">Farmhouse</option>
            </select>
        </div>
        
        <!-- Location -->
        <div class="PropFilt_category">
            <h3>Location</h3>
            <input type="text" id="propCityInput" placeholder="City">
            <input type="text" id="propStateInput" placeholder="State/Province">
        </div>
        
        <!-- Rooms -->
        <div class="PropFilt_category">
            <h3>Rooms</h3>
            <input type="number" id="propBedroomsInput" placeholder="Min Bedrooms" min="0">
            <input type="number" id="propBathroomsInput" placeholder="Min Bathrooms" min="0">
            <input type="number" id="propKitchenInput" placeholder="Min Kitchens" min="0">
            <input type="number" id="propLivingRoomInput" placeholder="Min Living Rooms" min="0">
        </div>
        
        <!-- Furnishing -->
        <div class="PropFilt_category">
            <h3>Furnishing</h3>
            <select id="propFurnishedSelect">
                <option value="">Any</option>
                <option value="Fully Furnished">Fully Furnished</option>
                <option value="Semi-Furnished">Semi-Furnished</option>
                <option value="Unfurnished">Unfurnished</option>
            </select>
        </div>
        
        <!-- Parking -->
        <div class="PropFilt_category">
            <h3>Parking</h3>
            <div class="PropFilt_checkbox_row">
                <input type="checkbox" id="propParkingCheck">
                <label for="propParkingCheck">Has Parking</label>
            </div>
            <input type="number" id="propParkingSlotsInput" placeholder="Min Parking Slots" min="0">
            <select id="propParkingTypeSelect">
                <option value="">Any Parking Type</option>
                <option value="Covered Garage">Covered Garage</option>
                <option value="Open Parking">Open Parking</option>
                <option value="Street Parking">Street Parking</option>
                <option value="Carport">Carport</option>
                <option value="Underground Parking">Underground Parking</option>
            </select>
        </div>
        
        <!-- Price Range -->
        <div class="PropFilt_category">
            <h3>Price Range</h3>
            <input type="number" id="propMinPriceInput" placeholder="Min Price">
            <input type="number" id="propMaxPriceInput" placeholder="Max Price">
            <select id="propRentalPeriodSelect">
                <option value="">Any Period</option>
                <option value="Monthly">Monthly</option>
                <option value="Annually">Annually</option>
                <option value="Daily">Daily</option>
            </select>
        </div>
        
        <!-- Purpose -->
        <div class="PropFilt_category">
            <h3>Purpose</h3>
            <select id="propPurposeSelect">
                <option value="">Any</option>
                <option value="Rent">Rent</option>
                <option value="Vacation_Rental">Vacation Rental</option>
            </select>
        </div>
        
        <!-- Submit Button -->
        <button class="PropFilt_action_btn" onclick="submitPropertyFilters()">Apply Filters</button>
    </div>
    
    <!-- Content Area -->
    <div class="PropFilt_content" style="width: 80%; padding: 20px;">
        <!-- Your property listings will go here -->
        <div id="propertyFilterResults"></div>
    </div>
</div>

<!-- Toggle Button -->
<button class="PropFilt_collapse_btn" id="collapseFilterBtn" onclick="togglePropertyFilters()"><img src="<?= ROOT ?>/assets/images/setting.png" class="PropFilt_icon"></button>

<script>
    function togglePropertyFilters() {
        const panel = document.getElementById('filterSidebar');
        const btn = document.getElementById('collapseFilterBtn');
        panel.classList.toggle('hidden');
        btn.classList.toggle('hidden');
    }
    
    function submitPropertyFilters() {
        const filters = {
            searchTerm: document.getElementById('propSearchInput').value,
            propertyType: document.getElementById('propTypeSelect').value,
            city: document.getElementById('propCityInput').value,
            stateProvince: document.getElementById('propStateInput').value,
            bedrooms: document.getElementById('propBedroomsInput').value,
            bathrooms: document.getElementById('propBathroomsInput').value,
            kitchen: document.getElementById('propKitchenInput').value,
            livingRoom: document.getElementById('propLivingRoomInput').value,
            furnished: document.getElementById('propFurnishedSelect').value,
            parking: document.getElementById('propParkingCheck').checked,
            parkingSlots: document.getElementById('propParkingSlotsInput').value,
            typeOfParking: document.getElementById('propParkingTypeSelect').value,
            minPrice: document.getElementById('propMinPriceInput').value,
            maxPrice: document.getElementById('propMaxPriceInput').value,
            rentalPeriod: document.getElementById('propRentalPeriodSelect').value,
            purpose: document.getElementById('propPurposeSelect').value
        };
        
        console.log("Applying property filters:", filters);
    }
</script>

</body>
</html>