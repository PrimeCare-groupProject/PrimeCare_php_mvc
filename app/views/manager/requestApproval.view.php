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
                placeholder="find request ..."
            >
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>
<div>
    <div class="filter-container-flex-start">
        <div class="input-group-aligned width-100">
            <label for="year-filter" class="input-label">Year:</label>
            <select id="year-filter" class="input-field-small">
                <option value="all">All</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <!-- Add more years as needed -->
            </select>
        </div>
        <div class="input-group-aligned width-100">
            <label for="month-filter" class="input-label">Month:</label>
            <select id="month-filter" class="input-field-small">
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
        <?php require __DIR__ . '/../components/requestApprovalCard.php'; ?>
        <?php require __DIR__ . '/../components/requestApprovalCard.php'; ?>
        <?php require __DIR__ . '/../components/requestApprovalCard.php'; ?>
        <?php require __DIR__ . '/../components/requestApprovalCard.php'; ?>
    </div>
</div>

<?php require_once 'managerFooter.view.php'; ?>