<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Agent Management </h2>
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
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add New Agent</span>
        </div>
    </div>
</div>

<div class="content_wrapper" id='formContainer'>
    <div class="employee-details-container">
        <table class="listing-table-for-customer-payments">
            <?php if (isset($userlist) && is_array($userlist) && count($userlist) > 0) : ?>
                <thead>
                    <tr>
                        <th class='first' style='max-width: 35px;' id="date-header">ID</th>
                        <th style='max-width: 20%;'>Name</th>
                        <th style='max-width: 30%;'>Email</th>
                        <th style='max-width: 60px'>NIC</th>
                        <th style='min-width: 60px;' class="sortable" id="user-type-header">
                            User Type
                            <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                        </th>
                        <th style='width: 5%;'>Image</th>
                        <th class='last' style='width: 35px;'>Status</th>
                        <th hidden>Reset Code</th>
                        <th hidden>Contact</th>
                    </tr>
                </thead>
            <?php endif ?>
                
            <tbody>
                <?php 
                    if (isset($userlist) && is_array($userlist) && count($userlist) > 0) {
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
                            echo "<td class='last'><input type='text' name='AccountStatus' value='" . ($user->AccountStatus ==  0 ? 'Inactive' : ($user->AccountStatus == -1 ? 'Blocked' : 'Active')) . "' style='color:" . ($user->AccountStatus == 1 ? "var(--green-color)" : "var(--red-color)") . ";' disabled></td>";
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
                        <button type="button" class="red btn" id="delete-btn" onclick="deleteAccount()">Delete</button>
                        
                        <button type="button" class="green btn" id="save-btn" onclick="saveChanges()" style="display: none;">Save</button>
                        <button type="button" class="green btn" id="unblock-btn" onclick="unblockAccount()" style="display: none;">Unblock</button>
                        <button type="button" class="red btn" id="block-btn" onclick="blockAccount()" style="display: none;">Block</button>
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
    // Form elements
    const searchUserForm = document.getElementById('searchUserForm');
    const formContainer = document.getElementById('formContainer');
    const findUserForm = document.getElementById('find-user');
    const loaderContainer = document.querySelector('.loader-container');

    // Popup form fields
    const popupId = document.getElementById('popup-id');
    const popupFname = document.getElementById('popup-fname');
    const popupLname = document.getElementById('popup-lname');
    const popupEmail = document.getElementById('popup-email');
    const popupNic = document.getElementById('popup-nic');
    const popupProfilePic = document.getElementById('popup-profile-pic');
    const popupStatus = document.getElementById('popup-status');
    const popupResetCode = document.getElementById('popup-reset-code');
    const popupContact = document.getElementById('popup-contact');

    // Buttons
    const blockBtn = document.getElementById('block-btn');
    const unblockBtn = document.getElementById('unblock-btn');
    const editBtn = document.getElementById('edit-btn');
    const deleteBtn = document.getElementById('delete-btn');
    const saveBtn = document.getElementById('save-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    let isDateAscending = true;
    let isUserTypeAscending = true;
    let initialData = {};

    function showUserDetailBox(row) {
        const cells = row.querySelectorAll('td');
        
        // Get values from table cells
        popupId.value = cells[0].querySelector('input').value;
        
        // Split name into first and last name
        const fullName = cells[1].querySelector('input').value;
        const names = fullName.split(' ');
        popupFname.value = names[0];
        popupLname.value = names[1] || '';
        
        // Get other values
        popupEmail.value = cells[2].querySelector('input').value;
        popupNic.value = cells[3].querySelector('input').value;
        popupProfilePic.src = cells[5].querySelector('img').src;
        popupStatus.value = cells[6].querySelector('input').value;
        popupStatus.style.color = cells[6].querySelector('input').style.color;
        popupResetCode.value = cells[7].querySelector('input').value;
        popupContact.value = cells[8].querySelector('input').value;
        
        // Show form and blur background
        searchUserForm.style.display = 'block';
        formContainer.classList.add('blurred-background');
        
        const accountStatus = popupStatus.value;
        
        if (accountStatus === 'Active') {
            popupStatus.style.color = 'var(--green-color)';
            editBtn.style.display = 'inline-block';
            deleteBtn.style.display = 'inline-block';

            blockBtn.style.display = 'none';
            unblockBtn.style.display = 'none';
            saveBtn.style.display = 'none';

        } else if (accountStatus === 'Blocked') {
            popupStatus.style.color = 'var(--red-color)';
            unblockBtn.style.display = 'inline-block';
            editBtn.style.display = 'inline-block';

            deleteBtn.style.display = 'none';
            blockBtn.style.display = 'none';
            deleteBtn.style.display = 'none';

        } else {
            popupStatus.style.color = 'var(--red-color)';
            editBtn.style.display = 'inline-block';
            blockBtn.style.display = 'inline-block';
            
            saveBtn.style.display = 'none';
            deleteBtn.style.display = 'none';
            unblockBtn.style.display = 'none';
        }
    }

    function makeEditable() {
        // Save initial data
        initialData = {
            pid: popupId.value,
            fname: popupFname.value,
            lname: popupLname.value,
            email: popupEmail.value,
            nic: popupNic.value,
            status: popupStatus.value,
            resetCode: popupResetCode.value,
            contact: popupContact.value
        };

        // Enable editing
        document.querySelectorAll('#searchUserForm .input-field').forEach(field => {
            if (field.id !== 'popup-id' && field.id !== 'popup-status') {
                field.disabled = false;
            }
        });

        // Toggle buttons
        const accountStatus = popupStatus.value;

        editBtn.style.display = 'none';
        cancelBtn.style.display = 'inline-block';
        saveBtn.style.display = 'inline-block';
        
        if (accountStatus === 'Active') {
            popupStatus.style.color = 'var(--green-color)';
            deleteBtn.style.display = 'none';

        } else if (accountStatus === 'Blocked') {
            unblockBtn.style.display = 'none';
            popupStatus.style.color = 'var(--red-color)';
        } else {
            popupStatus.style.color = 'var(--red-color)';
            blockBtn.style.display = 'none';
        }
    }

    function cancelChanges() {
        // Restore initial values
        popupId.value = initialData.pid;
        popupFname.value = initialData.fname;
        popupLname.value = initialData.lname;
        popupEmail.value = initialData.email;
        popupNic.value = initialData.nic;
        popupStatus.value = initialData.status;
        popupResetCode.value = initialData.resetCode;
        popupContact.value = initialData.contact;

        // Disable editing
        document.querySelectorAll('#searchUserForm .input-field').forEach(field => {
            field.disabled = true;
        });

        const accountStatus = popupStatus.value;

        editBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'none';
        saveBtn.style.display = 'none';
        
        if (accountStatus === 'Active') {
            deleteBtn.style.display = 'inline-block';
            blockBtn.style.display = 'none';
            unblockBtn.style.display = 'none';
        } else if (accountStatus === 'Blocked') {
            unblockBtn.style.display = 'inline-block';
            blockBtn.style.display = 'none';
            deleteBtn.style.display = 'none';
        } else {
            blockBtn.style.display = 'inline-block';
            deleteBtn.style.display = 'none';
            unblockBtn.style.display = 'none';
        }
    }

    function removeUserDetailBox(event) {
        event.preventDefault();
        cancelChanges();
        searchUserForm.style.display = 'none';
        formContainer.classList.remove('blurred-background');
    }

    function createFormWithAction(action) {
        const form = document.createElement('form');
        form.method = 'post';

        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'action';
        hiddenInput.value = action;
        form.appendChild(hiddenInput);

        const pidField = document.createElement('input');
        pidField.type = 'hidden';
        pidField.name = 'pid';
        pidField.value = popupId.value;
        form.appendChild(pidField);

        return form;
    }

    function deleteAccount() {
        const form = createFormWithAction('delete_user');
        document.body.appendChild(form);
        displayLoader();
        form.submit();
    }

    function blockAccount() {
        const form = createFormWithAction('block_user');
        document.body.appendChild(form);
        displayLoader();
        form.submit();
    }

    function unblockAccount() {
        const form = createFormWithAction('unblock_user');
        document.body.appendChild(form);
        displayLoader();
        form.submit();
    }

    function saveChanges() {
        const form = findUserForm;
        
        const currentValues = {
            pid: popupId.value,
            fname: popupFname.value,
            lname: popupLname.value,
            email: popupEmail.value,
            nic: popupNic.value,
            status: popupStatus.value,
            reset_code: popupResetCode.value,
            contact: popupContact.value,
        };

        let fieldCount = 0;
        for (let name in currentValues) {
            if (currentValues[name] !== initialData[name] || name === 'pid') {
                if (name === 'reset_code' && currentValues.reset_code === '---') {
                    continue;
                }
                let input = form.querySelector(`input[name="${name}"]`);
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = name;
                    form.appendChild(input);
                }
                input.value = currentValues[name];
                fieldCount++;
            }
        }

        if (fieldCount > 1) {
            displayLoader();
            form.submit();
        } else {
            cancelChanges();
        }
    }

    // Sorting functions
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
        loaderContainer.style.display = '';
    }

    // Event Listeners
    document.getElementById('date-header').addEventListener('click', () => {
        sortTableByDate(isDateAscending);
        isDateAscending = !isDateAscending;
    });

    document.getElementById('user-type-header').addEventListener('click', () => {
        sortTableByUserType(isUserTypeAscending);
        isUserTypeAscending = !isUserTypeAscending;
    });

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', displayLoader);
    });

    document.querySelectorAll('a').forEach(link => {
        if (!link.getAttribute('href').startsWith('#')) {
            link.addEventListener('click', displayLoader);
        }
    });
</script>
<?php require_once 'managerFooter.view.php'; ?>