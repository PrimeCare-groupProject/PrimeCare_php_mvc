<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>PROFILE</h2>
</div>

<div class="profile-container">
    <!-- Left side: Profile Picture and User Info -->
    <div class="profile-details">
        <!-- Hidden file input -->
        <input type="file" id="profile-picture-input" class="input-file" style="display: none;" hidden>

        <!-- Profile picture that will act as input -->
        <img src="<?=ROOT?>/assets/images/user.png" alt="Profile Picture" class="profile-picture" id="profile-picture-preview">

        <!-- User details -->
        <h2>Mr. Property Owner</h2>
        <p class="profile-id">EID - 23545</p>
    </div>

    <!-- Right side: Editable Form -->
    <div class="profile-edit-form">
        <form id="profile-edit-form">
            <div class="input-group">
                <div class="input-group-group">
                    <label for="first-name" class="input-label">First name</label>
                    <input type="text" id="first-name" class="input-field" value="Kamrul" disabled>
                </div>
                <div class="input-group-group">
                    <label for="last-name" class="input-label">Last name</label>
                    <input type="text" id="last-name" class="input-field" value="Hasan" disabled>
                </div>
            </div>
            <div class="input-group-group">
                <label for="email" class="input-label">Email</label>
                <input type="email" id="email" class="input-field" value="johndoe@gmail.com" disabled>
            </div>
            <div class="input-group-group">
                <label for="contact-number" class="input-label">Contact number</label>
                <input type="text" id="contact-number" class="input-field" value="+94 71 123 4567" disabled>
            </div>

            <div>
                <button type="button" class="primary-btn" id="edit-button">Edit</button>
                <button type="submit" class="primary-btn" id="save-button" style="display: none;">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const formFields = document.querySelectorAll('.input-field');
    const profilePictureInput = document.getElementById('profile-picture-input');
    const profilePicturePreview = document.getElementById('profile-picture-preview');

    // Enable form fields and profile picture edit when "Edit" button is clicked
    editButton.addEventListener('click', () => {
        formFields.forEach(field => field.disabled = false); // Enable input fields
        profilePicturePreview.classList.add('editable'); // Indicate the picture is editable
        editButton.style.display = 'none';
        saveButton.style.display = 'block';
    });

    // Handle profile picture click to trigger the file input
    profilePicturePreview.addEventListener('click', () => {
        if (!profilePicturePreview.classList.contains('editable')) return;
        profilePictureInput.click(); // Simulate click on the hidden input
        profilePicturePreview.style.cursor = 'pointer';
    });

    // Handle profile picture change
    profilePictureInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                profilePicturePreview.src = e.target.result; // Update the image preview
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once 'customerFooter.view.php'; ?>