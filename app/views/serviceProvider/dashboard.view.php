<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>dashboard</h2>
</div>

<div class="dashboard">
    <div class="top-section">
        <div class="card total-property">
            <h3>Monthly Income</h3>
            <span>LKR 300000</span>
        </div>
        <div class="card total-profit">
            <h3>Tasks Completed</h3>
            <span>4</span>
        </div>
        <div class="card total-income">
            <h3>Tasks Remaining</h3>
            <span>5</span>
        </div>
        <div class="card total-expense">
            <h3>Total Tasks</h3>
            <span>9</span>
        </div>
    </div>
    <div class="financial-details-container">
        <h2>Financial Details</h2>
        <!-- Table -->
        <table class="financial-table">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Month</th>
                    <th>Tenant</th>
                    <th>Income</th>
                    <th>Expenses</th>
                    <th>Total Income</th>
                    <th>Status</th>
                    <th>Profit Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2024</td>
                    <td>October</td>
                    <td>Matt Dickerson</td>
                    <td>400000.95 LKR</td>
                    <td>100000.95 LKR</td>
                    <td>300000.00 LKR</td>
                    <td><span class="border-button-sm green">Occupied</span></td>
                    <td><span class="border-button-sm green">Profit</span></td>
                </tr>
                <tr>
                    <td>2024</td>
                    <td>September</td>
                    <td>Matt Dickerson</td>
                    <td>400000.95 LKR</td>
                    <td>100000.95 LKR</td>
                    <td>300.00 LKR</td>
                    <td><span class="border-button-sm red">Occupied</span></td>
                    <td><span class="border-button-sm red">Profit</span></td>
                </tr>
                <tr>
                    <td>2024</td>
                    <td>August</td>
                    <td>Matt Dickerson</td>
                    <td>400000.95 LKR</td>
                    <td>100000.95 LKR</td>
                    <td>300000.00 LKR</td>
                    <td><span class="border-button-sm orange">Occupied</span></td>
                    <td><span class="border-button-sm orange">Profit</span></td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>

<?php require 'serviceproviderFooter.view.php' ?>