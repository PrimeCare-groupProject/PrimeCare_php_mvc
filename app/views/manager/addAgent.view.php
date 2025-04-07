<?php require_once 'managerHeader.view.php'; 
?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement'>
        <button class="back-btn">
            <img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons">
        </button>
    </a>
    <h2>Add Agent</h2>
</div>

<div id="formContainer">
    <!-- Consolidated Form -->
    <form id="addAgentForm" method="post" action="<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent" class="form_wrapper manager_form_wrapper">
        <!-- Personal Details View -->
        <input type="hidden" name="add_agent" value="1">
        <div id="personalDetailsView" class="AddnewAgentform">
            <div class="input-group-group">
                <label for="email" class="input-label">Email Address</label>
                <input type="email" name="email" id="email" class="input-field" 
                    placeholder="johndoe@gmail.com" required>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label for="phoneNo" class="input-label">Contact Number</label>
                    <input type="text" name="contact" id="phoneNo" class="input-field" 
                        placeholder="076XXXXXXX" required>
                </div>
                <div class="input-group-group">
                    <label for="nic" class="input-label">NIC</label>
                    <input type="text" name="nic" id="nic" class="input-field" 
                        placeholder="20020690111" required>
                </div>
            </div>
            <div class="input-group">
                <div class="input-group-group">
                    <label for="fname" class="input-label">First Name</label>
                    <input type="text" name="fname" id="fname" class="input-field" 
                        placeholder="John" required>
                </div>
                <div class="input-group-group">
                    <label for="lname" class="input-label">Last Name</label>
                    <input type="text" name="lname" id="lname" class="input-field" 
                        placeholder="Doe" required>
                </div>
            </div>

            <div class="input-group-aligned">
                <button type="button" class="green btn" onclick="showSearchBox()">Add existing User</button>
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
            <input type="hidden" name="find_agent" value="1">
            <div class="input-group-group">
                <label for="cardName" class="input-label">Name on Card</label>
                <input type="text" name="cardName" id="cardName" class="input-field" 
                    placeholder="John Doe" required>
            </div>
            <div class="input-group-group">
                <label for="accountNo" class="input-label">Account Number</label>
                <input type="text" name="accountNo" id="accountNo" class="input-field" 
                    placeholder="123456789" required>
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

<!-- floating search box -->
<div id="searchUserForm" style="display: none; height: 600px;">
    <form id="find-user" method="get" action="<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent">
        <input type="hidden" name="find_user" value="1">
        <div class="SearchBox">
            <button class="close_btn" id="close-btn" onclick="removeSearchBox(event)">X</button>
            <div class="flex-bar">
                <div name="search-bar" class="search-container">
                    <input 
                        type="text" 
                        class="search-input" 
                        name="searchterm" 
                        value="<?= isset($_GET['searchterm']) ? esc($_GET['searchterm']) : "" ?>" 
                        placeholder="Search user..."
                    >
                    <button class="search-btn" type="submit" onclick="updateSearchTerm()">
                        <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
                    </button>
                </div>
            </div>
            <div class="employee-details-container">
                <table class="listing-table-for-customer-payments">
                    <thead>
                        <tr>
                            <th>Created Date</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="last">Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if (isset($userlist) && count($userlist) > 0) {
                                foreach ($userlist as $user) {
                                    echo "<tr>
                                        <td>" . date('Y-m-d H:i:s', strtotime($user->created_date)) . "</td>
                                        <td>{$user->pid}</td>
                                        <td>{$user->fname} {$user->lname}</td>
                                        <td>{$user->email}</td>
                                        <td><img class='header-profile-picture' src=" . get_img($user->image_url) . "></td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No users found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<div class="loader-container" style="display: none;">
    <div class="spinner-loader"></div>
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

    function updateSearchTerm() {
        var searchTerm = document.querySelector('input[name="searchterm"]').value;
        var form = document.getElementById('find-user');
        form.action = '<?= ROOT ?>/dashboard/managementhome/agentmanagement/finduser?searchterm=' + encodeURIComponent(searchTerm);
        form.submit();
    }
    function showPersonalDetails() {
        document.getElementById('personalDetailsView').style.display = 'block';
        document.getElementById('bankDetailsView').style.display = 'none';
    }

    function showBankDetails() {
        document.getElementById('personalDetailsView').style.display = 'none';
        document.getElementById('bankDetailsView').style.display = 'block';
    }

    function showSearchBox() {
        document.getElementById('searchUserForm').style.display = 'block';
        document.getElementById('formContainer').classList.add('blurred-background');
    }

    function removeSearchBox(event) {
        event.preventDefault();
        document.getElementById('searchUserForm').style.display = 'none';
        document.getElementById('formContainer').classList.remove('blurred-background');
    }
</script>

<?php require_once 'managerFooter.view.php'; ?>
