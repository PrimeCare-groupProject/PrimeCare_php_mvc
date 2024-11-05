<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/userUpdate.css">
    <title>PrimeCare - Edit Profile</title>
</head>

<body>
    <div class="user_view-container">
        <!-- Side Menu -->
        <div class="user_view-sidemenu">
            <div class="user_view-sidemenu-logo">
                <img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare">
            </div>
            <div class="user_view-sidemenu-content">
                <ul>
                    <li><button class="btn">Dashboard</button></li>
                    <li><button class="btn">Profile</button></li>
                    <li><button class="btn">Properties</button></li>
                    <li><button class="btn">Maintenance</button></li>
                    <li><button class="btn">Contacts</button></li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="user_view-content_section">
            <div class="user_view-profile-section"></div>
            <div class="user_view-menu-bar">
                <p>Update Profile</p>
            </div>
            <div class="user_view-content-area">
                    <!-- Profile Picture Section -->
                    <div class="profile-picture-container">
                        <div class="profile-image-wrapper">
                            <img src="<?= ROOT ?>/assets/images/Profileimage.png" alt="Default Profile Picture" class="profile-picture">
                            <label for="profilePicture" class="profile-picture-overlay"></label>
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
                    <div class="profile-details-container">
                        <div class="user_view-menu-bar">
                            <h2>Edit Profile Details</h2>
                        </div> 
                        <div class="profile-details">
                            <form action="/manager/updateProfile" method="POST" class="profile-details-form">
                            <div class="row">
                                <div class="input-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" placeholder="First name" class="input-field">
                                </div>
                                <div class="input-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" placeholder="Last name" class="input-field">
                                </div>
                            </div>
                            <div class="input-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" placeholder="Email" class="input-field">
                            </div>
                            <div class="input-group">
                                <label for="phone">Contact Number</label>
                                <input type="text" id="phone" name="phone" placeholder="Contact number" class="input-field">
                            </div>
                            <div class="buttons">
                                <button type="reset" class="cancel-btn">Cancel</button>
                                <button type="submit" class="save-btn">Save</button>
                            </div>
                            </form>
                        </div>
                    </div>
            </div>
            <!-- Footer or additional content if needed 
            <div class="user_view-footer">
                <p>PrimeCare © 2024</p>
            </div>-->
        </div>
    </div>

</body>

</html>

<!--
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/userview.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/styles.css"> 
    <title>PrimeCare - Profile</title>
</head>

<body>
    <div class="user_view-container">
        Side Menu 
        <div class="user_view-sidemenu">
            <div class="user_view-sidemenu-logo">
                <img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare">
            </div>
            <div class="user_view-sidemenu-content">
                <ul>
                    <li><button class="btn">Dashboard</button></li>
                    <li><button class="btn">Profile</button></li>
                    <li><button class="btn">Properties</button></li>
                    <li><button class="btn">Maintenance</button></li>
                    <li><button class="btn">Contacts</button></li>
                </ul>
            </div>
        </div>

        Main Content 
        <div class="user_view-content_section">
            <div class="user_view-profile-section">
                <br><br><br>
                <div class="profile-container">
                    Profile Picture Section 
                    <div class="profile-picture-container">
                        <?php if (isset($manager)): ?>
                            <div class="profile-image-wrapper">
                                <?php if (isset($manager)): ?>
                                    <?php if (!empty($manager['profile_picture'])): ?>
                                        <img src="<?php echo $manager['profile_picture']; ?>" alt="Profile Picture" class="profile-picture">
                                    <?php else: ?>
                                        <img src="<?= ROOT ?>/assets/images/Profileimage.png " alt="Default Profile Picture" class="profile-picture">
                                    <?php endif; ?>
                                    <label for="profilePicture" class="profile-picture-overlay">Update Profile Picture</label>
                                <?php endif; ?>
                                <label for="profilePicture" class="profile-picture-overlay">Update Profile Picture</label>
                            </div>
                            <form action="/manager/updateProfile/<?php echo $manager['id']; ?>" method="POST" enctype="multipart/form-data">
                                <input type="file" name="profilePicture" id="profilePicture" class="file-input">
                                <button type="submit" class="upload-btn">Upload</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="profile-details-container">
                        <div class="user_view-menu-bar">
                            <h2>Update Profile</h2>
                        </div> 
                        <div class="profile-details">
                            <form action="/manager/updateProfile/<?php echo $manager['id']; ?>" method="POST" class="profile-details-form">
                                <?php if (isset($manager)): ?>
                                    <input type="text" name="name" value="<?php echo $manager['name']; ?>" placeholder="Full Name" class="input-field">
                                    <input type="email" name="email" value="<?php echo $manager['email']; ?>" placeholder="Email" class="input-field">
                                    <input type="text" name="phone" value="<?php echo $manager['phone']; ?>" placeholder="Phone Number" class="input-field">
                                    <input type="text" name="address" value="<?php echo $manager['address']; ?>" placeholder="Address" class="input-field">
                                    <p class="eid-text">EID - <?php echo $manager['employee_id']; ?></p>
                                <?php endif; ?>

                                <div class="buttons">
                                    <button type="submit" class="save-btn">Save</button>
                                    <button type="reset" class="cancel-btn">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        
        </div>
    </div>

</body>

</html>
-->
