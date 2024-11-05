<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/userview.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/button.css">
    <title>Repairs</title>
</head>

<body>
    <div class="user_view-container">
        <div class="header-line">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare" class="header-logo-png">
            <button class="toggle-sidebar-btn" onclick="toggleSidebar()">☰ Menu</button>
            <img src="<?= ROOT ?>/assets/images/user.png" alt="Profile" class="header-profile-picture">
        </div>
        <div class="content-section">
            <div class="user_view-sidemenu">
                <!-- import the side bar menu items -->
                <?php require_once 'serviceProvider.sidebar.php'; ?>

                <button class="secondary-btn">Logout</button>
            </div>
            <div class="user_view-content_section">
                <?php require_once 'repairListing.view.php'; ?>
            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('sidebar');
            sidebar.style.display = (sidebar.style.display === 'none' || sidebar.style.display === '') ? 'flex' : 'none';
        }
    </script>
</body>

</html>