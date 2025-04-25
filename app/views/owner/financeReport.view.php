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
                        <div class="stat-item">
                            <div class="stat-label">Units</div>
                            <div class="stat-value"><?= $totalUnits ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right sidebar with tenants list -->
            <div class="sidebar-right">
                <div class="savings-card">
                    <div class="card-header">
                        <span>Tenants</span>
                    </div>
                    <!-- Tenants list content here -->
                    <div class="tenants-list">
    <?php if (!empty($bookings)): ?>
        <?php 
            // Track displayed tenants to avoid duplicates
            $displayedTenantIds = []; 
            foreach($bookings as $booking): 
                // Use customer_id for tenant identification
                $customerId = $booking->customer_id ?? null;
                
                // Skip if no customer ID or already displayed
                if(!$customerId || in_array($customerId, $displayedTenantIds)) {
                    continue;
                }
                $displayedTenantIds[] = $customerId;
                
                // Get tenant details from the tenantDetails array
                $tenant = isset($tenantDetails[$customerId]) ? $tenantDetails[$customerId] : null;
                
                // Properly format tenant name with fallback options
                if ($tenant) {
                    if (!empty($tenant->name)) {
                        $tenantName = $tenant->name;
                    } elseif (!empty($tenant->fname)) {
                        $tenantName = $tenant->fname . ' ' . ($tenant->lname ?? '');
                    } else {
                        $tenantName = 'Tenant #' . $customerId;
                    }
                } else {
                    $tenantName = 'Tenant #' . $customerId;
                }
                
                // Get tenant profile image if available
                $defaultImage = ROOT . '/assets/images/serPro1.png';
                $tenantImage = $defaultImage;
                
                if ($tenant && !empty($tenant->image_url)) {
                    $imagePath = ROOT . '/assets/images/uploads/profile_pictures/' . $tenant->image_url;
                    if(file_exists($_SERVER['DOCUMENT_ROOT'] . str_replace(ROOT, '', $imagePath))) {
                        $tenantImage = $imagePath;
                    }
                }
                
                // Determine status and class for styling
                $status = $booking->accept_status ?? 'pending';
                $statusClass = '';
                
                switch(strtolower($status)) {
                    case 'accepted':
                        $statusClass = 'status-accepted';
                        break;
                    case 'pending':
                        $statusClass = 'status-pending';
                        break;
                    case 'rejected':
                        $statusClass = 'status-rejected';
                        break;
                    default:
                        $statusClass = 'status-other';
                                }
                        ?>
                        <div class="tenant <?= $statusClass ?>">
                            <div class="tenant-avatar">
                                <img src="<?= $tenantImage ?>" alt="<?= htmlspecialchars($tenantName) ?>">
                            </div>
                            <div class="tenant-info">
                                <div class="tenant-main">
                                    <h4><?= htmlspecialchars($tenantName) ?></h4>
                                    <!-- Status badge displayed separately -->
                                    <span class="tenant-status <?= $statusClass ?>"><?= ucfirst($status) ?></span>
                                </div>
                                <div class="tenant-details">
                                    <div class="detail-item">
                                        <span>Since: <?= date('M Y', strtotime($booking->start_date ?? $booking->booked_date ?? date('Y-m-d'))) ?></span>
                                    </div>
                                    <?php if(isset($booking->renting_period)): ?>
                                    <div class="detail-item">
                                        <span>Duration: <?= $booking->renting_period ?> months</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="detail-item">
                                        <span>Rent: Rs. <?= number_format($booking->price ?? 0, 2) ?></span>
                                    </div>
                                    <?php if($tenant && isset($tenant->contact) && !empty($tenant->contact)): ?>
                                    <div class="detail-item">
                                        <span>Contact: <?= htmlspecialchars($tenant->contact) ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="tenant">
                            <div class="tenant-info">
                                <div class="tenant-main">
                                    <h4>No active tenants</h4>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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
    
    <!-- Rest of your content -->
    <!-- Statistics, Property Income, etc. -->
    <!-- Main Content -->
    <main class="main-content">
        <!-- Add Property Income Section at the top -->
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
                                    $income += $booking->price;
                                    if(isset($booking->status) && strtolower($booking->status) === 'active' || 
                                       isset($booking->accept_status) && strtolower($booking->accept_status) === 'accepted') {
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
                        <small>(<?= $propertyIncome['active_bookings'] ?>/<?= $propertyIncome['units'] ?> units)</small>
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

        <div class="stats-cards">
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
</div>

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
                <a href="#" class="view-all">View All</a>
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
                        foreach($serviceLogs as $log) {
                            $recentTransactions[] = [
                                'date' => $log->date,
                                'description' => $log->service_description ?? $log->service_type,
                                'category' => 'Expense',
                                'amount' => -($log->total_cost)
                            ];
                        }
                        
                        // Add bookings as income
                        foreach($bookings as $booking) {
                            $recentTransactions[] = [
                                'date' => $booking->booked_date,
                                'description' => 'Rent payment for ' . date('M Y', strtotime($booking->start_date)),
                                'category' => 'Income',
                                'amount' => $booking->price
                            ];
                        }
                        
                        // Sort by date (newest first)
                        usort($recentTransactions, function($a, $b) {
                            return strtotime($b['date']) - strtotime($a['date']);
                        });
                        
                        // Show only the 5 most recent transactions
                        $recentTransactions = array_slice($recentTransactions, 0, 5);
                        
                        foreach($recentTransactions as $transaction):
                        ?>
                        <tr>
                            <td><?= date('Y-m-d', strtotime($transaction['date'])) ?></td>
                            <td><?= htmlspecialchars($transaction['description']) ?></td>
                            <td><span class="category-badge <?= strtolower($transaction['category']) ?>"><?= $transaction['category'] ?></span></td>
                            <td class="<?= $transaction['amount'] >= 0 ? 'positive' : 'negative' ?>">
                                Rs. <?= number_format(abs($transaction['amount']), 2) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($recentTransactions)): ?>
                        <tr>
                            <td colspan="4">No transaction data available</td>
                        </tr>
                        <?php endif; ?>
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

        // Initialize Income Sources Pie Chart
        const incomeCtx = document.getElementById('incomeSourcesChart').getContext('2d');
        new Chart(incomeCtx, {
            type: 'pie',
            data: {
                labels: ['Rent', 'Other Income'],
                datasets: [{
                    data: [90, 10],
                    backgroundColor: ['#4CAF50', '#FFC107'],
                    hoverBackgroundColor: ['#388E3C', '#FFB300'],
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

document.addEventListener('DOMContentLoaded', function() {
    // Create the statistics chart
    const ctx = document.getElementById('statisticsChart').getContext('2d');
    
    // Extract data from PHP
    const months = <?= json_encode(array_keys($monthlyData)) ?>;
    const incomeData = months.map(month => <?= json_encode(array_column($monthlyData, 'income')) ?>[months.indexOf(month)] || 0);
    const expenseData = months.map(month => <?= json_encode(array_column($monthlyData, 'expense')) ?>[months.indexOf(month)] || 0);
    const profitData = months.map(month => <?= json_encode(array_column($monthlyData, 'profit')) ?>[months.indexOf(month)] || 0);
    const occupancyData = months.map(month => <?= json_encode(array_column($monthlyData, 'occupancy_rate')) ?>[months.indexOf(month)] || 0);
    
    const statisticsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Income',
                    data: incomeData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Expenses',
                    data: expenseData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Profit',
                    data: profitData,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    hidden: true
                },
                {
                    label: 'Occupancy Rate (%)',
                    data: occupancyData,
                    type: 'line',
                    backgroundColor: 'rgba(255, 206, 86, 0.2)',
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    fill: false,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Amount (Rs.)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Occupancy Rate (%)'
                    },
                    max: 100,
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Existing chart initialization code ---
    
    // Income Sources Pie Chart with Filter Functionality
    const incomeCtx = document.getElementById('incomeSourcesChart').getContext('2d');
    
    // Data for different time periods (to be populated from server)
    const incomeSourcesData = {
        'all': { rent: <?= $totalIncome * 0.9 ?>, other: <?= $totalIncome * 0.1 ?> },
        '6': { rent: <?= $last6MonthsIncome * 0.9 ?? $totalIncome * 0.9 ?>, other: <?= $last6MonthsIncome * 0.1 ?? $totalIncome * 0.1 ?> },
        '3': { rent: <?= $last3MonthsIncome * 0.9 ?? $totalIncome * 0.7 ?>, other: <?= $last3MonthsIncome * 0.1 ?? $totalIncome * 0.3 ?> },
        '1': { rent: <?= $currentMonthIncome * 0.9 ?? $totalIncome * 0.85 ?>, other: <?= $currentMonthIncome * 0.1 ?? $totalIncome * 0.15 ?> }
    };
    
    // Initialize income chart with default data
    const incomePieChart = new Chart(incomeCtx, {
        type: 'pie',
        data: {
            labels: ['Rent', 'Other Income'],
            datasets: [{
                data: [incomeSourcesData.all.rent, incomeSourcesData.all.other],
                backgroundColor: ['#4CAF50', '#FFC107'],
                hoverBackgroundColor: ['#388E3C', '#FFB300'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 12 }
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
    
    // Expense Breakdown Pie Chart with Filter Functionality
    const expenseCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
    
    // Process expense data for different time periods
    <?php 
    // Prepare expense data for different periods
    $allExpenseTypes = [];
    $last6MonthsExpenseTypes = [];
    $last3MonthsExpenseTypes = [];
    $currentMonthExpenseTypes = [];
    
    foreach($serviceLogs as $log) {
        $type = $log->service_type;
        $cost = $log->cost_per_hour * $log->total_hours;
        $logDate = strtotime($log->date);
        $sixMonthsAgo = strtotime('-6 months');
        $threeMonthsAgo = strtotime('-3 months');
        $currentMonthStart = strtotime(date('Y-m-01'));
        
        // All time expenses
        if(!isset($allExpenseTypes[$type])) $allExpenseTypes[$type] = 0;
        $allExpenseTypes[$type] += $cost;
        
        // Last 6 months expenses
        if($logDate >= $sixMonthsAgo) {
            if(!isset($last6MonthsExpenseTypes[$type])) $last6MonthsExpenseTypes[$type] = 0;
            $last6MonthsExpenseTypes[$type] += $cost;
        }
        
        // Last 3 months expenses
        if($logDate >= $threeMonthsAgo) {
            if(!isset($last3MonthsExpenseTypes[$type])) $last3MonthsExpenseTypes[$type] = 0;
            $last3MonthsExpenseTypes[$type] += $cost;
        }
        
        // Current month expenses
        if($logDate >= $currentMonthStart) {
            if(!isset($currentMonthExpenseTypes[$type])) $currentMonthExpenseTypes[$type] = 0;
            $currentMonthExpenseTypes[$type] += $cost;
        }
    }
    ?>
    
    // Store expense data for different periods
    const expenseData = {
        'all': <?= json_encode($allExpenseTypes) ?>,
        '6': <?= json_encode($last6MonthsExpenseTypes) ?>,
        '3': <?= json_encode($last3MonthsExpenseTypes) ?>,
        '1': <?= json_encode($currentMonthExpenseTypes) ?>
    };
    
    // Format expense data for the chart
    function formatExpenseData(data) {
        // Use default data if nothing available
        if (Object.keys(data).length === 0) {
            return {
                labels: ['Maintenance', 'Repairs', 'Utilities', 'Other'],
                values: [40, 30, 20, 10],
                colors: ['#FF5722', '#9C27B0', '#2196F3', '#607D8B'],
                hoverColors: ['#E64A19', '#7B1FA2', '#1976D2', '#455A64']
            };
        }
        
        // Format the real data
        return {
            labels: Object.keys(data).map(type => type.charAt(0).toUpperCase() + type.slice(1)),
            values: Object.values(data),
            colors: ['#FF5722', '#9C27B0', '#2196F3', '#607D8B', '#FF9800', '#795548', '#009688', '#673AB7'],
            hoverColors: ['#E64A19', '#7B1FA2', '#1976D2', '#455A64', '#F57C00', '#5D4037', '#00796B', '#512DA8']
        };
    }
    
    // Initialize with default 'all time' data
    const defaultExpenseData = formatExpenseData(expenseData.all);
    
    const expensePieChart = new Chart(expenseCtx, {
        type: 'pie',
        data: {
            labels: defaultExpenseData.labels,
            datasets: [{
                data: defaultExpenseData.values,
                backgroundColor: defaultExpenseData.colors.slice(0, defaultExpenseData.labels.length),
                hoverBackgroundColor: defaultExpenseData.hoverColors.slice(0, defaultExpenseData.labels.length),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { boxWidth: 12 }
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
    
    // Event handlers for the filter selects
    document.querySelector('#incomeFilter').addEventListener('change', function(e) {
        const period = e.target.value;
        const selectedData = incomeSourcesData[period];
        
        // Update the chart data
        incomePieChart.data.datasets[0].data = [selectedData.rent, selectedData.other];
        
        // Update the detail rows below the chart
        document.querySelector('.analytics-details .detail-row:nth-child(1) .value').textContent = 
            'Rs. ' + selectedData.rent.toFixed(2);
        document.querySelector('.analytics-details .detail-row:nth-child(2) .value').textContent = 
            'Rs. ' + selectedData.other.toFixed(2);
        
        incomePieChart.update();
    });
    
    document.querySelector('#expenseFilter').addEventListener('change', function(e) {
        const period = e.target.value;
        const formattedData = formatExpenseData(expenseData[period]);
        
        // Update the chart data
        expensePieChart.data.labels = formattedData.labels;
        expensePieChart.data.datasets[0].data = formattedData.values;
        expensePieChart.data.datasets[0].backgroundColor = formattedData.colors.slice(0, formattedData.labels.length);
        expensePieChart.data.datasets[0].hoverBackgroundColor = formattedData.hoverColors.slice(0, formattedData.labels.length);
        
        // Update the expense details section
        const detailsContainer = document.querySelector('.analytics-card:nth-child(2) .analytics-details');
        detailsContainer.innerHTML = '';
        
        if(formattedData.labels.length > 0) {
            formattedData.labels.forEach((label, index) => {
                detailsContainer.innerHTML += `
                    <div class="detail-row">
                        <span class="label">${label}:</span>
                        <span class="value">Rs. ${formattedData.values[index].toFixed(2)}</span>
                    </div>
                `;
            });
        } else {
            detailsContainer.innerHTML = `
                <div class="detail-row">
                    <span class="label">No expense data available</span>
                </div>
            `;
        }
        
        expensePieChart.update();
    });
});
</script>
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/finance-report.css">

<style>
    /* Status Badge Styles */
    .tenant-avatar {
        position: relative;
    }
    
    .status-badge {
        position: absolute;
        bottom: -5px;
        right: -5px;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    
    /* Status Colors */
    .status-badge.status-accepted {
        background-color: #4CAF50;
    }
    
    .status-badge.status-pending {
        background-color: #FFC107;
        color: #333;
    }
    
    .status-badge.status-rejected {
        background-color: #F44336;
    }
    
    .status-badge.status-other {
        background-color: #9E9E9E;
    }
    
    /* Status Borders */
    .tenant.status-accepted {
        border-left: 3px solid #4CAF50;
    }
    
    .tenant.status-pending {
        border-left: 3px solid #FFC107;
    }
    
    .tenant.status-rejected {
        border-left: 3px solid #F44336;
    }
    
    .tenant.status-other {
        border-left: 3px solid #9E9E9E;
    }

    /* Status Badge Styles */
    .tenant-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        margin-left: 10px;
        color: white;
    }
    
    .tenant-status.status-accepted {
        background-color: #4CAF50;
    }
    
    .tenant-status.status-pending {
        background-color: #FFC107;
        color: #333;
    }
    
    .tenant-status.status-rejected {
        background-color: #F44336;
    }
    
    .tenant-status.status-other {
        background-color: #9E9E9E;
    }
    
    /* Tenant main section styles */
    .tenant-main {
        display: flex;
        align-items: center;
        margin-bottom: 5px;
    }
    
    .tenant-main h4 {
        margin: 0;
    }
    
    /* Border styles for tenant cards */
    .tenant.status-accepted {
        border-left: 3px solid #4CAF50;
    }
    
    .tenant.status-pending {
        border-left: 3px solid #FFC107;
    }
    
    .tenant.status-rejected {
        border-left: 3px solid #F44336;
    }
    
    .tenant.status-other {
        border-left: 3px solid #9E9E9E;
    }
</style>

<?php require_once 'ownerFooter.view.php'; ?>