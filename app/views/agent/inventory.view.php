<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>inventory</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/inventory/newinventory'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new inventory</span>
        </div>
    </div>
</div>
<div class="inventory-details-container">
    <table class="inventory-table">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Inventory ID ⬆⬇</th>
                <th>Inventory Name</th>
                <th onclick="sortTable(2)">Property ID ⬆⬇</th>
                <th>Property Name</th>
                <th onclick="sortTable(4)">Price ⬆⬇</th>
                <th onclick="sortTable(5)">Date ⬆⬇</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
        
    </table>
</div>

<script>
    function openFullPage(inventoryId) {
        window.location.href = "<?= ROOT ?>/inventory/view/" + inventoryId;
    }

    function sortTable(columnIndex) {
        let table = document.querySelector(".inventory-table");
        let rows = Array.from(table.rows).slice(1); // Exclude header row
        let isAscending = table.getAttribute("data-sort-order") === "asc";
        
        rows.sort((rowA, rowB) => {
            let cellA = rowA.cells[columnIndex].innerText.trim();
            let cellB = rowB.cells[columnIndex].innerText.trim();

            // Convert Price and Date for correct sorting
            if (columnIndex === 4) { // Price column
                cellA = parseFloat(cellA.replace(/[^0-9.]/g, "")) || 0;
                cellB = parseFloat(cellB.replace(/[^0-9.]/g, "")) || 0;
            } else if (columnIndex === 5) { // Date column
                cellA = new Date(cellA);
                cellB = new Date(cellB);
            }

            return isAscending ? (cellA > cellB ? 1 : -1) : (cellA < cellB ? 1 : -1);
        });

        // Reorder rows in the table
        rows.forEach(row => table.appendChild(row));

        // Toggle sorting order
        table.setAttribute("data-sort-order", isAscending ? "desc" : "asc");
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>