<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar" style="margin-bottom: 15px;">
    <div class="gap"></div>
    <h2>Maintenance</h2>
    <div class="flex-bar">
        <span style="color: var(--dark-grey-color)">Total Expenses:</span>
        <span class="bolder-text" id="total-expenses"><?= number_format($totalExpenses, 2) ?> LKR</span>
    </div>
</div>

<div style="max-width: 1400px; margin: 0 auto; padding: 0 10px 20px;">
    <!-- Futuristic Filtering Section -->
    <div class="filtering-section" style="margin-bottom: 15px; padding: 25px; background: var(--white-color); border-radius: 5px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); border: 1px solid rgba(255,255,255,0.7);">
        <div style="display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 15px; align-items: end;">
            <!-- Status Filter -->
            <div style="grid-column: 1;">
                <label for="status_filter" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 14px;">
                    <i class="fas fa-filter" style="margin-right: 6px; color: var(--primary-color);"></i> STATUS
                </label>
                <div style="position: relative;">
                    <select name="status_filter" id="status_filter" class="filter-input" style="width: 100%; padding: 11px 15px; border-radius: 8px; border: 1px solid #e0e0e0; background-color: #fff; font-size: 14px; appearance: none;">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="ongoing" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                        <option value="done" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'done' ? 'selected' : '' ?>>Done</option>
                        <option value="rejected" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        <option value="paid" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                    </select>
                    <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--primary-color);">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
            
            <!-- Date Filter -->
            <div style="grid-column: 2;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 14px;">
                    <i class="far fa-calendar-alt" style="margin-right: 6px; color: var(--primary-color);"></i> DATE RANGE
                </label>
                <div style="display: flex; gap: 8px; align-items: center;">
                    <input type="date" id="date_from" name="date_from" class="filter-input" value="<?= $_GET['date_from'] ?? '' ?>" style="flex: 1; padding: 11px 12px; border-radius: 8px; border: 1px solid #e0e0e0; font-size: 14px;">
                    <span style="color: #888; font-weight: 500;">to</span>
                    <input type="date" id="date_to" name="date_to" class="filter-input" value="<?= $_GET['date_to'] ?? '' ?>" style="flex: 1; padding: 11px 12px; border-radius: 8px; border: 1px solid #e0e0e0; font-size: 14px;">
                </div>
            </div>
            
            <!-- Sort Order -->
            <div style="grid-column: 3;">
                <label for="sort" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 14px;">
                    <i class="fas fa-sort" style="margin-right: 6px; color: var(--primary-color);"></i> SORT BY
                </label>
                <div style="position: relative;">
                    <select name="sort" id="sort" class="filter-input" style="width: 100%; padding: 11px 15px; border-radius: 8px; border: 1px solid #e0e0e0; background-color: #fff; font-size: 14px; appearance: none;">
                        <option value="date_desc" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'date_desc') ? 'selected' : '' ?>>Date (Newest First)</option>
                        <option value="date_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'date_asc' ? 'selected' : '' ?>>Date (Oldest First)</option>
                        <option value="property_id" <?= isset($_GET['sort']) && $_GET['sort'] == 'property_id' ? 'selected' : '' ?>>Property ID</option>
                    </select>
                    <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--primary-color);">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div style="grid-column: 4; display: flex; gap: 10px; align-self: end; margin-left: 10px;">
                <button type="button" id="reset-filters" style="height: 40px; min-width: 100px; padding: 0 15px; background: #6c757d; color: white; border: none; border-radius: 8px; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fas fa-redo-alt"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <div id="loading-spinner" style="display: none; text-align: center; padding: 20px;">
        <div class="spinner" style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid var(--primary-color); border-radius: 50%; margin: 0 auto; animation: spin 1s linear infinite;"></div>
    </div>

    <div id="service-logs-container">
        <?php if (empty($serviceLogs)): ?>
            <div style="padding: 20px; color: #0c5460; background: linear-gradient(145deg, #d1ecf1, #c3e6eb); border-radius: 12px; margin-bottom: 15px; text-align: center; font-size: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
                <i class="fas fa-info-circle" style="margin-right: 10px; color: #0c5460;"></i>
                No maintenance records found.
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 20px; width: 100%;">
                <?php foreach ($serviceLogs as $serviceLog): ?>
                    <div style="width: 100%;" class="service-log-item">
                        <?php include __DIR__ . '/../components/serviceCard.php'; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all filter inputs
    const filterInputs = document.querySelectorAll('.filter-input');
    const resetButton = document.getElementById('reset-filters');
    const loadingSpinner = document.getElementById('loading-spinner');
    const serviceLogsContainer = document.getElementById('service-logs-container');
    const totalExpensesElement = document.getElementById('total-expenses');

    // Add event listeners to all filter inputs
    filterInputs.forEach(input => {
        input.addEventListener('change', applyFilters);
    });

    // Reset filters
    resetButton.addEventListener('click', function() {
        filterInputs.forEach(input => {
            if(input.type === 'date') {
                input.value = '';
            } else if(input.tagName === 'SELECT') {
                if(input.id === 'sort') {
                    input.value = 'date_desc';
                } else {
                    input.value = '';
                }
            }
        });
        applyFilters();
    });

    // Function to apply filters
    function applyFilters() {
        // Show loading spinner
        loadingSpinner.style.display = 'block';
        serviceLogsContainer.style.opacity = '0.5';

        // Get filter values
        const status = document.getElementById('status_filter').value;
        const dateFrom = document.getElementById('date_from').value;
        const dateTo = document.getElementById('date_to').value;
        const sort = document.getElementById('sort').value;

        // Build query string
        const params = new URLSearchParams();
        if(status) params.append('status_filter', status);
        if(dateFrom) params.append('date_from', dateFrom);
        if(dateTo) params.append('date_to', dateTo);
        if(sort) params.append('sort', sort);
        params.append('ajax', '1');  // Flag for AJAX request

        // Update URL without reloading
        const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        history.pushState({}, '', newUrl);

        // Make AJAX request
        fetch('<?= ROOT ?>/dashboard/maintenance?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading spinner
            loadingSpinner.style.display = 'none';
            serviceLogsContainer.style.opacity = '1';

            // Update total expenses
            totalExpensesElement.textContent = data.totalExpenses + ' LKR';

            // Update service logs container
            serviceLogsContainer.innerHTML = '';

            if(data.serviceLogs.length === 0) {
                serviceLogsContainer.innerHTML = `
                    <div style="padding: 20px; color: #0c5460; background: linear-gradient(145deg, #d1ecf1, #c3e6eb); border-radius: 12px; margin-bottom: 15px; text-align: center; font-size: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
                        <i class="fas fa-info-circle" style="margin-right: 10px; color: #0c5460;"></i>
                        No maintenance records found.
                    </div>
                `;
            } else {
                const container = document.createElement('div');
                container.style.display = 'grid';
                container.style.gridTemplateColumns = '1fr';
                container.style.gap = '15px';
                container.style.marginBottom = '20px';
                container.style.width = '100%';

                data.serviceLogs.forEach(log => {
                    container.innerHTML += data.serviceCardHtml.replace(/\{\{([^}]+)\}\}/g, function(match, key) {
                        return log[key.trim()] || '';
                    });
                });

                serviceLogsContainer.appendChild(container);
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            loadingSpinner.style.display = 'none';
            serviceLogsContainer.style.opacity = '1';
            alert('An error occurred while fetching data. Please try again.');
        });
    }
});
</script>

<?php require_once 'ownerFooter.view.php'; ?>