<?php 
    if(isset($_SESSION['customerView']) && $_SESSION['customerView'] == true ){
        require_once 'customer/customerHeader.view.php'; 
    }else{        
        $type = $user->user_lvl;
        if($type == 0){
            require_once 'customer/customerHeader.view.php'; 
        }else if($type == 1){
            require_once 'owner/ownerHeader.view.php'; 

        }else if($type == 2){
            require_once 'serviceProvider/serviceProviderHeader.view.php'; 
            
        }else if($type == 3){
            require_once 'agent/agentHeader.view.php'; 
            
        }else if($type == 4){
            require_once 'manager/managerHeader.view.php'; 
        
        }
    }
?>

<!-- <div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Profile</h2>
    <div class="flex-bar">
        <div class="tooltip-container">
            <form action="<?= ROOT ?>/resetPassword" method="post" style="display: inline;">
                <input type="hidden" name="email_submission" value="1">
                <input type="hidden" name="email" value="<?= esc($user->email) ?>">
                <button type="submit" class="add-btn">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </button>
            </form>
            <span class="tooltip-text">Change password</span>
        </div>
    </div>
</div> -->
<!-- delete account -->
<div class="tooltip-container" style="position: absolute; top: 210px; right: 1px; z-index:1;">
    <form action="<?= ROOT ?>/resetPassword" method="post" style="display: inline;">
        <input type="hidden" name="email_submission" value="1">
        <input type="hidden" name="email" value="<?= esc($user->email) ?>">
        <button type="submit" class="add-btn">
            <i class="fa-solid fa-unlock-keyhole"></i>
        </button>
    </form>
    <span class="tooltip-text">Change password</span>
</div>

<form id="profile-edit-form" class="profile-banner-container" method="post" enctype="multipart/form-data" style="position: relative; width: 100%; overflow:hidden;">
    <!-- profile banner -->
    <div class="profile-banner">
        <img src="<?= ROOT ?>/assets/images/ProfileBanner.jpg" style="width: calc(100% - 10px); object-fit: cover; height: 160px; background-color: #ddd; margin: 6px 5px; border-radius: 8px;" alt="">
        <p class="input-label" style="position:absolute; top: 10px; right: 12px;"> NIC : <?= esc($user->nic) ?> </p>
    </div>
    <div class="profile-banner" style="width: calc(100% - 10px); height: 61vh; background-color: #fff; margin: 6px 5px; border-radius: 8px;">
        <p class="input-label" style="position:absolute; top: 10px; right: 12px;"> NIC : <?= esc($user->nic) ?> </p>
    </div>
    

    <div class="" style="display: flex; flex-direction: row;">
        <!-- Left side: Profile Picture and User Info -->
        <div class="profile-details" style="position: absolute; top: 5vh; left:0vw;">
            <!-- Hidden file input -->
            <input type="file" id="profile_picture" class="input-file" name="profile_picture" style="display: none;" hidden>

            <!-- Profile picture that will act as input -->
            <img src="<?= get_img($user->image_url)?>" alt="Profile Picture" class="profile-picture" id="profile-picture-preview">

            <!-- User details -->
            <h2 class="profile-name"><?= $user->fname .' '. $user->lname ?></h2>
            <p class="profile-role">PID - <?= $user->pid ?></p>
        </div>

        <!-- Right side: Editable Form -->
        <div class="profile-edit-form" style="position: absolute; left: 20vw; top: 160px;">
            <div>
                <div class="input-group">
                    <div class="input-group-group">
                        <label for="first-name" class="input-label">First name</label>
                        <input type="text" id="first-name" name="fname" class="input-field" value="<?= esc($user->fname) ?>" disabled>
                    </div>
                    <div class="input-group-group">
                        <label for="last-name" class="input-label">Last name</label>
                        <input type="text" id="last-name" name="lname" class="input-field" value="<?= esc($user->lname) ?>" disabled>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group-group">
                        <label for="contact-number" class="input-label">Contact number</label>
                        <input type="text" id="contact-number" class="input-field" name="contact" value="<?= esc($user->contact) ?>" disabled>
                    </div>
                    <div class="input-group-group">
                        <label for="nic" class="input-label">NIC</label>
                        <input type="text" id="nic" class="input-field" name="nic" value="<?= esc($user->nic) ?>" disabled>
                    </div>
                </div>
                <div class="input-group-group">
                    <label for="email" class="input-label">Email</label>
                    <input type="email" id="email" class="input-field" name="email" value="<?= esc($user->email) ?>" disabled>
                </div>

                <?php if ($_SESSION['user']->AccountStatus == 2): ?>
                    <p class="status-message" style="position: absolute; right: 40px; text-align: right; margin-bottom:-20px; color: var(--primary-color);">Pending details change request</p>
                <?php elseif ($_SESSION['user']->AccountStatus == -2): ?>
                    <p class="status-message" style="position: absolute; right: 40px; text-align: right; margin-bottom:-20px; color: var(--red-color);">Account removal ongoing</p>
                <?php endif; ?>

                <h5 class="editText" id="editText" style="position: absolute; left: 20vw; display: none;">click profile picture to edit !</h5>
                <div class="errors" 
                    style="display: <?= !empty($_SESSION['errors']) || !empty($_SESSION['status']) ? 'block' : 'none'; ?>; 
                            background-color: <?= !empty($errors) ? '#f8d7da' : (!empty($status) ? '#b5f9a2' : '#f8d7da'); ?>;">
                    <?php if (!empty($_SESSION['errors'])): ?>
                        <p><?= $errors[0] ?? '' ?></p>
                    <?php elseif (!empty($_SESSION['status'])): ?>
                        <p><?= $status ;  ?></p>
                    <?php endif; ?>
                </div>
                <div class="input-group-aligned" style="margin-top: 18vh;">
                    <button type="button" class="primary-btn" id="edit-button" onclick="() => {enableEditMode()}">Edit</button>
                    <button type="button" class="secondary-btn" id="cancel-button" onclick="cancelEdit();" style="display: none;">Cancel</button>
                    <button type="button" class="secondary-btn red" id="delete-button" onclick="showDeleteDialog();">Remove Account</button>
                    <button type="submit" class="primary-btn" id="save-button" onclick="prepareFormSubmission();" style="display: none;">Save</button>
                </div>


            </div>
            
        </div>
    </div>
    
