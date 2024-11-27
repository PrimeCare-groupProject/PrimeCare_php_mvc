<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/managementhome/propertymanagement'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>request Approval</h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input
                type="text"
                class="search-input"
                name="searchterm"
                placeholder="Find request...">
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>
<div>
    <div class="filter-container-flex-start">
        <div class="input-group">
            <label for="year-filter" class="input-label">Year:</label>
            <select id="year-filter" class="input-field">
                <option value="all">All</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <!-- Add more years as needed -->
            </select>
        </div>
        <div class="input-group">
            <label for="month-filter" class="input-label">Month:</label>
            <select id="month-filter" class="input-field">
                <option value="all">All</option>
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="Novermber">Novermber</option>
                <option value="December">December</option>
            </select>
        </div>
    </div>
    <div>
        <?php foreach($requests as $request): ?>
            <div class="approval-container">
                <div class="approval-left-content">
                    <h3>Update Request 1</h3>
                    <div class="input-group">
                        <div class="input-group">
                            <span class="input-label fixed_width_for_label"><strong>Property ID:</strong></span><span class="input-field small_margin"><?= $request->property_id ?></span>
                        </div>
                        <div class="input-group">
                            <span class="input-label fixed_width_for_label"><strong>Price(LKR):</strong></span><span class="input-field small_margin"><?= $request->rent_on_basis ?></span>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-group">
                            <span class="input-label fixed_width_for_label"><strong>Date:</strong></span><span class="input-field small_margin">2024/08/23</span>
                        </div>
                        <div class="input-group">
                            <span class="input-label fixed_width_for_label"><strong>Agent ID:</strong></span><span class="input-field small_margin"><?= $request->agent_id ?></span>
                        </div>
                    </div>
                    <div class="input-group">
                        <div class="input-group">
                            <span class="input-label fixed_width_for_label"><strong>Address:</strong></span><span class="input-field small_margin"><?= $request->address ?></span>
                        </div>
                    </div>
                    <!-- <div class="input-group">
                        <div class="input-group">
                            <span class="input-label fixed_width_for_label"><strong>Description:</strong></span><span class="input-field small_margin"><?= $request->description ?></span>
                        </div>
                    </div> -->
                </div>
                <div class="approval-right-content">
                    <button class="primary-btn" onclick="window.location.href='<?= ROOT ?>/dashboard/comparePropertyUpdate/<?= $request->property_id ?>'">See Changes</button>
                    <!-- <button class="secondary-btn">Decline</button> -->
                    <img src="<?= ROOT ?>/assets/images/listing_alt.jpg" alt="property" class="approval-right-content-img">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'managerFooter.view.php'; ?>