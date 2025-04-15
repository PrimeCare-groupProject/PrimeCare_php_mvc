<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/manageProviders/serviceproviders'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Add Service Provider</h2>
</div>

<div id="formContainer">
    <!-- Consolidated Form -->
    <form id="addAgentForm" method="post" action="<?= ROOT ?>/dashboard/manageProviders/serviceproviders/addserviceprovider" class="form_wrapper manager_form_wrapper" >
        <!-- Personal Details View -->
        <input type="hidden" name="add_agent" value="1">
        <div id="personalDetailsView" class="AddnewAgentform">
            <div class="subpart-name" style="margin-top: -10px;"> Personal Details </div>
            
            <div class="input-group">
                <div class="input-group-group">
                    <label for="fname" class="input-label">First Name</label>
                    <input type="text" name="fname" id="fname" class="input-field" 
                        placeholder="John" value="<?= old_value('fname') ?>" required>
                </div>
                <div class="input-group-group">
                    <label for="lname" class="input-label">Last Name</label>
                    <input type="text" name="lname" id="lname" class="input-field" 
                        placeholder="Doe" value="<?= old_value('lname') ?>" equired>
                </div>
            </div>
            
            <div class="input-group">
                <div class="input-group-group">
                    <label for="phoneNo" class="input-label">Contact Number</label>
                    <input type="text" name="contact" id="phoneNo" class="input-field" 
                        placeholder="076XXXXXXX" value="<?= old_value('contact') ?>" required>
                </div>
                <div class="input-group-group">
                    <label for="nic" class="input-label">NIC</label>
                    <input type="text" name="nic" id="nic" class="input-field" 
                        placeholder="20020690111" value="<?= old_value('nic') ?>"required>
                </div>
            </div>

            <div class="input-group-group">
                <label for="email" class="input-label">Email Address</label>
                <input type="email" name="email" id="email" class="input-field" 
                    placeholder="johndoe@gmail.com" value="<?= old_value('email') ?>" required>
            </div>

            <div class="subpart-name" style="margin-top: 10px;"> Location Details </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label for="province" class="input-label">Province</label>
                    <select name="province" id="province" class="input-field" required>
                        <option value="" disabled selected>Select Province</option>
                        <option value="Western">Western</option>
                        <option value="Central">Central</option>
                        <option value="Southern">Southern</option>
                        <option value="Northern">Northern</option>
                        <option value="Eastern">Eastern</option>
                        <option value="North Western">North Western</option>
                        <option value="North Central">North Central</option>
                        <option value="Uva">Uva</option>
                        <option value="Sabaragamuwa">Sabaragamuwa</option>
                    </select>
                </div>

                <div class="input-group-group">
                    <label for="district" class="input-label">District</label>
                    <select name="district" id="district" class="input-field" required>
                        <option value="" disabled selected>Select District</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Gampaha">Gampaha</option>
                        <option value="Kalutara">Kalutara</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Matale">Matale</option>
                        <option value="Nuwara Eliya">Nuwara Eliya</option>
                        <option value="Galle">Galle</option>
                        <option value="Matara">Matara</option>
                        <option value="Hambantota">Hambantota</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Kilinochchi">Kilinochchi</option>
                        <option value="Mannar">Mannar</option>
                        <option value="Vavuniya">Vavuniya</option>
                        <option value="Mullaitivu">Mullaitivu</option>
                        <option value="Batticaloa">Batticaloa</option>
                        <option value="Ampara">Ampara</option>
                        <option value="Trincomalee">Trincomalee</option>
                        <option value="Kurunegala">Kurunegala</option>
                        <option value="Puttalam">Puttalam</option>
                        <option value="Anuradhapura">Anuradhapura</option>
                        <option value="Polonnaruwa">Polonnaruwa</option>
                        <option value="Badulla">Badulla</option>
                        <option value="Monaragala">Monaragala</option>
                        <option value="Ratnapura">Ratnapura</option>
                        <option value="Kegalle">Kegalle</option>
                    </select>
                </div>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label for="address" class="input-label">Address</label>
                    <input type="text" name="address" id="address" class="input-field" placeholder="123 Main Street" value="<?= old_value('address') ?>"required>
                </div>
                
                <div class="input-group-group">
                    <label for="city" class="input-label">City</label>
                    <select name="city" id="city" class="input-field" required>
                        <option value="" disabled selected>Select City</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Galle">Galle</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Negombo">Negombo</option>
                        <option value="Anuradhapura">Anuradhapura</option>
                        <option value="Ratnapura">Ratnapura</option>
                        <option value="Badulla">Badulla</option>
                        <option value="Batticaloa">Batticaloa</option>
                        <option value="Trincomalee">Trincomalee</option>
                        <option value="Polonnaruwa">Polonnaruwa</option>
                        <option value="Hambantota">Hambantota</option>
                        <option value="Kalutara">Kalutara</option>
                        <option value="Nuwara Eliya">Nuwara Eliya</option>
                        <option value="Ampara">Ampara</option>
                        <option value="Monaragala">Monaragala</option>
                        <option value="Vavuniya">Vavuniya</option>
                        <option value="Kilinochchi">Kilinochchi</option>
                        <option value="Mannar">Mannar</option>
                        <option value="Mullaitivu">Mullaitivu</option>
                        <option value="Puttalam">Puttalam</option>
                        <option value="Matale">Matale</option>
                        <option value="Gampaha">Gampaha</option>
                        <option value="Kegalle">Kegalle</option>
                        <option value="Matara">Matara</option>
                        <option value="Kurunegala">Kurunegala</option>
                        <option value="Chilaw">Chilaw</option>
                        <option value="Wattala">Wattala</option>
                        <option value="Dehiwala">Dehiwala</option>
                        <option value="Moratuwa">Moratuwa</option>
                        <option value="Panadura">Panadura</option>
                        <option value="Maharagama">Maharagama</option>
                        <option value="Homagama">Homagama</option>
                        <option value="Avissawella">Avissawella</option>
                        <option value="Peliyagoda">Peliyagoda</option>
                        <option value="Kadawatha">Kadawatha</option>
                        <option value="Nugegoda">Nugegoda</option>
                        <option value="Boralesgamuwa">Boralesgamuwa</option>
                        <option value="Kotikawatta">Kotikawatta</option>
                        <option value="Kelaniya">Kelaniya</option>
                        <option value="Ja-Ela">Ja-Ela</option>
                        <option value="Seeduwa">Seeduwa</option>
                        <option value="Katunayake">Katunayake</option>
                        <option value="Weligama">Weligama</option>
                        <option value="Hikkaduwa">Hikkaduwa</option>
                        <option value="Beruwala">Beruwala</option>
                        <option value="Ambalangoda">Ambalangoda</option>
                        <option value="Tangalle">Tangalle</option>
                        <option value="Tissamaharama">Tissamaharama</option>
                        <option value="Hatton">Hatton</option>
                        <option value="Nawalapitiya">Nawalapitiya</option>
                        <option value="Bandarawela">Bandarawela</option>
                        <option value="Ella">Ella</option>
                        <option value="Dambulla">Dambulla</option>
                        <option value="Sigiriya">Sigiriya</option>
                        <option value="Haputale">Haputale</option>
                        <option value="Pinnawala">Pinnawala</option>
                        <option value="Gampola">Gampola</option>
                        <option value="Balangoda">Balangoda</option>
                        <option value="Embilipitiya">Embilipitiya</option>
                        <option value="Kataragama">Kataragama</option>
                        <option value="Mawanella">Mawanella</option>
                        <option value="Chavakachcheri">Chavakachcheri</option>
                        <option value="Point Pedro">Point Pedro</option>
                        <option value="Valvettithurai">Valvettithurai</option>
                        <option value="Kilinochchi">Kilinochchi</option>
                        <option value="Mullaitivu">Mullaitivu</option>
                        <option value="Vavuniya">Vavuniya</option>
                        <option value="Mannar">Mannar</option>
                    </select>
                </div>
            </div>


            <div class="input-group-aligned">
                <!-- <button type="button" class="green btn" onclick="showSearchBox()">Add existing User</button> -->
                <button type="button" class="primary-btn" style="margin-bottom: 10px;" onclick="showBankDetails()">Next</button>
            </div>

            <div class="errors" 
                style="display: <?= !empty($errors) || !empty($message) ? 'block' : 'none'; ?>; 
                        background-color: <?= !empty($errors) ? '#f8d7da' : (!empty($message) ? '#b5f9a2' : '#f8d7da'); ?>;">
                <?php if (!empty($errors)): ?>
                    <p><?= $errors['email'] ?? '' ?></p>
                    <p><?= $errors['contact'] ?? '' ?></p>
                    <p><?= $errors['fname'] ?? '' ?></p>
                    <p><?= $errors['lname'] ?? '' ?></p>
                    <p><?= $errors['auth'] ?? '' ?></p>
                    <p><?= $errors['payment'] ?? '' ?></p>
                <?php elseif (!empty($message)): ?>
                    <p><?= $message ;  ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Bank Details View -->
        <div id="bankDetailsView" class="AddnewAgentform" style="display: none;">
            <div class="subpart-name"> Bank Details </div>
            <input type="hidden" name="find_agent" value="1">
            <div class="input-group-group">
                <label for="cardName" class="input-label">Name on Card</label>
                <input type="text" name="cardName" id="cardName" class="input-field" 
                    placeholder="John Doe" value="<?= old_value('email') ?>" required>
            </div>
            <div class="input-group-group">
                <label for="accountNo" class="input-label">Account Number</label>
                <input type="text" name="accountNo" id="accountNo" class="input-field" 
                    placeholder="123456789" value="<?= old_value('email') ?>" required>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label for="branch" class="input-label">Branch</label>
                    <select name="branch" id="branch" class="input-field" required>
                        <option value="" disabled selected>Select Branch</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Galle">Galle</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Kandana">Kandana</option>
                        <option value="Negombo">Negombo</option>
                        <option value="Kurunegala">Kurunegala</option>
                        <option value="Matara">Matara</option>
                        <option value="Anuradhapura">Anuradhapura</option>
                        <option value="Ratnapura">Ratnapura</option>
                        <option value="Badulla">Badulla</option>
                        <option value="Batticaloa">Batticaloa</option>
                        <option value="Trincomalee">Trincomalee</option>
                        <option value="Polonnaruwa">Polonnaruwa</option>
                        <option value="Hambantota">Hambantota</option>
                        <option value="Kalutara">Kalutara</option>
                        <option value="Nuwara Eliya">Nuwara Eliya</option>
                        <option value="Ampara">Ampara</option>
                        <option value="Monaragala">Monaragala</option>
                        <option value="Vavuniya">Vavuniya</option>
                        <option value="Kilinochchi">Kilinochchi</option>
                        <option value="Mannar">Mannar</option>
                        <option value="Mullaitivu">Mullaitivu</option>
                        <option value="Puttalam">Puttalam</option>
                        <option value="Matale">Matale</option>
                        <option value="Gampaha">Gampaha</option>
                        <option value="Kegalle">Kegalle</option>
                        <option value="Colombo">Colombo</option>
                        <option value="Kandy">Kandy</option>
                        <option value="Galle">Galle</option>
                        <option value="Jaffna">Jaffna</option>
                        <option value="Kandana">Kandana</option>
                    </select>
                </div>
                <div class="input-group-group">
                    <label for="bankName" class="input-label">Bank Name</label>
                    <select name="bankName" id="bankName" class="input-field" required>
                        <option value="" disabled selected>Select Bank</option>
                        <option value="Bank of Ceylon">Bank of Ceylon</option>
                        <option value="Commercial Bank">Commercial Bank</option>
                        <option value="Hatton National Bank">Hatton National Bank</option>
                        <option value="Sampath Bank">Sampath Bank</option>
                        <option value="People's Bank">People's Bank</option>
                        <option value="National Savings Bank">National Savings Bank</option>
                        <option value="DFCC Bank">DFCC Bank</option>
                        <option value="Seylan Bank">Seylan Bank</option>
                        <option value="Union Bank">Union Bank</option>
                        <option value="Nations Trust Bank">Nations Trust Bank</option>
                        <option value="Pan Asia Bank">Pan Asia Bank</option>
                        <option value="Cargills Bank">Cargills Bank</option>
                        <option value="Amana Bank">Amana Bank</option>
                        <option value="HSBC">HSBC</option>
                        <option value="Standard Chartered Bank">Standard Chartered Bank</option>
                        <option value="Citibank">Citibank</option>
                        <option value="ICICI Bank">ICICI Bank</option>
                        <option value="Axis Bank">Axis Bank</option>
                        <option value="Indian Bank">Indian Bank</option>
                        <option value="State Bank of India">State Bank of India</option>
                        <!-- Add more banks as needed -->
                    </select>
                </div>

            </div>
            <div class="input-group-aligned">
                <button type="button" class="secondary-btn" onclick="showPersonalDetails()">Back</button>
                <button type="submit" class="primary-btn">Submit</button>
            </div>

            <?php if (!empty($errors) && count($errors) > 0): ?>
                <div class="errors" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">
                    <p><?= $errors['email'] ?? '' ?></p>
                    <p><?= $errors['contact'] ?? '' ?></p>
                    <p><?= $errors['fname'] ?? '' ?></p>
                    <p><?= $errors['lname'] ?? '' ?></p>
                    <p><?= $errors['auth'] ?? '' ?></p>
                </div>
            <?php elseif (!empty($message)): ?>
                <div class="errors" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
                    <p><?= $message; ?></p>
                </div>
            <?php endif; ?>

        </div>
    </form>
    <?php 
        // show($errors);
        // show($message);
    ?>
