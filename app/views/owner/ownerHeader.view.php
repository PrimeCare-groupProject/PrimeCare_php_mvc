<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/userview.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/manager.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/owner.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/button.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/userUpdate.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/financialReport.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/forms.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/serviceProvider.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/customer.css">
    <link rel="icon" href="<?= ROOT ?>/assets/images/p.png" type="image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <title>PrimeCare</title>
</head>

<body>
    <div class="user_view-container">
        <div class="header-line">
            <a href="<?= ROOT ?>/home"><img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare" class="header-logo-png"></a>
            <button class="toggle-sidebar-btn" onclick="toggleSidebar()">☰ Menu</button>
            <a href="<?= ROOT ?>/dashboard/profile"><img src="<?= ROOT ?>/assets/images/user.png" alt="Profile" class="header-profile-picture"></a>
        </div>
        <div class="content-section">
            <div class="user_view-sidemenu">
                <ul>
                    <li><a href="<?= ROOT ?>/dashboard"><button class="btn"><img src="<?= ROOT ?>/assets/images/dashboard.png" alt="">Dashboard</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/addproperty"><button class="btn"><img src="<?= ROOT ?>/assets/images/addproperty.png" alt="">Add Property</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/propertylisting"><button class="btn"><img src="<?= ROOT ?>/assets/images/propertylisting.png" alt="">Property Listing</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/maintenance"><button class="btn"><img src="<?= ROOT ?>/assets/images/maintenance.png" alt="">Maintenance</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/financereport"><button class="btn"><img src="<?= ROOT ?>/assets/images/finance.png" alt="">Finance Report</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/tenants"><button class="btn"><img src="<?= ROOT ?>/assets/images/tenants.png" alt="">Tenants</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/profile" data-section="profile"><button class="btn"><img src="<?= ROOT ?>/assets/images/profile.png" alt="">Profile</button></a></li>
                </ul>

                <button id="logout-btn" class="secondary-btn" style="display: none;">Logout</button>
            </div>

            <!-- <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const logoutBtn = document.getElementById('logout-btn');
                    const currentURL = window.location.href;

                    // Check if the current page is the profile page
                    if (currentURL.includes('profile')) {
                        // Show the logout button if the current page is the profile page
                        logoutBtn.style.display = 'block';
                    } else {
                        // Hide the logout button otherwise
                        logoutBtn.style.display = 'none';
                    }
                });
            </script> -->

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const sidebarLinks = document.querySelectorAll('.user_view-sidemenu ul li a');

                    // Loop through each sidebar link
                    sidebarLinks.forEach(link => {
                        const button = link.querySelector('button');
                        const href = link.getAttribute('href');
                        const currentURL = window.location.href;

                        // Check if the current page matches the link's href
                        if (currentURL.includes(href)) {
                            // Add 'active' class to the button
                            button.classList.add('active');
                            button.classList.remove('btn');
                        } else {
                            // Remove 'active' class from the button
                            button.classList.remove('active');
                            button.classList.add('btn');
                        }
                    });

                    const logoutBtn = document.getElementById('logout-btn');

                    // Check if the current page is the profile page
                    if (window.location.href.includes('profile')) {
                        // Show the logout button if the current page is the profile page
                        logoutBtn.style.display = 'block';
                    }
                });
            </script>

            <div class="user_view-content_section" id="content-section">