<?php require_once 'managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <h2>dashboard</h2>
</div>

<div class="dashboard">
    <div class="top-section">
        <div class="card total-property">
            <h3>Properties</h3>
            <span>45</span>
        </div>
        <div class="card total-profit">
            <h3>Service Providers</h3>
            <span>31</span>
        </div>
        <div class="card total-income">
            <h3>Agents</h3>
            <span>82</span>
        </div>
        <div class="card total-expense">
            <h3>Tenants</h3>
            <span>205</span>
        </div>
    </div>

    <div class="bottom-section">
        <div class="payment-history">
            <h3>Approval Requests</h3>
            <table>
                <tr>
                    <th>Property ID</th>
                    <th>Request Type</th>
                    <th>Date</th>
                </tr>
                <tr>
                    <td>P123</td>
                    <td>Removal</td>
                    <td>2024/09/09</td>
                </tr>
                <tr>
                    <td>P456</td>
                    <td>Update</td>
                    <td>2024/10/10</td>
                </tr>
                <tr>
                    <td>P789</td>
                    <td>Registration</td>
                    <td>2024/09/24</td>
                </tr>
            </table>
            <a href="" class="orange-link">see more</a>
        </div>

        <div class="maintenance-status">
            <h3>Assign Agents</h3>
            <table>
                <tr>
                    <td>Property 1</td>
                    <td class="green-color">In Progress</td>
                </tr>
                <tr>
                    <td>Property 2</td>
                    <td class="blue-color">Completed</td>
                </tr>
                <tr>
                    <td>Property 3</td>
                    <td class="">Pending</td>
                </tr>
                <tr>
                    <td>Property 4</td>
                    <td class="green-color">In Progress</td>
                </tr>
            </table>
            <a href="" class="orange-link">see more</a>
        </div>

    </div>
    <!-- <div class="outstanding-payments">
        <h3>Outstanding Payments</h3>
        <div class="outstanding-flex-bar">
            <p>Outstanding payments for March</p>
            <div>
                <span>LKR 300.00</span>
                <button class="secondary-btn">Pay Outstanding</button>
            </div>
        </div>
    </div> -->
</div>

<?php require_once 'managerFooter.view.php'; ?>