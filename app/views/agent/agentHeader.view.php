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
                    <li><a href="<?= ROOT ?>/dashboard/managementhome"><button class="btn"><img src="<?= ROOT ?>/assets/images/manage.png" alt="">Managements</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/contacts"><button class="btn"><img src="<?= ROOT ?>/assets/images/contact.png" alt="">Contacts</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/profile" data-section="profile"><button class="btn"><img src="<?= ROOT ?>/assets/images/profile.png" alt="">Profile</button></a></li>
                </ul>

                <button id="logout-btn" class="secondary-btn" style="display: none;">Logout</button>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const sidebarLinks = document.querySelectorAll('.user_view-sidemenu ul li a');
                    let isTabActive = false;
                    const currentURL = window.location.href;
                    const url = new URL(window.location.href);  // Get the current page URL
                    const path = url.pathname.replace(/^\/|\/$/g, '').split('/');  // Split the URL into an array
                    const currentPage = path[3] || "dashboard";
                    console.log(path[3]);
                    // Loop through each sidebar link
                    sidebarLinks.forEach(link => {
                        const button = link.querySelector('button');
                        const href = link.getAttribute('href');
                        console.log(href);
                        // Check if the current page matches the link's href
                        if (currentURL.includes(href)) {
                            // Add 'active' class to the button
                            button.classList.add('active');
                            button.classList.remove('btn');
                            isTabActive = true;  // Mark that a tab is active
                        } else {
                            // Remove 'active' class from the button
                            button.classList.remove('active');
                            button.classList.add('btn');
                        }
                    });

                    // If no tab is active, set the dashboard as the default active
                    const dashboardButton = document.querySelector('a[href*="dashboard"] button');
                    if (isTabActive && !(currentPage == "dashboard")) {
                        // console.log(" tab is active");
                        if (dashboardButton) {
                            // console.log(" button available");
                            dashboardButton.classList.add('btn');
                            dashboardButton.classList.remove('active');
                        }
                    }else{
                        // console.log(" tab is not active");

                        dashboardButton.classList.add('active');
                        dashboardButton.classList.remove('btn');
                    }

                    const logoutBtn = document.getElementById('logout-btn');

                    // Check if the current page is the profile page and show the logout button
                    if (window.location.href.includes('profile')) {
                        logoutBtn.style.display = 'block';
                    }
                });
            </script>

            <div class="user_view-content_section" id="content-section">