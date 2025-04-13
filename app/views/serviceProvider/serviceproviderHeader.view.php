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
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/loader.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/customer.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/propertyListingCssForAll.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Notifications.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <!-- toggle button -->
            <form method="post" class="toggle_wrapper" action="<?= ROOT ?>/dashboard/switchUser">
                <div class="toggle-button tooltip-container">
                    <span class="tooltip-text">Change To Customer Mood</span>
                    <!-- Outer track -->
                    <div class="toggle-track" id="toggleTrack" onclick="submitToggleForm()">
                        <!-- Inner knob -->
                        <div class="toggle-knob"></div>
                        <input type="hidden" name="toggle_state" id="toggleState" value="<?= isset($_SESSION['toggle_state']) ? $_SESSION['toggle_state'] : '0' ?>">
                        <input type="submit" name="toggle_btn" value="1" hidden>
                    </div>
                </div>
                
                <?php require __DIR__ . '/../components/notificationComponent.view.php'; ?>

                <a href="<?= ROOT ?>/dashboard/profile"><img src="<?= get_img($_SESSION['user']->image_url) ?>" alt="" class="header-profile-picture"></a>
            </form>
        </div>
        <div class="content-section">
            <div class="user_view-sidemenu">
                <ul>
                    <li><a href="<?= ROOT ?>/dashboard"><button class="btn"><i class="fa-solid fa-gauge"></i> Dashboard</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/repairlisting"><button class="btn"><i class="fa-solid fa-screwdriver-wrench"></i>Services</button></a></li>
                    <!--<li><a href="<?= ROOT ?>/dashboard/addlogs"><button class="btn"><img src="<?= ROOT ?>/assets/images/logs.png" alt="">Logs</button></a></li>-->
                    <li><a href="<?= ROOT ?>/dashboard/repairRequests"><button class="btn"><i class="fa-solid fa-envelope-open-text"></i>Repair Requests</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/earnings"><button class="btn"><i class="fa-solid fa-money-check-dollar"></i>Earnings</button></a></li>
                    <li><a href="<?= ROOT ?>/dashboard/profile" data-section="profile"><button class="btn"><i class="fa-solid fa-user"></i> Profile</button></a></li>
                </ul>
                <form method="post" id="logout">
                    <button id="logout-btn" class="secondary-btn" style="display: none;">Logout</button>
                    <input type="text" name="logout" value="1" hidden>
                </form>
            </div>

            <script>
                const BASE_URL = "<?= ROOT ?>";
            </script>

            <script src="<?= ROOT ?>/assets/js/notificationHandler.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const sidebarLinks = document.querySelectorAll('.user_view-sidemenu ul li a');
                    let isTabActive = false;
                    const currentURL = window.location.href;
                    const url = new URL(window.location.href); // Get the current page URL
                    const path = url.pathname.replace(/^\/|\/$/g, '').split('/'); // Split the URL into an array
                    const currentPage = path[3] || "dashboard";
                    console.log(path[3]);
                    // Loop through each sidebar link
                    sidebarLinks.forEach(link => {
                        const button = link.querySelector('button');
                        const href = link.getAttribute('href');

                        // Check if the current page matches the link's href
                        if (currentURL.includes(href)) {
                            // Add 'active' class to the button
                            button.classList.add('active');
                            button.classList.remove('btn');
                            isTabActive = true; // Mark that a tab is active
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
                    } else {
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

                function submitToggleForm() {
                    const submitBtn = document.querySelector('.toggle_wrapper');
                    const toggleTrack = document.getElementById('toggleTrack');

                    toggleTrack.classList.add('activeToggle');

                    setTimeout(() => {
                        submitBtn.submit();
                        displayLoader();
                    }, 300);
                }

                //loader effect
                function displayLoader() {
                    document.querySelector('.loader-container').style.display = '';
                    //onclick="displayLoader()"
                }

                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', displayLoader);
                });

                document.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', displayLoader);
                });
            </script>


            <div class="user_view-content_section" id="content-section">

                <?php
                if (isset($_SESSION['flash'])) {
                    flash_message();
                }
                ?>