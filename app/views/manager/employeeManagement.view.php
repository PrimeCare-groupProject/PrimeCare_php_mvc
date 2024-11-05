<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/userview.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/button.css">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/employeeManagement.css">
    <title>Employee Management</title>
</head>

<body>
    <div class="user_view-container">
        <div class="header-line">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="PrimeCare Logo" class="header-logo-png">
            <button class="toggle-sidebar-btn" onclick="toggleSidebar()">☰ Menu</button>
            <img src="<?= ROOT ?>/assets/images/user.png" alt="User Profile Picture" class="header-profile-picture">
        </div>

        <div class="user_view-menu-bar">
            <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
            <h2>Agent Management </h2>
            <div class="flex-bar">
                <div class="search-container">
                    <input type="text" class="search-input" placeholder="Search Employee ID...">
                    <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons"></button>
                </div>
            </div>
        </div>

        <div class="content-section">
            <div class="user_view-sidemenu">
                <!-- Import the sidebar menu items -->
                <?php require_once 'manager.sidebar.php'; ?>

                <!-- Logout button -->
                <button class="secondary-btn">Logout</button>
            </div>

            <!-- Agent listing section -->
            <div class="agent-list">
                <?php
                // Sample agent data (could be pulled from a database)
                $agents = [
                    [
                        "name" => "Agent1",
                        "date_joined" => "2024/07/26",
                        "agent_id" => "12233",
                        "address" => "66/2 marine drive, Colombo",
                        "property_id" => "11320",
                        "salary" => "55000.00",
                        "image" => "../../../public/assets/images/Agent.png" 
                    ],
                    [
                        "name" => "Agent2",
                        "date_joined" => "2024/07/26",
                        "agent_id" => "12233",
                        "address" => "66/2 marine drive, Colombo",
                        "property_id" => "11320",
                        "salary" => "55000.00",
                        "image" => "../../../public/assets/images/Agent.png" 
                    ],
                    [
                        "name" => "Agent3",
                        "date_joined" => "2024/07/26",
                        "agent_id" => "12233",
                        "address" => "66/2 marine drive, Colombo",
                        "property_id" => "11320",
                        "salary" => "55000.00",
                        "image" => "../../../public/assets/images/Agent.png" 
                    ]
                ];

                // Loop through each agent and render the details
                foreach ($agents as $agent) {
                    echo "
                    <div class='agent-card'>
                        <div class='agent-details'>
                            <p><Strong>Name </Strong>: {$agent['name']}</p>
                            <p><Strong>Date joined </Strong>: {$agent['date_joined']}</p>
                            <p><Strong>Agent ID </Strong>: {$agent['agent_id']}</p>
                            <p><Strong>Address </Strong>: {$agent['address']}</p>
                        </div>
                        <div class='agent-info'>
                            <p><Strong>Current assigned property ID </Strong>: {$agent['property_id']}</p>
                            <p><Strong>Salary (LKR) </Strong>: {$agent['salary']}</p>
                        </div>
                        <div class='agent-remove'>
                            <img src='" . ROOT . "/assets/images/{$agent['image']}' alt='Agent Image' class='agent-img'>
                            <form action='remove_agent.php' method='POST'>
                                <input type='hidden' name='agent_id' value='{$agent['agent_id']}' />
                                <button type='submit' class='remove-btn'>Remove Agent</button>
                            </form>
                        </div>
                    </div>
                    <hr>";
                }
                ?>
            </div>
        </div>
    </div>

    <script>
        // Function to toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.querySelector('.user_view-sidemenu');
            sidebar.classList.toggle('active');
        }
    </script>
</body>

</html>
