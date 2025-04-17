<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="user_view-menu-bar">
        <div class="gap"></div>
        <h2>Dashboard</h2>
    </div>
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

    

    <div class="agent-tables-row">
        <div class="agent-repair-table">
            <div class="agent-table-header">
                <h3><i class="fas fa-check-circle"></i> Approved Repairings</h3>
                <a href="<?=ROOT?>/dashboard/preInspection" class="agent-view-link">View All <i class="fas fa-chevron-right"></i></a>
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
                <a href="<?=ROOT?>/dashboard/preInspection" class="agent-view-link">View All <i class="fas fa-chevron-right"></i></a>
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
                    <?php foreach($services as $service): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($service->property_id) ?></strong></td>
                        <td><?= htmlspecialchars($service->date) ?></td>
                        <td><span class="agent-status agent-status-progress"><?= htmlspecialchars($service->status) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="agent-actions-section">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="agent-action-buttons">
            <a href="<?=ROOT?>/dashboard/inventory/newinventory" class="agent-action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Add Inventory</span>
            </a>
            <a href="<?=ROOT?>/dashboard/manageProviders/serviceproviders/addserviceprovider" class="agent-action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Add Service Provider</span>
            </a>
            <a href="<?=ROOT?>/dashboard/tasks" class="agent-action-btn">
                <i class="fas fa-tools"></i>
                <span>Request Repair</span>
            </a>

            <a href="<?=ROOT?>/dashboard/preInspection" class="agent-action-btn">
                <i class="fas fa-calendar-plus"></i>
                <span>Schedule Inspection</span>
            </a>
        </div>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>