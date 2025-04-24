<?php require_once 'customerHeader.view.php'; ?>

<div class="user_view-menu-bar" style="margin-bottom: 15px;">
    <div class="gap"></div>
    <h2>My Service Requests</h2>
    <div class="flex-bar">
        <span style="color: var(--dark-grey-color)">Total Expenses:</span>
        <span class="bolder-text"><?= number_format($totalExpenses, 2) ?> LKR</span>
    </div>
</div>

<div style="max-width: 1400px; margin: 0 auto; padding: 0 10px 20px;">
    <!-- Futuristic Filtering Section -->
    <div class="filtering-section" style="margin-bottom: 15px; padding: 25px; background: var(--white-color); border-radius: 5px; box-shadow: 0 10px 20px rgba(0,0,0,0.05); border: 1px solid rgba(255,255,255,0.7);">
        <form id="filter-form" method="GET" action="" style="display: grid; grid-template-columns: repeat(3, 1fr) auto; gap: 15px; align-items: end;">
            <!-- Status Filter -->
            <div style="grid-column: 1;">
                <label for="status_filter" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 14px;">
                    <i class="fas fa-filter" style="margin-right: 6px; color: var(--primary-color);"></i> STATUS
                </label>
                <div style="position: relative;">
                    <select name="status_filter" id="status_filter" style="width: 100%; padding: 11px 15px; border-radius: 8px; border: 1px solid #e0e0e0; background-color: #fff; font-size: 14px; appearance: none; cursor: pointer;">
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
                    <input type="date" id="date_from" name="date_from" value="<?= $_GET['date_from'] ?? '' ?>" style="flex: 1; padding: 11px 12px; border-radius: 8px; border: 1px solid #e0e0e0; font-size: 14px; cursor: pointer;">
                    <span style="color: #888; font-weight: 500;">to</span>
                    <input type="date" id="date_to" name="date_to" value="<?= $_GET['date_to'] ?? '' ?>" style="flex: 1; padding: 11px 12px; border-radius: 8px; border: 1px solid #e0e0e0; font-size: 14px; cursor: pointer;">
                </div>
            </div>
            
            <!-- Sort Order -->
            <div style="grid-column: 3;">
                <label for="sort" style="display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 14px;">
                    <i class="fas fa-sort" style="margin-right: 6px; color: var(--primary-color);"></i> SORT BY
                </label>
                <div style="position: relative;">
                    <select name="sort" id="sort" style="width: 100%; padding: 11px 15px; border-radius: 8px; border: 1px solid #e0e0e0; background-color: #fff; font-size: 14px; appearance: none; cursor: pointer;">
                        <option value="date_desc" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'date_desc') ? 'selected' : '' ?>>Date (Newest First)</option>
                        <option value="date_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'date_asc' ? 'selected' : '' ?>>Date (Oldest First)</option>
                        <option value="property_id" <?= isset($_GET['sort']) && $_GET['sort'] == 'property_id' ? 'selected' : '' ?>>Property ID</option>
                        <option value="cost_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'cost_asc' ? 'selected' : '' ?>>Cost (Low to High)</option>
                        <option value="cost_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'cost_desc' ? 'selected' : '' ?>>Cost (High to Low)</option>
                    </select>
                    <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--primary-color);">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div style="grid-column: 4; display: flex; gap: 10px; align-self: end; margin-left: 10px;">
                <button type="submit" style="height: 40px; min-width: 100px; padding: 0 15px; background-color: var(--primary-color); color: white; border: none; border-radius: 8px; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer;">
                    <i class="fas fa-search"></i> Apply
                </button>
                <a href="<?= ROOT ?>/customer/serviceRequests" style="height: 40px; min-width: 100px; padding: 0 15px; background: #6c757d; color: white; border-radius: 8px; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 6px; text-decoration: none; cursor: pointer;">
                    <i class="fas fa-redo-alt"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <div class="service-stats" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #f5f7fa, #e9ecef); border-radius: 10px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <h3 style="margin: 0; font-size: 15px; color: #495057;">Total Requests</h3>
            <p style="font-size: 24px; font-weight: 700; margin: 10px 0 0; color: #212529;"><?= count($serviceLogs) ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fff0e1, #ffe8cc); border-radius: 10px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <h3 style="margin: 0; font-size: 15px; color: #cc7a00;">Pending</h3>
            <p style="font-size: 24px; font-weight: 700; margin: 10px 0 0; color: #e67700;"><?= $pendingCount ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #e3f7e8, #d4f0dc); border-radius: 10px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <h3 style="margin: 0; font-size: 15px; color: #2b7a39;">Completed</h3>
            <p style="font-size: 24px; font-weight: 700; margin: 10px 0 0; color: #198038;"><?= $completedCount ?></p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #e7f5ff, #d0ebff); border-radius: 10px; padding: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <h3 style="margin: 0; font-size: 15px; color: #1864ab;">Awaiting Payment</h3>
            <p style="font-size: 24px; font-weight: 700; margin: 10px 0 0; color: #1971c2;"><?= $doneCount ?></p>
        </div>
    </div>

    <?php if (empty($serviceLogs)): ?>
        <div style="padding: 20px; color: #0c5460; background: linear-gradient(145deg, #d1ecf1, #c3e6eb); border-radius: 12px; margin-bottom: 15px; text-align: center; font-size: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.04);">
            <i class="fas fa-info-circle" style="margin-right: 10px; color: #0c5460;"></i>
            No service requests found.
        </div>
    <?php else: ?>
        <div id="service-logs-container" style="display: grid; grid-template-columns: 1fr; gap: 15px; margin-bottom: 20px; width: 100%;">
            <?php foreach ($serviceLogs as $serviceLog): ?>
                <div class="service-card" style="width: 96%; background: white; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); padding: 15px; display: flex; flex-direction: column; gap: 10px;" 
                     data-date="<?= $serviceLog->date ?? '' ?>" 
                     data-property-id="<?= $serviceLog->property_id ?? '' ?>"
                     data-status="<?= strtolower($serviceLog->status ?? '') ?>"
                     data-cost="<?= $serviceLog->total_cost ?? ($serviceLog->cost_per_hour * ($serviceLog->total_hours ?? 1)) ?>">
                    
                    <div class="service-card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; font-size: 18px; color: #333;"><?= htmlspecialchars($serviceLog->service_type ?? 'Service Request') ?></h3>
                        <span class="status-badge" style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; 
                            <?php
                            $status = strtolower($serviceLog->status ?? 'pending');
                            switch ($status) {
                                case 'pending':
                                    echo 'background-color: #fff3cd; color: #856404;';
                                    break;
                                case 'ongoing':
                                    echo 'background-color: #cce5ff; color: #004085;';
                                    break;
                                case 'done':
                                    echo 'background-color: #d4edda; color: #155724;';
                                    break;
                                case 'rejected':
                                    echo 'background-color: #f8d7da; color: #721c24;';
                                    break;
                                case 'paid':
                                    echo 'background-color: #c3e6cb; color: #155724;';
                                    break;
                                default:
                                    echo 'background-color: #e2e3e5; color: #383d41;';
                            }
                            ?>
                        ">
                            <?= ucfirst($status) ?>
                        </span>
                    </div>
                    
                    <div class="service-card-body" style="display: grid; grid-template-columns: 1fr 1fr;">
                        <div class="service-details">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #555;">
                                <i class="fas fa-home" style="width: 20px; color: #666;"></i>
                                <strong>Property:</strong> <?= htmlspecialchars($serviceLog->property_name ?? 'N/A') ?>
                            </p>
                            <p style="margin: 0 0 8px; font-size: 14px; color: #555;">
                                <i class="far fa-calendar-alt" style="width: 20px; color: #666;"></i>
                                <strong>Date:</strong> <?= date('M d, Y', strtotime($serviceLog->date ?? '')) ?>
                            </p>
                            <p style="margin: 0 0 8px; font-size: 14px; color: #555;">
                                <i class="fas fa-tasks" style="width: 20px; color: #666;"></i>
                                <strong>Description:</strong> <?= htmlspecialchars($serviceLog->service_description ?? 'No description') ?>
                            </p>
                        </div>
                        <div class="service-cost" style="text-align: right;">
                            <p style="margin: 0 0 8px; font-size: 14px; color: #555;">
                                <strong>Cost per hour:</strong> <?= number_format($serviceLog->cost_per_hour ?? 0, 2) ?> LKR
                            </p>
                            <?php if (isset($serviceLog->total_hours)): ?>
                                <p style="margin: 0 0 8px; font-size: 14px; color: #555;">
                                    <strong>Total hours:</strong> <?= $serviceLog->total_hours ?>
                                </p>
                            <?php endif; ?>
                            <p style="margin: 0 0 8px; font-size: 18px; font-weight: 700; color: #333;">
                                <strong>Total cost:</strong> <?= number_format($serviceLog->total_cost ?? ($serviceLog->cost_per_hour * ($serviceLog->total_hours ?? 1)), 2) ?> LKR
                            </p>
                        </div>
                    </div>
                    
                    <?php if (strtolower($serviceLog->status ?? '') === 'done'): ?>
                        <div class="service-actions" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px; border-top: 1px solid #eee; padding-top: 10px;">
                            <a href="<?= ROOT ?>/customer/payRegularService/<?= $serviceLog->service_id ?>" class="pay-button" style="padding: 8px 15px; background-color: var(--primary-color); color: white; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: 600;">
                                <i class="fas fa-credit-card" style="margin-right: 5px;"></i> Pay Now
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sortSelect = document.getElementById('sort');
    const serviceContainer = document.getElementById('service-logs-container');
    
    if (sortSelect && serviceContainer) {
        // Live sorting when dropdown changes
        sortSelect.addEventListener('change', function() {
            sortServiceCards(this.value);
        });
        
        // Function to sort service cards
        function sortServiceCards(sortOption) {
            const cards = Array.from(serviceContainer.querySelectorAll('.service-card'));
            if (cards.length === 0) return;
            
            cards.sort((a, b) => {
                switch (sortOption) {
                    case 'date_asc':
                        return new Date(a.dataset.date) - new Date(b.dataset.date);
                    case 'date_desc':
                        return new Date(b.dataset.date) - new Date(a.dataset.date);
                    case 'property_id':
                        return parseInt(a.dataset.propertyId) - parseInt(b.dataset.propertyId);
                    case 'cost_asc':
                        return parseFloat(a.dataset.cost) - parseFloat(b.dataset.cost);
                    case 'cost_desc':
                        return parseFloat(b.dataset.cost) - parseFloat(a.dataset.cost);
                    default:
                        return new Date(b.dataset.date) - new Date(a.dataset.date);
                }
            });
            
            // Clear container
            while (serviceContainer.firstChild) {
                serviceContainer.removeChild(serviceContainer.firstChild);
            }
            
            // Append sorted cards
            cards.forEach(card => {
                serviceContainer.appendChild(card);
            });
            
            // Visual feedback that sorting has occurred
            serviceContainer.classList.add('sorted');
            setTimeout(() => {
                serviceContainer.classList.remove('sorted');
            }, 300);
        }
    }
    
    // Initialize with current sort option
    if (sortSelect && serviceContainer) {
        sortServiceCards(sortSelect.value);
    }
});
</script>

<style>
#service-logs-container.sorted {
    transition: all 0.3s ease;
    opacity: 0.8;
    animation: flash 0.3s ease-out;
}

@keyframes flash {
    0% { opacity: 0.8; }
    50% { opacity: 0.9; }
    100% { opacity: 1; }
}

.service-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.service-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}
</style>

<!-- Add Font Awesome for icons if not already included -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php require_once 'customerFooter.view.php'; ?>