</div>

<script>
    //loader effect
    function displayLoader() {
        document.querySelector('.loader-container').style.display = '';
        //onclick="displayLoader()"
    }
    
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', displayLoader);
    });

    document.querySelectorAll('a').forEach(link => {
        if (!link.getAttribute('href').startsWith('#')) {
            link.addEventListener('click', displayLoader);
        }
    });

    function showPersonalDetails() {
        document.getElementById('personalDetailsView').style.display = 'block';
        document.getElementById('bankDetailsView').style.display = 'none';
    }

    function showBankDetails() {
        document.getElementById('personalDetailsView').style.display = 'none';
        document.getElementById('bankDetailsView').style.display = 'block';
    }

    const districtOptions = {
        "Western": ["Colombo", "Gampaha", "Kalutara"],
        "Central": ["Kandy", "Matale", "Nuwara Eliya"],
        "Southern": ["Galle", "Matara", "Hambantota"],
        "Northern": ["Jaffna", "Kilinochchi", "Mullaitivu", "Vavuniya", "Mannar"],
        "Eastern": ["Trincomalee", "Batticaloa", "Ampara"],
        "North Western": ["Kurunegala", "Puttalam"],
        "North Central": ["Anuradhapura", "Polonnaruwa"],
        "Uva": ["Badulla", "Monaragala"],
        "Sabaragamuwa": ["Ratnapura", "Kegalle"]
    };

    const cityOptions = {
        "Colombo": ["Colombo", "Dehiwala-Mount Lavinia", "Moratuwa", "Battaramulla", "Sri Jayawardenepura Kotte", "Nugegoda", "Maharagama", "Kesbewa", "Kaduwela", "Kolonnawa", "Homagama", "Piliyandala", "Boralesgamuwa", "Malabe", "Pita Kotte", "Avissawella", "Padukka", "Hanwella"],
        "Gampaha": ["Negombo", "Gampaha", "Ja-Ela", "Wattala", "Ragama", "Kadawatha", "Minuwangoda", "Kiribathgoda", "Hendala", "Mabole", "Katunayake", "Kandana", "Nittambuwa", "Kirindiwela", "Biyagama", "Peliyagoda"],
        "Kalutara": ["Kalutara", "Beruwala", "Panadura", "Aluthgama", "Wadduwa", "Matugama", "Bandaragama", "Horana", "Ingiriya", "Bulathsinhala", "Agalawatta"],
        "Kandy": ["Kandy", "Peradeniya", "Gampola", "Katugastota", "Nawalapitiya", "Pilimathalawa", "Wattegama", "Akurana", "Digana", "Gelioya", "Kundasale", "Galagedara", "Hanguranketa", "Ampitiya"],
        "Matale": ["Matale", "Dambulla", "Sigiriya", "Ukuwela", "Rattota", "Galewela", "Nalanda", "Palapathwela", "Aluvihare", "Yatawatta"],
        "Nuwara Eliya": ["Nuwara Eliya", "Hatton", "Talawakele", "Maskeliya", "Pussellawa", "Kotagala", "Lindula", "Ragala", "Walapane", "Hanguranketha"],
        "Galle": ["Galle", "Hikkaduwa", "Unawatuna", "Ambalangoda", "Karapitiya", "Baddegama", "Bentota", "Balapitiya", "Elpitiya", "Imaduwa", "Batapola", "Ahangama", "Ahungalla"],
        "Matara": ["Matara", "Weligama", "Dickwella", "Akuressa", "Kamburupitiya", "Hakmana", "Deniyaya", "Mirissa", "Devinuwara", "Malimboda", "Morawaka"],
        "Hambantota": ["Hambantota", "Tangalle", "Tissamaharama", "Kataragama", "Ambalantota", "Beliatta", "Weeraketiya", "Lunugamvehera", "Sooriyawewa", "Angunukolapelessa"],
        "Jaffna": ["Jaffna", "Nallur", "Chavakachcheri", "Point Pedro", "Karainagar", "Velanai", "Valvettithurai", "Kopay", "Kaithady", "Manipay", "Tellippalai", "Chunnakam", "Uduvil"],
        "Kilinochchi": ["Kilinochchi", "Pallai", "Paranthan", "Karachchi", "Mulankavil", "Pooneryn", "Kandavalai"],
        "Mullaitivu": ["Mullaitivu", "Puthukudiyiruppu", "Oddusuddan", "Thunukkai", "Mallavi", "Mankulam"],
        "Vavuniya": ["Vavuniya", "Cheddikulam", "Nedunkeni", "Omanthai"],
        "Mannar": ["Mannar", "Adampan", "Nanattan", "Musali", "Madhu"],
        "Trincomalee": ["Trincomalee", "Kinniya", "Mutur", "Kantale", "Nilaveli", "China Bay", "Seruwila", "Thampalakamam", "Kuchchaveli", "Gomarankadawala"],
        "Batticaloa": ["Batticaloa", "Eravur", "Valachchenai", "Kalkudah", "Oddamavadi", "Vakarai", "Kattankudy", "Chenkalady", "Araipattai"],
        "Ampara": ["Ampara", "Kalmunai", "Sammanthurai", "Dehiattakandiya", "Uhana", "Pottuvil", "Akkaraipattu", "Sainthamaruthu", "Thirukkovil", "Nintavur", "Addalachchenai", "Mahaoya"],
        "Kurunegala": ["Kurunegala", "Kuliyapitiya", "Maho", "Polgahawela", "Pannala", "Narammala", "Nikaweratiya", "Wariyapola", "Ibbagamuwa", "Alawwa", "Giriulla", "Bingiriya"],
        "Puttalam": ["Puttalam", "Chilaw", "Wennappuwa", "Anamaduwa", "Nattandiya", "Dankotuwa", "Kalpitiya", "Marawila", "Madampe", "Arachchikattuwa", "Norochcholai"],
        "Anuradhapura": ["Anuradhapura", "Kekirawa", "Medawachchiya", "Mihintale", "Thambuttegama", "Eppawala", "Kahatagasdigiliya", "Galenbindunuwewa", "Horowpothana", "Kebithigollewa", "Rambewa", "Thalawa"],
        "Polonnaruwa": ["Polonnaruwa", "Kaduruwela", "Hingurakgoda", "Medirigiriya", "Dimbulagala", "Manampitiya", "Lankapura", "Elahera", "Bakamuna", "Jayanthipura"],
        "Badulla": ["Badulla", "Bandarawela", "Ella", "Hali-Ela", "Welimada", "Mahiyanganaya", "Diyatalawa", "Haputale", "Passara", "Lunugala", "Uva-Paranagama", "Kandaketiya"],
        "Monaragala": ["Monaragala", "Wellawaya", "Bibile", "Buttala", "Kataragama", "Siyambalanduwa", "Thanamalvila", "Badalkumbura", "Madulla"],
        "Ratnapura": ["Ratnapura", "Balangoda", "Embilipitiya", "Pelmadulla", "Kuruwita", "Eheliyagoda", "Kalawana", "Kahawatta", "Rakwana", "Opanayaka", "Godakawela", "Nivithigala"],
        "Kegalle": ["Kegalle", "Mawanella", "Warakapola", "Rambukkana", "Galigamuwa", "Deraniyagala", "Yatiyantota", "Ruwanwella", "Dehiowita", "Aranayaka", "Hemmathagama"]
    };

    document.getElementById('province').addEventListener('change', function () {
        const province = this.value;
        const districtSelect = document.getElementById('district');
        const citySelect = document.getElementById('city');

        // Update districts based on selected province
        districtSelect.innerHTML = '<option value="" disabled selected>Select District</option>';
        if (province in districtOptions) {
            districtOptions[province].forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }

        // Clear city dropdown
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
    });

    document.getElementById('district').addEventListener('change', function () {
        const district = this.value;
        const citySelect = document.getElementById('city');

        // Update cities based on selected district
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        if (district in cityOptions) {
            cityOptions[district].forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        }
    });
</script>


<?php require_once 'agentFooter.view.php'; ?>