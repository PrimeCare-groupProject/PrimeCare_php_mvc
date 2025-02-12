<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/manageProviders/serviceproviders'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Add Service Provider</h2>
</div>

<!-- <form method="POST" action="your_php_file.php" enctype="multipart/form-data">
    <div class="owner-addProp-container">
        <div class="owner-addProp-form-left">
            <label class="input-label">First Name</label>
            <input type="text" name="firstname" placeholder="Enter First Name" class="input-field" required>

            <label class="input-label">Last Name</label>
            <input type="text" name="lastname" placeholder="Enter Last Name" class="input-field" required>

            <label class="input-label">Date Of Birth</label>
            <input type="date" name="date" placeholder="Enter the Date" class="input-field" required>

            <label class="input-label">Gender</label>
            <input type="text" name="gender" placeholder="Male/Female" class="input-field" required>

            <label class="input-label">Contact Number</label>
            <input type="text" name="primarycontactnumber" placeholder="Enter Primary Contact Number" class="input-field" required>

            <label class="input-label">Contact Number(Secondary)</label>
            <input type="text" name="secondarycontactnumber" placeholder="Enter Secondary Contact Number" class="input-field" required>

            <label class="input-label">Email Address</label>
            <input type="email" name="email" placeholder="Enter Email Address" class="input-field" required>

            <label class="input-label">Bank Account Number</label>
            <input type="text" name="email" placeholder="Enter Bank Account Number" class="input-field" required>

            <label class="input-label">Address</label>
            <textarea name="address" placeholder="Enter the address" class="input-field" required></textarea>

        </div>

        <div class="owner-addProp-form-right">
            <label class="input-label">Marital Status</label>
            <input type="text" name="maritalstatus" placeholder="Married/Unmarried" class="input-field" required>

            <label class="input-label">NIC Number</label>
            <input type="text" name="NIC Number" placeholder="Enter the NIC Number" class="input-field" required>

            <label class="input-label">Upload Profile Image</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>

            <label class="input-label">Resume/CV</label>
            <div class="owner-addProp-file-upload">
                <input type="file" name="property_image[]" id="property_image" class="input-field" multiple required>
                <div class="owner-addProp-upload-area">
                    <img src="<?= ROOT ?>/assets/images/upload.png" alt="Nah bro" class="owner-addProp-upload-logo">
                    <p class="upload-area-no-margin">Drop your files here</p>
                    <button type="button" class="primary-btn" onclick="document.getElementById('property_image').click()">Choose File</button>
                </div>
            </div>

            <div class="buttons-to-right">
                <button type="submit" class="primary-btn">Submit</button>
            </div>
        </div>
    </div>
</form> -->


<div id="formContainer">
    <!-- Consolidated Form -->
    <form id="addAgentForm" method="post" action="<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent" class="form_wrapper manager_form_wrapper">
        <!-- Personal Details View -->
        <input type="hidden" name="add_agent" value="1">
        <div id="personalDetailsView" class="AddnewAgentform">
            <div class="input-group-group">
                <label for="email" class="input-label">Email Address</label>
                <input type="email" name="email" id="email" class="input-field" 
                    placeholder="johndoe@gmail.com" value="king.ed.wendt888@gmail.com" required>
            </div>

            <div class="input-group-group">
                <label for="phoneNo" class="input-label">Contact Number</label>
                <input type="text" name="contact" id="phoneNo" class="input-field" 
                    placeholder="076XXXXXXX" value="0762213874" required>
            </div>

            <div class="input-group">
                <div class="input-group-group">
                    <label for="fname" class="input-label">First Name</label>
                    <input type="text" name="fname" id="fname" class="input-field" 
                        placeholder="John" value="john" required>
                </div>
                <div class="input-group-group">
                    <label for="lname" class="input-label">Last Name</label>
                    <input type="text" name="lname" id="lname" class="input-field" 
                        placeholder="Doe" value="doe" required>
                </div>
            </div>

            <div class="input-group-aligned">
                <button type="button" class="green btn" onclick="showSearchBox()">Add existing User</button>
                <button type="button" class="primary-btn" onclick="showBankDetails()">Next</button>
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
                    placeholder="John Doe" value="John Doe" required>
            </div>
            <div class="input-group-group">
                <label for="accountNo" class="input-label">Account Number</label>
                <input type="text" name="accountNo" id="accountNo" class="input-field" 
                    placeholder="123456789" value="123456789" required>
            </div>
            <div class="input-group-group">
                <label for="branch" class="input-label">Branch</label>
                <input type="text" name="branch" id="branch" class="input-field" 
                    placeholder="Main Branch" value="1" required>
            </div>
            <div class="input-group-group">
                <label for="bankName" class="input-label">Bank Name</label>
                <input type="text" name="bankName" id="bankName" class="input-field" 
                    placeholder="ABC Bank" value="1" required>
            </div>

            <div class="input-group-aligned">
                <button type="button" class="secondary-btn" onclick="showPersonalDetails()">Back</button>
                <button type="submit" class="primary-btn">Submit</button>
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
                <?php elseif (!empty($message)): ?>
                    <p><?= $message ;  ?></p>
                <?php endif; ?>
            </div>

        </div>
    </form>
</div>

<!-- floating search box -->
<div id="searchUserForm" style="display: none; height: 600px;">
    <form id="find-user" method="post">
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
                    <button class="search-btn" type="submit">
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

<script>
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


<?php require_once 'agentFooter.view.php'; ?>