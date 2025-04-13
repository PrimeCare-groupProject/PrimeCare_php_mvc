<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2><?= $property->name ?? 'Property Name' ?></h2>
                <p><span>Maintained By: </span><?= $agent->fname ?? '' ?> <?= $agent->lname ?? "Agent's Name" ?></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<div class="container">
    <!-- Left Sidebar -->
    <div class="sidebar">
        <div class="menu-card">
            <p>Current User:</p>
            <?php if ($user && isset($user->image_url) && !empty($user->image_url)): ?>
                <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $user->image_url ?>" alt="Owner">
            <?php else: ?>
                <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Owner">
            <?php endif; ?>

            <a href="<?= ROOT ?>/owner/profile" class="menu-item">
                Profile
            </a>
        </div>

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
                                    <?php 
                                    // Get tenant user data if available
                                    $tenantUser = null;
                                    if(isset($booking->tenant_id)) {
                                        $tenantModel = new User();
                                        $tenantUser = $tenantModel->where(['pid' => $booking->tenant_id])[0] ?? null;
                                    }
                                    ?>
                                    <?php if ($tenantUser && isset($tenantUser->image_url) && !empty($tenantUser->image_url)): ?>
                                        <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $tenantUser->image_url ?>" alt="Tenant Avatar" />
                                    <?php else: ?>
                                        <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Tenant Avatar" />
                                    <?php endif; ?>
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

    <!-- Main Content -->
    <main class="main-content">
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
                            <td><?= $transaction['category'] ?></td>
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

<style>
/* Add new CSS for enhanced financial report */
.stats-cards {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    flex: 1;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-card.earnings {
    background-color: #f0f8f1;
    border-left: 4px solid #4CAF50;
}

.stat-card.spendings {
    background-color: #fff9f0;
    border-left: 4px solid #FCA311;
}

.stat-card.profit {
    background-color: #f0f4ff;
    border-left: 4px solid #2196F3;
}

.stat-amount {
    font-size: 24px;
    font-weight: bold;
    margin: 10px 0;
}

.positive {
    color: #4CAF50;
}

.negative {
    color: #F44336;
}

.detailed-analytics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.analytics-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.analytics-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.analytics-content {
    display: flex;
}

.pie-chart-container {
    flex: 1;
    height: 200px;
}

.analytics-details {
    flex: 1;
    padding-left: 20px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid #f0f0f0;
}

.transactions-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.transactions-card .card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.view-all {
    color: #2196F3;
    font-size: 14px;
    text-decoration: none;
}

.transactions-table {
    width: 100%;
    overflow-x: auto;
}

.transactions-table table {
    width: 100%;
    border-collapse: collapse;
}

.transactions-table th,
.transactions-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.transactions-table th {
    background-color: #f9f9f9;
    font-weight: 600;
}

.financial-summary-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    overflow: hidden;
}

.summary-details {
    padding: 15px;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.summary-item:last-child {
    border-bottom: none;
}

.summary-item .label {
    color: #666;
}

.summary-item .value {
    font-weight: 600;
}

@media (max-width: 768px) {
    .stats-cards,
    .detailed-analytics {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'ownerFooter.view.php'; ?>