</form>

<!-- <form id="profile-edit-form" class="profile-container lur-overlay" method="post" enctype="multipart/form-data">
    
</form> -->

<form id="delete-account-form" method="post" >
    <input type="hidden" name="delete_account" value="1">
    <div class="dialog-container" style="display:none">
        <div class="dialog-header">
            Are you sure you want to delete <strong><?= $user->fname ." ". $user->lname  ?></strong>?
        </div>
        <div class="warning-box">
            <span class="warning-icon">⚠️</span>
            <div class="warning-text">
            <strong>Warning</strong><br>
            By deleting this account, the You won't be able to access the system.
            </div>
        </div>
        <div class="input-group-aligned">
            <button type="button" class="secondary-btn green" id="cancel-delete-button" onclick="hideDeleteDialog();">No</button>
            <button type="submit" class="secondary-btn red">Delete</button>
        </div>
    </div>
</form>


<script>

    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const cancelButton = document.getElementById('cancel-button');
    const removeButton = document.getElementById('delete-button');
    const editText = document.getElementById('editText');
    const formFields = document.querySelectorAll('.input-field');
    const profilePictureInput = document.getElementById('profile_picture');
    const profilePicturePreview = document.getElementById('profile-picture-preview');
    const dialogContainer = document.querySelector('.dialog-container');
    const cancelDeleteButton = document.getElementById('cancel-delete-button');
    const blurBackground = document.getElementById('profile-edit-form');
    const errorAlert = document.querySelector('.errors');

    const initialFormValues = {};
    const profilePicture = profilePicturePreview

    formFields.forEach(field => {
        initialFormValues[field.id] = field.value;
    });//save form values initaily to reset when cancel button is clicked

    // Handle profile picture click to trigger the file input
    profilePicturePreview.addEventListener('click', () => {
        if (!profilePicturePreview.classList.contains('editable')) return;
        profilePictureInput.click(); // Simulate click on the hidden input
        profilePicturePreview.style.cursor = 'pointer';
    });

        // Handle profile picture change and preview 
    profilePictureInput.addEventListener('change', (event) => {
        const file = event.target.files[0];    
        if (file) {
            // Check if the file type is one of the allowed image types (JPEG, PNG, GIF)
            const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            const maxSizeInBytes = 2 * 1024 * 1024; // 2 MB in bytes

            if (!allowedMimeTypes.includes(file.type)) {
                // alert('Invalid file type! Please upload an image (JPEG, PNG, or GIF).');
                profilePictureInput.value = ''; // Clear the input if file type is invalid
                const error = document.createElement('p');
                error.textContent = 'Invalid file type! Please upload an image (JPEG, PNG, or GIF).';
                errorAlert.innerHTML = ''; // Clear previous errors
                errorAlert.appendChild(error);
                errorAlert.style.display = 'block';

            } else if (file.size > maxSizeInBytes) {
                // alert('File size exceeds 2 MB! Please upload a smaller image.');
                profilePictureInput = profilePicture; // Clear the input if file type is invalid
                const error = document.createElement('p');
                error.textContent = 'File size exceeds 2 MB! Please upload a smaller image.';
                errorAlert.innerHTML = ''; // Clear previous errors
                errorAlert.appendChild(error);
                errorAlert.style.display = 'block';
                
            } else {
                const reader = new FileReader();
                reader.onload = (e) => {
                    profilePicturePreview.src = e.target.result; // Update the image preview
                };
                reader.readAsDataURL(file); // Read the file as a data URL for image preview
            }
        }
    });

        // Handle form submission
    document.getElementById('profile-edit-form').addEventListener('submit', function (event) {
        // Enable all fields before submitting, as they might be disabled
        formFields.forEach(field => field.disabled = false);
        
        // Submit form using the browser's default submission method
        this.submit();
    });
    
        // Enable form fields and profile picture edit when "Edit" button is clicked
    editButton.addEventListener('click', () => {
        formFields.forEach(field => {
            if (field.id !== 'nic') { // Prevent the email field from being editable
                field.disabled = false;
            }
        }); // Enable input fields
        profilePicturePreview.classList.add('editable'); // Indicate the picture is editable
        editButton.style.display = 'none';
        removeButton.style.display = 'none';
        saveButton.style.display = 'block';
        cancelButton.style.display = 'block'; // Show Cancel button
        editText.style.display = 'block'; // Show Cancel button
    });

  
    
    cancelButton.addEventListener('click', () => {// Add logic for the Cancel button
        // Reset form fields to their initial values
        formFields.forEach(field => {
            field.value = initialFormValues[field.id];
            field.disabled = true; // Disable fields again
        });
        // console.log(initialFormValues);
        // Hide Save and Cancel buttons, show Edit button
        saveButton.style.display = 'none';
        cancelButton.style.display = 'none';
        editButton.style.display = 'block';
        removeButton.style.display = 'block';
        editText.style.display = 'none'; // Show Cancel button
        errorAlert.style.display = 'none';

        // Remove editable state from profile picture
        profilePicturePreview.classList.remove('editable');
        profilePicturePreview.style.cursor = 'default';
    });

    removeButton.addEventListener('click', () => {
        dialogContainer.style.display = 'block';
        blurBackground.classList.add('blurred-background'); // Add blur class
    });

    cancelDeleteButton.addEventListener('click', () => {
        dialogContainer.style.display = 'none';
        blurBackground.classList.remove('blurred-background'); // Remove blur class
    });

</script>
<?php 
// Display the uploaded file's name
// if (isset($_FILES['profile_picture'])) {
    // show($_FILES['profile_picture'] ?? "null");
    // show( $_POST );
    // show( $user );
    // show( $_SESSION);
// }
// show(get_img($user->image_url));
?>

<?php 
    $type = $user->user_lvl;
    if($type == 0){
        require_once 'customer/customerFooter.view.php'; 

    }
    if($type == 1){
        require_once 'owner/ownerFooter.view.php'; 

    }else if($type == 2){
        require_once 'serviceProvider/serviceProviderFooter.view.php'; 
        
    }else if($type == 3){
        require_once 'agent/agentFooter.view.php'; 
        
    }else if($type == 4){
        require_once 'manager/managerFooter.view.php'; 
        
    }
    // show($user);
?>
