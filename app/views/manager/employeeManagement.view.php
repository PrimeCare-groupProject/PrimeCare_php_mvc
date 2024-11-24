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
                    <th style='max-width: 15%;' class="extra-space sortable first" id="date-header">
                        Created Date
                        <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                    </th>
                    <th style='max-width: 35px;'>ID</th>
                    <th style='max-width: 20%;'>Name</th>
                    <th style='max-width: 23%;'>Email</th>
                    <th style='min-width: 75px;' class="sortable" id="user-type-header">
                        User Type
                        <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                    </th>
                    <th style='width: 5%;' class="last">Image</th>
                    <th hidden>Reset Code</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (isset($userlist) && count($userlist) > 0) {
                        foreach ($userlist as $user) {
                            echo "<tr onclick='showUserDetailBox(this)'>";
                            echo "<td class='first'><input type='text' name='created_date' value='" . ($user->created_date ? date('Y-m-d', strtotime($user->created_date)) : "-") . "' disabled></td>";
                            echo "<td><input type='text' name='id' value='{$user->pid}' disabled></td>";
                            echo "<td><input type='text' name='name' value='{$user->fname} {$user->lname}' disabled></td>";
                            echo "<td><input type='email' name='email' value='{$user->email}' disabled></td>";
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
                            echo "<td class='last'><img class='header-profile-picture' style='margin:0px' src='".get_img($user->image_url)."'></td>";
                            echo "<td hidden><input type='text' value='" . (empty($user->reset_code) ? "---" : $user->reset_code) . "' disabled></td>";
                            echo "<td hidden><input type='text' value='{$user->contact}' disabled></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No records found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
    <div class="pagination">
        <?php echo $paginationLinks; ?>
    </div>
</div>

<div id="searchUserForm" style="display: none"><!--style="display: none"-->
    <form id="find-user" method="post">
        <input type="hidden" name="find_user" value="1">
        <div class="SearchBox">
            <button class="close_btn" id="close-btn" onclick="removeUserDetailBox(event)">X</button>
            <div class="input-group">
                <div class="user-details left_details">
                    <div class="input-group">
                        <div class="input-group-group">
                            <label for="popup-created-date" class="input-label">Created Date:</label>
                            <input id="popup-created-date" type="text" class="input-field" disabled>
                        </div>
                        <div class="input-group-group">
                            <label for="popup-id" class="input-label">ID:</label>
                            <input id="popup-id" type="text" class="input-field" disabled>
                        </div>
                    </div>
                    <label for="popup-fname" class="input-label">First Name:</label>
                    <input id="popup-fname" type="text" class="input-field" disabled>
                    <label for="popup-lname" class="input-label">Last Name:</label>
                    <input id="popup-lname" type="text" class="input-field" disabled>
                        <label for="popup-email" class="input-label">Email:</label>
                        <input id="popup-email" type="email" class="input-field" disabled>
                    <div class="input-group">
                        <div class="input-group-group">
                            <label for="popup-reset-code" class="input-label">Reset Code:</label>
                            <input id="popup-reset-code" type="text" class="input-field" disabled>
                        </div>
                        <div class="input-group-group">
                            <label for="popup-contact" class="input-label">Contact :</label>
                            <input id="popup-contact" type="text" class="input-field" disabled>
                        </div>
                    </div>
                </div>
                <div class="user-details right_details">
                    <!-- profile picture -->
                    <img src="" class="profile_pic_large" id="popup-profile-pic">
                    <!-- buttons -->
                    <div class="input-group-aligned">
                    <div class="input-group-aligned">
                        <button type="button" class="secondary-btn" id="edit-btn" style="margin-top: 0px;" onclick="makeEditable()">Edit</button>
                        <button type="button" class="red btn" id="delete-btn" onclick="makeEditable()">Delete</button>
                        <button type="button" class="green btn" id="save-btn" onclick="saveChanges()" style="display: none;">Save</button>
                        <button type="button" class="red btn" id="cancel-btn" onclick="cancelChanges()" style="display: none;">Cancel</button>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    let isDateAscending = true;
    let isUserTypeAscending = true;

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

    function showUserDetailBox(row) {
        const cells = row.querySelectorAll('td');
        console.log(cells)
        document.getElementById('popup-created-date').value = cells[0].querySelector('input').value;
        document.getElementById('popup-id').value = cells[1].querySelector('input').value;
        document.getElementById('popup-fname').value = cells[2].querySelector('input').value.split(' ')[0];
        document.getElementById('popup-lname').value = cells[2].querySelector('input').value.split(' ')[1];
        document.getElementById('popup-email').value = cells[3].querySelector('input').value;
        document.getElementById('popup-reset-code').value = cells[6].querySelector('input').value.trim();
        document.getElementById('popup-contact').value = cells[7].querySelector('input').value.trim();

        // Set the profile image
        const imgSrc = cells[5].querySelector('img').src; // Get the image source from the table
        document.getElementById('popup-profile-pic').src = imgSrc;

        document.getElementById('searchUserForm').style.display = 'block';
        document.getElementById('formContainer').classList.add('blurred-background');
    }

    function removeUserDetailBox(event) {
        event.preventDefault();
        cancelChanges();
        document.getElementById('searchUserForm').style.display = 'none';
        document.getElementById('formContainer').classList.remove('blurred-background');
    }

    let initialData = {};

    function makeEditable() {
        // Save the initial data for all fields
        initialData = {
            // createdDate: document.getElementById('popup-created-date').value,
            // id: document.getElementById('popup-id').value,
            fname: document.getElementById('popup-fname').value,
            lname: document.getElementById('popup-lname').value,
            email: document.getElementById('popup-email').value,
            resetCode: document.getElementById('popup-reset-code').value,
            contact: document.getElementById('popup-contact').value
        };

        // Enable fields for editing
        document.querySelectorAll('#searchUserForm .input-field').forEach(field => {
            field.disabled = false;
        });

        // Show Save and Cancel buttons, hide Edit button
        document.getElementById('edit-btn').style.display = 'none';
        document.getElementById('delete-btn').style.display = 'none';
        document.getElementById('save-btn').style.display = 'inline-block';
        document.getElementById('cancel-btn').style.display = 'inline-block';
    }

    function cancelChanges() {
        // Revert all fields to their initial values
        document.getElementById('popup-created-date').value = initialData.createdDate;
        document.getElementById('popup-id').value = initialData.id;
        document.getElementById('popup-fname').value = initialData.fname;
        document.getElementById('popup-lname').value = initialData.lname;
        document.getElementById('popup-email').value = initialData.email;
        document.getElementById('popup-reset-code').value = initialData.resetCode;
        document.getElementById('popup-contact').value = initialData.contact;

        // Disable editing for fields
        document.querySelectorAll('#searchUserForm .input-field').forEach(field => {
            field.disabled = true;
        });

        // Hide Save and Cancel buttons, show Edit button
        document.getElementById('edit-btn').style.display = 'inline-block';
        document.getElementById('delete-btn').style.display = 'inline-block';
        document.getElementById('save-btn').style.display = 'none';
        document.getElementById('cancel-btn').style.display = 'none';
    }

    function saveChanges() {
        // Disable fields after saving
        document.querySelectorAll('#searchUserForm .input-field').forEach(field => {
            field.disabled = true;
        });

        // Hide Save and Cancel buttons, show Edit button
        document.getElementById('edit-btn').style.display = 'inline-block';
        document.getElementById('delete-btn').style.display = 'inline-block';
        document.getElementById('save-btn').style.display = 'none';
        document.getElementById('cancel-btn').style.display = 'none';

        // Optionally, send the updated data to the server via AJAX
        const updatedData = {
            createdDate: document.getElementById('popup-created-date').value,
            id: document.getElementById('popup-id').value,
            fname: document.getElementById('popup-fname').value,
            lname: document.getElementById('popup-lname').value,
            email: document.getElementById('popup-email').value,
            resetCode: document.getElementById('popup-reset-code').value,
            contact: document.getElementById('popup-contact').value
        };

        console.log('Saving updated data:', updatedData);
        // Example AJAX POST request
        // fetch('/save-user-details', {
        //     method: 'POST',
        //     headers: { 'Content-Type': 'application/json' },
        //     body: JSON.stringify(updatedData)
        // }).then(response => {
        //     if (response.ok) alert('Changes saved successfully!');
        // }).catch(error => console.error('Error saving changes:', error));
    }

</script>

<?php require_once 'managerFooter.view.php'; ?>
