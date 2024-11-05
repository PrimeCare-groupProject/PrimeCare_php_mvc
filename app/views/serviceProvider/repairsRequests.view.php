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
                <option value="Pending">Pending</option>
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
                <th>Service ID</th>
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
                <td>Ref001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>10000.95 LKR</td>
                <td><span class="border-button-sm green">Done</span></td>
                <td>-</td>
            </tr>
            <tr data-href="">
                <td>2024/09/11</td>
                <td>RT001</td>
                <td>Ref001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>1000.95 LKR</td>
                <td><span class="border-button-sm orange">Pending</span></td>
                <td>36 hr</td>
            </tr>
            <tr data-href="">
                <td>2024/09/3</td>
                <td>RT001</td>
                <td>Ref001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>100040.95 LKR</td>
                <td><span class="border-button-sm green">Done</span></td>
                <td>-</td>
            </tr>
            <tr data-href="">
                <td>2024/09/30</td>
                <td>RT001</td>
                <td>Ref001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>1000.95 LKR</td>
                <td><span class="border-button-sm green">Done</span></td>
                <td>-</td>
            </tr>
            <tr data-href="">
                <td>2024/09/4</td>
                <td>RT001</td>
                <td>Ref001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>103400.95 LKR</td>
                <td><span class="border-button-sm orange">Pending</span></td>
                <td>36 hr</td>
            </tr>
            <tr data-href="">
                <td>2024/10/23</td>
                <td>RT001</td>
                <td>Ref001</td>
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
    // Event listener for status filter change
    document.getElementById('status-filter').addEventListener('change', filterTable);

    let isDateAscending = true;
    let isEarningsAscending = true;

    // Sorting logic for date
    document.getElementById('date-header').addEventListener('click', function() {
        sortTableByDate(isDateAscending);
        isDateAscending = !isDateAscending; // Toggle sorting order
    });

    // Sorting logic for earnings
    document.getElementById('earnings-header').addEventListener('click', function() {
        sortTableByEarnings(isEarningsAscending);
        isEarningsAscending = !isEarningsAscending; // Toggle sorting order
    });

    // Filtering logic
    function filterTable() {
        const statusValue = document.getElementById('status-filter').value.toLowerCase();
        const rows = document.querySelectorAll('.listing-table tbody tr');

        rows.forEach(row => {
            const status = row.querySelector('td:nth-child(7) span').textContent.toLowerCase(); // Update to correct column
            let isVisible = true;
            if (statusValue !== 'all' && status !== statusValue) {
                isVisible = false;
            }
            row.style.display = isVisible ? '' : 'none';
        });
    }

    // Sorting function for date column
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

    // Sorting function for earnings column
    function sortTableByEarnings(ascending) {
        const rows = Array.from(document.querySelectorAll('.listing-table tbody tr'));

        rows.sort((a, b) => {
            const earningsA = parseFloat(a.querySelector('td:nth-child(6)').textContent.replace(/[^0-9.-]+/g, ""));
            const earningsB = parseFloat(b.querySelector('td:nth-child(6)').textContent.replace(/[^0-9.-]+/g, ""));
            return ascending ? earningsA - earningsB : earningsB - earningsA;
        });

        const tbody = document.querySelector('.listing-table tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }

    // Event listener for row clicks
    document.querySelectorAll('.listing-table tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            // Get the status value of the current row
            const status = this.querySelector('td:nth-child(7) span').textContent.toLowerCase();

            // Redirect based on status value
            if (status === 'done') {
                // Redirect to AddLogs controller's summary view for "done" status
                window.location.href = 'index.php?url=AddLogs/summeryView';
            } else if (status === 'pending') {
                // Redirect to AddLogs controller's addLogs view for "pending" status
                window.location.href = 'index.php?url=AddLogs/addLogsView';
            }
        });
    });


</script>
