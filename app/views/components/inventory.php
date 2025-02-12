<div class="inventory-container">
    <h2>Inventory List</h2>
    <table class="inventory-table">
        <thead>
            <tr>
                <th>Inventory ID</th>
                <th>Inventory Name</th>
                <th>Property ID</th>
                <th>Property Name</th>
                <th>Price</th>
                <th>Date</th>
                <th>More</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>P2022</td>
                <td>C2023</td>
                <td>House</td>
                <td>Modern Villa</td>
                <td>$500,000</td>
                <td>2025-02-11</td>
                <td><button class="btn-more" onclick="openFullPage('P2022')">➕</button></td>
            </tr>
            <tr>
                <td>P2023</td>
                <td>C2024</td>
                <td>Apartment</td>
                <td>City Heights</td>
                <td>$320,000</td>
                <td>2025-02-10</td>
                <td><button class="btn-more" onclick="openFullPage('P2023')">➕</button></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
    function openFullPage(inventoryId) {
        window.location.href = "<?= ROOT ?>/inventory/view/" + inventoryId;
    }
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap');

    /* Table Container */
    .inventory-container {
        width: 90%;
        margin: auto;
        padding: 20px;
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
        margin-top: 10px;
    }

    .inventory-table th, .inventory-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #ddd;
        text-align: left;
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

    /* More Button */
    .btn-more {
        background: linear-gradient(135deg, #007bff, #0056b3);
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
