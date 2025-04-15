<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="javascript:history.back()"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Financial Report: <?= $property->name ?? 'Property Name' ?></h2>
                <p><span>Property ID: </span><?= $property->property_id ?? 'N/A' ?> | <span>Maintained By: </span><?= $agent->fname ?? '' ?> <?= $agent->lname ?? "Agent's Name" ?></p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
<div class="container">
    <!-- Left Sidebar -->
    <div class="sidebar">
        <!-- Profile Card (Keep in sidebar) -->
        <div class="menu-card">
            <p>Current User:</p>
            <?php if ($user && isset($user->image_url) && !empty($user->image_url)): ?>
                <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $user->image_url ?>" alt="Owner">
            <?php else: ?>
                <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Owner">
            <?php endif; ?>

            <a href="<?= ROOT ?>/dashboard/profile/" class="menu-item">
                Profile
            </a>
        </div>

        <!-- Property Details Card (MOVED FROM MAIN CONTENT TO SIDEBAR) -->
        <div class="property-info-card">
            <div class="card-header">
                <span>Property Details</span>
            </div>
            <div class="property-details">
                <div class="detail-row">
                    <span class="label">Address:</span>
                    <span class="value"><?= htmlspecialchars($property->address ?? 'N/A') ?>, <?= htmlspecialchars($property->city ?? '') ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Units:</span>
                    <span class="value"><?= $property->units ?? 'N/A' ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Property Type:</span>
                    <span class="value"><?= htmlspecialchars($property->type ?? 'N/A') ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Size:</span>
                    <span class="value"><?= number_format($property->size_sqr_ft ?? 0) ?> sq ft</span>
                </div>
            </div>
        </div>

        <!-- Financial Summary Card (Keep in sidebar) -->
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
                    <span class="value"><?= is_array($bookings) ? count($bookings) : 0 ?></span>
                </div>
                <div class="summary-item">
                    <span class="label">Active Bookings:</span>
                    <span class="value"><?= isset($activeBookings) ? $activeBookings : 0 ?></span>
                </div>
                <div class="summary-item">
                    <span class="label">Maintenance Requests:</span>
                    <span class="value"><?= is_array($serviceLogs) ? count($serviceLogs) : 0 ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Stats Cards (Keep in main content) -->
        <div class="stats-cards">
            <div class="stat-card earnings">
                <h3 class="positive">Earnings</h3>
                <div class="stat-amount">Rs. <?= number_format($totalIncome ?? 0, 2) ?></div>
                <div class="stat-change">
                    <span>since <?= date('Y-m-d', strtotime('-6 months')) ?></span>
                </div>
            </div>

            <div class="stat-card spendings">
                <h3 class="negative">Spendings</h3>
                <div class="stat-amount">Rs. <?= number_format($totalExpenses ?? 0, 2) ?></div>
                <div class="stat-change">
                    <span class="change-amount"><?= is_array($serviceLogs) ? count($serviceLogs) : 0 ?> services</span>
                </div>
            </div>

            <div class="stat-card profit">
                <h3 class="<?= (($profit ?? 0) >= 0) ? 'positive' : 'negative' ?>">Net Profit</h3>
                <div class="stat-amount">Rs. <?= number_format($profit ?? 0, 2) ?></div>
                <div class="stat-change">
                    <span class="change-amount">
                        <?= (($profit ?? 0) >= 0) ? '+' : '' ?>
                        <?= ($totalIncome ?? 0) > 0 ? number_format((($profit ?? 0)/($totalIncome ?? 1))*100, 1) : 0 ?>% margin
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Tenant Details Card (MOVED FROM SIDEBAR TO MAIN CONTENT) -->
        <div class="Tenant-table">
            <div class="card-header">
                <h3>Active Tenants</h3>
            </div>
            <div class="Ttenant-card">

                <div class="tenants-list">
                    <div class="tenant-header-row">
                        <div class="tenant-header-cell">Avatar</div>
                        <div class="tenant-header-cell">Tenant Info</div>
                        <div class="tenant-header-cell">Rent</div>
                        <div class="tenant-header-cell">Duration</div>
                    </div>
                    <?php if (is_array($bookings) && !empty($bookings)): ?>
                        <?php 
                        $acceptedBookings = false;
                        foreach($bookings as $booking): 
                            if(isset($booking->accept_status) && $booking->accept_status === 'accepted'): 
                                $acceptedBookings = true;
                        ?>
                            <div class="tenant">
                                <?php 
                                // Get tenant user data using customer_id from booking table
                                $tenantUser = null;
                                if(isset($booking->customer_id) && !empty($booking->customer_id)) {
                                    $tenantModel = new User();
                                    $tenantUser = $tenantModel->where(['pid' => $booking->customer_id])[0] ?? null;
                                }
                                ?>
                                <div class="tenant-avatar">
                                    <?php if ($tenantUser && isset($tenantUser->image_url) && !empty($tenantUser->image_url)): ?>
                                        <img src="<?= ROOT ?>/assets/images/uploads/profile_pictures/<?= $tenantUser->image_url ?>" alt="Tenant Photo" />
                                    <?php else: ?>
                                        <div class="avatar-placeholder">
                                            <?php 
                                            $initials = '';
                                            if ($tenantUser) {
                                                $initials = substr($tenantUser->fname ?? '', 0, 1) . substr($tenantUser->lname ?? '', 0, 1);
                                            } else {
                                                $initials = 'T';
                                            }
                                            echo htmlspecialchars($initials); 
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="tenant-info-cell">
                                    <div class="tenant-main">
                                        <?php if ($tenantUser): ?>
                                            <h4><?= $tenantUser->fname ?? '' ?> <?= $tenantUser->lname ?? '' ?></h4>
                                        <?php else: ?>
                                            <h4>Tenant #<?= $booking->customer_id ?? 'N/A' ?></h4>
                                        <?php endif; ?>
                                        <div class="tenant-badge">Active</div>
                                    </div>
                                    <div class="tenant-details">
                                        <div class="detail-item">
                                            <span class="detail-icon">📅</span>
                                            <span>Since: <?= date('M Y', strtotime($booking->start_date)) ?></span>
                                        </div>
                                        <?php if($tenantUser && !empty($tenantUser->contact)): ?>
                                        <div class="detail-item">
                                            <span class="detail-icon">📞</span>
                                            <span><?= $tenantUser->contact ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="tenant-rent-cell">
                                    Rs. <?= number_format($booking->price, 2) ?>
                                </div>
                                <div class="tenant-duration-cell">
                                    <div class="tenant-duration-badge">
                                        <?= $booking->renting_period ?? '1' ?> months
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <?php if (!$acceptedBookings): ?>
                            <div class="tenant-empty-state">
                                <div class="empty-icon">👤</div>
                                <div class="empty-text">No active tenants</div>
                            </div>
                        <?php endif; ?>

                    <?php else: ?>
                        <div class="tenant-empty-state">
                            <div class="empty-icon">👤</div>
                            <div class="empty-text">No active tenants</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Transaction History Card (Keep in main content) -->
        <div class="card-transactions-history-card">
            <div class="lcard-header">
                <h3>Transaction History</h3>
                <div class="filter-controls">
                    <select id="transactionFilter">
                        <option value="all">All Transactions</option>
                        <option value="income">Income Only</option>
                        <option value="expense">Expenses Only</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="transactions-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Transaction ID</th>
                                <th>Description</th>
                                <th>Category</th>
                                <th class="amount-column">Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Combine booking income and service expenses into a unified transaction list
                            $allTransactions = [];

                            // Process bookings (income)
                            if(is_array($bookings)) {
                                foreach($bookings as $booking) {
                                    $paymentDate = isset($booking->payment_date) ? $booking->payment_date : $booking->booked_date;
                                    $allTransactions[] = [
                                        'date' => $paymentDate,
                                        'timestamp' => strtotime($paymentDate),
                                        'id' => 'B-' . $booking->booking_id,
                                        'description' => 'Rent payment - ' . 
                                            (isset($booking->tenant_name) ? $booking->tenant_name : 'Property Booking') . 
                                            ' (' . date('M Y', strtotime($booking->start_date)) . ')',
                                        'category' => 'Income',
                                        'amount' => $booking->price,
                                        'status' => $booking->accept_status ?? 'Completed'
                                    ];
                                }
                            }

                            // Process service logs (expenses)
                            if(is_array($serviceLogs)) {
                                foreach($serviceLogs as $log) {
                                    $cost = $log->cost_per_hour * $log->total_hours;
                                    $allTransactions[] = [
                                        'date' => $log->date,
                                        'timestamp' => strtotime($log->date),
                                        'id' => 'S-' . $log->service_id,
                                        'description' => ucfirst($log->service_type) . ' - ' . 
                                            ($log->service_description ?? 'Maintenance service'),
                                        'category' => 'Expense',
                                        'amount' => -$cost, // Negative for expenses
                                        'status' => ucfirst($log->status)
                                    ];
                                }
                            }

                            // Sort by timestamp (newest first)
                            usort($allTransactions, function($a, $b) {
                                return $b['timestamp'] - $a['timestamp'];
                            });

                            // Display transactions
                            if(!empty($allTransactions)) {
                                foreach($allTransactions as $transaction) {
                                    $isIncome = $transaction['amount'] >= 0;
                                    $amountClass = $isIncome ? 'positive' : 'negative';
                                    $statusClass = strtolower($transaction['status']) === 'completed' ? 'status-completed' : 
                                         (strtolower($transaction['status']) === 'accepted' ? 'status-completed' : 
                                         (strtolower($transaction['status']) === 'pending' ? 'status-pending' : 'status-other'));
                            ?>
                            <tr data-category="<?= strtolower($transaction['category']) ?>">
                                <td class="date-column">
                                    <div class="transaction-date"><?= date('Y-m-d', $transaction['timestamp']) ?></div>
                                    <div class="transaction-time"><?= date('H:i', $transaction['timestamp']) ?></div>
                                </td>
                                <td><?= htmlspecialchars($transaction['id']) ?></td>
                                <td class="description-column"><?= htmlspecialchars($transaction['description']) ?></td>
                                <td>
                                    <span class="category-badge <?= strtolower($transaction['category']) ?>">
                                        <?= $transaction['category'] ?>
                                    </span>
                                </td>
                                <td class="amount-column <?= $amountClass ?>">
                                    Rs. <?= number_format(abs($transaction['amount']), 2) ?>
                                </td>
                                <td>
                                    <span class="status-badge <?= $statusClass ?>">
                                        <?= $transaction['status'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php 
                                }
                            } else {
                            ?>
                            <tr>
                                <td colspan="6" class="no-data">No transaction history available</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
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
                        if(is_array($serviceLogs)) {
                            foreach($serviceLogs as $log) {
                                $type = $log->service_type;
                                $cost = $log->cost_per_hour * $log->total_hours;
                                if(!isset($expenseTypes[$type])) {
                                    $expenseTypes[$type] = 0;
                                }
                                $expenseTypes[$type] += $cost;
                            }
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
                        if(is_array($serviceLogs)) {
                            foreach($serviceLogs as $log) {
                                $recentTransactions[] = [
                                    'date' => $log->date,
                                    'description' => $log->service_description ?? $log->service_type,
                                    'category' => 'Expense',
                                    'amount' => -($log->cost_per_hour * $log->total_hours)
                                ];
                            }
                        }

                        // Add bookings as income
                        if(is_array($bookings)) {
                            foreach($bookings as $booking) {
                                $recentTransactions[] = [
                                    'date' => $booking->booked_date,
                                    'description' => 'Rent payment for ' . date('M Y', strtotime($booking->start_date)),
                                    'category' => 'Income',
                                    'amount' => $booking->price
                                ];
                            }
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
        // Prepare data from PHP - add a fallback for empty data
        const monthlyData = <?= json_encode(is_array($monthlyData) ? array_values($monthlyData) : []) ?>;
        const monthLabels = <?= json_encode(is_array($monthlyData) ? array_keys($monthlyData) : []) ?>;
        
        // If data is missing, provide empty arrays
        const monthlyIncomeData = monthlyData.map(item => item?.income || 0);
        const monthlyExpenseData = monthlyData.map(item => item?.expense || 0);
        const monthlyProfitData = monthlyData.map(item => item?.profit || 0);
        const occupancyRateData = monthlyData.map(item => item?.occupancy_rate || 0);

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
                layout: {
                    padding: 5
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
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
                layout: {
                    padding: 5
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 15
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
/* LAYOUT STRUCTURE */
.container {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.sidebar {
    width: 280px;
    position: sticky;
    top: 20px;
    height: fit-content;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.main-content {
    display: flex;
    flex-direction: column;
    gap: 20px;
    width: 100%;
}

/* UNIFIED CARD SYSTEM */
.card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8f9fa;
    border-radius: 15px 15px 0 0;
}

.card-header h2, 
.card-header h3,
.card-header span {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.card-body {
    padding: 20px;
}

/* MENU CARD */
.menu-card {
    text-align: center;
}

.menu-card .card-body {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.menu-card p {
    font-weight: 600;
    margin: 5px 0 10px;
    width: 100%;
    text-align: center;
}

.menu-card img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 15px;
    border: 3px solid #f0f0f0;
}

.menu-item {
    display: block;
    padding: 10px 15px;
    background-color: #4CAF50;
    color: white !important;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 500;
    width: 100%;
    text-align: center;
    transition: background-color 0.2s;
    margin-top: 10px;
    width: 50%;
    background: #FCA311;
}
.menu-item:hover {
    background-color: #e89200;
    transform: translateY(-2px) scale(1.02);
    box-shadow: 0 4px 8px rgba(252, 163, 17, 0.3);
    transition: all 0.3s ease;
    color: white;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.menu-item {
    /* Existing styles kept */
    transition: all 0.3s ease;
}

/* TENANTS LIST */
.tenants-list {
    max-height: 250px;
    overflow-y: auto;
    padding: 10px;
}

.tenant {
    display: flex;
    padding: 5px;
    border-bottom: 1px solid #f0f0f0;
}

.tenant:last-child {
    border-bottom: none;
}

.tenant-avatar {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 16px;
    position: relative;
    width: 60px;
    height: 60px;
}

.tenant-avatar img {
    width: 56px;
    height: 56px;
    border-radius: 50%; 
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
    z-index: 2;
}

.tenant-avatar img:hover {
    transform: scale(1.08);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    border-color: #f0f8ff;
}

.tenant-avatar::after {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: radial-gradient(circle, rgba(252,163,17,0.15) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    z-index: 1;
}

.avatar-placeholder {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #4CAF50, #2E7D32);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    font-weight: bold;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    position: relative;
    z-index: 2;
    transition: transform 0.3s, box-shadow 0.3s;
}


/* Hover effect for placeholder */
.avatar-placeholder:hover {
    transform: scale(1.08);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Fix cell alignment in tenant grid */
.tenant {
    display: grid;
    grid-template-columns: 70px minmax(250px, 2fr) 100px 100px;
    align-items: center;
    padding: 14px 16px;
}


.tenant-info {
    flex: 1;
}

.tenant-main h4 {
    margin: 0 0 5px;
    font-size: 16px;
}

.tenant-details {
    font-size: 13px;
    color: #666;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 0;
    padding: 2px 0;
}

/* FINANCIAL SUMMARY */
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
    padding-bottom: 0;
}

.summary-item .label {
    color: #666;
}

.summary-item .value {
    font-weight: 600;
}

/* PROPERTY DETAILS */
.property-details .detail-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.property-details .detail-row:last-child {
    border-bottom: none;
}

.property-details .label {
    color: #666;
    font-weight: 500;
}

.property-details .value {
    font-weight: 600;
}

/* STATS CARDS */
.stats-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

.stat-card {
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
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

/* TRANSACTION TABLE */
.transactions-table {
    width: 100%;
    overflow-x: auto;
    max-height: 400px;
    overflow-y: auto;
    border-radius: 8px;
}

.property-info-card{
    background: white;
    border-radius: 15px;
}

.transactions-table table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.transactions-table thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.lcard-header{
    border-radius: 15px 15px 0 0;
    padding: 10px;
    background: #f8f9fa;
}

.transactions-table th {
    background-color: #f2f2f2;
    font-weight: 600;
    color: #333;
    text-align: left;
    padding: 14px 16px;
    border-bottom: 2px solid #ddd;
    position: sticky;
    top: 0;
    box-shadow: 0 2px 3px rgba(0,0,0,0.05);
}

.transactions-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.transactions-table tbody tr:hover {
    background-color: #f8f9ff;
}

.transactions-table tbody tr:last-child td {
    border-bottom: none;
}

.description-column {
    max-width: 300px;
    white-space: normal;
    word-break: break-word;
}

.savings-card{
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
    border-radius: 15px;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    min-width: 80px;
}

.category-badge.income {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.category-badge.expense {
    background-color: #ffebee;
    color: #c62828;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    min-width: 90px;
}

.status-completed {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.financial-summary-card{
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
    border-radius: 15px;
    background-color: white;
}

.status-pending {
    background-color: #fff8e1;
    color: #ff8f00;
}

.status-other {
    background-color: #e0f2f1;
    color: #00796b;
}

.date-column {
    white-space: nowrap;
}

.transaction-date {
    font-weight: 500;
}

.transaction-time {
    color: #777;
    font-size: 12px;
}

.amount-column {
    font-weight: 600;
    text-align: right;
    white-space: nowrap;
}

.amount-column.positive {
    color: #2e7d32;
}

.amount-column.negative {
    color: #c62828;
}

/* CHARTS */
.statistics-chart-container {
    height: 400px;
    width: 100%;
    position: relative;
}

.chart-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.legend {
    display: flex;
    gap: 15px;
}

.legend-item {
    display: flex;
    align-items: center;
    font-size: 14px;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
}

.dot.earnings { background-color: #FCA311; }
.dot.spendings { background-color: #f9c570d1; }
.dot.profit { background-color: #4CAF50; }

/* ANALYTICS SECTION */
.detailed-analytics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.analytics-content {
    display: flex;
    height: 240px;
}

.pie-chart-container {
    width: 50%;
    height: 200px;
    position: relative;
}

.analytics-details {
    width: 50%;
    padding-left: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.filter-controls select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background-color: white;
    font-size: 14px;
}

/* RESPONSIVE STYLES */
@media (max-width: 1200px) {
    .stats-cards {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 1024px) {
    .container {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        position: static;
        width: 100%;
    }
    
    .detailed-analytics {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .analytics-content {
        flex-direction: column;
        height: auto;
    }
    
    .pie-chart-container {
        width: 100%;
        height: 200px;
        margin-bottom: 20px;
    }
    
    .analytics-details {
        width: 100%;
        padding: 10px ;
    }
    
    .transactions-table table {
        min-width: 800px;
    }
}

/* Enhanced Payment History Table Styles */
.transactions-history-card {
    margin-bottom: 30px;
    border-radius: 12px;
    overflow: hidden;
    width: 100%; /* Ensure full width */
    position: relative; /* Create stacking context */
    z-index: 1; /* Prevent overlap issues */
}

.transactions-table {
    width: 100%;
    overflow-x: auto;
    max-height: 400px;
    overflow-y: auto;
    border-radius: 8px;
}

.property-info-card{
    background: white;
    border-radius: 15px;
}

.transactions-table table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.transactions-table thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.lcard-header{
    border-radius: 15px 15px 0 0;
    padding: 10px;
    background: #f8f9fa;
}

.transactions-table th {
    background-color: #f2f2f2;
    font-weight: 600;
    color: #333;
    text-align: left;
    padding: 14px 16px;
    border-bottom: 2px solid #ddd;
    position: sticky;
    top: 0;
    box-shadow: 0 2px 3px rgba(0,0,0,0.05);
}

.transactions-table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #eee;
    vertical-align: middle;
}

.transactions-table tbody tr:hover {
    background-color: #f8f9ff;
}

.transactions-table tbody tr:last-child td {
    border-bottom: none;
}

.description-column {
    max-width: 300px;
    white-space: normal;
    word-break: break-word;
}

.category-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    min-width: 80px;
}

.category-badge.income {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.category-badge.expense {
    background-color: #ffebee;
    color: #c62828;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    min-width: 90px;
}

.status-completed {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.status-pending {
    background-color: #fff8e1;
    color: #ff8f00;
}

.status-other {
    background-color: #e0f2f1;
    color: #00796b;
}

.date-column {
    white-space: nowrap;
}

.transaction-date {
    font-weight: 500;
}

.transaction-time {
    color: #777;
    font-size: 12px;
}

.amount-column {
    font-weight: 600;
    text-align: right;
    white-space: nowrap;
}

.amount-column.positive {
    color: #2e7d32;
}

.amount-column.negative {
    color: #c62828;
}

/* CHARTS */
.statistics-chart-container {
    height: 400px;
    width: 100%;
    position: relative;
}
.card-transactions-history-card{
    background: white;
    border-radius: 15px;
}
.chart-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.legend {
    display: flex;
    gap: 15px;
}

.legend-item {
    display: flex;
    align-items: center;
    font-size: 14px;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
}

.dot.earnings { background-color: #FCA311; }
.dot.spendings { background-color: #f9c570d1; }
.dot.profit { background-color: #4CAF50; }

/* ANALYTICS SECTION */
.detailed-analytics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.analytics-content {
    display: flex;
    height: 240px;
}

.pie-chart-container {
    width: 50%;
    height: 200px;
    position: relative;
}

.user_view-menu-bar {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-content: center;
    align-items: center;
    margin: 5px;
    padding: 5px;
    border-radius: 5px;
    height: 10%;
    background-color: var(--white-color);
}

.analytics-details {
    width: 50%;
    padding-left: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}


/* RESPONSIVE STYLES */
@media (max-width: 1200px) {
    .stats-cards {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 1024px) {
    .container {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        position: static;
        width: 100%;
    }
    
    .detailed-analytics {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .analytics-content {
        flex-direction: column;
        height: auto;
    }
    
    .pie-chart-container {
        width: 100%;
        height: 200px;
        margin-bottom: 20px;
    }
    
    .analytics-details {
        width: 100%;
        padding-left: 20px;
    }
    
    .transactions-table table {
        min-width: 800px;
    }
}

/* Enhanced Payment History Table Styles */
.transactions-history-card {
    margin-bottom: 30px;
    border-radius: 12px;
    overflow: hidden;
    width: 100%; /* Ensure full width */
    position: relative; /* Create stacking context */
    z-index: 1; /* Prevent overlap issues */
}

/* Fix table container */
.transactions-history-card .card-body {
    padding: 0; /* Remove padding to maximize table space */
}

.transactions-history-card .transactions-table {
    width: 100%;
    max-height: 450px;
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 0; /* Remove conflicting border radius */
    box-shadow: none; /* Remove conflicting shadow */
}

/* Fix search filters positioning */
.transaction-filters {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #eee;
}

/* Ensure table fills container properly */
.transactions-table table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

/* Fix table header positioning */
.transactions-table thead {
    position: sticky;
    top: 0;
    z-index: 5;
}

/* Add table summary footer */
.table-summary {
    display: flex;
    justify-content: flex-end;
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
    font-weight: 600;
}

/* Fix redundant Recent Transactions section */
.transactions-card {
    display: none; /* Hide redundant transaction table */
}

/* Enhanced Transaction Table Styling */
.card-transactions-history-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    overflow: hidden;
}

.lcard-header {
    padding: 18px 20px;
    background: linear-gradient(to right, #f8f9fa, #f2f4f6);
    border-bottom: 1px solid #eaeef2;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.lcard-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
    letter-spacing: -0.2px;
}

/* Table Container Styling */
.transactions-table {
    width: 100%;
    max-height: 450px;
    overflow-y: auto;
    overflow-x: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f1f5f9;
}

/* Custom Scrollbar Styling */
.transactions-table::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.transactions-table::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 8px;
}

.transactions-table::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 8px;
    border: 2px solid #f1f5f9;
}

.transactions-table::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Table Structure */
.transactions-table table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
    table-layout: fixed;
}

/* Enhanced Table Header */
.transactions-table thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.transactions-table th {
    background: linear-gradient(to bottom, #f9fafb, #f1f5f9);
    font-weight: 600;
    color: #4a5568;
    text-align: left;
    padding: 16px;
    border-bottom: 2px solid #e2e8f0;
    position: sticky;
    top: 0;
    box-shadow: 0 2px 3px rgba(0,0,0,0.05);
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
}

/* Table Cells */
.transactions-table td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid #f0f4f8;
    vertical-align: middle;
    color: #4a5568;
    transition: background-color 0.15s ease;
}

/* Zebra Striping for Better Readability */
.transactions-table tbody tr:nth-child(even) {
    background-color: #f9fafc;
}

/* Date Column */
.date-column {
    white-space: nowrap;
}

.transaction-date {
    font-weight: 500;
}

.transaction-time {
    color: #777;
    font-size: 12px;
}

.amount-column {
    font-weight: 600;
    text-align: right;
    white-space: nowrap;
}

.amount-column.positive {
    color: #2e7d32;
}

.amount-column.negative {
    color: #c62828;
}

/* CHARTS */
.statistics-chart-container {
    height: 400px;
    width: 100%;
    position: relative;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}
.card-transactions-history-card{
    background: white;
    border-radius: 15px;
}
.chart-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.legend {
    display: flex;
    gap: 15px;
}

.legend-item {
    display: flex;
    align-items: center;
    font-size: 14px;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 5px;
}

.dot.earnings { background-color: #FCA311; }
.dot.spendings { background-color: #f9c570d1; }
.dot.profit { background-color: #4CAF50; }

/* ANALYTICS SECTION */
.detailed-analytics {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.analytics-content {
    display: flex;
    height: 240px;
}

.pie-chart-container {
    width: 50%;
    height: 200px;
    position: relative;
}

.user_view-menu-bar {
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-content: center;
    align-items: center;
    margin: 5px;
    padding: 5px;
    border-radius: 5px;
    height: 10%;
    background-color: var(--white-color);
}

.analytics-details {
    width: 50%;
    padding-left: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}


/* RESPONSIVE STYLES */
@media (max-width: 1200px) {
    .stats-cards {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 1024px) {
    .container {
        grid-template-columns: 1fr;
    }
    
    .sidebar {
        position: static;
        width: 100%;
    }
    
    .detailed-analytics {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .analytics-content {
        flex-direction: column;
        height: auto;
    }
    
    .pie-chart-container {
        width: 100%;
        height: 200px;
        margin-bottom: 20px;
    }
    
    .analytics-details {
        width: 100%;
        padding-left: 20px;
    }
    
    .transactions-table table {
        min-width: 800px;
    }
}

/* Enhanced Payment History Table Styles */
.transactions-history-card {
    margin-bottom: 30px;
    border-radius: 12px;
    overflow: hidden;
    width: 100%; /* Ensure full width */
    position: relative; /* Create stacking context */
    z-index: 1; /* Prevent overlap issues */
}

/* Fix table container */
.transactions-history-card .card-body {
    padding: 0; /* Remove padding to maximize table space */
}

.transactions-history-card .transactions-table {
    width: 100%;
    max-height: 450px;
    overflow-y: auto;
    overflow-x: auto;
    border-radius: 0; /* Remove conflicting border radius */
    box-shadow: none; /* Remove conflicting shadow */
}

/* Fix search filters positioning */
.transaction-filters {
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-bottom: 1px solid #eee;
}

/* Ensure table fills container properly */
.transactions-table table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

/* Fix table header positioning */
.transactions-table thead {
    position: sticky;
    top: 0;
    z-index: 5;
}

/* Add table summary footer */
.table-summary {
    display: flex;
    justify-content: flex-end;
    padding: 15px 20px;
    background-color: #f8f9fa;
    border-top: 1px solid #e9ecef;
    font-weight: 600;
}

/* Fix redundant Recent Transactions section */
.transactions-card {
    display: none; /* Hide redundant transaction table */
}

.analytics-card{
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08)
}

/* Enhanced Tenant Card Styling
.tenant-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    margin-bottom: 25px;
}

 */

.Ttenant-card{
    background: white;
    padding: 20px;
    border-radius: 15px;
    border-radius: top 15px;
}

.tenant-table-caption h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.Tenant-table{
    background-color: white;
    border-radius: 15px;
}

.tenant-table-caption {
    padding: 16px 20px;
    background: linear-gradient(to right, #f8f9fa, #f2f4f6);
    border-bottom: 1px solid #eaeef2;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/*.tenant-card .card-header {
    padding: 16px 20px;
    background: linear-gradient(to right, #f8f9fa, #f2f4f6);
    border-bottom: 1px solid #eaeef2;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tenant-card .card-header span {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
}*/

.tenants-list {
    max-height: 350px;
    overflow-y: auto;
    padding: 0;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f1f5f9;
}

.tenants-list::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

.tenants-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 8px;
}

.tenants-list::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 8px;
    border: 2px solid #f1f5f9;
}

/* Individual Tenant Styling */
.tenant {
    display: grid;
    grid-template-columns: 70px minmax(250px, 2fr) 100px 100px;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}
.tenant:hover {
    background-color: #f9fbfd;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
}

.tenant:last-child {
    border-bottom: none;
}

/* Tenant Avatar */
.tenant-avatar {
    margin-right: 16px;
    flex-shrink: 0;
    position: relative;
}

.tenant-avatar img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.tenant:hover .tenant-avatar img {
    transform: scale(1.05);
}

.avatar-placeholder {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #4CAF50, #2E7D32);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    font-weight: bold;
    border: 3px solid #fff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Tenant Info */
.tenant-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.tenant-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.tenant-main h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
}

.tenant-badge {
    background-color: #e8f5e9;
    color: #2e7d32;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid rgba(46, 125, 50, 0.2);
}

/* Tenant Details */
.tenant-details {
    font-size: 13px;
    color: #666;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

.detail-item {
    display: flex;
    align-items: center;
    margin-bottom: 0;
}

.detail-icon {
    margin-right: 6px;
    opacity: 0.8;
    font-size: 14px;
}

/* Empty State */
.tenant-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
    color: #718096;
}

.empty-icon {
    font-size: 36px;
    margin-bottom: 15px;
    opacity: 0.6;
}

.empty-text {
    font-size: 16px;
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .tenant-details {
        grid-template-columns: 1fr;
    }
    
    .tenant {
        padding: 12px;
    }
    
    .tenant-avatar img,
    .avatar-placeholder {
        width: 50px;
        height: 50px;
    }
}

@media (max-width: 480px) {
    .tenant {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .tenant-avatar {
        margin-right: 0;
        margin-bottom: 12px;
    }
    
    .tenant-main {
        flex-direction: column;
    }
    
    .tenant-badge {
        margin-top: 8px;
    }
    
    .tenant-details {
        justify-items: center;
    }
}

/* Table-like Enhancements for Tenant Section */
.tenant-card {
    background: white;
    border-radius: 15px;
    /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08); */
    overflow: hidden;
    margin-bottom: 25px;
}

/* Table header row styling */
.tenant-header-row {
    display: grid;
    grid-template-columns: 70px minmax(250px, 2fr) 100px 100px;
    padding: 12px 16px;
    background: #f1f5f9;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
    border-radius: 15px;
    position: relative;
}

.tenant-header-cell {
    padding: 8px 12px;
    text-align: left;
    position: relative;

}

.tenant-header-cell:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 15%;
    height: 70%;
    width: 1px;
    background-color: #d1d5db;
}

/* Convert tenant items to table-like rows */
.tenant {
    display: grid;
    grid-template-columns: 80px 1fr 120px 120px;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
    position: relative;
}

/* Add divider for avatar column */
.tenant > div:nth-child(1)::after {
    content: '';
    position: absolute;
    left: 70px;
    top: 15%;
    height: 70%;
    width: 1px;
    background-color: #e2e8f0;
}

/* Add divider for info column */
.tenant > div:nth-child(2)::after {
    content: '';
    position: absolute;
    right: 220px;
    top: 15%;
    height: 70%;
    width: 1px;
    background-color: #e2e8f0;
}

/* Add divider for rent column */
.tenant > div:nth-child(3)::after {
    content: '';
    position: absolute;
    right: 100px;
    top: 15%;
    height: 70%;
    width: 1px;
    background-color: #e2e8f0;
}

.tenant:hover {
    background-color: rgba(249, 251, 253, 0.5);
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Fixed width for avatar column */
.tenant-avatar {
    margin-right: 0;
    width: 60px;
}

/* Main tenant info cell */
.tenant-info-cell {
    display: flex;
    flex-direction: column;
}

/* Rent amount cell */
.tenant-rent-cell {
    font-family: 'Roboto Mono', monospace;
    font-weight: 600;
    color: #2e7d32;
    text-align: right;
    padding-right: 15px;
}

/* Duration cell */
.tenant-duration-cell {
    text-align: center;
    white-space: nowrap;
}

.tenant-duration-badge {
    display: inline-flex;
    padding: 4px 10px;
    border-radius: 20px;
    background-color: #f0f4ff;
    color: #4361ee;
    font-size: 12px;
    font-weight: 600;
    align-items: center;
}

/* Tenant details restructured for better tabular alignment */
.tenant-details {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 8px;
}


/* Alternating row colors */
.tenant:nth-child(even) {
    background-color: #fafbfd;
}

.tenant:nth-child(even):hover {
    background-color: rgba(249, 251, 253, 0.8);
}

/* Empty state adjustments */
.tenant-empty-state {
    padding: 30px;
    border-radius: 0;
}

/* Scrollable container with fixed headers */
.tenants-list {
    max-height: 350px;
    overflow-y: auto;
}
/* Add these sorting controls to the header */
.tenant-sort-controls {
    display: flex;
    gap: 10px;
    align-items: center;
}

.tenant-sort-select {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 13px;
}

/* Enhanced Tenant Table Styling */
.Tenant-table {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 25px;
    overflow: hidden;
}

/* Match transaction table's card header styling */
.card-header {
    padding: 18px 20px;
    background: linear-gradient(to right, #f8f9fa, #f2f4f6);
    border-bottom: 1px solid #eaeef2;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin: 0;
    letter-spacing: -0.2px;
}

/* Improved tenant table header row */
.tenant-header-row {
    display: grid;
    grid-template-columns: 70px minmax(250px, 2fr) 100px 100px;
    padding: 14px 16px;
    background: linear-gradient(to bottom, #f9fafb, #f1f5f9);
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e2e8f0;
    position: sticky;
    top: 0;
    z-index: 10;
    box-shadow: 0 2px 3px rgba(0,0,0,0.05);
}

/* Better tenant list container with matching scrollbar */
.tenants-list {
    max-height: 350px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f1f5f9;
    padding: 0;
    background: white;
}

/* Scrollbar styling to match transaction table */
.tenants-list::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.tenants-list::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 8px;
}

.tenants-list::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 8px;
    border: 2px solid #f1f5f9;
}

.tenants-list::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Improved tenant row styling */
.tenant {
    display: grid;
    grid-template-columns: 70px minmax(250px, 2fr) 100px 100px;
    padding: 14px 16px;
    border-bottom: 1px solid #f0f4f8;
    transition: all 0.2s ease;
    align-items: center;
}

/* Subtle hover effect like transaction table */
.tenant:hover {
    background-color: #f8fafd;
    transform: translateY(-1px);
    box-shadow: 0 1px 3px rgba(0,0,0,0.03);
}

/* Alternating row colors like transaction table */
.tenant:nth-child(even) {
    background-color: #f9fafc;
}

.tenant:nth-child(even):hover {
    background-color: #f3f6fa;
}

/* Last row styling */
.tenant:last-child {
    border-bottom: none;
}

/* Improved tenant info cell styling */
.tenant-info-cell {
    padding: 0 10px;
}

.tenant-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}

.tenant-main h4 {
    margin: 0;
    font-size: 15px;
    font-weight: 600;
    color: #2d3748;
}

/* Enhanced badge styling */
.tenant-badge {
    background-color: #e8f5e9;
    color: #2e7d32;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
    border: 1px solid rgba(46, 125, 50, 0.15);
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}

/* Better tenant details styling */
.tenant-details {
    display: flex;
    flex-direction: column;
    font-size: 13px;
    color: #718096;
    margin-top: 5px;
}

.detail-item {
    margin-bottom: 3px;
    display: flex;
    align-items: center;
}

.detail-icon {
    margin-right: 6px;
    opacity: 0.7;
    font-size: 14px;
    color: #4a5568;
    width: 16px;
    text-align: center;
}

/* Enhanced tenant rent and duration cells */
.tenant-rent-cell {
    font-family: 'Roboto Mono', monospace;
    font-weight: 600;
    color: #2e7d32;
    text-align: right;
    font-size: 14px;
}

.tenant-duration-cell {
    display: flex;
    justify-content: center;
}

.tenant-duration-badge {
    display: inline-flex;
    padding: 5px 12px;
    border-radius: 20px;
    background-color: #f0f4ff;
    color: #4361ee;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid rgba(67, 97, 238, 0.15);
    box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    margin-left: 30px;
}

/* Empty state styling to match transaction table */
.tenant-empty-state {
    padding: 30px 20px;
    text-align: center;
    color: #718096;
    background-color: #fafbfc;
    border-top: 1px dashed #e2e8f0;
}

.empty-icon {
    font-size: 32px;
    margin-bottom: 10px;
    opacity: 0.6;
    color: #a0aec0;
}

.empty-text {
    font-size: 14px;
    font-weight: 500;
    color: #718096;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Transaction filter functionality
    document.querySelector('#transactionFilter').addEventListener('change', function(e) {
        const filterValue = e.target.value;
        const rows = document.querySelectorAll('.transactions-table tbody tr[data-category]');
        
        rows.forEach(row => {
            const category = row.getAttribute('data-category');
            if (filterValue === 'all' || category === filterValue) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>

<?php require_once 'ownerFooter.view.php'; ?>