<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href="<?= ROOT ?>/dashboard/bookings">
        <button class="back-btn">
            <img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons">
        </button>
    </a>
    <h2>Booking History</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything..." id="searchInput">
            <button class="search-btn">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons">
            </button>
        </div>
    </div>
</div>

<div class="inventory-details-container">
    <table class="inventory-table">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Booking ID</th>
                <th>Property Name</th>
                <th>Customer Name</th>
                <th onclick="sortTable(3)">Renting Period</th>
                <th>Payment Status</th>
                <th>Booking Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)) : ?>
                <?php foreach ($bookings as $booking) : ?>
                    <tr >
                        <td><?= $booking->booking_id ?></td>
                        <td><?= $booking->name ?></td>
                        <td><?= $booking->fname ?> <?= $booking->lname ?></td>
                        <td><?= $booking->renting_period ?></td>
                        <td><?= $booking->payment_status ?></td>
                        <td><?= $booking->accept_status ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding: 60px 0;">
                        <div style="opacity: 0.6;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <path d="M13 2v7h7"></path>
                                <circle cx="12" cy="15" r="4"></circle>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                            <h3 style="margin-top: 10px; color: #666;">No bookings found</h3>
                            <p style="color: #999;">There are currently no bookings available in the system.</p>
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    const sortStates = [];

    function sortTable(columnIndex) {
        const table = document.querySelector(".inventory-table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        
        // Determine current sort direction
        sortStates[columnIndex] = sortStates[columnIndex] === 'asc' ? 'desc' : 'asc';
        const direction = sortStates[columnIndex];

        // Sort rows based on column
        rows.sort((a, b) => {
            const cellA = a.cells[columnIndex].textContent.trim();
            const cellB = b.cells[columnIndex].textContent.trim();
            
            let valA = cellA, valB = cellB;

            if (!isNaN(Date.parse(cellA))) {
                valA = new Date(cellA);
                valB = new Date(cellB);
            } else if (!isNaN(cellA)) {
                valA = parseFloat(cellA);
                valB = parseFloat(cellB);
            } else {
                valA = cellA.toLowerCase();
                valB = cellB.toLowerCase();
            }

            if (valA < valB) return direction === 'asc' ? -1 : 1;
            if (valA > valB) return direction === 'asc' ? 1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    }

    // Optional: Search filter functionality (basic)
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll(".inventory-table tbody tr");
        rows.forEach(row => {
            const match = [...row.cells].some(cell =>
                cell.textContent.toLowerCase().includes(filter)
            );
            row.style.display = match ? '' : 'none';
        });
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>
