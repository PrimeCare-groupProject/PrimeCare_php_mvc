<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Agent Management</h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input
                type="text"
                class="search-input"
                name="searchterm"
                placeholder="find agent ...">
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/managementhome/agentmanagement/addagent'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add New Agent</span>
        </div>
    </div>
</div>
<div>
    <?php for ($i = 0; $i < 5; $i++): ?>
        <div class="approval-container">
            <div class="approval-left-content">
                <h3>Agent 1</h3>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Agent ID:</strong></span><span class="input-field small_margin">A1236</span>
                    </div>
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Name:</strong></span><span class="input-field small_margin">Agent's Name</span>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Date joined:</strong></span><span class="input-field small_margin">2024/08/23</span>
                    </div>
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Salary(LKR):</strong></span><span class="input-field small_margin">200000</span>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Assigend Property:</strong></span><span class="input-field small_margin">P1234</span>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Address:</strong></span><span class="input-field small_margin">No 90 , Colombo , Sri Lanka</span>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Contact No:</strong></span><span class="input-field small_margin">0703954031</span>
                    </div>
                    <div class="input-group">
                        <span class="input-label fixed_width_for_label"><strong>Email:</strong></span><span class="input-field small_margin">agent1@gmail.com</span>
                    </div>
                </div>
            </div>
            <div class="approval-right-content">
                <button class="secondary-btn">Remove Agent</button>
                <img src="<?= ROOT ?>/assets/images/user.png" alt="property" class="rounded-images">
            </div>
        </div>
    <?php endfor; ?>
</div>

<?php require_once 'managerFooter.view.php'; ?>