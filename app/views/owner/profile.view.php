<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>PROFILE</h2>
</div>

<form id="profile-edit-form" class="profile-container" method="post" enctype="multipart/form-data">
    <!-- Left side: Profile Picture and User Info -->
    <div class="profile-details">
        <!-- Hidden file input -->
        <input type="file" id="profile_picture" class="input-file" name="profile_picture" style="display: none;" hidden>

        <!-- Profile picture that will act as input -->
        <img src="<?=ROOT?>/assets/images/user.png" alt="Profile Picture" class="profile-picture" id="profile-picture-preview">

        <!-- User details -->
        <h2>Mr. Property Owner</h2>
        <p class="profile-id">EID - 23545</p>
    </div>

    <!-- Right side: Editable Form -->
    <div class="profile-edit-form">
        <div>
            <div class="input-group">
                <div class="input-group-group">
                    <label for="first-name" class="input-label">First name</label>
                    <input type="text" id="first-name" name="fname" class="input-field" value="Kamrul" disabled>
                </div>
                <div class="input-group-group">
                    <label for="last-name" class="input-label">Last name</label>
                    <input type="text" id="last-name" name="lname" class="input-field" value="Hasan" disabled>
                </div>
            </div>
            <div class="input-group-group">
                <label for="email" class="input-label">Email</label>
                <input type="email" id="email" class="input-field" name="email" value="johndoe@gmail.com" disabled>
            </div>
            <div class="input-group-group">
                <label for="contact-number" class="input-label">Contact number</label>
                <input type="text" id="contact-number" class="input-field" name="contact" value="+94 71 123 4567" disabled>
            </div>

            <div class="input-group-aligned">
                <button type="button" class="primary-btn" id="edit-button">Edit</button>
                <button type="button" class="secondary-btn" id="cancel-button" style="display: none;">Cancel</button>
                <button type="submit" class="primary-btn" id="save-button" style="display: none;">Save</button>
            </div>
            <h5 class="editText" id="editText" style="display: none;">click profile picture to edit !</h5>
        </div>
    </div>
</form>

<script>
    // Logic for enabling fields on "Edit", handling "Save" and "Cancel"

    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const cancelButton = document.getElementById('cancel-button');
    const editText = document.getElementById('editText');
    const formFields = document.querySelectorAll('.input-field');
    const profilePictureInput = document.getElementById('profile_picture');
    const profilePicturePreview = document.getElementById('profile-picture-preview');

    // Store initial form field values for reset functionality
    const initialFormValues = {};
    formFields.forEach(field => {
        initialFormValues[field.id] = field.value; // Store initial values
    });

    // Enable form fields and profile picture edit when "Edit" button is clicked
    editButton.addEventListener('click', () => {
        formFields.forEach(field => field.disabled = false); // Enable input fields
        profilePicturePreview.classList.add('editable'); // Indicate the picture is editable
        editButton.style.display = 'none';
        saveButton.style.display = 'block';
        cancelButton.style.display = 'block'; // Show Cancel button
        editText.style.display = 'block'; // Show Cancel button
    });

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
        
        if (!allowedMimeTypes.includes(file.type)) {
            alert('Invalid file type! Please upload an image (JPEG, PNG, or GIF).');
            profilePictureInput.value = ''; // Clear the input if file type is invalid
            profilePicturePreview.src = '<?=ROOT?>/assets/images/user.png'; // Reset the image preview to default
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

    // Add logic for the Cancel button
    cancelButton.addEventListener('click', () => {
        // Reset form fields to their initial values
        formFields.forEach(field => {
            field.value = initialFormValues[field.id];
            field.disabled = true; // Disable fields again
        });

        // Hide Save and Cancel buttons, show Edit button
        saveButton.style.display = 'none';
        cancelButton.style.display = 'none';
        editButton.style.display = 'block';

        // Remove editable state from profile picture
        profilePicturePreview.classList.remove('editable');
        profilePicturePreview.style.cursor = 'default';
    });

</script>

<?php 
// Display the uploaded file's name
if (isset($_FILES['profile_picture'])) {
    echo "Uploaded file: " . $_FILES['profile_picture']['name'];
    show( $_POST );
}
?>

<?php require_once 'ownerFooter.view.php'; ?>
