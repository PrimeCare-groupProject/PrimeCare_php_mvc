<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>inventory</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything..." id="inventory-search">
            <button class="search-btn" id="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
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
                <th onclick="sortTable(1)">Inventory Name ⬆⬇</th>
                <th onclick="sortTable(2)">Property ID ⬆⬇</th>
                <th onclick="sortTable(3)">Property Name ⬆⬇</th>
                <th onclick="sortTable(4)">Price ⬆⬇</th>
                <th onclick="sortTable(5)">Date ⬆⬇</th>
            </tr>
        </thead>
        <tbody id="inventory-table-body">
            <?php if (!empty($inventories)) : ?>
                <?php foreach ($inventories as $inventory) : ?>
                    <tr onclick="window.location.href='<?= ROOT ?>/dashboard/inventory/editinventory/<?= $inventory->inventory_id ?>'">
                        <td><?= $inventory->inventory_id ?></td>
                        <td><?= $inventory->inventory_name ?></td>
                        <td><?= $inventory->property_id ?></td>
                        <td><?= $inventory->property_name ?></td>
                        <td><?= $inventory->total_price ?></td>
                        <td><?= $inventory->date ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr style="height: 240px; background-color: #f8f9fa;">
                    <td colspan="6" style="text-align: center; vertical-align: middle; padding: 0;">
                        <div style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 0;">
                            <!-- Empty state icon -->
                            <div style="margin-bottom: 15px; opacity: 0.5;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                    <path d="M13 2v7h7"></path>
                                    <circle cx="12" cy="15" r="4"></circle>
                                    <line x1="9" y1="15" x2="15" y2="15"></line>
                                </svg>
                            </div>
                            
                            <!-- Message -->
                            <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No inventory items found</h3>
                            <p style="font-size: 14px; color: #777; margin: 8px 0 0 0; max-width: 400px;">
                                There are currently no inventory items matching your criteria.
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
    let originalRows = []; // Store original rows for search functionality
    
    function sortTable(columnIndex) {
        const table = document.querySelector(".inventory-table");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr"));
        const isDateColumn = columnIndex === 5; // Date column
        const isPriceColumn = columnIndex === 4; // Price column
        
        // Toggle sort direction
        sortStates[columnIndex] = sortStates[columnIndex] === 'asc' ? 'desc' : 'asc';
        const sortDirection = sortStates[columnIndex];
        
        // Reset sort indicators on all headers
        table.querySelectorAll("th").forEach((th, index) => {
            if (index !== columnIndex) {
                th.innerHTML = th.textContent.trim();
                sortStates[index] = null;
            }
        });
        
        // Update sort indicator on current header
        const currentHeader = table.querySelectorAll("th")[columnIndex];
        currentHeader.innerHTML = `${currentHeader.textContent.trim()} `;
        
        // Sort rows
        rows.sort((rowA, rowB) => {
            const cellA = rowA.cells[columnIndex].textContent.trim();
            const cellB = rowB.cells[columnIndex].textContent.trim();
            
            let valueA, valueB;
            
            if (isDateColumn) {
                // Date comparison
                valueA = new Date(cellA);
                valueB = new Date(cellB);
            } else if (isPriceColumn) {
                // Price comparison (remove non-numeric characters)
                valueA = parseFloat(cellA.replace(/[^0-9.]/g, "")) || 0;
                valueB = parseFloat(cellB.replace(/[^0-9.]/g, "")) || 0;
            } else if (columnIndex === 0 || columnIndex === 2) {
                // ID columns (numeric comparison)
                valueA = parseInt(cellA) || 0;
                valueB = parseInt(cellB) || 0;
            } else {
                // Text comparison
                valueA = cellA.toLowerCase();
                valueB = cellB.toLowerCase();
            }
            
            if (valueA < valueB) return sortDirection === 'asc' ? -1 : 1;
            if (valueA > valueB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Re-append sorted rows
        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));
    }

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('inventory-search');
        const searchBtn = document.getElementById('search-btn');
        const tableBody = document.getElementById('inventory-table-body');
        
        // Store original rows
        originalRows = Array.from(tableBody.querySelectorAll('tr'));
        
        function performSearch() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            
            if (searchTerm === '') {
                // If search is empty, restore original rows
                tableBody.innerHTML = '';
                originalRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
                return;
            }
            
            // Filter rows
            const filteredRows = originalRows.filter(row => {
                // Skip the empty state row if present
                if (row.cells.length === 1) return false;
                
                const cells = row.cells;
                for (let i = 0; i < cells.length; i++) {
                    const cellText = cells[i].textContent.toLowerCase();
                    if (cellText.includes(searchTerm)) {
                        return true;
                    }
                }
                return false;
            });
            
            // Update table
            tableBody.innerHTML = '';
            if (filteredRows.length > 0) {
                filteredRows.forEach(row => tableBody.appendChild(row.cloneNode(true)));
            } else {
                // Show "no results" message
                tableBody.innerHTML = `
                    <tr style="height: 240px; background-color: #f8f9fa;">
                        <td colspan="6" style="text-align: center; vertical-align: middle; padding: 0;">
                            <div style="width: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 0;">
                                <div style="margin-bottom: 15px; opacity: 0.5;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                </div>
                                <h3 style="font-size: 16px; color: #555; margin: 0; font-weight: 500;">No matching items found</h3>
                                <p style="font-size: 14px; color: #777; margin: 8px 0 0 0; max-width: 400px;">
                                    No inventory items match your search for "${searchTerm}".
                                </p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }
        
        // Event listeners
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
        
        // Add debounce for better performance
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300);
        });
    });
</script>

<?php require_once 'agentFooter.view.php'; ?>