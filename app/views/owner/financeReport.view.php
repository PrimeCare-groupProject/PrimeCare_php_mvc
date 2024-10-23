<?php require_once 'ownerHeader.view.php'; ?>
<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <!-- <img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"> -->
            <div>
                <h2>Name of Property</h2>
                <p><span>Maintained By: </span>Agent's Name</p>
            </div>
        </div>
        <img src="<?= ROOT ?>/assets/images/printer.png" alt="Print" class="small-icons align-to-right">
    </div>
</div>

<div class="financial-details-container">
    <h2>Financial Details</h2>

    <!-- Filter Options -->
    <div class="filter-container">
        <div>
            <label for="year-filter">Year:</label>
            <select id="year-filter">
                <option value="all">All</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
                <!-- Add more years as needed -->
            </select>
        </div>

        <div>
            <label for="month-filter">Month:</label>
            <select id="month-filter">
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

                <!-- Add more months as needed -->
            </select>
        </div>

        <div>
            <label for="tenant-filter">Tenant:</label>
            <input type="text" id="tenant-filter" placeholder="Enter tenant name">
        </div>
    </div>

    <!-- Table -->
    <table class="financial-table">
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Tenant</th>
                <th>Income</th>
                <th>Expenses</th>
                <th>Total Income</th>
                <th>Status</th>
                <th>Profit Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>2024</td>
                <td>October</td>
                <td>Matt Dickerson</td>
                <td>400000.95 LKR</td>
                <td>100000.95 LKR</td>
                <td>300000.00 LKR</td>
                <td><span class="border-button-sm green">Occupied</span></td>
                <td><span class="border-button-sm green">Profit</span></td>
            </tr>
            <tr>
                <td>2024</td>
                <td>September</td>
                <td>Matt Dickerson</td>
                <td>400000.95 LKR</td>
                <td>100000.95 LKR</td>
                <td>300.00 LKR</td>
                <td><span class="border-button-sm red">Occupied</span></td>
                <td><span class="border-button-sm red">Profit</span></td>
            </tr>
            <tr>
                <td>2024</td>
                <td>August</td>
                <td>Matt Dickerson</td>
                <td>400000.95 LKR</td>
                <td>100000.95 LKR</td>
                <td>300000.00 LKR</td>
                <td><span class="border-button-sm orange">Occupied</span></td>
                <td><span class="border-button-sm orange">Profit</span></td>
            </tr>
            <!-- Add more rows as needed -->
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
    </div>
</div>

<script>
    document.getElementById('year-filter').addEventListener('change', filterTable);
    document.getElementById('month-filter').addEventListener('change', filterTable);
    document.getElementById('tenant-filter').addEventListener('keyup', filterTable);

    function filterTable() {
        const yearValue = document.getElementById('year-filter').value;
        const monthValue = document.getElementById('month-filter').value.toLowerCase();
        const tenantValue = document.getElementById('tenant-filter').value.toLowerCase();

        const rows = document.querySelectorAll('.financial-table tbody tr');

        rows.forEach(row => {
            const year = row.querySelector('td:nth-child(1)').textContent;
            const month = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const tenant = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

            let isVisible = true;

            if (yearValue !== 'all' && year !== yearValue) {
                isVisible = false;
            }

            if (monthValue !== 'all' && month !== monthValue) {
                isVisible = false;
            }

            if (tenantValue && !tenant.includes(tenantValue)) {
                isVisible = false;
            }

            row.style.display = isVisible ? '' : 'none';
        });
    }
</script>

<?php require_once 'ownerFooter.view.php'; ?>