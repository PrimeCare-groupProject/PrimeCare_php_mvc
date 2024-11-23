<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement'>
        <button class="back-btn">
            <img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons">
        </button>
    </a>
    <h2>Add Agent</h2>
</div>

<div id="formContainer">
    <!-- Personal Details View -->
    <div id="personalDetailsView" class="AddnewAgentform">
        <form id="personalDetailsForm" class="form_wrapper">
            <div class="input-group-group">
                <label for="email" class="input-label">Email Address</label>
                <input type="email" name="email" id="email" class="input-field" 
                    placeholder="johndoe@gmail.com" required>
            </div>

            <div class="input-group-group">
                <label for="phoneNo" class="input-label">Contact Number</label>
                <input type="text" name="contact" id="phoneNo" class="input-field" 
                    placeholder="076XXXXXXX" required>
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
                <button type="button" class="primary-btn" onclick="validateAndProceed()">Next</button>
            </div>

            <div class="errors" style="display: <?= !empty($user->errors) ? 'block' : 'none'; ?>">
                <p><?= $user->errors['auth'] ?? '' ?></p>
            </div>
            <div class="success" style="display: <?= isset($success) ? 'block' : 'none'; ?>">
                <p><?= $success ?></p>
            </div>
        </form>
        
    </div>

    
    <!-- Bank Details View -->
    <div id="bankDetailsView" class="AddnewAgentform" style="display: none;">
        <form id="bankDetailsForm" class="form_wrapper">
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
            <div class="input-group-group">
                <label for="branch" class="input-label">Branch</label>
                <input type="text" name="branch" id="branch" class="input-field" 
                    placeholder="Main Branch" required>
            </div>
            <div class="input-group-group">
                <label for="bankName" class="input-label">Bank Name</label>
                <input type="text" name="bankName" id="bankName" class="input-field" 
                    placeholder="ABC Bank" required>
            </div>

            <div class="input-group-aligned">
                <button type="button" class="secondary-btn" onclick="showPersonalDetails()">Back</button>
                <button type="button" class="primary-btn" onclick="submitForms()">Submit</button>
            </div>
            <div class="errors" style="display: <?= !empty($user->errors) ? 'block' : 'none'; ?>">
                <p><?= $user->errors['auth'] ?? '' ?></p>
            </div>
            <div class="success" style="display: <?= isset($success) ? 'block' : 'none'; ?>">
                <p><?= $success ?></p>
            </div>
        </form>
    </div>
</div>

<!-- floating search box-->
<div id="searchUserForm" style="display: none;">
    <form id="find-user" method="post" >
        <input type="hidden" name="find_user" value="1">
        <div class="SearchBox" >
            <button class="close_btn" id="close-btn" onclick="removeSearchBox(event)"><!-- searchbar -->
                X
            </button>
            <div class="flex-bar"><!-- searchbar -->
                <div name='search-bar' class="search-container" method="GET">
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
                <table class="listing-table-for-customer-payments"><!-- table -->
                    <thead >
                        <tr style="width: 100%;">
                            <th style='width: 100%;' class="extra-space sortable first" id="date-header">
                                Created Date
                                <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                            </th>
                            <th style='width: 100%;'>ID</th>
                            <th style='width: 100%;'>Name</th>
                            <th style='width: 100%;'>Email</th>
                            <th style='width: 100%;'class="last">Image</th>
                            <th hidden>Reset code</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(isset($userlist) && count($userlist) > 0){
                                foreach ($userlist as $user) {
                                    echo "<tr>";
                                    echo "<td class='first'>" . ($user->created_date ? date('Y-m-d H:i:s', strtotime($user->created_date)) : "-") . "</td>";
                                    echo "<td>{$user->pid}</td>";
                                    echo "<td>{$user->fname} {$user->lname}</td>";
                                    echo "<td>{$user->email}</td>";
                                    echo "<td class='last' ><img class='header-profile-picture' style='margin:0px' src=".get_img($user->image_url)."></td>";
                                    echo "</tr>";
                                }
                            }else{
                                echo "<tr>";
                                echo "<td class='first'> ---</td>";
                                echo "<td> ---</td>";
                                echo "<td> --- </td>";
                                echo "<td> --- </td>";
                                echo "<td class='last' > --- </td>";
                                echo "<td hidden> --- </td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script>
    // Validate personal details and proceed to the next step
    function validateAndProceed() {
        const form = document.getElementById('personalDetailsForm');
        if (form.checkValidity()) {//check validity isa a build  function to check validity
            personalData = {
                email: document.getElementById('email').value,
                contact: document.getElementById('phoneNo').value,
                fname: document.getElementById('fname').value,
                lname: document.getElementById('lname').value,
            };
            showBankDetails();
        } else {
            form.reportValidity();
        }
    }

    function showPersonalDetails() {
        document.getElementById('personalDetailsView').style.display = 'block';
        document.getElementById('bankDetailsView').style.display = 'none';
        // Restore personal data
        if (personalData.email) {
            document.getElementById('email').value = personalData.email;
            document.getElementById('phoneNo').value = personalData.contact;
            document.getElementById('fname').value = personalData.fname;
            document.getElementById('lname').value = personalData.lname;
        }
    }

    function showBankDetails() {
        document.getElementById('personalDetailsView').style.display = 'none';
        document.getElementById('bankDetailsView').style.display = 'block';
    }

    // Submit forms
    function submitForms() {
        const bankForm = document.getElementById('bankDetailsForm');
        if (bankForm.checkValidity()) {
            const formData = new FormData();
            Object.keys(personalData).forEach(key => {
                formData.append(key, personalData[key]);
            });
            formData.append('cardName', document.getElementById('cardName').value);
            formData.append('accountNo', document.getElementById('accountNo').value);
            formData.append('branch', document.getElementById('branch').value);
            formData.append('bankName', document.getElementById('bankName').value);

            fetch('<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent/submit', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '<?= ROOT ?>/dashboard/managementhome/agentmanagement';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the form');
            });
        } else {
            bankForm.reportValidity();
        }
    }

    // Search Box Controls
    function showSearchBox() {
        document.getElementById('searchUserForm').style.display = 'block';
        document.getElementById('formContainer').classList.add('blurred-background');
    }

    function removeSearchBox(event) {
        event.preventDefault(); // Prevent page refresh
        document.getElementById('searchUserForm').style.display = 'none';
        document.getElementById('formContainer').classList.remove('blurred-background');
    }

    // Table Sorting
    function sortTableByDate(isAscending) {
        const rows = Array.from(document.querySelectorAll('.listing-table-for-customer-payments tbody tr'));
        rows.sort((a, b) => {
            const dateA = new Date(a.querySelector('td:nth-child(1)').textContent);
            const dateB = new Date(b.querySelector('td:nth-child(1)').textContent);
            return isAscending ? dateA - dateB : dateB - dateA;
        });

        const tbody = document.querySelector('.listing-table-for-customer-payments tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }

    document.getElementById('date-header').addEventListener('click', function () {
        const isDateAscending = !this.classList.contains('ascending');
        sortTableByDate(isDateAscending);
        this.classList.toggle('ascending', isDateAscending); // Update sorting class
    });

</script>

<?php require_once 'managerFooter.view.php'; ?>
