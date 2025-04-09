<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>repair requests</h2>
</div>

<div class="financial-details-container">
    <div class="filter-container">
        <div>
            <label for="status-filter">Status:</label>
            <select id="status-filter">
                <option value="all" <?= $selected_status === 'all' ? 'selected' : '' ?>>All</option>
                <option value="Done" <?= $selected_status === 'Done' ? 'selected' : '' ?>>Done</option>
                <option value="Ongoing" <?= $selected_status === 'Ongoing' ? 'selected' : '' ?>>Ongoing</option>
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
                <th>Property ID</th>
                <th>Property Name</th>
                <th class="sortable" id="earnings-header">
                    Earnings
                    <img src="<?= ROOT ?>/assets/images/sort.png" alt="sort">
                </th>
                <th>Status</th>
                <th class="last">Time left</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($services)) : ?>
            <?php foreach ($services as $service) : ?>
                <tr 
            onclick="window.location.href='<?= strtolower($service->status) === 'ongoing' 
                ? ROOT . '/serviceprovider/addLogs?service_id=' . $service->service_id . '&property_id=' . $service->property_id . '&property_name=' . urlencode($service->property_name) . '&service_type=' . urlencode($service->service_type) . '&status=' . urlencode($service->status) . '&earnings=' . $service->earnings : 
                ROOT . '/serviceprovider/serviceSummery?service_id=' . $service->service_id . '&status=' . urlencode($service->status) ?>'">
                    <td class="first"><?= date('Y/m/d', strtotime($service->date)) ?></td>
                    <td><?= esc($service->service_type) ?></td>
                    <td><?= esc($service->property_id) ?></td>
                    <td><?= esc($service->property_name) ?></td>
                    <td><?= number_format($service->earnings, 2) ?> LKR</td>
                    <td>
                        <span 
                            class="border-button-sm <?= strtolower($service->status) === 'done' ? 'green' : 'orange' ?>"
                        >
                            <?= esc($service->status) ?>
                        </span>
                    </td>
                    <td class="last"><?= $service->time_left ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr style="height: 240px; background-color: #f8f9fa;">
                <td colspan="7" style="text-align: center; vertical-align: middle; padding: 30px;">
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
                            <h3 style="font-size: 18px; color: #444; margin: 0 0 8px 0; font-weight: 500;">No service requests found</h3>
                            <p style="color: #777; font-size: 14px; margin: 0;">There are currently no service requests assigned to you.</p>
                        <?php elseif ($selected_status === 'Done'): ?>
                            <h3 style="font-size: 18px; color: #444; margin: 0 0 8px 0; font-weight: 500;">No completed service requests</h3>
                            <p style="color: #777; font-size: 14px; margin: 0;">You don't have any completed service requests in your history.</p>
                        <?php elseif ($selected_status === 'Ongoing'): ?>
                            <h3 style="font-size: 18px; color: #444; margin: 0 0 8px 0; font-weight: 500;">No ongoing service requests</h3>
                            <p style="color: #777; font-size: 14px; margin: 0;">You don't have any service requests in progress at the moment.</p>
                        <?php else: ?>
                            <h3 style="font-size: 18px; color: #444; margin: 0 0 8px 0; font-weight: 500;">No <?= esc($selected_status) ?> service requests</h3>
                            <p style="color: #777; font-size: 14px; margin: 0;">There are no service requests with status "<?= esc($selected_status) ?>".</p>
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

    document.querySelectorAll('.listing-table tbody tr').forEach(row => {
        row.addEventListener('click', function() {
            if (this.dataset.href) {
                window.location.href = this.dataset.href;
            }
        });
    });
</script>

<?php require 'serviceproviderFooter.view.php' ?>