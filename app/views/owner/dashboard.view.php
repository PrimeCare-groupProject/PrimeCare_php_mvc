<?php require_once 'ownerHeader.view.php'; ?>
<!-- Place scripts at the top with specific versions -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --accent: #4895ef;
        --success: #4cc9f0;
        --warning: #f72585;
        --danger: #e5383b;
        --neutral: #e9ecef;
        --dark: #212529;
        --light: #f8f9fa;
    }
    
    body {
        background-color: #f0f2f5;
    }
    
    .modern-dashboard {
        padding: 1rem;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        display: flex;
        position: relative;
        transition: transform 0.2s, box-shadow 0.2s;
        border-left: 4px solid var(--primary);
    }
    
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
    
    .summary-card .icon-container {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.5rem;
    }
    
    .summary-card.properties .icon-container {
        background-color: rgba(67, 97, 238, 0.15);
        color: var(--primary);
    }
    
    .summary-card.properties {
        border-left-color: var(--primary);
    }
    
    .summary-card.income .icon-container {
        background-color: rgba(76, 201, 240, 0.15);
        color: var(--success);
    }
    
    .summary-card.income {
        border-left-color: var(--success);
    }
    
    .summary-card.expenses .icon-container {
        background-color: rgba(247, 37, 133, 0.15);
        color: var(--warning);
    }
    
    .summary-card.expenses {
        border-left-color: var(--warning);
    }
    
    .summary-card.profit .icon-container {
        background-color: rgba(63, 55, 201, 0.15);
        color: var(--secondary);
    }
    
    .summary-card.profit {
        border-left-color: var(--secondary);
    }
    
    .summary-card.bookings .icon-container {
        background-color: rgba(72, 149, 239, 0.15);
        color: var(--accent);
    }
    
    .summary-card.bookings {
        border-left-color: var(--accent);
    }
    
    .summary-card.system-total .icon-container {
        background-color: rgba(229, 56, 59, 0.15);
        color: var(--danger);
    }
    
    .summary-card.system-total {
        border-left-color: var(--danger);
    }
    
    .card-content {
        flex: 1;
    }
    
    .card-content h3 {
        margin: 0;
        font-size: 0.9rem;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .card-content .value {
        font-size: 1.8rem;
        font-weight: 600;
        margin: 0.6rem 0;
    }
    
    .card-content .value.positive {
        color: var(--success);
    }
    
    .card-content .value.negative {
        color: var(--danger);
    }
    
    .card-footer {
        font-size: 0.85rem;
        color: #757575;
        display: flex;
        align-items: center;
    }
    
    .analytics-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 992px) {
        .analytics-section {
            grid-template-columns: 1fr;
        }
    }
    
    .chart-container, .expense-breakdown, .transactions-section, 
    .property-section, .maintenance-section, .booking-rate-section, .recent-props-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .card-header h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--dark);
    }
    
    .period-selector select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 0.4rem;
        background-color: white;
        font-size: 0.9rem;
    }
    
    .chart-area {
        height: 300px;
        position: relative;
    }
    
    .pie-chart-container {
        height: 200px;
        position: relative;
        margin-bottom: 1rem;
    }
    
    .expense-legend {
        max-height: 180px;
        overflow-y: auto;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        border-radius: 4px;
        transition: background-color 0.2s;
    }
    
    .legend-item:hover {
        background-color: rgba(0,0,0,0.03);
    }
    
    .bullet {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    .label {
        flex: 1;
        font-size: 0.9rem;
    }
    
    .amount {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .transaction-table, .property-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .transaction-table th, .property-table th {
        text-align: left;
        padding: 0.75rem;
        border-bottom: 2px solid #eee;
        font-size: 0.85rem;
        font-weight: 600;
        color: #757575;
    }
    
    .transaction-table td, .property-table td {
        padding: 0.75rem;
        border-bottom: 1px solid #eee;
        font-size: 0.9rem;
    }
    
    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .badge-success {
        background-color: rgba(76, 201, 240, 0.15);
        color: var(--success);
    }
    
    .badge-warning {
        background-color: rgba(247, 37, 133, 0.15);
        color: var(--warning);
    }
    
    .badge-danger {
        background-color: rgba(229, 56, 59, 0.15);
        color: var(--danger);
    }
    
    .badge-info {
        background-color: rgba(72, 149, 239, 0.15);
        color: var(--accent);
    }
    
    .badge-primary {
        background-color: rgba(67, 97, 238, 0.15);
        color: var(--primary);
    }
    
    .two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .three-columns {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 992px) {
        .three-columns {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .two-columns, .three-columns {
            grid-template-columns: 1fr;
        }
    }
    
    .action-buttons {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }
    
    .action-button {
        background-color: var(--primary);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        text-decoration: none;
        margin-left: 0.5rem;
        transition: background-color 0.2s;
    }
    
    .action-button:hover {
        background-color: var(--secondary);
    }
    
    .action-button.secondary {
        background-color: #757575;
    }
    
    .action-button.secondary:hover {
        background-color: #616161;
    }
    
    .status-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }
    
    .status-active {
        background-color: var(--success);
    }
    
    .status-pending {
        background-color: var(--warning);
    }
    
    .status-inactive {
        background-color: var(--danger);
    }
    
    .no-data {
        text-align: center;
        padding: 2rem;
        color: #757575;
    }
    
    .speedometer-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    
    .gauge-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dark);
        margin-top: 1rem;
    }
    
    .recent-property-card {
        display: flex;
        margin-bottom: 1rem;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .recent-property-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .property-image {
        width: 100px;
        height: 70px;
        background-size: cover;
        background-position: center;
        background-color: #eee;
    }
    
    .property-details {
        flex: 1;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    
    .property-name {
        font-weight: 600;
        margin: 0 0 0.25rem;
        font-size: 0.95rem;
    }
    
    .property-address {
        font-size: 0.8rem;
        color: #666;
        margin: 0;
    }
    
    .property-status {
        display: inline-block;
        font-size: 0.75rem;
        margin-top: 0.5rem;
    }
    
    /* Debug indicator styles */
    .debug-indicator {
        position: fixed;
        bottom: 10px;
        right: 10px;
        padding: 5px 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        border-radius: 4px;
        font-size: 12px;
        z-index: 9999;
    }
    
    .chart-placeholder {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #757575;
        font-style: italic;
    }

    /* Fallback content styling */
    .fallback-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(255, 255, 255, 0.9);
    }
</style>

<div class="user_view-menu-bar">
    <h2>Dashboard</h2>
</div>

<div class="modern-dashboard">
    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card properties">
            <div class="icon-container">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-content">
                <h3>My Properties</h3>
                <div class="value"><?= $propertyCount ?? 0 ?></div>
                <div class="card-footer">
                    <a href="<?= ROOT ?>/dashboard/propertylisting">Manage Properties</a>
                </div>
            </div>
        </div>
        
        
        <div class="summary-card income">
            <div class="icon-container">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="card-content">
                <h3>Total Income</h3>
                <div class="value">
                    <?php
                    // Ensure we have a valid totalIncome value
                    $displayIncome = isset($totalIncome) && is_numeric($totalIncome) ? $totalIncome : 0;
                    echo 'LKR ' . number_format($displayIncome, 2);
                    ?>
                </div>
                <div class="card-footer">
                    <span><?= $activeBookings ?? 0 ?> active bookings</span>
                </div>
            </div>
        </div>
        
        <div class="summary-card profit">
            <div class="icon-container">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-content">
                <h3>Net Profit</h3>
                <?php
                $calculatedProfit = ($totalIncome ?? 0) - ($totalServiceCost ?? 0);
                ?>
                <div class="value <?= $calculatedProfit >= 0 ? 'positive' : 'negative' ?>">
                    LKR <?= number_format($calculatedProfit, 2) ?>
                </div>
                <div class="card-footer">
                    <?php 
                    $income = $totalIncome ?? 0;
                    $margin = $income > 0 ? ($calculatedProfit / $income) * 100 : 0;
                    ?>
                    <span><?= number_format($margin, 1) ?>% margin</span>
                </div>
            </div>
        </div>
        
        <!-- New total bookings card -->
        <div class="summary-card bookings">
            <div class="icon-container">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="card-content">
                <h3>Total Bookings</h3>
                <div class="value">
                    <?php 
                    // Calculate total bookings here
                    $ownerId = $_SESSION['user']->pid;
                    $bookingModel = new BookingModel();
                    $totalBookingsCount = 0;
                    
                    // Get all properties owned by this user
                    $propertyModel = new PropertyConcat();
                    $ownerProperties = $propertyModel->where(['person_id' => $ownerId]);
                    
                    if($ownerProperties) {
                        foreach($ownerProperties as $prop) {
                            $propertyBookings = $bookingModel->where(['property_id' => $prop->property_id]);
                            if($propertyBookings) {
                                $totalBookingsCount += count($propertyBookings);
                            }
                        }
                    }
                    
                    echo $totalBookingsCount;
                    ?>
                </div>
                <div class="card-footer">
                    <span>Total Revenue: LKR <?= number_format($totalIncome ?? 0, 2) ?></span>
                </div>
            </div>
        </div>
        
        <!-- New system total properties card -->
        <div class="summary-card system-total">
            <div class="icon-container">
                <i class="fas fa-building"></i>
            </div>
            <div class="card-content">
                <h3>System Properties</h3>
                <?php
                // Get total property count in the system
                $property = new PropertyConcat();
                $totalSystemProperties = count($property->findAll()) ?? 0;
                ?>
                <div class="value"><?= $totalSystemProperties ?></div>
                <div class="card-footer">
                    <span>Your share: <?= $propertyCount > 0 && $totalSystemProperties > 0 ? number_format(($propertyCount / $totalSystemProperties) * 100, 1) : 0 ?>%</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking Rate and Recent Properties -->
    <div class="two-columns">
        <!-- Booking Rate Section (Speedometer) with fallback -->
        <div class="booking-rate-section">
            <div class="card-header">
                <h3>Property Booking Rate</h3>
            </div>
            <div class="speedometer-container">
                <div id="booking-gauge" style="height: 200px; width: 100%; position: relative;">
                    <!-- Static fallback that will be replaced by JS if working -->
                    <div id="gauge-fallback" class="fallback-content">
                        <div style="text-align: center;">
                            <div style="font-size: 3rem; font-weight: bold; color: #4361ee;">
                                <?php
                                // Calculate and use the actual booking rate
                                $bookingRate = isset($occupancyRate) ? $occupancyRate : (
                                    ($totalUnits > 0 && isset($activeBookings)) ? 
                                    min(100, ($activeBookings / $totalUnits) * 100) : 0
                                );
                                echo number_format($bookingRate, 1) . '%';
                                ?>
                            </div>
                            <div style="font-size: 1rem; color: #666;">Occupancy Rate</div>
                        </div>
                    </div>
                </div>
                <div class="gauge-value">
                    <?= number_format($bookingRate, 1) ?>% Occupancy
                </div>  
            </div>
                <div class="gauge-value">
                    <?= number_format($bookingRate, 1) ?>% Occupancy
                </div>  
            </div>
        </div>
        
        <!-- Recent Properties Section -->
        <div class="recent-props-section">
            <div class="card-header">
                <h3>Recent Properties</h3>
                <a href="<?= ROOT ?>/dashboard/propertylisting" class="action-button">View All</a>
            </div>
            
            <?php 
            // Make sure properties are available
            if (isset($properties) && is_array($properties) && count($properties) > 0):
                
                // Check if PropertyImageModel class exists, if not, create a basic version
                if (!class_exists('PropertyImageModel')) {
                    class PropertyImageModel {
                        use Model;
                        protected $table = 'property_images';
                        public function where($conditions) {
                            return [];  // Return empty if not implemented
                        }
                    }
                }
                
                // Sort by newest first (assuming property_id is auto-incremented)
                usort($properties, function($a, $b) {
                    return isset($b->property_id) && isset($a->property_id) ? 
                        $b->property_id - $a->property_id : 0;
                });
                
                // Get the 4 most recent properties
                $recentProperties = array_slice($properties, 0, 4);
                
                // Set up a default image if none is found
                $defaultImage = ROOT . '/assets/images/property-default.jpg';
                
                foreach($recentProperties as $property): 
                    // Try to get property image
                    $imageUrl = $defaultImage;
                    try {
                        $propertyImage = new PropertyImageModel();
                        $images = $propertyImage->where(['property_id' => $property->property_id ?? 0]);
                        if (!empty($images)) {
                            $imageUrl = ROOT . '/assets/images/uploads/property_images/' . $images[0]->image_url;
                        }
                    } catch (Exception $e) {
                        // Use default image if error
                    }
            ?>
                <div class="recent-property-card">
                    <div class="property-image" style="background-image: url('<?= $imageUrl ?>');"></div>
                    <div class="property-details">
                        <div>
                            <h4 class="property-name"><?= htmlspecialchars($property->name ?? 'Unnamed Property') ?></h4>
                            <p class="property-address"><?= htmlspecialchars($property->address ?? 'No address') ?></p>
                        </div>
                        <?php 
                        $statusClass = 'badge-info';
                        $status = $property->status ?? 'Unknown';
                        
                        if(strtolower($status) === 'active') $statusClass = 'badge-success';
                        else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                        else if(strtolower($status) === 'inactive') $statusClass = 'badge-danger';
                        ?>
                        <span class="badge <?= $statusClass ?> property-status"><?= htmlspecialchars($status) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-home" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p>No properties found. Add your first property!</p>
                    <a href="<?= ROOT ?>/dashboard/propertylisting/addproperty" class="action-button">Add Property</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Analytics Section with Fallbacks -->
    <div class="analytics-section">
        <div class="chart-container">
            <div class="card-header">
                <h3>Financial Overview</h3>
                <div class="period-selector">
                    <select id="chartPeriod">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
            </div>
            <div class="chart-area">
                <canvas id="financialOverview"></canvas>
                <!-- Static fallback chart -->
                <div id="finance-fallback" class="fallback-content">
                    <table style="width: 80%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; text-align: left; border-bottom: 2px solid #eee;">Month</th>
                                <th style="padding: 8px; text-align: right; border-bottom: 2px solid #eee;">Income</th>
                                <th style="padding: 8px; text-align: right; border-bottom: 2px solid #eee;">Expenses</th>
                                <th style="padding: 8px; text-align: right; border-bottom: 2px solid #eee;">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $demoMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                            $demoIncome = [15000, 22000, 18500, 25000, 27500, 30000];
                            $demoExpenses = [5000, 8000, 6500, 9000, 7500, 10000];
                            
                            foreach($demoMonths as $i => $month): 
                                $profit = $demoIncome[$i] - $demoExpenses[$i];
                            ?>
                            <tr>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= $month ?></td>
                                <td style="padding: 8px; text-align: right; border-bottom: 1px solid #eee; color: #4cc9f0;">
                                    LKR <?= number_format($demoIncome[$i], 2) ?>
                                </td>
                                <td style="padding: 8px; text-align: right; border-bottom: 1px solid #eee; color: #f72585;">
                                    LKR <?= number_format($demoExpenses[$i], 2) ?>
                                </td>
                                <td style="padding: 8px; text-align: right; border-bottom: 1px solid #eee; color: #3f37c9; font-weight: bold;">
                                    LKR <?= number_format($profit, 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="expense-breakdown">
            <div class="card-header">
                <h3>Expense Breakdown</h3>
            </div>
            <div class="pie-chart-container">
                <canvas id="expenseBreakdown"></canvas>
                <!-- Static fallback for expense breakdown -->
                <div id="expense-fallback" class="fallback-content">
                    <div style="width: 100%;">
                        <?php
                        $demoExpenseTypes = ['Plumbing', 'Electrical', 'Cleaning', 'Maintenance'];
                        $demoExpenseValues = [1500, 2300, 900, 1800];
                        $total = array_sum($demoExpenseValues);
                        $colors = ['#4361ee', '#f72585', '#4cc9f0', '#fb8500'];
                        
                        foreach($demoExpenseTypes as $i => $type):
                            $percentage = ($demoExpenseValues[$i] / $total) * 100;
                        ?>
                        <div style="display: flex; align-items: center; margin-bottom: 10px; padding: 5px; border-radius: 4px;">
                            <div style="width: 12px; height: 12px; border-radius: 50%; margin-right: 10px; background-color: <?= $colors[$i] ?>;"></div>
                            <div style="flex: 1;"><?= $type ?></div>
                            <div style="font-weight: bold;">
                                LKR <?= number_format($demoExpenseValues[$i], 2) ?> 
                                <span style="color: #666; font-size: 0.9em; margin-left: 5px;">(<?= number_format($percentage, 1) ?>%)</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="expense-legend" id="expenseLegend">
                <!-- Will be populated by JavaScript or fallback -->
                <?php if (!isset($expenseTypes) || empty($expenseTypes)): ?>
                    <div id="expense-legend-fallback">
                        <?php
                        $demoExpenseTypes = ['Plumbing', 'Electrical', 'Cleaning', 'Maintenance'];
                        $demoExpenseValues = [1500, 2300, 900, 1800];
                        $total = array_sum($demoExpenseValues);
                        $colors = ['#4361ee', '#f72585', '#4cc9f0', '#fb8500'];
                        
                        foreach($demoExpenseTypes as $i => $type):
                            $percentage = ($demoExpenseValues[$i] / $total) * 100;
                        ?>
                        <div class="legend-item">
                            <div class="bullet" style="background-color: <?= $colors[$i] ?>;"></div>
                            <div class="label"><?= $type ?></div>
                            <div class="amount">LKR <?= number_format($demoExpenseValues[$i], 2) ?> (<?= number_format($percentage, 1) ?>%)</div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Properties and Maintenance Section -->
    <div class="two-columns">
        <div class="property-section">
            <div class="card-header">
                <h3>My Properties</h3>
                <a href="<?= ROOT ?>/dashboard/propertylisting/addproperty" class="action-button">Add Property</a>
            </div>
            <?php if(isset($properties) && is_array($properties) && count($properties) > 0): ?>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach(array_slice($properties, 0, 5) as $property): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <div>
                                    <div style="font-weight: 500;"><?= htmlspecialchars($property->name ?? 'Unnamed Property') ?></div>
                                    <div style="font-size: 0.8rem; color: #757575;"><?= htmlspecialchars($property->address ?? 'No address') ?></div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php 
                            $statusClass = 'badge-info';
                            $status = $property->status ?? 'Unknown';
                            
                            if(strtolower($status) === 'active') $statusClass = 'badge-success';
                            else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                            else if(strtolower($status) === 'inactive') $statusClass = 'badge-danger';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                        </td>
                        <td>
                            <a href="<?= ROOT ?>/dashboard/propertyListing/propertyunitowner/<?= $property->property_id ?? 0 ?>" class="action-button secondary">View</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if(count($properties) > 5): ?>
            <div class="action-buttons">
                <a href="<?= ROOT ?>/dashboard/propertylisting" class="action-button">View All</a>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <div class="no-data" style="text-align: center; padding: 2rem;">
                <i class="fas fa-home" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                <p>No properties found. Add your first property!</p>
                <a href="<?= ROOT ?>/dashboard/propertylisting/addproperty" class="action-button">Add Property</a>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="maintenance-section">
            <div class="card-header">
                <h3>Maintenance Requests</h3>
                <a href="<?= ROOT ?>/dashboard/maintenance" class="action-button">View All</a>
            </div>
            <?php if(isset($serviceLogs) && is_array($serviceLogs) && count($serviceLogs) > 0): ?>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach(array_slice($serviceLogs, 0, 5) as $log): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500;"><?= htmlspecialchars($log->service_type ?? 'Unknown Service') ?></div>
                            <div style="font-size: 0.8rem; color: #757575;">
                                <?= htmlspecialchars($log->property_name ?? 'Unknown Property') ?>
                            </div>
                        </td>
                        <td><?= isset($log->date) ? date('M d, Y', strtotime($log->date)) : 'Unknown Date' ?></td>
                        <td>
                            <?php
                            $statusClass = 'badge-info';
                            $status = $log->status ?? 'Unknown';
                            
                            if(strtolower($status) === 'done') $statusClass = 'badge-success';
                            else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                            else if(strtolower($status) === 'ongoing') $statusClass = 'badge-primary';
                            ?>
                            <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <div class="no-data" style="text-align: center; padding: 2rem;">
                <i class="fas fa-tools" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                <p>No maintenance requests found.</p>
                <a href="<?= ROOT ?>/dashboard/propertyListing/servicerequest" class="action-button">Request Service</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Transactions -->
    <div class="transactions-section">
        <div class="card-header">
            <h3>Recent Transactions</h3>
            <a href="<?= ROOT ?>/dashboard/financeReport" class="action-button">View All</a>
        </div>
        
        <?php
        // Generate demo transactions if none exist
        if(!isset($recentTransactions) || !is_array($recentTransactions) || empty($recentTransactions)) {
            // Create some sample transactions based on service logs
            $recentTransactions = [];
            
            if(isset($serviceLogs) && is_array($serviceLogs)) {
                foreach(array_slice($serviceLogs, 0, 3) as $log) {
                    $recentTransactions[] = [
                        'date' => $log->date ?? date('Y-m-d'),
                        'description' => 'Payment for ' . ($log->service_type ?? 'Service'),
                        'category' => 'Expense',
                        'amount' => -($log->total_cost ?? 0),
                        'status' => $log->status ?? 'Pending'
                    ];
                }
            }
            
            // Add a sample income transaction
            $recentTransactions[] = [
                'date' => date('Y-m-d'),
                'description' => 'Monthly Rental Income',
                'category' => 'Income',
                'amount' => 25000,
                'status' => 'Paid'
            ];
        }
        ?>
        
        <?php if(is_array($recentTransactions) && !empty($recentTransactions)): ?>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recentTransactions as $transaction): ?>
                <tr>
                    <td><?= isset($transaction['date']) ? date('M d, Y', strtotime($transaction['date'])) : 'Unknown' ?></td>
                    <td><?= htmlspecialchars($transaction['description'] ?? 'Unknown Transaction') ?></td>
                    <td>
                        <?php if(isset($transaction['category']) && $transaction['category'] === 'Income'): ?>
                        <span class="badge badge-success">Income</span>
                        <?php else: ?>
                        <span class="badge badge-danger">Expense</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-weight: 600; <?= isset($transaction['amount']) && $transaction['amount'] >= 0 ? 'color: var(--success);' : 'color: var(--warning);' ?>">
                        LKR <?= number_format(abs($transaction['amount'] ?? 0), 2) ?>
                    </td>
                    <td>
                        <?php
                        $statusClass = 'badge-info';
                        $status = $transaction['status'] ?? 'Unknown';
                        
                        if(strtolower($status) === 'active' || strtolower($status) === 'accepted' || strtolower($status) === 'done' || strtolower($status) === 'paid') 
                            $statusClass = 'badge-success';
                        else if(strtolower($status) === 'pending') 
                            $statusClass = 'badge-warning';
                        else if(strtolower($status) === 'rejected' || strtolower($status) === 'cancelled') 
                            $statusClass = 'badge-danger';
                        ?>
                        <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="no-data" style="text-align: center; padding: 2rem;">
            <i class="fas fa-exchange-alt" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
            <p>No transactions found.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Move scripts to the bottom for faster page loading -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Use actual data from PHP variables instead of demo data
    const monthlyData = <?= !empty($monthlyData) && is_array($monthlyData) ? json_encode($monthlyData) : '{}' ?>;
    const expenseTypes = <?= !empty($expenseTypes) && is_array($expenseTypes) ? json_encode($expenseTypes) : '{}' ?>;
    
    // Convert PHP data to format needed for charts
    const months = Object.keys(monthlyData);
    const incomeData = months.map(month => monthlyData[month].income || 0);
    const expenseData = months.map(month => monthlyData[month].expense || 0);  
    const profitData = months.map(month => monthlyData[month].profit || 0);
    
    // For expense breakdown
    const expenseLabels = Object.keys(expenseTypes);
    const expenseValues = Object.values(expenseTypes);
    const expenseColors = generateColors(expenseLabels.length); 
    
    // Get booking rate from PHP (with fallback)
    let bookingRate = <?= isset($bookingRate) ? floatval($bookingRate) : (isset($occupancyRate) ? floatval($occupancyRate) : 0) ?>;
    
    // Helper function to generate colors
    function generateColors(count) {
        const colors = [
            '#4361ee', '#3f37c9', '#4895ef', '#4cc9f0', '#f72585',
            '#e5383b', '#6a994e', '#386641', '#bc4749', '#fb8500'
        ];
        return Array(count).fill(0).map((_, i) => colors[i % colors.length]);
    }
    
    // Helper function to check if libraries are loaded
    function isLibraryLoaded(libraryName) {
        switch(libraryName) {
            case 'Chart':
                return typeof Chart !== 'undefined';
            case 'Gauge':
                return typeof Gauge !== 'undefined';
            default:
                return false;
        }
    }
    
    // Function to hide a fallback element
    function removeFallback(id) {
        const fallback = document.getElementById(id);
        if (fallback) fallback.style.display = 'none';
    }
    
    // Try to initialize the financial chart
    try {
        if (isLibraryLoaded('Chart')) {
            // Financial Overview Chart
            const financialOverviewElement = document.getElementById('financialOverview');
            if (financialOverviewElement) {
                removeFallback('finance-fallback');
                const ctx = financialOverviewElement.getContext('2d');
                
                // Use real data from PHP
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: [
                            {
                                label: 'Income',
                                data: incomeData,
                                backgroundColor: 'rgba(76, 201, 240, 0.6)',
                                borderColor: '#4cc9f0',
                                borderWidth: 1
                            },
                            {
                                label: 'Expenses',
                                data: expenseData,
                                backgroundColor: 'rgba(247, 37, 133, 0.6)',
                                borderColor: '#f72585',
                                borderWidth: 1
                            },
                            {
                                label: 'Profit',
                                data: profitData,
                                type: 'line',
                                fill: false,
                                tension: 0.4,
                                backgroundColor: 'rgba(63, 55, 201, 0.6)',
                                borderColor: '#3f37c9',
                                borderWidth: 2,
                                pointBackgroundColor: '#3f37c9',
                                pointRadius: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'LKR ' + (value >= 1000 ? (value/1000).toFixed(1) + 'K' : value);
                                    }
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': LKR ' + context.raw.toFixed(2);
                                    }
                                }
                            },
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            }
            
            // Expense breakdown chart
            const expenseBreakdownElement = document.getElementById('expenseBreakdown');
            if (expenseBreakdownElement && expenseLabels.length > 0) {
                removeFallback('expense-fallback');
                removeFallback('expense-legend-fallback');
                
                const expenseCtx = expenseBreakdownElement.getContext('2d');
                
                const expenseChart = new Chart(expenseCtx, {
                    type: 'doughnut',
                    data: {
                        labels: expenseLabels,
                        datasets: [{
                            data: expenseValues,
                            backgroundColor: expenseColors,
                            borderColor: expenseColors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.raw;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${context.label}: LKR ${value.toFixed(2)} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
                
                // Update expense legend dynamically
                const expenseLegendContainer = document.getElementById('expenseLegend');
                if (expenseLegendContainer) {
                    let legendHTML = '';
                    
                    const totalExpense = expenseValues.reduce((a, b) => a + b, 0);
                    
                    expenseLabels.forEach((type, i) => {
                        const value = expenseValues[i];
                        const color = expenseColors[i];
                        const percentage = ((value / totalExpense) * 100).toFixed(1);
                        
                        legendHTML += `
                            <div class="legend-item">
                                <div class="bullet" style="background-color: ${color}"></div>
                                <div class="label">${type}</div>
                                <div class="amount">LKR ${Number(value).toFixed(2)} (${percentage}%)</div>
                            </div>
                        `;
                    });
                    
                    expenseLegendContainer.innerHTML = legendHTML;
                }
            }
        }
    } catch (error) {
        console.error("Failed to initialize charts:", error);
    }
    
    // Try to initialize the gauge chart
    try {
        if (isLibraryLoaded('Gauge')) {
            const gaugeElement = document.getElementById('booking-gauge');
            if (gaugeElement) {
                removeFallback('gauge-fallback');
                
                // Fix the issue by explicitly setting the gauge's dimensions
                gaugeElement.style.width = '100%';
                gaugeElement.style.height = '200px'; 
                gaugeElement.width = gaugeElement.offsetWidth;
                gaugeElement.height = gaugeElement.offsetHeight;
                
                const opts = {
                    angle: 0.15, 
                    lineWidth: 0.44,
                    radiusScale: 1,
                    pointer: {
                        length: 0.6,
                        strokeWidth: 0.035,
                        color: '#000000'
                    },
                    limitMax: false,
                    limitMin: false,
                    colorStart: '#4cc9f0',
                    colorStop: '#4cc9f0', 
                    strokeColor: '#E0E0E0',
                    generateGradient: true,
                    highDpiSupport: true,
                    staticZones: [
                        {strokeStyle: "#f72585", min: 0, max: 30}, // Red
                        {strokeStyle: "#fb8500", min: 30, max: 60}, // Orange
                        {strokeStyle: "#4361ee", min: 60, max: 80}, // Blue
                        {strokeStyle: "#4cc9f0", min: 80, max: 100} // Light Blue
                    ],
                    staticLabels: {
                        font: "10px sans-serif",
                        labels: [0, 25, 50, 75, 100],
                        color: "#000000",
                        fractionDigits: 0
                    }
                };
                
                const gauge = new Gauge(gaugeElement).setOptions(opts);
                gauge.maxValue = 100;
                gauge.setMinValue(0);
                gauge.animationSpeed = 32;
                gauge.set(bookingRate); // Use real booking rate
                
                console.log("Gauge initialized with value:", bookingRate);
            }
        }
    } catch (error) {
        console.error("Failed to initialize gauge:", error);
    }
    
    // Chart period selector
    const periodSelector = document.getElementById('chartPeriod');
    if (periodSelector) {
        periodSelector.addEventListener('change', function() {
            alert('This would fetch data for the selected period: ' + this.value);
        });
    }
});
</script>

<?php require_once 'ownerFooter.view.php'; ?>