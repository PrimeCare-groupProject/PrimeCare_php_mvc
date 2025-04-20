<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>tenants</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
    </div>
</div>

<div class="inventory-details-container">
    <table class="inventory-table">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Tenant ID ⬆⬇</th>
                <th>Tenant Name</th>
                <th onclick="sortTable(2)">Property ID ⬆⬇</th>
                <th>Property Name</th>
                <th onclick="sortTable(4)">Total Payment ⬆⬇</th>
                <th onclick="sortTable(5)">Duration (Months) ⬆⬇</th>
            </tr>
        </thead>
        <tbody id="tenant-table-body">
            <?php if (!empty($bookings)) : ?>
                <?php foreach ($bookings as $booking) : ?>
                    <?php foreach ($persons as $person) : ?>
                        <?php foreach ($properties as $property) : ?>
                            <?php if ($booking->customer_id == $person->pid) : ?>
                                <?php if ($booking->property_id == $property->property_id) : ?>
                                    <?php if ($booking->accept_status == 'accepted') : ?>
                                        <tr onclick="window.location.href='<?= ROOT ?>/dashboard/manageProviders/managetenants/edittenants/<?= $booking->booking_id ?>'">
                                            <td><?= $booking->booking_id ?></td>
                                            <td><?= $person->fname ?> <?= $person->lname ?></td>
                                            <td><?= $booking->property_id ?></td>
                                            <td><?= $property->name ?></td>
                                            <td><?= $booking->price ?></td>
                                            <td><?= $booking->renting_period ?></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <tr style="height: 240px; background-color: #f8f9fa;">
                    <td colspan="6" style="text-align: center; vertical-align: middle; padding: 0;">
                        <div style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 0;">
                            <div style="margin-bottom: 15px; opacity: 0.5;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <path d="M13 2v7h7"></path>
                                    <circle cx="12" cy="15" r="4"></circle>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                            <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No tenants found</h3>
                            <p style="font-size: 14px; color: #777; margin: 8px 0 0 0; max-width: 400px;">
                                There are currently no tenants matching your criteria.
                            </p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-input');
    const tableBody = document.querySelector('#tenant-table-body');
    
    function performSearch() {
        const searchTerm = searchInput.value.trim().toLowerCase();
        const rows = Array.from(tableBody.querySelectorAll('tr'));

        if (searchTerm === '') {
            rows.forEach(row => row.style.display = 'table-row');
            return;
        }

        const filteredRows = rows.filter(row => {
            const tenantName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const propertyName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            const totalPayment = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
            return tenantName.includes(searchTerm) || propertyName.includes(searchTerm) || totalPayment.includes(searchTerm);
        });

        // Show only the filtered rows
        rows.forEach(row => row.style.display = 'none');  // Hide all rows
        filteredRows.forEach(row => row.style.display = 'table-row');  // Show filtered rows

        // If no results, show a message
        if (filteredRows.length === 0) {
            tableBody.innerHTML = `
                <tr style="height: 240px; background-color: #f8f9fa;">
                    <td colspan="6" style="text-align: center; vertical-align: middle; padding: 0;">
                        <div style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 0;">
                            <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No matching tenants found</h3>
                            <p style="font-size: 14px; color: #777; margin: 8px 0 0 0;">
                                No tenants match your search for "${searchTerm}".
                            </p>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Event listener for the search input field (for dynamic searching)
    searchInput.addEventListener('input', performSearch);  // Run the search function every time the user types

    // Sorting function for table columns
    const sortStates = Array(6).fill(null); // null = unsorted, 'asc' = ascending, 'desc' = descending

    function sortTable(columnIndex) {
        const rows = Array.from(tableBody.querySelectorAll("tr"));
        const sortDirection = sortStates[columnIndex] === 'asc' ? 'desc' : 'asc';
        sortStates[columnIndex] = sortDirection;

        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].textContent.trim();
            const cellB = rowB.cells[columnIndex].textContent.trim();

            let valueA, valueB;
            if (columnIndex === 4) { // Price column
                valueA = parseFloat(cellA.replace(/[^0-9.]/g, "")) || 0;
                valueB = parseFloat(cellB.replace(/[^0-9.]/g, "")) || 0;
            } else {
                valueA = cellA.toLowerCase();
                valueB = cellB.toLowerCase();
            }

            return (valueA < valueB ? -1 : valueA > valueB ? 1 : 0) * (sortDirection === 'asc' ? 1 : -1);
        });

        rows.forEach(row => tableBody.appendChild(row)); // Re-order rows
    }

    // Attach click handlers for sorting columns
    document.querySelectorAll("th").forEach((th, index) => {
        th.addEventListener('click', () => sortTable(index));
    });
});

</script>

<?php require_once 'agentFooter.view.php'; ?>
