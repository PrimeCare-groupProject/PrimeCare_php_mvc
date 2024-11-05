<div class="user_view-menu-bar">
    <h2>request Approval</h2>
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
        <?php require __DIR__ . './../components/requestApprovalCard.php'; ?>
        <?php require __DIR__ . './../components/requestApprovalCard.php'; ?>
        <?php require __DIR__ . './../components/requestApprovalCard.php'; ?>
        <?php require __DIR__ . './../components/requestApprovalCard.php'; ?>
    </div>
</div>