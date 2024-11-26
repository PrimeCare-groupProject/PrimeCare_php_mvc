<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
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
                <th class="extra-space sortable" id="date-header">
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
                <th>Time left</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($services)) : ?>
            <?php foreach ($services as $service) : ?>
                <tr 
            onclick="window.location.href='<?= strtolower($service->status) === 'ongoing' 
                ? ROOT . '/serviceprovider/addLogs?service_id=' . $service->service_id . '&property_id=' . $service->property_id . '&property_name=' . urlencode($service->property_name) . '&service_type=' . urlencode($service->service_type) . '&status=' . urlencode($service->status) . '&earnings=' . $service->earnings : 
                ROOT . '/serviceprovider/serviceSummery?service_id=' . $service->service_id . '&status=' . urlencode($service->status) ?>'">
                    <td><?= date('Y/m/d', strtotime($service->date)) ?></td>
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
                    <td><?= $service->time_left ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
    <tr>
        <td colspan="6" class="text-center">No service requests found</td>
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