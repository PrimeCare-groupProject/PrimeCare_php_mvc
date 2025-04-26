<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>External Service Requests</h2>
</div>

<div class="financial-details-container">
    <div class="filter-container">
        <div>
            <label for="status-filter">Status:</label>
            <select id="status-filter">
                <option value="all" <?= $selected_status === 'all' ? 'selected' : '' ?>>All</option>
                <option value="ongoing" <?= $selected_status === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                <option value="done" <?= $selected_status === 'done' ? 'selected' : '' ?>>Done</option>
                <option value="paid" <?= $selected_status === 'paid' ? 'selected' : '' ?>>Paid</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <table class="listing-table">
        <thead>
            <tr>
                <th class="extra-space sortable first" id="date-header">
                    Date
                    <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                </th>
                <th>Service Type</th>
                <th>Property Address</th>
                <th>Cost/Hour</th>
                <th>Hours</th>
                <th class="sortable" id="earnings-header">
                    Total Cost
                    <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                </th>
                <th>Status</th>
                <th class="last">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($services)) : ?>
            <?php foreach ($services as $service) : ?>
                <?php 
                    $totalCost = $service->total_cost ?? (($service->cost_per_hour ?? 0) * ($service->total_hours ?? 0)); 
                    $totalCost += ($service->additional_charges ?? 0);
                    
                    // Get the status color
                    $statusColor = 'orange'; // Default
                    if (strtolower($service->status) === 'done') $statusColor = 'green';
                    if (strtolower($service->status) === 'paid') $statusColor = 'blue';
                ?>
                <tr onclick="window.location.href='<?= strtolower($service->status) === 'ongoing' 
                    ? ROOT . '/serviceprovider/updateExternalService?id=' . $service->id 
                    : ROOT . '/serviceprovider/externalServiceSummary?id=' . $service->id ?>'">
                    <td class="first"><?= date('Y/m/d', strtotime($service->date)) ?></td>
                    <td><?= esc($service->service_type) ?></td>
                    <td><?= esc($service->property_address) ?></td>
                    <td><?= number_format($service->cost_per_hour ?? 0, 2) ?> LKR</td>
                    <td><?= $service->total_hours ?? '0' ?></td>
                    <td><?= number_format($totalCost, 2) ?> LKR</td>
                    <td>
                        <span class="border-button-sm <?= $statusColor ?>">
                            <?= ucfirst(esc($service->status)) ?>
                        </span>
                    </td>
                    <td class="last">
                        <?php if (strtolower($service->status) === 'ongoing'): ?>
                            <button class="action-btn update-btn" onclick="event.stopPropagation(); window.location.href='<?= ROOT ?>/serviceprovider/updateExternalService?id=<?= $service->id ?>'">Update</button>
                        <?php else: ?>
                            <button class="action-btn view-btn" onclick="event.stopPropagation(); window.location.href='<?= ROOT ?>/serviceprovider/externalServiceSummary?id=<?= $service->id ?>'">View</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr style="height: 240px; background-color: #f8f9fa;">
                <td colspan="8" style="text-align: center; vertical-align: middle; padding: 30px;">
                    <div style="display: flex; flex-direction: column; align-items: center; max-width: 400px; margin: 0 auto;">
                        <!-- Empty state icon -->
                        <div style="margin-bottom: 15px; opacity: 0.5;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <path d="M13 2v7h7"></path>
                                <circle cx="12" cy="15" r="4"></circle>
                                <line x1="9" y1="15" x2="15" y2="15"></line>
                            </svg>
                        </div>
                        
                        <!-- Message based on selected status -->
                        <?php if ($selected_status === 'all'): ?>
                            <h3 style="font-size: 18px; color: #444; margin: 0 0 8px 0; font-weight: 500;">No external service requests found</h3>
                            <p style="color: #777; font-size: 14px; margin: 0;">There are currently no external service requests assigned to you.</p>
                        <?php else: ?>
                            <h3 style="font-size: 18px; color: #444; margin: 0 0 8px 0; font-weight: 500;">No <?= esc($selected_status) ?> external service requests</h3>
                            <p style="color: #777; font-size: 14px; margin: 0;">There are no external service requests with status "<?= ucfirst(esc($selected_status)) ?>".</p>
                        <?php endif; ?>
                        
                        <!-- Filter guidance -->
                        <p style="color: #888; font-size: 13px; margin-top: 20px;">Try selecting a different status filter</p>
                    </div>
                </td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($total_pages > 1) : ?>
    <div class="pagination">
        <button class="prev-page" <?= $current_page <= 1 ? 'disabled' : '' ?> onclick="changePage(<?= $current_page - 1 ?>)">
            <img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous">
        </button>
        <span class="current-page"><?= $current_page ?></span>
        <button class="next-page" <?= $current_page >= $total_pages ? 'disabled' : '' ?> onclick="changePage(<?= $current_page + 1 ?>)">
            <img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next">
        </button>
    </div>
    <?php endif; ?>
</div>

<style>
.action-btn {
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.85rem;
    border: none;
    color: white;
}
.update-btn {
    background-color: #f39c12;
}
.view-btn {
    background-color: #3498db;
}
.action-btn:hover {
    opacity: 0.9;
}
</style>

<script>
    document.getElementById('status-filter').addEventListener('change', function() {
        const status = this.value;
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('status', status);
        currentUrl.searchParams.set('page', '1'); // Reset to first page on filter change
        window.location.href = currentUrl.toString();
    });

    function changePage(page) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('page', page);
        window.location.href = currentUrl.toString();
    }
</script>

<?php require 'serviceproviderFooter.view.php' ?>