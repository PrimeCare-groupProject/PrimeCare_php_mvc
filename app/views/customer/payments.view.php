<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>Payments</h2>
</div>

<div class="financial-details-container">
    <table class="listing-table-for-customer-payments">
        <thead>
            <tr>
                <th class="extra-space sortable" id="date-header">
                    Date
                    <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                </th>
                <th>Reference Number</th>
                <th>Property ID</th>
                <th>Property Name</th>
                <th class="sortable" id="earnings-header">
                    Payment
                    <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                </th>
                <th>Payment Status</th>
                <th>Receipt</th>
            </tr>
        </thead>
        <tbody>
            <tr data-href="">
                <td>2024/09/02</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>10000.95 LKR</td>
                <td><span class="border-button-sm green">Paid</span></td>
                <td><a href="/generate-receipt/RT001" class="border-button-sm green">Generate Receipt</a></td>
            </tr>
            <tr data-href="">
                <td>2024/09/11</td>
                <td>RT002</td>
                <td>P124</td>
                <td>Seaside Cottage</td>
                <td>1000.95 LKR</td>
                <td><span class="border-button-sm orange">Pending</span></td>
                <td><a href="/proceed-to-pay/RT002" class="border-button-sm orange">Proceed to Pay</a></td>
            </tr>
            <tr data-href="">
                <td>2024/09/3</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>100040.95 LKR</td>
                <td><span class="border-button-sm green">Paid</span></td>
                <td><a href="/generate-receipt/RT001" class="border-button-sm green">Generate Receipt</a></td>
            </tr>
            <tr data-href="">
                <td>2024/09/30</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>1000.95 LKR</td>
                <td><span class="border-button-sm green">Paid</span></td>
                <td><a href="/generate-receipt/RT001" class="border-button-sm green">Generate Receipt</a></td>
            </tr>
            <tr data-href="">
                <td>2024/09/4</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>103400.95 LKR</td>
                <td><span class="border-button-sm orange">pending</span></td>
                <td><a href="/proceed-to-pay/RT002" class="border-button-sm orange">Proceed to Pay</a></td>
            </tr>
            <tr data-href="">
                <td>2024/10/23</td>
                <td>RT001</td>
                <td>P123</td>
                <td>Oceanvilla</td>
                <td>10.95 LKR</td>
                <td><span class="border-button-sm green">Paid</span></td>
                <td><a href="/generate-receipt/RT001" class="border-button-sm green">Generate Receipt</a></td>
            </tr>
            <!-- Additional rows here -->
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

    function sortTableByDate(ascending) {
        const rows = Array.from(document.querySelectorAll('.listing-table-for-customer-payments tbody tr'));

        rows.sort((a, b) => {
            const dateA = new Date(a.querySelector('td:nth-child(1)').textContent);
            const dateB = new Date(b.querySelector('td:nth-child(1)').textContent);
            return ascending ? dateA - dateB : dateB - dateA;
        });

        const tbody = document.querySelector('.listing-table-for-customer-payments tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }

    function sortTableByEarnings(ascending) {
        const rows = Array.from(document.querySelectorAll('.listing-table-for-customer-payments tbody tr'));

        rows.sort((a, b) => {
            const earningsA = parseFloat(a.querySelector('td:nth-child(5)').textContent.replace(/[^0-9.-]+/g, ""));
            const earningsB = parseFloat(b.querySelector('td:nth-child(5)').textContent.replace(/[^0-9.-]+/g, ""));
            return ascending ? earningsA - earningsB : earningsB - earningsA;
        });

        const tbody = document.querySelector('.listing-table-for-customer-payments tbody');
        rows.forEach(row => tbody.appendChild(row)); // Re-append sorted rows
    }

    document.querySelectorAll('.listing-table-for-customer-payments tbody tr').forEach(row => {
        row.addEventListener('mouseover', function() {
            this.style.backgroundColor = "#f0f0f0"; // Light gray background on hover
        });

        row.addEventListener('mouseout', function() {
            this.style.backgroundColor = ""; // Reset background color when not hovering
        });
    });
</script>

<?php require 'customerFooter.view.php' ?>