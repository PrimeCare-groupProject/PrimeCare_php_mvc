<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/serviceRequests/'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <!-- <div class="gap"></div> -->
    <h2>Tasks</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn"><img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons"></button>
        </div>
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/tasks/newtask'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new task</span>
        </div>
    </div>
</div>
<div class="inventory-details-container">
    <table class="inventory-table">
        <thead>
            <tr>
                <th onclick="sortTable(0)">Service ID ⬆⬇</th>
                <th>Service Type</th>
                <th onclick="sortTable(2)">Date ⬆⬇</th>
                <th>Property Name</th>
                <th onclick="sortTable(4)">Cost Per Hour ⬆⬇</th>
                <th onclick="sortTable(5)">Status </th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tasks)) : ?>
                <?php foreach ($tasks as $task) : ?>
                    <tr onclick="window.location.href='<?= ROOT ?>/dashboard/tasks/edittasks/<?= $task->service_id ?>'">
                        <td><?= $task->service_id ?></td>
                        <td><?= $task->service_type ?></td>
                        <td><?= $task->date ?></td>
                        <td><?= $task->property_name?></td>
                        <td><?= $task->cost_per_hour ?></td>
                        <td><?= $task->status ?></td>
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
        rows.forEach(row => tbody.appendChild(row));
    }
</script>

<?php require_once 'agentFooter.view.php'; ?>