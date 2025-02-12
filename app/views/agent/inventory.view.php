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
<div>
    <div class="inventory-container">
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>image</th>
                    <th onclick="sortTable(0)">Inventory ID ⬆⬇</th>
                    <th>Inventory Name</th>
                    <th onclick="sortTable(2)">Property ID ⬆⬇</th>
                    <th>Property Name</th>
                    <th onclick="sortTable(4)">Price ⬆⬇</th>
                    <th onclick="sortTable(5)">Date ⬆⬇</th>
                    <th>More</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="<?= ROOT ?>/assets/images/sofa.jpg" alt="Inventory Image" class="inventory-img"></td>
                    <td>P2022</td>
                    <td>C2023</td>
                    <td>House</td>
                    <td>Modern Villa</td>
                    <td>$500,000</td>
                    <td>2025-02-11</td>
                    <td><button class="btn-more" onclick="openFullPage('P2022')">➕</button></td>
                </tr>
                <tr>
                    <td><img src="<?= ROOT ?>/assets/images/sofa.jpg" alt="Inventory Image" class="inventory-img"></td>
                    <td>P2023</td>
                    <td>City Heights City Heights</td>
                    <td>Apartment</td>
                    <td>City Heights City Heights</td>
                    <td>$320,000</td>
                    <td>2025-02-10</td>
                    <td><button class="btn-more" onclick="openFullPage('P2023')">➕</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- Pagination Buttons -->
<div class="pagination">
        <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button>
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

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap');

    /* Table Container */
    .inventory-container {
        width: 90%;
        margin: auto;
        padding-left: 20px;
        padding-bottom: 20px;
        padding-right: 20px;
        padding-top: 10px;
        background: #fff;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        font-family: 'Outfit', sans-serif;
        text-align: center;
    }

    .inventory-container h2 {
        margin-bottom: 15px;
        color: #333;
        font-weight: 600;
    }

    /* Inventory Table */
    .inventory-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }

    .inventory-table th, .inventory-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
        cursor: pointer;
        word-wrap: break-word;
    }

    /* Define fixed width for each column */
    
    .inventory-table th:nth-child(1), .inventory-table td:nth-child(1) {
        width: 10px; /* Inventory ID */
    }
    .inventory-table th:nth-child(2), .inventory-table td:nth-child(2) {
        width: 150px; /* Inventory ID */
    }

    .inventory-table th:nth-child(3), .inventory-table td:nth-child(3) {
        width: 250px; /* Inventory Name */
    }

    .inventory-table th:nth-child(4), .inventory-table td:nth-child(4) {
        width: 150px; /* Property ID */
    }

    .inventory-table th:nth-child(5), .inventory-table td:nth-child(5) {
        width: 250px; /* Property Name */
    }

    .inventory-table th:nth-child(6), .inventory-table td:nth-child(6) {
        width: 100px; /* Price */
    }

    .inventory-table th:nth-child(7), .inventory-table td:nth-child(7) {
        width: 500px; /* Date */
    }

    .inventory-table th:nth-child(8), .inventory-table td:nth-child(8) {
        width: 25px; /* More */
    }

    .inventory-table th {
        background: #007bff;
        color: white;
        text-transform: uppercase;
        font-size: 14px;
    }

    .inventory-table tr:hover {
        background: rgba(0, 123, 255, 0.1);
        transition: all 0.3s ease-in-out;
    }

    /* Round Image */
    .inventory-img {
        width: 50px; /* Set the size of the image */
        height: 50px; /* Set the size of the image */
        object-fit: cover; /* Make sure the image covers the container without distortion */
        border-radius: 50%; /* Makes the image round */
        margin-right: 10px; /* Add space between the image and the text */
    }


    /* More Button */
    .btn-more {
        background: white;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: bold;
        font-size: 18px;
    }

    .btn-more:hover {
        background: linear-gradient(135deg, #0056b3, #003b80);
        transform: scale(1.1);
    }
</style>

<?php require_once 'agentFooter.view.php'; ?>