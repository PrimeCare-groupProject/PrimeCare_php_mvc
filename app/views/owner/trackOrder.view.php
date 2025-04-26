<?php require_once 'ownerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/dashboard/propertylisting/propertyunitowner/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Maintenance Timeline - <?= $property->name ?></h2>
                <p>Track all maintenance activities for this property</p>
            </div>
        </div>
    </div>
</div>

<div class="errors" style="display: <?= !empty($status) ? 'block' : 'none'; ?>; background-color: #b5f9a2;">
    <?php if (!empty($status)): ?>
        <p><?= $status;  ?></p>
    <?php endif; ?>
    <?php $_SESSION['status'] = '' ?>
</div>

<div class="maintenance-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <form action="" method="GET" class="filter-form">
            <input type="hidden" name="property_id" value="<?= $property->property_id ?>">
            
            <div class="filter-group">
                <label for="status_filter">Status:</label>
                <select name="status_filter" id="status_filter">
                    <option value="">All Statuses</option>
                    <option value="pending" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="ongoing" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                    <option value="done" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'done' ? 'selected' : '' ?>>Done</option>
                    <option value="rejected" <?= isset($_GET['status_filter']) && $_GET['status_filter'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="date_from">From:</label>
                <input type="date" name="date_from" id="date_from" value="<?= $_GET['date_from'] ?? '' ?>">
            </div>
            
            <div class="filter-group">
                <label for="date_to">To:</label>
                <input type="date" name="date_to" id="date_to" value="<?= $_GET['date_to'] ?? '' ?>">
            </div>
            
            <div class="filter-group">
                <label for="sort">Sort By:</label>
                <select name="sort" id="sort">
                    <option value="date_desc" <?= (!isset($_GET['sort']) || $_GET['sort'] == 'date_desc') ? 'selected' : '' ?>>Newest First</option>
                    <option value="date_asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'date_asc' ? 'selected' : '' ?>>Oldest First</option>
                </select>
            </div>
            
            <button type="submit" class="filter-button">Apply Filters</button>
            <a href="<?= ROOT ?>/dashboard/propertylisting/trackOrder/<?= $property->property_id ?>" class="reset-button">Reset</a>
        </form>
    </div>

    <!-- Service Logs Summary -->
    <div class="summary-box">
        <div class="summary-item">
            <span class="summary-label">Total Services:</span>
            <span class="summary-value"><?= count($serviceLogs) ?></span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Cost:</span>
            <span class="summary-value">LKR <?= number_format($totalCost, 2) ?></span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Pending Services:</span>
            <span class="summary-value"><?= $pendingCount ?></span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Last Serviced:</span>
            <span class="summary-value">
                <?php 
                $lastServiced = "Never"; 
                foreach($serviceLogs as $log) {
                    if (strtolower($log->status) == 'done') {
                        $lastServiced = date('M d, Y', strtotime($log->date));
                        break;
                    }
                }
                echo $lastServiced;
                ?>
            </span>
        </div>
    </div>
    
    <!-- Service History Timeline -->
    <div class="timeline-container">
        <?php if (empty($serviceLogs)): ?>
            <div class="no-records">
                <p>No maintenance records found for this property.</p>
                <a href="<?= ROOT ?>/dashboard/propertylisting/repairlisting?property_name=<?= urlencode($property->name) ?>&property_id=<?= urlencode($property->property_id) ?>" class="add-service-btn">Request New Service</a>
            </div>
        <?php else: ?>
            <!-- Group service logs by year and month -->
            <?php
            $groupedLogs = [];
            foreach ($serviceLogs as $log) {
                $year = date('Y', strtotime($log->date));
                $month = date('F', strtotime($log->date));
                $day = date('d', strtotime($log->date));
                
                if (!isset($groupedLogs[$year][$month][$day])) {
                    $groupedLogs[$year][$month][$day] = [];
                }
                
                $groupedLogs[$year][$month][$day][] = $log;
            }
            ?>
            
            <div class="timeline">
                <?php foreach ($groupedLogs as $year => $months): ?>
                    <div class="timeline-year">
                        <div class="timeline-year-header">
                            <h3><?= $year ?></h3>
                        </div>
                        
                        <?php foreach ($months as $month => $days): ?>
                            <div class="timeline-month">
                                <div class="timeline-month-header">
                                    <h4><?= $month ?></h4>
                                </div>
                                
                                <?php foreach ($days as $day => $logs): ?>
                                    <div class="timeline-day">
                                        <div class="timeline-day-marker">
                                            <div class="timeline-date">
                                                <span class="day"><?= $day ?></span>
                                                <span class="month"><?= substr($month, 0, 3) ?></span>
                                            </div>
                                        </div>
                                        
                                        <div class="timeline-entries">
                                            <?php foreach ($logs as $log): ?>
                                                <div class="timeline-entry status-<?= strtolower(str_replace(' ', '-', $log->status)) ?>">
                                                    <div class="timeline-dot"></div>
                                                    <div class="timeline-content">
                                                        <div class="timeline-header">
                                                            <h5><?= $log->service_type ?></h5>
                                                            <span class="timeline-time"><?= date('h:i A', strtotime($log->date)) ?></span>
                                                            <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $log->status)) ?>"><?= $log->status ?></span>
                                                        </div>
                                                        
                                                        <div class="timeline-details">
                                                            <p><?= $log->service_description ?></p>
                                                            <div class="timeline-meta">
                                                                <div class="meta-item">
                                                                    <span class="meta-label">Service ID:</span>
                                                                    <span class="meta-value">#<?= $log->service_id ?></span>
                                                                </div>
                                                                <div class="meta-item">
                                                                    <span class="meta-label">Cost:</span>
                                                                    <span class="meta-value">LKR <?= number_format($log->total_cost, 2) ?></span>
                                                                </div>
                                                                <?php if ($log->total_hours): ?>
                                                                    <div class="meta-item">
                                                                        <span class="meta-label">Hours:</span>
                                                                        <span class="meta-value"><?= $log->total_hours ?></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                            <?php if ($log->status == 'Done'): ?>
                                                                <a href="<?= ROOT ?>/dashboard/payment/<?= $log->service_id ?>" class="action-button pay-button">Pay Now</a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="timeline-actions">
                <a href="<?= ROOT ?>/dashboard/propertylisting/repairlisting?property_name=<?= urlencode($property->name) ?>&property_id=<?= urlencode($property->property_id) ?>" class="request-service-btn">
                    <i class="fa fa-plus-circle"></i> Request New Service
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .maintenance-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Arial', sans-serif;
    }
    
    .filter-section {
        background-color: #f7f9fc;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: flex-end;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
        flex: 1;
    }
    
    .filter-group label {
        margin-bottom: 5px;
        color: #555;
        font-weight: 500;
        font-size: 0.9rem;
    }
    
    .filter-group select,
    .filter-group input {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
    }
    
    .filter-button, 
    .reset-button {
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .filter-button {
        background-color: #4CAF50;
        color: white;
    }
    
    .filter-button:hover {
        background-color: #45a049;
    }
    
    .reset-button {
        background-color: #f1f1f1;
        color: #333;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .reset-button:hover {
        background-color: #e7e7e7;
    }
    
    /* Summary Box Styles */
    .summary-box {
        display: flex;
        justify-content: space-between;
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .summary-item {
        text-align: center;
        flex: 1;
    }
    
    .summary-label {
        display: block;
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }
    
    .summary-value {
        display: block;
        font-size: 1.4rem;
        font-weight: bold;
        color: #333;
    }
    
    /* Timeline Styles */
    .timeline-container {
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .timeline {
        position: relative;
        padding: 20px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 100px;
        width: 4px;
        background-color: #eaeaea;
        z-index: 0;
    }
    
    .timeline-year {
        position: relative;
        margin-bottom: 30px;
    }
    
    .timeline-year-header {
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }
    
    .timeline-year-header h3 {
        background-color: #3f51b5;
        color: white;
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 1.2rem;
        margin-left: 20px;
    }
    
    .timeline-month {
        margin-bottom: 20px;
    }
    
    .timeline-month-header {
        margin-bottom: 15px;
        position: relative;
        z-index: 1;
    }
    
    .timeline-month-header h4 {
        color: #555;
        font-size: 1.1rem;
        border-bottom: 2px solid #eaeaea;
        padding-bottom: 10px;
        margin-left: 120px;
    }
    
    .timeline-day {
        display: flex;
        margin-bottom: 20px;
    }
    
    .timeline-day-marker {
        width: 100px;
        padding-right: 20px;
        text-align: right;
        flex-shrink: 0;
    }
    
    .timeline-date {
        background-color: #f5f5f5;
        border-radius: 50%;
        width: 60px;
        height: 60px;
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
        z-index: 1;
    }
    
    .timeline-date .day {
        font-size: 1.3rem;
        font-weight: bold;
        color: #333;
        line-height: 1;
    }
    
    .timeline-date .month {
        font-size: 0.8rem;
        color: #777;
    }
    
    .timeline-entries {
        flex-grow: 1;
    }
    
    .timeline-entry {
        position: relative;
        border-radius: 8px;
        margin-bottom: 15px;
        background-color: #f9f9f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .timeline-entry:hover {
        box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    
    .timeline-entry.status-pending {
        border-left: 4px solid #ffc107;
    }
    
    .timeline-entry.status-ongoing {
        border-left: 4px solid #2196F3;
    }
    
    .timeline-entry.status-done {
        border-left: 4px solid #4CAF50;
    }
    
    .timeline-entry.status-rejected {
        border-left: 4px solid #f44336;
    }
    
    .timeline-dot {
        position: absolute;
        left: -34px;
        top: 20px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: white;
        border: 3px solid #ccc;
        z-index: 1;
    }
    
    .status-pending .timeline-dot {
        border-color: #ffc107;
    }
    
    .status-ongoing .timeline-dot {
        border-color: #2196F3;
    }
    
    .status-done .timeline-dot {
        border-color: #4CAF50;
    }
    
    .status-rejected .timeline-dot {
        border-color: #f44336;
    }
    
    .timeline-content {
        padding: 15px;
    }
    
    .timeline-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }
    
    .timeline-header h5 {
        font-size: 1.1rem;
        color: #333;
        margin: 0;
        flex-grow: 1;
    }
    
    .timeline-time {
        color: #777;
        font-size: 0.9rem;
        margin-right: 10px;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 20px;
        color: white;
    }
    
    .status-badge.status-pending {
        background-color: #ffc107;
        color: #856404;
    }
    
    .status-badge.status-ongoing {
        background-color: #2196F3;
        color: white;
    }
    
    .status-badge.status-done {
        background-color: #4CAF50;
        color: white;
    }
    
    .status-badge.status-rejected {
        background-color: #f44336;
        color: white;
    }
    
    .timeline-details {
        padding-top: 10px;
        border-top: 1px solid #eee;
    }
    
    .timeline-details p {
        margin-top: 0;
        color: #555;
        line-height: 1.5;
    }
    
    .timeline-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 15px;
    }
    
    .meta-item {
        font-size: 0.9rem;
    }
    
    .meta-label {
        color: #777;
        margin-right: 5px;
    }
    
    .meta-value {
        font-weight: 500;
        color: #333;
    }
    
    .action-button {
        display: inline-block;
        margin-top: 15px;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .pay-button {
        background-color: #2196F3;
        color: white;
    }
    
    .pay-button:hover {
        background-color: #1976D2;
    }
    
    .no-records {
        padding: 50px 20px;
        text-align: center;
    }
    
    .no-records p {
        color: #777;
        margin-bottom: 20px;
    }
    
    .add-service-btn, .request-service-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .add-service-btn:hover, .request-service-btn:hover {
        background-color: #45a049;
    }
    
    .timeline-actions {
        text-align: center;
        margin-top: 30px;
        padding: 20px;
        border-top: 1px solid #eee;
    }
    
    .request-service-btn {
        font-size: 1rem;
    }
    
    @media (max-width: 768px) {
        .filter-form {
            flex-direction: column;
            gap: 10px;
        }
        
        .filter-group {
            width: 100%;
        }
        
        .summary-box {
            flex-wrap: wrap;
        }
        
        .summary-item {
            flex-basis: 50%;
            margin-bottom: 15px;
        }
        
        .timeline:before {
            left: 30px;
        }
        
        .timeline-day {
            flex-direction: column;
        }
        
        .timeline-day-marker {
            width: 100%;
            text-align: left;
            padding-bottom: 15px;
        }
        
        .timeline-date {
            margin-left: 30px;
        }
        
        .timeline-month-header h4 {
            margin-left: 50px;
        }
        
        .timeline-entry .timeline-dot {
            left: -24px;
        }
    }
</style>

<?php require_once 'ownerFooter.view.php'; ?>