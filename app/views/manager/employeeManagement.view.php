<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Employee Management </h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input 
                type="text" 
                class="search-input" 
                name="searchterm" 
                value="<?= isset($_GET['searchterm']) ? esc($_GET['searchterm']) : "" ?>" 
                placeholder="Search Employee ..."
            >
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>

<div class="content_wrapper" id='formContainer'>
    <div class="employee-details-container">
        <table class="listing-table-for-customer-payments">
            <thead>
                <tr>
                    <th class='first' style='max-width: 35px;' id="date-header">ID</th>
                    <th style='max-width: 20%;'>Name</th>
                    <th style='max-width: 23%;'>Email</th>
                    <th style='max-width: 15%;'>NIC</th>
                    <th style='min-width: 75px;' class="sortable" id="user-type-header">
                        User Type
                        <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                    </th>
                    <th style='width: 5%;'>Image</th>
                    <th class='last' style='width: 5%;'>Status</th>
                    <th hidden>Reset Code</th>
                    <th hidden>Contact</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (isset($userlist) && count($userlist) > 0) {
                        foreach ($userlist as $user) {
                            echo "<tr onclick='showUserDetailBox(this)'>";
                            echo "<td class='first'><input type='text' name='id' value='{$user->pid}' disabled></td>";
                            echo "<td><input type='text' name='name' value='{$user->fname} {$user->lname}' disabled></td>";
                            echo "<td><input type='email' name='email' value='{$user->email}' disabled></td>";
                            echo "<td><input type='text' name='nic' value='{$user->nic}' disabled></td>";
                            echo '<td><button class=" ';
                            switch ($user->user_lvl) {
                                case 4: echo 'manager_button">Manager'; break;
                                case 3: echo 'agent_button">Agent'; break;
                                case 2: echo 'serviceprovider_button">Service Provider'; break;
                                case 1: echo 'owner_button">Owner'; break;
                                case 0: echo 'customer_button">Customer'; break;
                                default: echo '_button">Unknown'; break;
                            }
                            echo "</button></td>";
                            echo "<td><img class='header-profile-picture' style='margin:0px' src='".get_img($user->image_url)."'></td>";
                            echo "<td class='last'><input type='text' name='AccountStatus' value='" . ($user->AccountStatus ? 'Active' : 'Inactive') . "' style='color:" . ($user->AccountStatus ? "var(--green-color)" : "var(--red-color)") . ";' disabled></td>";
                            echo "<td hidden><input type='text' value='" . (empty($user->reset_code) ? "---" : $user->reset_code) . "' disabled></td>";
                            echo "<td hidden><input type='text' value='{$user->contact}' disabled></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No records found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <?php echo $paginationLinks; ?>
    </div>
</div>

<div id="searchUserForm" style="display: none">
    <form id="find-user" method="post">
        <input type="hidden" name="action" value="update_user">
        <div class="SearchBox">
            <button class="close_btn" id="close-btn" onclick="removeUserDetailBox(event)">X</button>
            <div class="input-group">
                <div class="user-details left_details">
                    <div class="input-group">
                        <div class="input-group-group">
                            <label for="popup-id" class="input-label">ID:</label>
                            <input id="popup-id" type="text" class="input-field" disabled>
                        </div>
                        <div class="input-group-group">
                            <label for="popup-status" class="input-label">Account Status:</label>
                            <input id="popup-status" type="text" class="input-field" disabled>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-group-group">
                            <label for="popup-fname" class="input-label">First Name:</label>
                            <input id="popup-fname" type="text" class="input-field" disabled>
                        </div>
                        <div class="input-group-group">
                            <label for="popup-lname" class="input-label">Last Name:</label>
                            <input id="popup-lname" type="text" class="input-field" disabled>
                        </div>
                    </div>

                    <label for="popup-email" class="input-label">Email:</label>
                    <input id="popup-email" type="email" class="input-field" disabled>
                    
                    <div class="input-group">
                        <div class="input-group-group">
                            <label for="popup-nic" class="input-label">NIC:</label>
                            <input id="popup-nic" type="text" class="input-field" disabled>
                        </div>
                        <div class="input-group-group">
                            <label for="popup-contact" class="input-label">Contact:</label>
                            <input id="popup-contact" type="text" class="input-field" disabled>
                        </div>
                    </div>
                    <div class="input-group-group">
                        <label for="popup-reset-code" class="input-label">Reset Code:</label>
                        <input id="popup-reset-code" type="text" class="input-field" disabled>
                    </div>
                </div>
                <div class="user-details right_details">
                    <img src="" class="profile_pic_large" id="popup-profile-pic">
                    <div class="input-group-aligned">
                        <button type="button" class="secondary-btn" id="edit-btn" style="margin-top: 0px;" onclick="makeEditable()">Edit</button>
                        <button type="button" class="red btn" id="delete-btn" onclick="makeEditable()">Delete</button>
                        <button type="button" class="green btn" id="save-btn" onclick="saveChanges()" style="display: none;">Save</button>
                        <button type="button" class="red btn" id="cancel-btn" onclick="cancelChanges()" style="display: none;">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="loader-container" style="display: none;">
        <div class="spinner-loader"></div>
    </div>
</div>

<script>
    let isDateAscending = true;
    let isUserTypeAscending = true;
    let initialData = {};

    function showUserDetailBox(row) {
        const cells = row.querySelectorAll('td');
        
        //Get values from table cells
        document.getElementById('popup-id').value = cells[0].querySelector('input').value;
        
        //Split name into first and last name
        const fullName = cells[1].querySelector('input').value;
        const names = fullName.split(' ');
        document.getElementById('popup-fname').value = names[0];
        document.getElementById('popup-lname').value = names[1] || '';
        
        // Get other values
        document.getElementById('popup-email').value = cells[2].querySelector('input').value;
        document.getElementById('popup-nic').value = cells[3].querySelector('input').value;
        document.getElementById('popup-profile-pic').src = cells[5].querySelector('img').src;
        document.getElementById('popup-status').value = cells[6].querySelector('input').value;
        document.getElementById('popup-status').style.color = cells[6].querySelector('input').style.color;
        document.getElementById('popup-reset-code').value = cells[7].querySelector('input').value;
        document.getElementById('popup-contact').value = cells[8].querySelector('input').value;
        
        // Show form and blur background
        document.getElementById('searchUserForm').style.display = 'block';
        document.getElementById('formContainer').classList.add('blurred-background');
        
        if (document.getElementById('popup-status').value === 'Active') {
            document.getElementById('popup-status').style.color = 'var(--green-color)';
        } else {
            document.getElementById('popup-status').style.color = 'var(--red-color)';
        }
    }

    function removeUserDetailBox(event) {
        event.preventDefault();
        cancelChanges();
        document.getElementById('searchUserForm').style.display = 'none';
        document.getElementById('formContainer').classList.remove('blurred-background');
    }

    function makeEditable() {
        // Save initial data
        initialData = {
            pid: document.getElementById('popup-id').value,
            fname: document.getElementById('popup-fname').value,
            lname: document.getElementById('popup-lname').value,
            email: document.getElementById('popup-email').value,
            nic: document.getElementById('popup-nic').value,
            status: document.getElementById('popup-status').value,
            resetCode: document.getElementById('popup-reset-code').value,
            contact: document.getElementById('popup-contact').value
        };

        // Enable editing
        document.querySelectorAll('#searchUserForm .input-field').forEach(field => {
            if (field.id !== 'popup-id' && field.id !== 'popup-status') {
                field.disabled = false;
            }
        });

        // Toggle buttons
        document.getElementById('edit-btn').style.display = 'none';
        document.getElementById('delete-btn').style.display = 'none';
        document.getElementById('save-btn').style.display = 'inline-block';
        document.getElementById('cancel-btn').style.display = 'inline-block';
    }

    function cancelChanges() {
        // Restore initial values
        document.getElementById('popup-id').value = initialData.pid;
        document.getElementById('popup-fname').value = initialData.fname;
        document.getElementById('popup-lname').value = initialData.lname;
        document.getElementById('popup-email').value = initialData.email;
        document.getElementById('popup-nic').value = initialData.nic;
        document.getElementById('popup-status').value = initialData.status;
        document.getElementById('popup-reset-code').value = initialData.resetCode;
        document.getElementById('popup-contact').value = initialData.contact;

        // Disable editing
        document.querySelectorAll('#searchUserForm .input-field').forEach(field => {
            field.disabled = true;
        });

        // Toggle buttons
        document.getElementById('edit-btn').style.display = 'inline-block';
        document.getElementById('delete-btn').style.display = 'inline-block';
        document.getElementById('save-btn').style.display = 'none';
        document.getElementById('cancel-btn').style.display = 'none';
    }

    function saveChanges() {
        // Create hidden inputs for all the data
        const form = document.getElementById('find-user');
        
        const hiddenInputs = {
            'pid': document.getElementById('popup-id').value,
            'fname': document.getElementById('popup-fname').value,
            'lname': document.getElementById('popup-lname').value,
            'email': document.getElementById('popup-email').value,
            'nic': document.getElementById('popup-nic').value,
            'status': document.getElementById('popup-status').value,
            'reset_code': document.getElementById('popup-reset-code').value,
            'contact': document.getElementById('popup-contact').value,
        };

        // Add or update hidden inputs only if the value has changed
        let fieldCount = 0;
        console.log("count is", fieldCount);
        for(let name in hiddenInputs) {
            if(hiddenInputs[name] !== initialData[name] || name === 'pid') {
                let input = form.querySelector(`input[name="${name}"]`);
                if(name == 'reset_code' && hiddenInputs['reset_code'] === '---') {
                    continue;
                }
                if(!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    form.appendChild(input);
                }
                input.value = hiddenInputs[name];
                fieldCount++;
            }
        }
        fieldCount--;
        console.log("count is", fieldCount);
        if(fieldCount > 0){
            // Submit the form
            form.submit();
        }else{
            cancelChanges();
        }
    }

    document.getElementById('date-header').addEventListener('click', function() {
        sortTableByDate(isDateAscending);
        isDateAscending = !isDateAscending;
    });

    document.getElementById('user-type-header').addEventListener('click', function() {
        sortTableByUserType(isUserTypeAscending);
        isUserTypeAscending = !isUserTypeAscending;
    });

    function sortTableByDate(isAscending) {
        const rows = Array.from(document.querySelectorAll('.listing-table-for-customer-payments tbody tr'));
        rows.sort((a, b) => {
            const dateA = new Date(a.querySelector('td:first-child input').value);
            const dateB = new Date(b.querySelector('td:first-child input').value);
            return isAscending ? dateA - dateB : dateB - dateA;
        });
        const tbody = document.querySelector('.listing-table-for-customer-payments tbody');
        rows.forEach(row => tbody.appendChild(row));
    }

    function sortTableByUserType(isAscending) {
        const rows = Array.from(document.querySelectorAll('.listing-table-for-customer-payments tbody tr'));
        rows.sort((a, b) => {
            const textA = a.querySelector('td:nth-child(5) button').textContent.trim().toUpperCase();
            const textB = b.querySelector('td:nth-child(5) button').textContent.trim().toUpperCase();
            return isAscending ? textA.localeCompare(textB) : textB.localeCompare(textA);
        });
        const tbody = document.querySelector('.listing-table-for-customer-payments tbody');
        rows.forEach(row => tbody.appendChild(row));
    }

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
</script>
<?php show($_POST); ?>
<?php require_once 'managerFooter.view.php'; ?>