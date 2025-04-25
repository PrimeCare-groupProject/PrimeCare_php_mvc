<?php require_once 'agentHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Dashboard</h2>
</div>

<div class="agent-dashboard-container">
    <!-- Stats Row -->
    <<div class="agent-stats-row">
        <!-- Properties -->
        <div class="agent-stat-card agent-property-card">
            <div class="agent-card-icon">
                <i class="fas fa-building"></i>
            </div>
            <div class="agent-card-details">
                <h3>Properties</h3>
                <span class="agent-count"><?= htmlspecialchars($totalProperties ?? 0) ?></span>
                <p class="agent-trend agent-trend-up"><i class="fas fa-arrow-up"></i> 12% from last month</p>
            </div>
        </div>

        <!-- Service Providers -->
        <div class="agent-stat-card agent-provider-card">
            <div class="agent-card-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="agent-card-details">
                <h3>Service Providers</h3>
                <span class="agent-count"><?= htmlspecialchars($totalServiceProviders ?? 0) ?></span>
                <p class="agent-trend agent-trend-down"><i class="fas fa-arrow-down"></i> 5% from last month</p>
            </div>
        </div>

        <!-- Repair Requests -->
        <div class="agent-stat-card agent-repair-card">
            <div class="agent-card-icon">
                <i class="fas fa-tools"></i>
            </div>
            <div class="agent-card-details">
                <h3>Repair Requests</h3>
                <span class="agent-count"><?= htmlspecialchars($totalRepairRequests ?? 0) ?></span>
                <p class="agent-trend agent-trend-up"><i class="fas fa-arrow-up"></i> 23% from last month</p>
            </div>
        </div>

        <!-- Bookings -->
        <div class="agent-stat-card agent-booking-card">
            <div class="agent-card-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="agent-card-details">
                <h3>Bookings</h3>
                <span class="agent-count"><?= htmlspecialchars($totalBookings ?? 0) ?></span>
                <p class="agent-trend agent-trend-up"><i class="fas fa-arrow-up"></i> 8% from last month</p>
            </div>
        </div>
    </div>
</div>

    <!-- Tables Row -->
    <div class="agent-tables-row">
        <!-- Tasks Table -->
        <div class="agent-repair-table">
            <div class="agent-table-header">
                <h3><i class="fas fa-check-circle"></i> Tasks</h3>
                <a href="<?= ROOT ?>/dashboard/tasks" class="agent-view-link">View All <i class="fas fa-chevron-right"></i></a>
            </div>
            <table class="agent-data-table">
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>Task Type</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tasks)): ?>
                        <?php for ($i = 0; $i < 4; $i++): ?>
                            <?php if (isset($tasks[$i])): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($tasks[$i]->service_id) ?></strong></td>
                                    <td><?= htmlspecialchars($tasks[$i]->service_type) ?></td>
                                    <td><?= htmlspecialchars(date('Y-m-d', strtotime($tasks[$i]->date))) ?></td>
                                    <td>
                                        <span class="agent-status <?= $tasks[$i]->status === 'Completed' ? 'agent-status-complete' : ($tasks[$i]->status === 'Pending' ? 'agent-status-pending' : 'agent-status-progress') ?>">
                                            <?= htmlspecialchars($tasks[$i]->status) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endfor; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No tasks available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pre-Inspections Table -->
        <div class="agent-inspection-table">
            <div class="agent-table-header">
                <h3><i class="fas fa-clipboard-check"></i> Pre-Inspections</h3>
                <a href="<?= ROOT ?>/dashboard/preInspection" class="agent-view-link">View All <i class="fas fa-chevron-right"></i></a>
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
                    <?php foreach ($services as $service): ?>
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

    <!-- Quick Actions -->
    <div class="agent-actions-section">
        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
        <div class="agent-action-buttons">
            <a href="<?= ROOT ?>/dashboard/inventory/newinventory" class="agent-action-btn">
                <i class="fas fa-plus-circle"></i>
                <span>Add Inventory</span>
            </a>
            <a href="<?= ROOT ?>/dashboard/manageProviders/serviceproviders/addserviceprovider" class="agent-action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Add Service Provider</span>
            </a>
            <a href="<?= ROOT ?>/dashboard/tasks" class="agent-action-btn">
                <i class="fas fa-tools"></i>
                <span>Request Repair</span>
            </a>
            <a href="<?= ROOT ?>/dashboard/preInspection" class="agent-action-btn">
                <i class="fas fa-calendar-plus"></i>
                <span>Schedule Inspection</span>
            </a>
        </div>
    </div>
</div>

<?php require_once 'agentFooter.view.php'; ?>