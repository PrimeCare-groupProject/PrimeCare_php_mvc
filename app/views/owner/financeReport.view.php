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
    <!-- Left sidebar with horizontal layout -->
    <div class="L_idebar">
        <!-- Wrapper for horizontal layout -->
        <div class="sidebar-flex-container">
            <!-- Owner profile section on the left -->
            <div class="sidebar-left">
                <!-- Replace the current profile card with vertical layout -->
                <div class="owner-profile-card">
                    <!-- Image on top -->
                    <div class="profile-image-container">
                        <?php if ($user && isset($user->image_url) && !empty($user->image_url)): ?>
                            <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $user->image_url ?>" alt="Owner">
                        <?php else: ?>
                            <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Owner">
                        <?php endif; ?>
                    </div>
                    
                    <!-- Details below the image -->
                    <div class="profile-details">
                        <h3 class="profile-name"><?= $user->name ?? 'Property Owner' ?></h3>
                        <span class="profile-role">Property Owner</span>
                    </div>
                    
                    <!-- Stats positioned right below details -->
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-label">Properties</div>
                            <div class="stat-value"><?= count($properties) ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Tenants</div>
                            <div class="stat-value"><?= $activeBookings ?? 0 ?></div>
                        </div>
                    </div>
                    
                    <a href="<?= ROOT ?>/dashboard/profile" class="profile-action">
                        View Profile
                    </a>
                </div>
            </div>
            
            <!-- Tenant and Financial Summary sections on the right -->
            <div class="sidebar-right">
                <div class="savings-card">
                    <div class="card-header">
                        <span>Tenants</span>
                    </div>
                    <div class="tenants-list">
                        <?php if (!empty($bookings)): ?>
                            <?php foreach($bookings as $booking): ?>
                                <?php if($booking->accept_status === 'accepted'): ?>
                                    <div class="tenant">
                                        <div class="tenant-avatar">
                                            <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Tenant Avatar" />
                                        </div>
                                        <div class="tenant-info">
                                            <div class="tenant-main">
                                                <h4>Tenant #<?= $booking->tenant_id ?></h4>
                                            </div>
                                            <div class="tenant-details">
                                                <div class="detail-item">
                                                    <span>Since: <?= date('M Y', strtotime($booking->start_date)) ?></span>
                                                </div>
                                                <div class="detail-item">
                                                    <span>Duration: <?= $booking->renting_period ?? '1' ?> months</span>
                                                </div>
                                                <div class="detail-item">
                                                    <span>Rent: Rs. <?= number_format($booking->price, 2) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
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

                <!-- Financial Summary Card -->
                <div class="financial-summary-card">
                    <div class="card-header">
                        <span>Financial Summary</span>
                    </div>
                    <div class="summary-details">
                        <div class="summary-item">
                            <span class="label">Occupancy Rate:</span>
                            <span class="value"><?= number_format($occupancyRate, 1) ?>%</span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Total Bookings:</span>
                            <span class="value"><?= count($bookings) ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Active Bookings:</span>
                            <span class="value"><?= isset($activeBookings) ? $activeBookings : 0 ?></span>
                        </div>
                        <div class="summary-item">
                            <span class="label">Maintenance Requests:</span>
                            <span class="value"><?= count($serviceLogs) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Add Property Income Section at the top -->
        <div class="property-income-card">
            <div class="card-header">
                <h3>Monthly Income by Property</h3>
            </div>
            <div class="property-income-wrapper">
                <table>
                    <thead>
                        <tr>
                            <!-- Added a Month column -->
                            <th>Month</th>
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
                        if (!empty($bookings)) {
                            foreach ($bookings as $booking) {
                                if ($booking->property_id == $property->property_id) {
                                    $income += $booking->price;
                                }
                            }
                        }
                        $propertyIncomes[] = [
                            'name' => $property->name ?? ('Property #' . $property->property_id),
                            'income' => $income
                        ];
                    }
                }

                if (!empty($propertyIncomes)):
                    foreach ($propertyIncomes as $propertyIncome):
                ?>
                <tr>
                    <!-- Display the current month/year here as an example -->
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
                    <tfoot>
                        <tr>
                            <td><strong>Total</strong></td>
                            <td><strong>Rs. <?= number_format($totalIncome, 2) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="stats-cards">
            <div class="stat-card earnings">
                <h3 class="positive">Earnings</h3>
                <div class="stat-amount">Rs. <?= number_format($totalIncome, 2) ?></div>
                <div class="stat-change">
                    <span>since <?= date('Y-m-d', strtotime('-6 months')) ?></span>
                </div>
            </div>

            <div class="stat-card spendings">
                <h3 class="negative">Spendings</h3>
                <div class="stat-amount">Rs. <?= number_format($totalExpenses, 2) ?></div>
                <div class="stat-change">
                    <span class="change-amount"><?= count($serviceLogs) ?> services</span>
                </div>
            </div>

            <div class="stat-card profit">
                <h3 class="<?= ($profit >= 0) ? 'positive' : 'negative' ?>">Net Profit</h3>
                <div class="stat-amount">Rs. <?= number_format($profit, 2) ?></div>
                <div class="stat-change">
                    <span class="change-amount"><?= ($profit >= 0) ? '+' : '' ?><?= number_format(($profit/$totalIncome)*100, 1) ?>% margin</span>
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
                            $cost = $log->cost_per_hour * $log->total_hours;
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
                                'amount' => -($log->cost_per_hour * $log->total_hours)
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
</script>
<link rel="stylesheet" href="<?= ROOT ?>/assets/css/finance-report.css">

<?php require_once 'ownerFooter.view.php'; ?>