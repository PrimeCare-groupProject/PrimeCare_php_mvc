<div class="user_view-menu-bar">
    <h2>Update Profile</h2>
</div>


<div class="owner-addProp-container">
    <!-- Profile Picture Section -->
    <div class="profile-picture-container">
        <div class="profile-image-wrapper">
            <img src="<?= ROOT ?>/assets/images/user.png" alt="Default Profile Picture" class="profile-picture">
            <label for="profilePicture" class="input-label"></label>
        </div>
        <form action="/manager/updateProfile" method="POST" enctype="multipart/form-data">
            <input type="file" name="profilePicture" id="profilePicture" class="file-input" style="display: none;">
        </form>
        <div class="Profile-Indexes">
            <h1>Mr.Manager</h1>
            <h3>EID - 00001</h3>
        </div>
    </div>

    <!-- Profile Details Section -->
    <div class="owner-addProp-form-right">
        <div class="grey-text">
            <h2>Edit Profile Details</h2>
        </div>
        <form action="/manager/updateProfile" method="POST" class="profile-details-form">
            <div class="input-group">
                <div class="input-group-group">
                    <label for="first_name" class="input-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" placeholder="First name" class="input-field">
                </div>
                <div class="input-group-group">
                    <label for="last_name" class="input-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Last name" class="input-field">
                </div>
            </div>
            <div class="input-group-group">
                <label for="email" class="input-label">Email</label>
                <input type="email" id="email" name="email" placeholder="Email" class="input-field">
            </div>
            <div class="input-group-group">
                <label for="phone" class="input-label">Contact Number</label>
                <input type="text" id="phone" name="phone" placeholder="Contact number" class="input-field">
            </div>
            <div class="buttons">
                <button type="reset" class="secondary-btn">Cancel</button>
                <button type="submit" class="primary-btn">Save</button>
            </div>
        </form>
    </div>
</div>