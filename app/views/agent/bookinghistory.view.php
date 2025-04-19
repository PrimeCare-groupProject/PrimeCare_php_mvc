<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/bookings'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>booking history</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
    </div>
</div>
<style>
    .bookings-table-container {
        width: 100%;
        margin: 20px 0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .bookings-table thead {
        background-color: #2c3e50;
        color: white;
    }

    .bookings-table th {
        padding: 16px 12px;
        text-align: left;
        font-weight: 600;
        position: relative;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .bookings-table th:hover {
        background-color: #34495e;
    }

    .bookings-table th.sortable::after {
        content: "⬆⬇";
        position: absolute;
        right: 8px;
        opacity: 0.6;
        font-size: 12px;
    }

    .bookings-table tbody tr {
        border-bottom: 1px solid #e0e0e0;
        transition: background-color 0.2s;
    }

    .bookings-table tbody tr:last-child {
        border-bottom: none;
    }

    .bookings-table tbody tr:hover {
        background-color: #f5f7fa;
        cursor: pointer;
    }

    .bookings-table td {
        padding: 14px 12px;
        color: #333;
    }

    .bookings-table .status-pending {
        color: #e67e22;
        font-weight: 500;
    }

    .bookings-table .status-accepted {
        color: #27ae60;
        font-weight: 500;
    }

    .bookings-table .status-rejected {
        color: #e74c3c;
        font-weight: 500;
    }

    .bookings-empty-state {
        height: 240px;
        background-color: #f8f9fa;
        text-align: center;
    }

    .bookings-empty-content {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px 0;
    }

    .bookings-empty-icon {
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .bookings-empty-title {
        font-size: 16px;
        color: #555;
        margin: 0;
        font-weight: 500;
    }

    .bookings-empty-message {
        font-size: 14px;
        color: #777;
        margin: 8px 0 0 0;
        max-width: 400px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .bookings-table th, 
        .bookings-table td {
            padding: 12px 8px;
            font-size: 14px;
        }
    }
</style>

<div class="bookings-table-container">
    <table class="bookings-table">
        <thead>
            <tr>
                <th class="sortable" onclick="sortTable(0)">Booking ID</th>
                <th>Property Name</th>
                <th>Customer Name</th>
                <th class="sortable" onclick="sortTable(3)">Renting Period</th>
                <th>Payment Status</th>
                <th>Accept Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)) : ?>
                <?php foreach ($bookings as $booking) : ?>
                    <tr onclick="window.location.href='<?= ROOT ?>/dashboard/bookings/history/showhistory/<?= $booking->booking_id ?>'">
                        <td><?= $booking->booking_id ?></td>
                        <td><?= $booking->name ?></td>
                        <td><?= $booking->fname ?> <?= $booking->lname ?></td>
                        <td><?= $booking->renting_period ?> months</td>
                        <td class="status-<?= strtolower($booking->payment_status) ?>">
                            <?= $booking->payment_status ?>
                        </td>
                        <td class="status-<?= strtolower($booking->accept_status) ?>">
                            <?= $booking->accept_status ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="bookings-empty-state">
                    <td colspan="6">
                        <div class="bookings-empty-content">
                            <div class="bookings-empty-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <path d="M13 2v7h7"></path>
                                    <circle cx="12" cy="15" r="4"></circle>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                            <h3 class="bookings-empty-title">No bookings found</h3>
                            <p class="bookings-empty-message">
                                There are currently no bookings matching your criteria.
                            </p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    // Track sorting state for each column
    const sortStates = Array(6).fill(null); // null = unsorted, 'asc' = ascending, 'desc' = descending
    
    function sortTable(columnIndex) {
        const table = document.querySelector(".bookings-table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr:not(.bookings-empty-state)"));
        const headers = table.querySelectorAll("th");
        
        // Skip if empty state is shown
        if (rows.length === 0) return;
        
        // Toggle sort direction
        sortStates[columnIndex] = sortStates[columnIndex] === 'asc' ? 'desc' : 'asc';
        const sortDirection = sortStates[columnIndex];
        
        // Reset sort indicators on all headers
        headers.forEach((header, index) => {
            if (index !== columnIndex) {
                sortStates[index] = null;
                header.classList.remove('sorted-asc', 'sorted-desc');
            }
        });
        
        // Update current header
        const currentHeader = headers[columnIndex];
        currentHeader.classList.remove('sorted-asc', 'sorted-desc');
        currentHeader.classList.add(sortDirection === 'asc' ? 'sorted-asc' : 'sorted-desc');
        
        // Sort rows
        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].textContent.trim();
            const cellB = rowB.cells[columnIndex].textContent.trim();
            
            let valueA, valueB;
            
            if (columnIndex === 3) { // Renting Period column
                valueA = parseInt(cellA) || 0;
                valueB = parseInt(cellB) || 0;
            } else if (columnIndex === 0) { // Booking ID column
                valueA = parseInt(cellA) || 0;
                valueB = parseInt(cellB) || 0;
            } else {
                valueA = cellA.toLowerCase();
                valueB = cellB.toLowerCase();
            }
            
            if (valueA < valueB) return sortDirection === 'asc' ? -1 : 1;
            if (valueA > valueB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>