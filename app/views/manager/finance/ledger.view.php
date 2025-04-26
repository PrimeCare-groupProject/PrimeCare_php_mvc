<div style="width: 100%; height: calc(95vh - 200px); overflow-y: auto; overflow-x: hidden; box-sizing: border-box;">
    <div class="financial-details-container">
        <table class="listing-table-for-customer-payments">
            <thead>
                <tr>
                    <th class="extra-space sortable first" id="date-header">
                        Date
                        <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                    </th>
                    <th>ID</th>
                    <th>Transaction</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th class="sortable" id="earnings-header">
                        Amount
                        <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ledger) {
                    foreach ($ledger as $ledgerItem): ?>
                        <tr data-href="">
                            <td><?= date('Y-m-d', strtotime($ledgerItem->created_at)) ?></td>
                            <td><?= $ledgerItem->id ?></td>
                            <td><?= getTransactionType($ledgerItem->transaction_type) ?></td>
                            <td><?= getReferenceType($ledgerItem->reference_type) ?></td>
                            <td><?= $ledgerItem->reference_id ?></td>
                            <td><?= $ledgerItem->amount ?></td>
                        </tr>
                <?php endforeach;
                } ?>
            </tbody>
        </table>
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
            const earningsA = parseFloat(a.querySelector('td:nth-child(6)').textContent.replace(/[^0-9.-]+/g, ""));
            const earningsB = parseFloat(b.querySelector('td:nth-child(6)').textContent.replace(/[^0-9.-]+/g, ""));
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