<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>repair requests</h2>
</div>


<div class="financial-details-container">
    <div class="filter-container">
        <div>
            <label for="status-filter">Status:</label>
            <select id="status-filter">
                <option value="all">All</option>
                <option value="done">Done</option>
                <option value="pending">Pending</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <table class="listing-table">
        <thead>
            <tr>
                <th class="extra-space sortable" id="date-header">
                    Date
                    <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                </th>
                <th>Service Type</th>
                <th>Property ID</th>
                <th>Property Name</th>
                <th class="sortable" id="earnings-header">
                    Earnings
                    <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                </th>
                <th>Status</th>
                <th>Time left</th>
            </tr>
        </thead>
        <tbody>
            <tr data-href="">
                <td>2024/09/2</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>10000.95 LKR</td>
                <td><span class="border-button-sm green">Done</span></td>
                <td>-</td>
            </tr>
            <tr data-href="">
                <td>2024/09/11</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>1000.95 LKR</td>
                <td><span class="border-button-sm orange">pending</span></td>
                <td>36 hr</td>
            </tr>
            <tr data-href="">
                <td>2024/09/3</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>100040.95 LKR</td>
                <td><span class="border-button-sm green">Done</span></td>
                <td>-</td>
            </tr>
            <tr data-href="">
                <td>2024/09/30</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>1000.95 LKR</td>
                <td><span class="border-button-sm green">Done</span></td>
                <td>-</td>
            </tr>
            <tr data-href="">
                <td>2024/09/4</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>103400.95 LKR</td>
                <td><span class="border-button-sm orange">pending</span></td>
                <td>36 hr</td>
            </tr>
            <tr data-href="">
                <td>2024/10/23</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>10.95 LKR</td>
                <td><span class="border-button-sm green">Done</span></td>
                <td>-</td>
            </tr>
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
    document.getElementById('status-filter').addEventListener('change', filterTable);

    let isDateAscending = true;
    let isEarningsAscending = true;

    document.getElementById('date-header').addEventListener('click', function() {
        sortTableByDate(isDateAscending);
        isDateAscending = !isDateAscending; // Toggle sorting order
    });

    document.getElementById('earnings-header').addEventListener('click', function() {
        sortTableByEarnings(isEarningsAscending);
        isEarningsAscending = !isEarningsAscending; // Toggle sorting order
    });

    function filterTable() {
        const statusValue = document.getElementById('status-filter').value.toLowerCase();

        const rows = document.querySelectorAll('.listing-table tbody tr');

        rows.forEach(row => {
            const status = row.querySelector('td:nth-child(6) span').textContent.toLowerCase();

            let isVisible = true;

            if (statusValue !== 'all' && status !== statusValue) {
                isVisible = false;
            }

            row.style.display = isVisible ? '' : 'none';
        });
    }

    function sortTableByDate(ascending) {
        const rows = Array.from(document.querySelectorAll('.listing-table tbody tr'));

        rows.sort((a, b) => {
            const dateA = new Date(a.querySelector('td:nth-child(1)').textContent);
            const dateB = new Date(b.querySelector('td:nth-child(1)').textContent);
            return ascending ? dateA - dateB : dateB - dateA;
        });

        const tbody = document.querySelector('.listing-table tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }

    function sortTableByEarnings(ascending) {
        const rows = Array.from(document.querySelectorAll('.listing-table tbody tr'));

        rows.sort((a, b) => {
            const earningsA = parseFloat(a.querySelector('td:nth-child(5)').textContent.replace(/[^0-9.-]+/g, ""));
            const earningsB = parseFloat(b.querySelector('td:nth-child(5)').textContent.replace(/[^0-9.-]+/g, ""));
            return ascending ? earningsA - earningsB : earningsB - earningsA;
        });

        const tbody = document.querySelector('.listing-table tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }

    document.querySelectorAll('.listing-table tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            window.location.href = this.dataset.href; // Redirect to the URL in the data-href attribute
        });
    });
</script>

<?php require 'serviceproviderFooter.view.php' ?>