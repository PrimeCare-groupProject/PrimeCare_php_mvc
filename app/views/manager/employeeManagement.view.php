<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Agent Management </h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Employee ID...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons"></button>
        </div>
    </div>
</div>

<div class="content-section">
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

<?php require_once 'managerFooter.view.php'; ?>