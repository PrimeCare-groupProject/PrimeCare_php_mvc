<?php require_once 'customerHeader.view.php'; ?>

<!-- Include Chart.js and Gauge.js libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #FFC107;
        --secondary: #FCA311;
        --accent: #4895ef;
        --success: #4cc9f0;
        --color1:#06D001;
        --warning: #f72585;
        --danger: #e5383b;
        --neutral: #e9ecef;
        --dark: #212529;
        --light: #f8f9fa;
        --system-yellow: #FFC107; 
    }
    
    body {
        background-color: #f0f2f5;
    }
    
    .modern-dashboard {
        padding: 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Futuristic summary cards with background images */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.75rem;
        margin-bottom: 2.5rem;
    }
    
    .summary-card {
        position: relative;
        border-radius: 16px;
        padding: 1.8rem;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        z-index: 1;
        min-height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    /* Background image styles */
    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-size: cover;
        background-position: center;
        z-index: -2;
        filter: saturate(1.1) contrast(1.05);
        transition: all 0.4s ease;
    }
    
    /* Glass effect overlay */
    .summary-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        z-index: -1;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s ease;
    }
    
    /* Hover effects */
    .summary-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .summary-card:hover::before {
        filter: saturate(1.2);
        transform: scale(1.05);
    }
    
    .summary-card:hover::after {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(1px);
        -webkit-backdrop-filter: blur(1px);
    }
    
    /* Individual card background images */
    .summary-card.current-rental::before {
        background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?ixlib=rb-4.0.3');
    }
    
    .summary-card.payment-history::before {
        background-image: url('https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?ixlib=rb-4.0.3');
    }
    
    .summary-card.service-requests::before {
        background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3');
    }
    
    .summary-card.upcoming-payment::before {
        background-image: url('https://images.unsplash.com/photo-1543286386-2e659306cd6c?ixlib=rb-4.0.3');
    }
    
    /* Modern icon containers */
    .summary-card .icon-container {
        width: 65px;
        height: 65px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 1rem;
        position: relative;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .summary-card:hover .icon-container {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Card content styles */
    .card-content {
        position: relative;
        z-index: 1;
    }
    
    .card-content h3 {
        margin: 0;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #555;
        font-weight: 600;
        position: relative;
        display: inline-block;
    }
    
    .card-content h3:after {
        content: '';
        position: absolute;
        width: 40%;
        height: 2px;
        bottom: -5px;
        left: 0;
        background: rgba(0,0,0,0.1);
        border-radius: 2px;
    }
    
    .card-content .value {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 1rem 0;
        letter-spacing: -0.5px;
    }
    
    .card-content .value.positive {
        color: var(--success);
    }
    
    .card-content .value.negative {
        color: var(--danger);
    }
    
    .card-footer {
        font-size: 0.85rem;
        color: #666;
        margin-top: auto;
        padding-top: 0.8rem;
        border-top: 1px dashed rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-footer a {
        text-decoration: none;
        color: var(--primary);
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .card-footer a:hover {
        color: var(--secondary);
    }
    
    /* Dashboard sections */
    .two-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 768px) {
        .two-columns {
            grid-template-columns: 1fr;
        }
    }
    
    .chart-container, .payment-section, .rental-history, .service-section {
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
    
    /* Tables */
    .payment-table, .rental-table, .service-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .payment-table th, .rental-table th, .service-table th {
        text-align: left;
        padding: 0.75rem;
        border-bottom: 2px solid #eee;
        font-size: 0.85rem;
        font-weight: 600;
        color: #757575;
    }
    
    .payment-table td, .rental-table td, .service-table td {
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
        background-color: rgba(251, 133, 0, 0.15);
        color: var(--warning);
    }
    
    .badge-danger {
        background-color: rgba(230, 57, 70, 0.15);
        color: var(--danger);
    }
    
    .badge-info {
        background-color: rgba(58, 134, 255, 0.15);
        color: var(--primary);
    }
    
    /* Chart area */
    .chart-area {
        height: 300px;
        position: relative;
    }
    
    /* Property card */
    .property-card {
        display: flex;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }
    
    .property-image {
        width: 140px;
        height: 140px;
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
    }
    
    .property-details {
        padding: 1.5rem;
        flex: 1;
    }
    
    .property-title {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
    }
    
    .property-address {
        color: #757575;
        margin: 0 0 1rem 0;
        font-size: 0.9rem;
    }
    
    .property-meta {
        display: flex;
        gap: 1.5rem;
        margin-top: 1rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #666;
    }
    
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
    
    .no-data {
        text-align: center;
        padding: 2rem;
        color: #757575;
    }
</style>

<div class="user_view-menu-bar">
    <h2>Customer Dashboard</h2>
</div>

<div class="modern-dashboard">
    <!-- Summary Cards for Customer -->
    <div class="summary-cards">
        <!-- Current Rental -->
        <div class="summary-card current-rental">
            <div class="icon-container" style="color: var(--primary);">
                <i class="fas fa-home"></i>
            </div>
            <div class="card-content">
                <h3>Current Rental</h3>
                <div class="value">
                    <?php 
                    // Get count of active rentals
                    $activeRentals = isset($activeBookings) && is_array($activeBookings) 
                        ? count($activeBookings)
                        : 0;
                    echo $activeRentals;
                    ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i>
                    <span>Active Property Rentals</span>
                </div>
            </div>
        </div>
        
        <!-- Total Expenses Card -->
        <div class="summary-card service-requests">
            <div class="icon-container" style="color: var(--warning);">
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="card-content">
                <h3>Service Expenses</h3>
                <div class="value">
                    <?php echo 'LKR ' . number_format($totalExpenses ?? 0, 2); ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-tools" style="color: var(--warning);"></i>
                    <span>Total maintenance costs</span>
                </div>
            </div>
        </div>
        
        <!-- Service Requests Card -->
        <div class="summary-card service-requests">
            <div class="icon-container" style="color: var(--warning);">
                <i class="fas fa-tools"></i>
            </div>
            <div class="card-content">
                <h3>Service Requests</h3>
                <div class="value">
                    <?php
                    // Count of service requests
                    $serviceRequestCount = isset($serviceRequests) && is_array($serviceRequests)
                        ? count($serviceRequests)
                        : 0;
                    echo $serviceRequestCount;
                    ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-wrench" style="color: var(--warning);"></i>
                    <a href="<?= ROOT ?>/dashboard/requestService">Request New Service</a>
                </div>
            </div>
        </div>
        
        <!-- Rental History Card -->
        <div class="summary-card current-rental">
            <div class="icon-container" style="color: var(--color1);">
                <i class="fas fa-history"></i>
            </div>
            <div class="card-content">
                <h3>Rental History</h3>
                <div class="value">
                    <?php
                    // Count of all bookings
                    $bookingCount = isset($bookings) && is_array($bookings)
                        ? count($bookings)
                        : 0;
                    echo $bookingCount;
                    ?>
                </div>
                <div class="card-footer">
                    <i class="fas fa-list" style="color: var(--color1);"></i>
                    <a href="<?= ROOT ?>/dashboard/occupiedProperties">View Rental History</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Current Rental Property -->
    <div class="property-card">
        <?php if (isset($currentProperty) && $currentProperty): ?>
            <div class="property-image" style="background-image: url('<?= ROOT ?>/assets/images/uploads/property_images/<?= $currentProperty->image_url ?? 'default.jpg' ?>');"></div>
            <div class="property-details">
                <h3 class="property-title"><?= htmlspecialchars($currentProperty->name ?? 'Property Name') ?></h3>
                <p class="property-address"><?= htmlspecialchars($currentProperty->address ?? 'Property Address') ?></p>
                
                <div class="property-meta">
                    <div class="meta-item">
                        <i class="fas fa-bed"></i>
                        <span><?= $currentProperty->bedrooms ?? '?' ?> Bedrooms</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-bath"></i>
                        <span><?= $currentProperty->bathrooms ?? '?' ?> Bathrooms</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-ruler-combined"></i>
                        <span><?= $currentProperty->area ?? '?' ?> sq ft</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar-day"></i>
                        <span>Since: <?= isset($currentBooking) && isset($currentBooking->start_date) 
                            ? date('M d, Y', strtotime($currentBooking->start_date)) 
                            : 'Unknown' ?>
                        </span>
                    </div>
                </div>
                
                <div style="margin-top: 1rem;">
                    <a href="<?= ROOT ?>/dashboard/property/<?= $currentProperty->property_id ?? 0 ?>" class="action-button">View Details</a>
                    <a href="<?= ROOT ?>/dashboard/requestService" class="action-button">Request Service</a>
                </div>
            </div>
        <?php else: ?>
            <div class="property-details" style="text-align: center; width: 100%;">
                <i class="fas fa-home" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                <h3>No Current Rental</h3>
                <p>You don't have any active rentals at the moment.</p>
                <a href="<?= ROOT ?>/properties" class="action-button">Browse Properties</a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Two Columns Section -->
    <div class="two-columns">
        <!-- Expense History Section -->
        <div class="chart-container">
            <div class="card-header">
                <h3>Expense History</h3>
                <div class="period-selector">
                    <select id="chartPeriod">
                        <option value="6months">Last 6 Months</option>
                        <option value="1year">Last Year</option>
                    </select>
                </div>
            </div>
            <div class="chart-area">
                <canvas id="expenseHistoryChart"></canvas>
                
                <!-- Fallback for when chart doesn't load -->
                <div id="chart-fallback" class="fallback-content">
                    <table style="width: 80%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="padding: 8px; text-align: left; border-bottom: 2px solid #eee;">Month</th>
                                <th style="padding: 8px; text-align: right; border-bottom: 2px solid #eee;">Expenses</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Real expense data
                            $months = array_keys($monthlyExpenses ?? []);
                            if (empty($months)) {
                                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                            }
                            
                            $expenseAmounts = isset($monthlyExpensesArray) && is_array($monthlyExpensesArray) 
                                ? $monthlyExpensesArray 
                                : array_fill(0, 6, 0);
                            
                            foreach($months as $i => $month): 
                                $amount = $expenseAmounts[$i] ?? 0;
                            ?>
                            <tr>
                                <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= $month ?></td>
                                <td style="padding: 8px; text-align: right; border-bottom: 1px solid #eee; color: #e63946;">
                                    LKR <?= number_format($amount, 2) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Service Requests Section -->
        <div class="service-section">
            <div class="card-header">
                <h3>Service Requests</h3>
                <a href="<?= ROOT ?>/dashboard/repairListing" class="action-button">View All</a>
            </div>
            
            <?php if (isset($serviceRequests) && is_array($serviceRequests) && count($serviceRequests) > 0): ?>
                <table class="service-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_slice($serviceRequests, 0, 5) as $request): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($request->date ?? 'now')) ?></td>
                                <td><?= htmlspecialchars($request->service_type ?? 'General') ?></td>
                                <td><?= htmlspecialchars(substr($request->service_description ?? 'No description', 0, 30)) . (strlen($request->service_description ?? '') > 30 ? '...' : '') ?></td>
                                <td>
                                    <?php
                                        $statusClass = 'badge-info';
                                        $status = $request->status ?? 'Pending';
                                        
                                        if(strtolower($status) === 'completed' || strtolower($status) === 'done') $statusClass = 'badge-success';
                                        else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                                        else if(strtolower($status) === 'rejected' || strtolower($status) === 'cancelled') $statusClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-tools" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p>No service requests found.</p>
                    <a href="<?= ROOT ?>/dashboard/requestService" class="action-button">Request Service</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Two Columns Section -->
    <div class="two-columns">
        <!-- Service Requests Section -->
        <div class="service-section">
            <div class="card-header">
                <h3>Service Requests</h3>
                <a href="<?= ROOT ?>/dashboard/repairListing" class="action-button">View All</a>
            </div>
            
            <?php if (isset($serviceRequests) && is_array($serviceRequests) && count($serviceRequests) > 0): ?>
                <table class="service-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_slice($serviceRequests, 0, 5) as $request): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($request->date ?? 'now')) ?></td>
                                <td><?= htmlspecialchars($request->service_type ?? 'General') ?></td>
                                <td><?= htmlspecialchars(substr($request->service_description ?? 'No description', 0, 30)) . (strlen($request->service_description ?? '') > 30 ? '...' : '') ?></td>
                                <td>
                                    <?php
                                        $statusClass = 'badge-info';
                                        $status = $request->status ?? 'Pending';
                                        
                                        if(strtolower($status) === 'completed' || strtolower($status) === 'done') $statusClass = 'badge-success';
                                        else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                                        else if(strtolower($status) === 'rejected' || strtolower($status) === 'cancelled') $statusClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-tools" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p>No service requests found.</p>
                    <a href="<?= ROOT ?>/dashboard/requestService" class="action-button">Request Service</a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Rental History Section -->
        <div class="rental-history">
            <div class="card-header">
                <h3>Rental History</h3>
                <a href="<?= ROOT ?>/dashboard/occupiedProperties" class="action-button">View All</a>
            </div>
            
            <?php if (isset($rentalHistory) && is_array($rentalHistory) && count($rentalHistory) > 0): ?>
                <table class="rental-table">
                    <thead>
                        <tr>
                            <th>Property</th>
                            <th>Period</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(array_slice($rentalHistory, 0, 5) as $rental): ?>
                            <tr>
                                <td><?= htmlspecialchars($rental->property_name ?? 'Unknown Property') ?></td>
                                <td>
                                    <?= 
                                    date('M d, Y', strtotime($rental->start_date ?? 'now')) . ' - ' . 
                                    (isset($rental->end_date) ? date('M d, Y', strtotime($rental->end_date)) : 'Present')
                                    ?>
                                </td>
                                <td>LKR <?= number_format($rental->price ?? 0, 2) ?></td>
                                <td>
                                    <?php
                                        $statusClass = 'badge-info';
                                        $status = $rental->status ?? 'Unknown';
                                        
                                        if(strtolower($status) === 'active' || strtolower($status) === 'accepted') $statusClass = 'badge-success';
                                        else if(strtolower($status) === 'pending') $statusClass = 'badge-warning';
                                        else if(strtolower($status) === 'completed' || strtolower($status) === 'past') $statusClass = 'badge-info';
                                        else if(strtolower($status) === 'cancelled' || strtolower($status) === 'rejected') $statusClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($status) ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-history" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;"></i>
                    <p>No rental history found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Update chart initialization script -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Check if Chart.js is loaded
    if (typeof Chart !== 'undefined') {
        const ctx = document.getElementById('expenseHistoryChart').getContext('2d');
        const fallback = document.getElementById('chart-fallback');
        
        if (fallback) fallback.style.display = 'none';
        
        // Get expense data from PHP
        const months = <?= json_encode(array_keys($monthlyExpenses ?? [])) ?> || ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const expenseData = <?= json_encode($monthlyExpensesArray ?? []) ?> || [0, 0, 0, 0, 0, 0];
        
        // Create expense chart
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Monthly Expenses',
                    data: expenseData,
                    backgroundColor: 'rgba(230, 57, 70, 0.6)',
                    borderColor: '#e63946',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'LKR ' + value.toLocaleString();
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Expense: LKR ' + context.raw.toLocaleString();
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }
});
</script>

<?php require_once 'customerFooter.view.php'; ?>