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
    <!-- Filtering Section -->
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
                        <option value="service_type" <?= isset($_GET['sort']) && $_GET['sort'] == 'service_type' ? 'selected' : '' ?>>Service Type</option>
                        <option value="cost_desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'cost_desc' ? 'selected' : '' ?>>Cost (Highest First)</option>
                        <option value="cost_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'cost_asc' ? 'selected' : '' ?>>Cost (Lowest First)</option>
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

    <!-- Statistics Cards -->
    <div class="stats-cards" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px;">
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(25, 118, 210, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <i class="fas fa-clipboard-list" style="font-size: 22px; color: #1976D2;"></i>
            </div>
            <div class="stat-value" style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 5px;"><?= count($serviceLogs) ?></div>
            <div class="stat-label" style="font-size: 14px; color: #777;">Total Requests</div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(76, 175, 80, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <i class="fas fa-check-circle" style="font-size: 22px; color: #4CAF50;"></i>
            </div>
            <div class="stat-value" style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 5px;"><?= $completedCount ?></div>
            <div class="stat-label" style="font-size: 14px; color: #777;">Paid</div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(33, 150, 243, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <i class="fas fa-credit-card" style="font-size: 22px; color: #2196F3;"></i>
            </div>
            <div class="stat-value" style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 5px;"><?= $doneCount ?></div>
            <div class="stat-label" style="font-size: 14px; color: #777;">Awaiting Payment</div>
        </div>
        
        <div class="stat-card" style="background: white; border-radius: 10px; padding: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; justify-content: center;">
            <div class="stat-icon" style="width: 50px; height: 50px; border-radius: 50%; background: rgba(255, 152, 0, 0.1); display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
                <i class="fas fa-clock" style="font-size: 22px; color: #FF9800;"></i>
            </div>
            <div class="stat-value" style="font-size: 24px; font-weight: 700; color: #333; margin-bottom: 5px;"><?= $pendingCount ?></div>
            <div class="stat-label" style="font-size: 14px; color: #777;">Pending</div>
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
                <div class="service-card" 
                     data-date="<?= $serviceLog->date ?? '' ?>"
                     data-service-type="<?= $serviceLog->service_type ?? '' ?>"
                     data-status="<?= strtolower($serviceLog->status ?? '') ?>"
                     data-cost="<?= $serviceLog->total_cost ?? ($serviceLog->cost_per_hour * ($serviceLog->total_hours ?? 1)) ?>">
                    
                    <div class="service-card-content">
                        <!-- Header with service type and styled status badge -->
                        <div class="service-header">
                            <div class="service-title">
                                <h3><?= htmlspecialchars($serviceLog->service_type ?? 'Service Request') ?></h3>
                                <span class="service-id">#<?= $serviceLog->service_id ?? 'N/A' ?></span>
                            </div>
                            
                            <!-- Status badge styling to match maintenance view -->
                            <div class="status-badge status-<?= strtolower($serviceLog->status ?? 'pending') ?>">
                                <span class="status-dot"></span>
                                <span class="status-text"><?= ucfirst($serviceLog->status ?? 'Pending') ?></span>
                            </div>
                        </div>
                        
                        <!-- Restructured service details -->
                        <div class="service-body">
                            <div class="service-info">
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-home"></i> Property</span>
                                    <span class="info-value"><?= htmlspecialchars($serviceLog->property_name ?? 'N/A') ?></span>
                                </div>
                                
                                <div class="info-item">
                                    <span class="info-label"><i class="fas fa-info-circle"></i> Description</span>
                                    <span class="info-value"><?= htmlspecialchars($serviceLog->service_description ?? 'No description provided') ?></span>
                                </div>
                                
                                <div class="info-row">
                                    <div class="info-item">
                                        <span class="info-label"><i class="far fa-calendar-alt"></i> Request Date</span>
                                        <span class="info-value"><?= date('M d, Y', strtotime($serviceLog->date ?? 'now')) ?></span>
                                    </div>
                                    
                                    <?php if (!empty($serviceLog->service_provider_name)): ?>
                                    <div class="info-item">
                                        <span class="info-label"><i class="fas fa-user-circle"></i> Service Provider</span>
                                        <span class="info-value"><?= htmlspecialchars($serviceLog->service_provider_name) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Cost summary box -->
                            <div class="cost-summary">
                                <h4><i class="fas fa-receipt"></i> Cost Summary</h4>
                                
                                <div class="cost-details">
                                    <div class="cost-row">
                                        <span>Hourly Rate</span>
                                        <span>LKR <?= number_format($serviceLog->cost_per_hour ?? 0, 2) ?></span>
                                    </div>
                                    
                                    <?php if (isset($serviceLog->total_hours)): ?>
                                        <div class="cost-row">
                                            <span>Hours Worked</span>
                                            <span><?= $serviceLog->total_hours ?> hrs</span>
                                        </div>
                                        
                                        <?php if (!empty($serviceLog->additional_charges)): ?>
                                        <div class="cost-row">
                                            <span>Additional Charges</span>
                                            <span>LKR <?= number_format($serviceLog->additional_charges, 2) ?></span>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="cost-row total">
                                            <span>Total Cost</span>
                                            <span>LKR 
                                            <?php
                                                $totalCost = ($serviceLog->cost_per_hour ?? 0) * ($serviceLog->total_hours ?? 0);
                                                $totalCost += ($serviceLog->additional_charges ?? 0);
                                                echo number_format($totalCost, 2);
                                            ?>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <div class="pending-note">
                                            <i class="fas fa-clock"></i> Final cost will be calculated when work is complete
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment button for completed services -->
                        <?php if (strtolower($serviceLog->status ?? '') === 'done'): ?>
                        <div class="service-actions">
                            <a href="<?= ROOT ?>/dashboard/payRegularService/<?= $serviceLog->service_id ?>" class="payment-button">
                                <i class="fas fa-credit-card"></i> Proceed to Pay
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Service card styling */
.service-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.25s ease;
    margin-bottom: 16px;
    border-left: 4px solid #e0e0e0;
}

.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

/* Status-based borders */
.service-card[data-status="pending"] { border-left-color: #FF9800; }
.service-card[data-status="ongoing"] { border-left-color: #2196F3; }
.service-card[data-status="done"], 
.service-card[data-status="paid"] { border-left-color: #4CAF50; }
.service-card[data-status="rejected"] { border-left-color: #F44336; }

.service-card-content {
    padding: 20px;
    position: relative;
}

/* Header styling */
.service-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.service-title {
    display: flex;
    align-items: center;
}

.service-title h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
    font-weight: 600;
}

.service-id {
    margin-left: 8px;
    color: #777;
    font-size: 14px;
}

/* Status badge styling - matching maintenance view */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    margin-right: 6px;
}

/* Status colors */
.status-pending {
    background-color: #FFF8E1;
    color: #F57C00;
}

.status-pending .status-dot {
    background-color: #F57C00;
    box-shadow: 0 0 0 2px rgba(245, 124, 0, 0.2);
}

.status-ongoing {
    background-color: #E3F2FD;
    color: #1976D2;
}

.status-ongoing .status-dot {
    background-color: #1976D2;
    box-shadow: 0 0 0 2px rgba(25, 118, 210, 0.2);
}

.status-done, .status-paid {
    background-color: #E8F5E9;
    color: #388E3C;
}

.status-done .status-dot, .status-paid .status-dot {
    background-color: #388E3C;
    box-shadow: 0 0 0 2px rgba(56, 142, 60, 0.2);
}

.status-rejected {
    background-color: #FFEBEE;
    color: #D32F2F;
}

.status-rejected .status-dot {
    background-color: #D32F2F;
    box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.2);
}

/* Service body with improved layout */
.service-body {
    display: flex;
    gap: 20px;
    margin-bottom: 16px;
}

.service-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.info-label {
    font-size: 13px;
    color: #666;
    font-weight: 500;
}

.info-label i {
    margin-right: 4px;
    color: #555;
}

.info-value {
    font-size: 15px;
    color: #333;
    line-height: 1.4;
}

.info-row {
    display: flex;
    gap: 24px;
}

.info-row .info-item {
    flex: 1;
}

/* Cost summary styling */
.cost-summary {
    width: 260px;
    background: #f9fafb;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.05);
    border: 1px solid #f0f0f0;
    align-self: flex-start;
}

.cost-summary h4 {
    margin: 0 0 12px 0;
    font-size: 15px;
    color: #444;
    font-weight: 600;
    padding-bottom: 8px;
    border-bottom: 1px dashed #e0e0e0;
    display: flex;
    align-items: center;
}

.cost-summary h4 i {
    margin-right: 6px;
    color: #555;
}

.cost-details {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.cost-row {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
}

.cost-row.total {
    margin-top: 8px;
    padding-top: 10px;
    border-top: 1px dashed #e0e0e0;
    font-weight: 600;
    color: #333;
}

.cost-row.total span:last-child {
    color: #388E3C;
}

.pending-note {
    background: #fffbeb;
    color: #92400e;
    padding: 10px;
    border-radius: 6px;
    font-size: 13px;
    text-align: center;
    margin-top: 8px;
    border: 1px dashed #fcd34d;
    font-style: italic;
}

.pending-note i {
    margin-right: 5px;
}

/* Service actions */
.service-actions {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: flex-end;
}

.payment-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    background: linear-gradient(135deg, #4CAF50 0%, #388E3C 100%);
    color: white;
    border-radius: 30px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s;
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
}

.payment-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(76, 175, 80, 0.4);
}

.payment-button i {
    margin-right: 8px;
    font-size: 16px;
}

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

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .service-body {
        flex-direction: column;
    }
    
    .cost-summary {
        width: 100%;
        margin-top: 16px;
    }
    
    .info-row {
        flex-direction: column;
        gap: 12px;
    }
    
    .filtering-section form {
        grid-template-columns: 1fr !important;
    }
}
</style>

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
                    case 'service_type':
                        return a.dataset.serviceType.localeCompare(b.dataset.serviceType);
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
        
        // Initialize with current sort option
        sortServiceCards(sortSelect.value);
    }
});
</script>

<!-- Add Font Awesome for icons if not already included -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<?php require_once 'customerFooter.view.php'; ?>