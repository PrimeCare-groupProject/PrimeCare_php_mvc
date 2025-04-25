<?php require_once 'ownerHeader.view.php'; ?>

<?php
// Set default values for undefined variables
$totalIncome = $totalIncome ?? 0;
$totalExpenses = $totalExpenses ?? 0;
$profit = $profit ?? 0;
$occupancyRate = $occupancyRate ?? 0;
$activeBookings = $activeBookings ?? 0;
$bookings = $bookings ?? [];
$serviceLogs = $serviceLogs ?? [];
$monthlyData = $monthlyData ?? [];
$user = $user ?? null;

// Prevent divide by zero errors
$profitMargin = ($totalIncome > 0) ? ($profit/$totalIncome)*100 : 0;
?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Financial Overview</h2>
                <p><span>All Properties</span></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<div class="container">
    <!-- Owner Profile and Tenants section -->
    <div class="L_idebar">
        <div class="sidebar-flex-container">
            <!-- Left sidebar with owner profile -->
            <div class="sidebar-left">
                <div class="owner-profile-card">
                    <div class="profile-image-container">
                        <?php
                        $defaultUserImage = ROOT . '/assets/images/user.png';
                        $ownerImageUrl = null;
                        
                        if (isset($user->image_url) && !empty($user->image_url)) {
                            $imagePath = ROOTPATH . 'public/assets/images/uploads/profile_pictures/' . $user->image_url;
                            if (file_exists($imagePath)) {
                                $ownerImageUrl = ROOT . '/assets/images/uploads/profile_pictures/' . $user->image_url;
                            }
                        }
                        ?>
                        <img src="<?= $ownerImageUrl ?: $defaultUserImage ?>" alt="Profile Image">
                    </div>
                    <div class="profile-details">
                        <h2 class="profile-name"><?= $user->fname ?? 'User' ?> <?= $user->lname ?? '' ?></h2>
                        <span class="profile-role">Property Owner</span>
                    </div>
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-label">Properties</div>
                            <div class="stat-value"><?= count($properties) ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Tenants</div>
                            <div class="stat-value"><?= $activeBookings ?></div>
                        </div>
                        <!-- <div class="stat-item">
                            <div class="stat-label">Units</div>
                            <div class="stat-value"><?= $totalUnits ?></div>
                        </div> -->
                    </div>
                </div>
            </div>
            
            <!-- Right sidebar with tenants list -->
            <div class="sidebar-right">
                <!-- <div class="savings-card"> -->
                    <!-- <div class="card-header">
                        <span>Tenants</span>
                    </div> -->
                    <!-- Tenants list content here -->
                    <div class="tenants-list">
                        <h2>Current Tenants</h2>
                        <div class="tenants-list-content">
                        <?php if (empty($bookings)): ?>
                            <div class="empty-tenants">
                                <p>No active tenants found</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking):
                                // Get tenant information using person_id from booking_orders
                                $tenantId = $booking->person_id ?? null;
                                $tenant = null;
                                $tenantName = 'Unknown Tenant';
                                
                                // Check if we have tenant details for this person_id
                                if ($tenantId && isset($tenantDetails[$tenantId])) {
                                    $tenant = $tenantDetails[$tenantId];
                                    $tenantName = trim(($tenant->fname ?? '') . ' ' . ($tenant->lname ?? ''));
                                    if (empty($tenantName)) {
                                        $tenantName = 'Tenant #' . $tenantId;
                                    }
                                }
                                
                                // Get tenant profile image
                                $defaultUserImage = ROOT . '/assets/images/user.png';
                                $tenantImage = $defaultUserImage;
                                
                                if ($tenant && !empty($tenant->image_url)) {
                                    $imagePath = ROOTPATH . 'public/assets/images/uploads/profile_pictures/' . $tenant->image_url;
                                    if (file_exists($imagePath)) {
                                        $tenantImage = ROOT . '/assets/images/uploads/profile_pictures/' . $tenant->image_url;
                                    }
                                }
                            ?>
                            <div class="tenant-item" data-status="<?= $booking->booking_status ?? 'Active' ?>">
                                <div class="tenant-avatar">
                                    <img src="<?= $tenantImage ?>" alt="<?= htmlspecialchars($tenantName) ?>">
                                </div>
                                <div class="tenant-info">
                                    <h4><?= htmlspecialchars($tenantName) ?></h4>
                                    <span><i class="fas fa-home fa-sm"></i> Property ID: <?= $booking->property_id ?? 'N/A' ?></span>
                                    <span><i class="fas fa-calendar fa-sm"></i> Since: <?= date('M Y', strtotime($booking->start_date ?? date('Y-m-d'))) ?></span>
                                    <span><i class="fas fa-wallet fa-sm"></i> Rent: Rs. <?= number_format(
                                        isset($booking->total_amount) ? $booking->total_amount : 
                                        (isset($booking->rental_price) && isset($booking->duration) ? 
                                        $booking->rental_price * $booking->duration : 0), 
                                        2) ?>
                                    </span>
                                    <span><?= $booking->booking_status ?? 'Active' ?></span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <!-- Full-width Financial Summary section -->
    <div class="financial-summary-section">
        <div class="financial-summary-card">
            <div class="card-header">
                <h2>Financial Summary</h2>
            </div>
            <div class="summary-details">
                <div class="summary-item">
                    <div class="label">Total Income:</div>
                    <div class="value">Rs. <?= number_format($totalIncome, 2) ?></div>
                </div>
                <div class="summary-item">
                    <div class="label">Total Expenses:</div>
                    <div class="value">Rs. <?= number_format($totalExpenses, 2) ?></div>
                </div>
                <div class="summary-item">
                    <div class="label">Net Profit:</div>
                    <div class="value">Rs. <?= number_format($profit, 2) ?></div>
                </div>
                <div class="summary-item">
                    <div class="label">Profit Margin:</div>
                    <div class="value"><?= number_format(($totalIncome > 0 ? ($profit/$totalIncome)*100 : 0), 1) ?>%</div>
                </div>
                <div class="summary-item">
                    <div class="label">Occupancy Rate:</div>
                    <div class="value"><?= number_format($occupancyRate, 1) ?>%</div>
                </div>
                <div class="summary-item">
                    <div class="label">Active Bookings:</div>
                    <div class="value"><?= $activeBookings ?> / <?= $totalUnits ?> Units</div>
                </div>
            </div>
        </div>
    </div>
    
    <main class="main-content">
        <div class="property-income-card">
            <h2>Property Income</h2>
            <div class="property-income-table">
                <table>
                    <thead>
                        <tr>
                            <th>Period</th>
                            <th>Property</th>
                            <th>Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $propertyIncomes = [];
                        // Calculate income for each property
                        if (!empty($properties)) {
                            foreach ($properties as $property) {
                                $income = 0;
                                $activeBkgs = 0;
                                if (!empty($bookings)) {
                                    foreach ($bookings as $booking) {
                                        if ($booking->property_id == $property->property_id) {
                                            // Calculate booking amount from booking_orders correctly
                                            if (isset($booking->total_amount)) {
                                                $income += $booking->total_amount;
                                            } else if (isset($booking->rental_price) && isset($booking->duration)) {
                                                $income += $booking->rental_price * $booking->duration;
                                            } else {
                                                $income += 0;
                                            }
                                            
                                            // Update status check for active bookings from booking_orders
                                            $bookingStatus = strtolower($booking->booking_status ?? '');
                                            $paymentStatus = strtolower($booking->payment_status ?? '');
                                            
                                            if (($bookingStatus === 'confirmed' || $bookingStatus === 'completed') && 
                                                $paymentStatus === 'paid') {
                                                $activeBkgs++;
                                            }
                                        }
                                    }
                                }
                                $propertyIncomes[] = [
                                    'name' => $property->name ?? ('Property #' . $property->property_id),
                                    'income' => $income,
                                    'active_bookings' => $activeBkgs,
                                    'units' => $property->units ?? 0
                                ];
                            }
                        }

                        if (!empty($propertyIncomes)):
                            foreach ($propertyIncomes as $propertyIncome):
                        ?>
                        <tr>
                            <td><?= date('F Y') ?></td>
                            <td title="<?= htmlspecialchars($propertyIncome['name']) ?>">
                                <?= strlen($propertyIncome['name']) > 20 
                                    ? htmlspecialchars(substr($propertyIncome['name'], 0, 20) . '...') 
                                    : htmlspecialchars($propertyIncome['name']) ?>
                            </td>
                            <td>Rs. <?= number_format($propertyIncome['income'], 2) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="3">No property data</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- <div class="stats-cards">
            <div class="stat-card earnings">
                <div class="stat-value">Rs. <?= number_format($totalIncome, 2) ?></div>
                <div class="stat-label">Total Income</div>
                <div class="stat-change <?= $totalIncome > $totalExpenses ? 'positive' : 'negative' ?>">
                    <?= number_format(($totalIncome > 0 ? ($profit/$totalIncome)*100 : 0), 1) ?>% profit margin
                </div>
            </div>

            <div class="stat-card spendings">
                <div class="stat-value">Rs. <?= number_format($totalExpenses, 2) ?></div>
                <div class="stat-label">Total Expenses</div>
                <div class="stat-change">
                    <?= $activeBookings ?> active bookings
                </div>
            </div>

            <div class="stat-card profit">
                <div class="stat-value">Rs. <?= number_format($profit, 2) ?></div>
                <div class="stat-label">Net Profit</div>
                <div class="stat-change <?= $occupancyRate > 50 ? 'positive' : 'negative' ?>">
                    <?= number_format($occupancyRate, 1) ?>% occupancy
                </div>
            </div>
        </div> -->

        <div class="statistics-card">
            <div class="card-header">
                <h2>Statistics</h2>
                <div class="chart-controls">
                    <div class="legend">
                        <span class="legend-item">
                            <span class="dot earnings"></span>
                            Earnings
                        </span>
                        <span class="legend-item">
                            <span class="dot spendings"></span>
                            Spendings
                        </span>
                        <span class="legend-item">
                            <span class="dot profit"></span>
                            Profit
                        </span>
                    </div>
                    <select class="period-selector" id="chartPeriod">
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>
            </div>
            <canvas id="statisticsChart"></canvas>
        </div>

        <!-- Detailed Analytics Section -->
        <div class="detailed-analytics">
            <div class="analytics-card">
                <div class="card-header">
                    <h3>Income Sources</h3>
                    <div class="filter-controls">
                        <select id="incomeFilter">
                            <option value="all">All Time</option>
                            <option value="6">Last 6 Months</option>
                            <option value="3">Last 3 Months</option>
                            <option value="1">This Month</option>
                        </select>
                    </div>
                </div>
                <div class="analytics-content">
                    <div class="pie-chart-container">
                        <canvas id="incomeSourcesChart"></canvas>
                    </div>
                    <div class="analytics-details">
                        <div class="detail-row">
                            <span class="label">Rent Income:</span>
                            <span class="value">Rs. <?= number_format($totalIncome * 0.9, 2) ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Other Income:</span>
                            <span class="value">Rs. <?= number_format($totalIncome * 0.1, 2) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="analytics-card">
                <div class="card-header">
                    <h3>Expense Breakdown</h3>
                    <div class="filter-controls">
                        <select id="expenseFilter">
                            <option value="all">All Time</option>
                            <option value="6">Last 6 Months</option>
                            <option value="3">Last 3 Months</option>
                            <option value="1">This Month</option>
                        </select>
                    </div>
                </div>
                <div class="analytics-content">
                    <div class="pie-chart-container">
                        <canvas id="expenseBreakdownChart"></canvas>
                    </div>
                    <div class="analytics-details">
                        <?php
                        $expenseTypes = [];
                        foreach($serviceLogs as $log) {
                            $type = $log->service_type;
                            $cost = $log->total_cost; 
                            if(!isset($expenseTypes[$type])) {
                                $expenseTypes[$type] = 0;
                            }
                            $expenseTypes[$type] += $cost;
                        }
                        ?>

                        <?php foreach($expenseTypes as $type => $amount): ?>
                        <div class="detail-row">
                            <span class="label"><?= ucfirst($type) ?>:</span>
                            <span class="value">Rs. <?= number_format($amount, 2) ?></span>
                        </div>
                        <?php endforeach; ?>

                        <?php if(empty($expenseTypes)): ?>
                        <div class="detail-row">
                            <span class="label">No expense data available</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="transactions-card">
            <div class="card-header">
                    <h3>Recent Transactions</h3>
                </div>
            <div class="transactions-table">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $recentTransactions = [];
                        
                        // Add service logs as expenses
                        if (is_array($serviceLogs) && !empty($serviceLogs)) {
                            foreach($serviceLogs as $log) {
                                // Calculate total cost properly using cost_per_hour * total_hours if total_cost is missing
                                $totalCost = isset($log->total_cost) ? $log->total_cost : 
                                            (isset($log->cost_per_hour) && isset($log->total_hours) ? 
                                            $log->cost_per_hour * $log->total_hours : 0);
                                            
                                $recentTransactions[] = [
                                    'date' => $log->date ?? date('Y-m-d'),
                                    'description' => $log->service_description ?? $log->service_type ?? 'Service Request',
                                    'category' => 'Expense',
                                    'amount' => -$totalCost
                                ];
                            }
                        }
                        
                        // Add bookings as income from booking_orders
                        if (is_array($bookings) && !empty($bookings)) {
                            foreach($bookings as $booking) {
                                // Calculate booking amount from booking_orders
                                if (isset($booking->total_amount)) {
                                    $bookingAmount = $booking->total_amount;
                                } else if (isset($booking->rental_price) && isset($booking->duration)) {
                                    $bookingAmount = $booking->rental_price * $booking->duration;
                                } else {
                                    $bookingAmount = 0;
                                }
                                
                                $recentTransactions[] = [
                                    'date' => $booking->start_date ?? date('Y-m-d'),
                                    'description' => 'Rent payment for ' . date('M Y', strtotime($booking->start_date ?? date('Y-m-d'))),
                                    'category' => 'Income',
                                    'amount' => $bookingAmount
                                ];
                            }
                        }
                        
                        // Sort by date (newest first)
                        usort($recentTransactions, function($a, $b) {
                            return strtotime($b['date'] ?? date('Y-m-d')) - strtotime($a['date'] ?? date('Y-m-d'));
                        });
                        
                        // Show only the 5 most recent transactions
                        $recentTransactions = array_slice($recentTransactions, 0, 5);
                        
                        if (empty($recentTransactions)): ?>
                            <tr>
                                <td colspan="4" class="text-center">No recent transactions found</td>
                            </tr>
                        <?php else:
                            foreach($recentTransactions as $transaction): ?>
                            <tr>
                                <td><?= date('M d, Y', strtotime($transaction['date'] ?? date('Y-m-d'))) ?></td>
                                <td><?= htmlspecialchars($transaction['description']) ?></td>
                                <td><span class="category-badge <?= strtolower($transaction['category']) ?>"><?= $transaction['category'] ?></span></td>
                                <td class="<?= $transaction['amount'] >= 0 ? 'positive' : 'negative' ?>">
                                    LKR <?= number_format(abs($transaction['amount']), 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; 
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prepare data from PHP
        const monthlyData = <?= json_encode(array_values($monthlyData)) ?>;
        const monthLabels = <?= json_encode(array_keys($monthlyData)) ?>;
        
        // Prepare arrays for Chart.js
        const monthlyIncomeData = monthlyData.map(item => item.income);
        const monthlyExpenseData = monthlyData.map(item => item.expense);
        const monthlyProfitData = monthlyData.map(item => item.profit);
        const occupancyRateData = monthlyData.map(item => item.occupancy_rate);

        // Initialize Main Chart
        const ctx = document.getElementById('statisticsChart').getContext('2d');
        ctx.canvas.style.height = '400px';
        ctx.canvas.style.width = '100%';

        const statisticsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [
                    {
                        label: 'Earnings',
                        data: monthlyIncomeData,
                        backgroundColor: '#FCA311',
                        borderRadius: 8,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Spendings',
                        data: monthlyExpenseData,
                        backgroundColor: '#f9c570d1',
                        borderRadius: 8,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Profit',
                        data: monthlyProfitData,
                        backgroundColor: '#4CAF50',
                        borderRadius: 8,
                        barPercentage: 0.6,
                        hidden: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#EEF2FE',
                            drawBorder: false,
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Rs.' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rs.' + context.raw.toFixed(2);
                            }
                        }
                    }
                }
            }
        });

        
        // Income Sources Pie Chart showing Property-wise Income
        const incomeCtx = document.getElementById('incomeSourcesChart').getContext('2d');
        
        // Process property-wise income data
        <?php
        // Calculate property-wise income for different time periods
        $propertyWiseIncome = [];
        $propertyWiseIncomeLast6 = [];
        $propertyWiseIncomeLast3 = [];
        $propertyWiseIncomeCurrentMonth = [];
        
        // Current date references for filtering
        $sixMonthsAgo = strtotime('-6 months');
        $threeMonthsAgo = strtotime('-3 months');
        $currentMonthStart = strtotime(date('Y-m-01'));
        
        if (!empty($properties) && !empty($bookings)) {
            foreach ($properties as $property) {
                $propertyName = $property->name ?? ('Property #' . $property->property_id);
                $propertyWiseIncome[$propertyName] = 0;
                $propertyWiseIncomeLast6[$propertyName] = 0;
                $propertyWiseIncomeLast3[$propertyName] = 0;
                $propertyWiseIncomeCurrentMonth[$propertyName] = 0;
                
                foreach ($bookings as $booking) {
                    if ($booking->property_id == $property->property_id) {
                        // Calculate booking amount
                        if (isset($booking->total_amount)) {
                            $amount = $booking->total_amount;
                        } else if (isset($booking->rental_price) && isset($booking->duration)) {
                            $amount = $booking->rental_price * $booking->duration;
                        } else {
                            $amount = 0;
                        }
                        
                        // Only count active bookings
                        $bookingStatus = strtolower($booking->booking_status ?? '');
                        $paymentStatus = strtolower($booking->payment_status ?? '');
                        if (($bookingStatus === 'confirmed' || $bookingStatus === 'completed') && 
                            $paymentStatus === 'paid') {
                            
                            // Add to all-time income
                            $propertyWiseIncome[$propertyName] += $amount;
                            
                            // Check and add to period-specific income
                            if (isset($booking->start_date)) {
                                $bookingDate = strtotime($booking->start_date);
                                
                                if ($bookingDate >= $sixMonthsAgo) {
                                    $propertyWiseIncomeLast6[$propertyName] += $amount;
                                }
                                
                                if ($bookingDate >= $threeMonthsAgo) {
                                    $propertyWiseIncomeLast3[$propertyName] += $amount;
                                }
                                
                                if ($bookingDate >= $currentMonthStart) {
                                    $propertyWiseIncomeCurrentMonth[$propertyName] += $amount;
                                }
                            }
                        }
                    }
                }
            }
        }
        ?>
        
        // Store property-wise income data for different periods
        const incomeSourcesData = {
            'all': <?= json_encode($propertyWiseIncome) ?>,
            '6': <?= json_encode($propertyWiseIncomeLast6) ?>,
            '3': <?= json_encode($propertyWiseIncomeLast3) ?>,
            '1': <?= json_encode($propertyWiseIncomeCurrentMonth) ?>
        };
        
        // Color palette for property segments
        const propertyColors = [
            '#4CAF50', '#2196F3', '#9C27B0', '#FF9800', '#795548', 
            '#009688', '#673AB7', '#FFC107', '#FF5722', '#607D8B'
        ];
        
        const propertyHoverColors = [
            '#388E3C', '#1976D2', '#7B1FA2', '#F57C00', '#5D4037', 
            '#00796B', '#512DA8', '#FFB300', '#E64A19', '#455A64'
        ];
        
        // Format data for the chart
        function formatPropertyIncomeData(data) {
            // If no data, show a message
            if (Object.keys(data).length === 0) {
                return {
                    labels: ['No Property Data'],
                    values: [100],
                    colors: ['#e0e0e0'],
                    hoverColors: ['#d0d0d0']
                };
            }
            
            // Get property names and their income values
            const labels = Object.keys(data);
            const values = Object.values(data);
            
            // Get colors for each property (cycling through the array if needed)
            const colors = labels.map((_, index) => propertyColors[index % propertyColors.length]);
            const hoverColors = labels.map((_, index) => propertyHoverColors[index % propertyHoverColors.length]);
            
            return { labels, values, colors, hoverColors };
        }
        
        // Get initial data
        const defaultPropertyData = formatPropertyIncomeData(incomeSourcesData.all);
        
        // Initialize income chart with property-wise data
        const incomePieChart = new Chart(incomeCtx, {
            type: 'pie',
            data: {
                labels: defaultPropertyData.labels,
                datasets: [{
                    data: defaultPropertyData.values,
                    backgroundColor: defaultPropertyData.colors,
                    hoverBackgroundColor: defaultPropertyData.hoverColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        align: 'start',
                        labels: { 
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return label + ': Rs. ' + value.toFixed(2) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        
        // Update the analytics details section to show property-wise breakdown
        function updatePropertyIncomeDetails(data) {
            const detailsContainer = document.querySelector('.analytics-card:nth-child(1) .analytics-details');
            detailsContainer.innerHTML = '';
            
            if (Object.keys(data).length > 0) {
                Object.entries(data).forEach(([property, amount]) => {
                    detailsContainer.innerHTML += `
                        <div class="detail-row">
                            <span class="label">${property}:</span>
                            <span class="value">Rs. ${parseFloat(amount).toFixed(2)}</span>
                        </div>
                    `;
                });
            } else {
                detailsContainer.innerHTML = `
                    <div class="detail-row">
                        <span class="label">No income data available</span>
                    </div>
                `;
            }
        }
        
        // Initialize with default data
        updatePropertyIncomeDetails(incomeSourcesData.all);
        
        // Event handler for income filter
        document.querySelector('#incomeFilter').addEventListener('change', function(e) {
            const period = e.target.value;
            const selectedData = incomeSourcesData[period];
            const formattedData = formatPropertyIncomeData(selectedData);
            
            // Update chart data
            incomePieChart.data.labels = formattedData.labels;
            incomePieChart.data.datasets[0].data = formattedData.values;
            incomePieChart.data.datasets[0].backgroundColor = formattedData.colors;
            incomePieChart.data.datasets[0].hoverBackgroundColor = formattedData.hoverColors;
            
            // Update details section
            updatePropertyIncomeDetails(selectedData);
            
            incomePieChart.update();
        });
        
        // Initialize Expense Breakdown Pie Chart
        const expenseCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
        
        // Process expense data from PHP
        const expenseTypes = <?= json_encode($expenseTypes ?? []) ?>;
        const expenseLabels = Object.keys(expenseTypes).map(type => type.charAt(0).toUpperCase() + type.slice(1));
        const expenseValues = Object.values(expenseTypes);
        
        // Use default data if no real data available
        const chartLabels = expenseLabels.length ? expenseLabels : ['Maintenance', 'Repairs', 'Utilities', 'Other'];
        const chartData = expenseValues.length ? expenseValues : [40, 30, 20, 10];
        
        new Chart(expenseCtx, {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: ['#FF5722', '#9C27B0', '#2196F3', '#607D8B'],
                    hoverBackgroundColor: ['#E64A19', '#7B1FA2', '#1976D2', '#455A64'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        align: 'start',
                        labels: {
                            boxWidth: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return label + ': ' + percentage + '%';
                            }
                        }
                    }
                }
            }
        });

        // Period selector change handler
        document.querySelector('#chartPeriod').addEventListener('change', function(e) {
            const period = e.target.value;
            const profitDataset = statisticsChart.data.datasets[2];
            
            if (period === 'yearly') {
                // Show profit, hide expenses
                profitDataset.hidden = false;
                statisticsChart.data.datasets[1].hidden = true;
            } else {
                // Show expenses, hide profit
                profitDataset.hidden = true;
                statisticsChart.data.datasets[1].hidden = false;
            }
            
            statisticsChart.update();
        });

        // Filter handlers for analytics charts
        document.querySelector('#incomeFilter').addEventListener('change', function(e) {
            console.log('Income filter changed to:', e.target.value);
            // In a real application, this would filter the data based on the selected time period
        });

        document.querySelector('#expenseFilter').addEventListener('change', function(e) {
            console.log('Expense filter changed to:', e.target.value);
            // In a real application, this would filter the data based on the selected time period
        });
    });

document.addEventListener('DOMContentLoaded', function() {
    
    // Income Sources Pie Chart showing Property-wise Income
    const incomeCtx = document.getElementById('incomeSourcesChart').getContext('2d');
    
    // Process property-wise income data
    <?php
    // Calculate property-wise income for different time periods
    $propertyWiseIncome = [];
    $propertyWiseIncomeLast6 = [];
    $propertyWiseIncomeLast3 = [];
    $propertyWiseIncomeCurrentMonth = [];
    
    // Current date references for filtering
    $sixMonthsAgo = strtotime('-6 months');
    $threeMonthsAgo = strtotime('-3 months');
    $currentMonthStart = strtotime(date('Y-m-01'));
    
    if (!empty($properties) && !empty($bookings)) {
        foreach ($properties as $property) {
            $propertyName = $property->name ?? ('Property #' . $property->property_id);
            $propertyWiseIncome[$propertyName] = 0;
            $propertyWiseIncomeLast6[$propertyName] = 0;
            $propertyWiseIncomeLast3[$propertyName] = 0;
            $propertyWiseIncomeCurrentMonth[$propertyName] = 0;
            
            foreach ($bookings as $booking) {
                if ($booking->property_id == $property->property_id) {
                    // Calculate booking amount
                    if (isset($booking->total_amount)) {
                        $amount = $booking->total_amount;
                    } else if (isset($booking->rental_price) && isset($booking->duration)) {
                        $amount = $booking->rental_price * $booking->duration;
                    } else {
                        $amount = 0;
                    }
                    
                    // Only count active bookings
                    $bookingStatus = strtolower($booking->booking_status ?? '');
                    $paymentStatus = strtolower($booking->payment_status ?? '');
                    if (($bookingStatus === 'confirmed' || $bookingStatus === 'completed') && 
                        $paymentStatus === 'paid') {
                        
                        // Add to all-time income
                        $propertyWiseIncome[$propertyName] += $amount;
                        
                        // Check and add to period-specific income
                        if (isset($booking->start_date)) {
                            $bookingDate = strtotime($booking->start_date);
                            
                            if ($bookingDate >= $sixMonthsAgo) {
                                $propertyWiseIncomeLast6[$propertyName] += $amount;
                            }
                            
                            if ($bookingDate >= $threeMonthsAgo) {
                                $propertyWiseIncomeLast3[$propertyName] += $amount;
                            }
                            
                            if ($bookingDate >= $currentMonthStart) {
                                $propertyWiseIncomeCurrentMonth[$propertyName] += $amount;
                            }
                        }
                    }
                }
            }
        }
    }
    ?>
    
    // Store property-wise income data for different periods
    const incomeSourcesData = {
        'all': <?= json_encode($propertyWiseIncome) ?>,
        '6': <?= json_encode($propertyWiseIncomeLast6) ?>,
        '3': <?= json_encode($propertyWiseIncomeLast3) ?>,
        '1': <?= json_encode($propertyWiseIncomeCurrentMonth) ?>
    };
    
    // Color palette for property segments
    const propertyColors = [
        '#4CAF50', '#2196F3', '#9C27B0', '#FF9800', '#795548', 
        '#009688', '#673AB7', '#FFC107', '#FF5722', '#607D8B'
    ];
    
    const propertyHoverColors = [
        '#388E3C', '#1976D2', '#7B1FA2', '#F57C00', '#5D4037', 
        '#00796B', '#512DA8', '#FFB300', '#E64A19', '#455A64'
    ];
    
    // Format data for the chart
    function formatPropertyIncomeData(data) {
        // If no data, show a message
        if (Object.keys(data).length === 0) {
            return {
                labels: ['No Property Data'],
                values: [100],
                colors: ['#e0e0e0'],
                hoverColors: ['#d0d0d0']
            };
        }
        
        // Get property names and their income values
        const labels = Object.keys(data);
        const values = Object.values(data);
        
        // Get colors for each property (cycling through the array if needed)
        const colors = labels.map((_, index) => propertyColors[index % propertyColors.length]);
        const hoverColors = labels.map((_, index) => propertyHoverColors[index % propertyHoverColors.length]);
        
        return { labels, values, colors, hoverColors };
    }
    
    // Get initial data
    const defaultPropertyData = formatPropertyIncomeData(incomeSourcesData.all);
    
    // Initialize income chart with property-wise data
    const incomePieChart = new Chart(incomeCtx, {
        type: 'pie',
        data: {
            labels: defaultPropertyData.labels,
            datasets: [{
                data: defaultPropertyData.values,
                backgroundColor: defaultPropertyData.colors,
                hoverBackgroundColor: defaultPropertyData.hoverColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    align: 'start',
                    labels: { 
                        boxWidth: 12,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw;
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return label + ': Rs. ' + value.toFixed(2) + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    
    // Update the analytics details section to show property-wise breakdown
    function updatePropertyIncomeDetails(data) {
        const detailsContainer = document.querySelector('.analytics-card:nth-child(1) .analytics-details');
        detailsContainer.innerHTML = '';
        
        if (Object.keys(data).length > 0) {
            Object.entries(data).forEach(([property, amount]) => {
                detailsContainer.innerHTML += `
                    <div class="detail-row">
                        <span class="label">${property}:</span>
                        <span class="value">Rs. ${parseFloat(amount).toFixed(2)}</span>
                    </div>
                `;
            });
        } else {
            detailsContainer.innerHTML = `
                <div class="detail-row">
                    <span class="label">No income data available</span>
                </div>
            `;
        }
    }
    
    // Initialize with default data
    updatePropertyIncomeDetails(incomeSourcesData.all);
    
    // Event handler for income filter
    document.querySelector('#incomeFilter').addEventListener('change', function(e) {
        const period = e.target.value;
        const selectedData = incomeSourcesData[period];
        const formattedData = formatPropertyIncomeData(selectedData);
        
        // Update chart data
        incomePieChart.data.labels = formattedData.labels;
        incomePieChart.data.datasets[0].data = formattedData.values;
        incomePieChart.data.datasets[0].backgroundColor = formattedData.colors;
        incomePieChart.data.datasets[0].hoverBackgroundColor = formattedData.hoverColors;
        
        // Update details section
        updatePropertyIncomeDetails(selectedData);
        
        incomePieChart.update();
    });
    
    // Initialize Expense Breakdown Pie Chart
    const expenseCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
    
    // Process expense data from PHP
    const expenseTypes = <?= json_encode($expenseTypes ?? []) ?>;
    const expenseLabels = Object.keys(expenseTypes).map(type => type.charAt(0).toUpperCase() + type.slice(1));
    const expenseValues = Object.values(expenseTypes);
    
    // Use default data if no real data available
    const chartLabels = expenseLabels.length ? expenseLabels : ['Maintenance', 'Repairs', 'Utilities', 'Other'];
    const chartData = expenseValues.length ? expenseValues : [40, 30, 20, 10];
    
    new Chart(expenseCtx, {
        type: 'pie',
        data: {
            labels: chartLabels,
            datasets: [{
                data: chartData,
                backgroundColor: ['#FF5722', '#9C27B0', '#2196F3', '#607D8B'],
                hoverBackgroundColor: ['#E64A19', '#7B1FA2', '#1976D2', '#455A64'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw;
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return label + ': ' + percentage + '%';
                        }
                    }
                }
            }
        }
    });

        // Period selector change handler
        document.querySelector('#chartPeriod').addEventListener('change', function(e) {
            const period = e.target.value;
            const profitDataset = statisticsChart.data.datasets[2];
            
            if (period === 'yearly') {
                // Show profit, hide expenses
                profitDataset.hidden = false;
                statisticsChart.data.datasets[1].hidden = true;
            } else {
                // Show expenses, hide profit
                profitDataset.hidden = true;
                statisticsChart.data.datasets[1].hidden = false;
            }
            
            statisticsChart.update();
        });

        // Filter handlers for analytics charts
        document.querySelector('#incomeFilter').addEventListener('change', function(e) {
            console.log('Income filter changed to:', e.target.value);
            // In a real application, this would filter the data based on the selected time period
        });

        document.querySelector('#expenseFilter').addEventListener('change', function(e) {
            console.log('Expense filter changed to:', e.target.value);
            // In a real application, this would filter the data based on the selected time period
        });
    });
</script>
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/finance-report.css">

<style>
/* Critical Ui update */

.statistics-card{
    margin-left: 10px;
    margin-right: 10px;
}

.financial-summary-card{
    margin-left: 10px;
    margin-right: 10px;
}

.property-income-card{
    margin-left: 10px;
    margin-right: 10px;
}

.transactions-card{
    margin-left: 10px;
    margin-right: 10px;
}
.tenants-list {
    background: white;
    border-radius: 18px;
    padding: 1.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    position: relative;
    overflow: hidden;
    max-height: 400px; 
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

.tenants-list h2 {
    margin-bottom: 15px;
    flex-shrink: 0; /* Prevent header from scrolling */
}

.tenants-list-content {
    overflow-y: auto; 
    padding-right: 5px; 
    margin-right: -5px; 
    flex-grow: 1;
}

/* Modern scrollbar styling */
.tenants-list-content::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.tenants-list-content::-webkit-scrollbar-track {
    background: rgba(240, 242, 250, 0.5);
    border-radius: 10px;
}

.detailed-analytics{
    margin-left: 10px;
    margin-right: 10px;
}

.tenants-list-content::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #4e6ef7, #7f53ea);
    border-radius: 10px;
}

.tenants-list-content::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #3f5ce1, #6e44d5);
}

/* Firefox scrollbar styling */
.tenants-list-content {
    scrollbar-width: thin;
    scrollbar-color: #FFD600 #f0f2fa; 
}

/* Tenant Card Styling */
.tenant-item {
    display: flex;
    align-items: center;
    padding: 15px;
    margin-bottom: 16px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.tenant-item:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #4e6ef7, #7f53ea);
    opacity: 0.8;
    transition: all 0.3s ease;
}

.tenant-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(78, 89, 105, 0.12);
    background: rgba(255, 255, 255, 0.95);
}

/* Enhanced Tenant Avatar Styling */
.tenant-avatar {
    position: relative;
    margin-right: 18px;
    flex-shrink: 0;
    width: 64px;
    height: 64px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tenant-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%; /* Make it perfectly circular */
    object-fit: cover;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border: 3px solid #fff;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Improve avatar container alignment */
.tenant-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 64px; /* Match avatar height for alignment */
}

.tenant-info h4 {
    font-size: 17px;
    font-weight: 600;
    margin: 0 0 8px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: -0.2px;
}

.tenant-info span {
    display: flex;
    align-items: center;
    font-size: 13px;
    color: #666;
    margin-bottom: 5px;
    gap: 6px;
}

.tenant-info span i {
    color: #7f53ea;
    font-size: 12px;
    width: 14px;
    text-align: center;
}

/* Status Indicator */
.tenant-info span:last-child {
    margin-top: 8px;
    font-weight: 500;
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    letter-spacing: 0.5px;
    background: rgba(80, 200, 120, 0.12);
    color: rgb(60, 180, 100);
    align-self: flex-start;
}

/* Status Colors */
.tenant-item[data-status="Active"]:before,
.tenant-item[data-status="Confirmed"]:before {
    background: linear-gradient(to bottom, #50C878, #4CAF50);
}

.tenant-item[data-status="Pending"]:before {
    background: linear-gradient(to bottom, #FFC107, #FF9800);
}

.tenant-item[data-status="Cancelled"]:before {
    background: linear-gradient(to bottom, #FF5252, #F44336);
}

.tenant-item[data-status="Cancel Requested"]:before {
    background: linear-gradient(to bottom, #FF4081, #E91E63);
}

.tenant-item[data-status="Completed"]:before {
    background: linear-gradient(to bottom, #A8FFCE, #50C878, #4CAF50);
}

.tenant-item[data-status="Active"] .tenant-info span:last-child,
.tenant-item[data-status="Confirmed"] .tenant-info span:last-child {
    background: rgba(76, 175, 80, 0.12);
    color: rgb(60, 150, 70);
}

.tenant-item[data-status="Pending"] .tenant-info span:last-child {
    background: rgba(255, 193, 7, 0.12);
    color: rgb(200, 150, 0);
}

.tenant-item[data-status="Cancelled"] .tenant-info span:last-child {
    background: rgba(244, 67, 54, 0.12);
    color: rgb(200, 60, 50);
}

.tenant-item[data-status="Cancel Requested"] .tenant-info span:last-child {
    background: linear-gradient(90deg, #ffe4ec 60%, #ffd6e3 100%);
    color: #e91e63;
}

/* Empty State */
.empty-tenants {
    padding: 35px 20px;
    text-align: center;
    color: #888;
    font-style: italic;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 12px;
    border: 1px dashed #ddd;
    margin-top: 10px;
}

.empty-tenants p {
    margin: 0;
    font-size: 14px;
}
</style>

<?php require_once 'ownerFooter.view.php'; ?>