<?php require_once 'agentHeader.view.php'; ?>

<div class="agent-menu-bar">
    <div class="agent-gap"></div>
    <h2><i class="fas fa-tachometer-alt"></i> Dashboard Overview</h2>
</div>

<div class="agent-dashboard-container">
    <div class="agent-stats-row">
        <div class="agent-stat-card agent-property-card">
            <div class="agent-card-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="agent-card-details">
                <h3>Properties</h3>
                <span class="agent-count">45</span>
                <p class="agent-trend agent-trend-up"><i class="fas fa-arrow-up"></i> 12% from last month</p>
            </div>
        </div>
        
        <div class="agent-stat-card agent-provider-card">
            <div class="agent-card-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="agent-card-details">
                <h3>Service Providers</h3>
                <span class="agent-count">31</span>
                <p class="agent-trend agent-trend-down"><i class="fas fa-arrow-down"></i> 5% from last month</p>
            </div>
        </div>
        
        <div class="agent-stat-card agent-repair-card">
            <div class="agent-card-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="agent-card-details">
                <h3>Repair Requests</h3>
                <span class="agent-count">82</span>
                <p class="agent-trend agent-trend-up"><i class="fas fa-arrow-up"></i> 23% from last month</p>
            </div>
        </div>
        
        <div class="agent-stat-card agent-booking-card">
            <div class="agent-card-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="agent-card-details">
                <h3>Bookings</h3>
                <span class="agent-count">205</span>
                <p class="agent-trend agent-trend-up"><i class="fas fa-arrow-up"></i> 8% from last month</p>
            </div>
        </div>
    </div>

    <div class="agent-chart-section">
        <div class="agent-chart-box">
            <h3><i class="fas fa-chart-line"></i> Monthly Activity</h3>
            <div class="agent-chart-placeholder">
                <!-- This would be replaced with an actual chart in implementation -->
                <img src="https://via.placeholder.com/600x250?text=Monthly+Activity+Chart" alt="Monthly Activity Chart">
            </div>
        </div>
    </div>

    <div class="agent-tables-row">
        <div class="agent-repair-table">
            <div class="agent-table-header">
                <h3><i class="fas fa-check-circle"></i> Approved Repairings</h3>
                <a href="" class="agent-view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <table class="agent-data-table">
                <thead>
                    <tr>
                        <th>Property ID</th>
                        <th>Repair Type</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>P123</strong></td>
                        <td>Removal</td>
                        <td>2024/09/09</td>
                        <td><span class="agent-status agent-status-complete">Completed</span></td>
                    </tr>
                    <tr>
                        <td><strong>P456</strong></td>
                        <td>Update</td>
                        <td>2024/10/10</td>
                        <td><span class="agent-status agent-status-progress">In Progress</span></td>
                    </tr>
                    <tr>
                        <td><strong>P789</strong></td>
                        <td>Registration</td>
                        <td>2024/09/24</td>
                        <td><span class="agent-status agent-status-pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td><strong>P101</strong></td>
                        <td>Electrical</td>
                        <td>2024/10/15</td>
                        <td><span class="agent-status agent-status-scheduled">Scheduled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="agent-inspection-table">
            <div class="agent-table-header">
                <h3><i class="fas fa-clipboard-check"></i> Pre-Inspections</h3>
                <a href="" class="agent-view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <table class="agent-data-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Inspection Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Luxury Villa - Colombo</strong></td>
                        <td>2024/10/05</td>
                        <td><span class="agent-status agent-status-progress">In Progress</span></td>
                    </tr>
                    <tr>
                        <td><strong>Apartment - Kandy</strong></td>
                        <td>2024/10/12</td>
                        <td><span class="agent-status agent-status-complete">Completed</span></td>
                    </tr>
                    <tr>
                        <td><strong>Beach House - Galle</strong></td>
                        <td>2024/10/18</td>
                        <td><span class="agent-status agent-status-pending">Pending</span></td>
                    </tr>
                    <tr>
                        <td><strong>Office Space - Colombo 2</strong></td>
                        <td>2024/10/22</td>
                        <td><span class="agent-status agent-status-scheduled">Scheduled</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="agent-actions-section">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="agent-action-buttons">
            <button class="agent-action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Add Property</span>
            </button>
            <button class="agent-action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Add Service Provider</span>
            </button>
            <button class="agent-action-btn">
                <i class="fas fa-tools"></i>
                <span>Request Repair</span>
            </button>
            <button class="agent-action-btn">
                <i class="fas fa-calendar-plus"></i>
                <span>Schedule Inspection</span>
            </button>
        </div>
    </div>
</div>

<style>
    /* Agent Dashboard Styles */
.agent-dashboard-container {
    padding: 20px;
    background-color: #f5f7fa;
}

.agent-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.agent-stat-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.agent-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}

.agent-card-icon {
    font-size: 24px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: white;
}

.agent-property-card .agent-card-icon { background: #4e73df; }
.agent-provider-card .agent-card-icon { background: #1cc88a; }
.agent-repair-card .agent-card-icon { background: #36b9cc; }
.agent-booking-card .agent-card-icon { background: #f6c23e; }

.agent-card-details h3 {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 5px;
}

.agent-card-details .agent-count {
    font-size: 24px;
    font-weight: 700;
    color: #5a5c69;
}

.agent-trend {
    font-size: 12px;
    margin-top: 5px;
}

.agent-trend-up { color: #1cc88a; }
.agent-trend-down { color: #e74a3b; }

.agent-chart-section {
    margin-bottom: 30px;
}

.agent-chart-box {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.agent-chart-box h3 {
    margin-bottom: 15px;
    color: #5a5c69;
}

.agent-chart-placeholder {
    width: 100%;
    height: 250px;
    background: #f8f9fc;
    border-radius: 5px;
    overflow: hidden;
}

.agent-tables-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 30px;
}

.agent-repair-table, .agent-inspection-table {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.agent-table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.agent-table-header h3 {
    color: #5a5c69;
}

.agent-data-table {
    width: 100%;
    border-collapse: collapse;
}

.agent-data-table thead th {
    background-color: #f8f9fc;
    color: #5a5c69;
    font-weight: 600;
    padding: 12px 15px;
    text-align: left;
}

.agent-data-table tbody td {
    padding: 12px 15px;
    border-bottom: 1px solid #e3e6f0;
    color: #5a5c69;
}

.agent-data-table tbody tr:last-child td {
    border-bottom: none;
}

.agent-status {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.agent-status-complete {
    background-color: #d1fae5;
    color: #065f46;
}

.agent-status-progress {
    background-color: #dbeafe;
    color: #1e40af;
}

.agent-status-pending {
    background-color: #fef3c7;
    color: #92400e;
}

.agent-status-scheduled {
    background-color: #ede9fe;
    color: #5b21b6;
}

.agent-actions-section {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
}

.agent-actions-section h3 {
    color: #5a5c69;
    margin-bottom: 15px;
}

.agent-action-buttons {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.agent-action-btn {
    background: #f8f9fc;
    border: none;
    border-radius: 8px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #4e73df;
    cursor: pointer;
    transition: all 0.3s ease;
}

.agent-action-btn:hover {
    background: #4e73df;
    color: white;
    transform: translateY(-3px);
}

.agent-action-btn i {
    font-size: 24px;
    margin-bottom: 8px;
}

.agent-action-btn span {
    font-size: 13px;
    font-weight: 600;
}

.agent-view-link {
    color: #fd7e14;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
}

.agent-view-link:hover {
    text-decoration: underline;
}
</style>

<?php require_once 'agentFooter.view.php'; ?>