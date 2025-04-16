<?php require 'serviceproviderHeader.view.php' ?>

<?php
// Recalculate distribution-based percentages to match the pie chart
$grandTotalServices = array_sum($serviceTypeDistribution ?? []);
$serviceTypePercentage = [];

// Compute fresh percentages if there's data
if ($grandTotalServices > 0) {
    foreach ($serviceTypeDistribution as $type => $count) {
        $serviceTypePercentage[$type] = ($count / $grandTotalServices) * 100;
    }
}
?>

<div class="user_view-menu-bar">
    <h2>Service Provider Dashboard</h2>
</div>

<div class="dashboard">
    <!-- Summary Cards -->
    <div class="top-section">
        <div class="card total-earnings">
            <div class="card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-content">
                <h3>Total Earnings</h3>
                <span>LKR <?= number_format($totalProfit ?? 0, 2) ?></span>
            </div>
        </div>
        <div class="card total-tasks">
            <div class="card-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="card-content">
                <h3>Total Works</h3>
                <span><?= $totalWorks ?? 0 ?></span>
            </div>
        </div>
        <div class="card total-completed">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-content">
                <h3>Completed Tasks</h3>
                <span><?= $completedWorks ?? 0 ?> (<?= $completionRate ?? 0 ?>%)</span>
            </div>
        </div>
        <div class="card total-hours">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-content">
                <h3>Total Hours Worked</h3>
                <span><?= $totalHoursWorked ?? 0 ?> hrs</span>
            </div>
        </div>
    </div>

    <div class="dashboard-content">
        <!-- Left column -->
        <div class="dashboard-column">
            <!-- Work Efficiency Stats -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-tachometer-alt"></i> Work Efficiency</h2>
                </div>
                <div class="efficiency-stats">
                    <div class="stat-item">
                        <div class="stat-label">Avg. Hours Per Task</div>
                        <div class="stat-value"><?= $avgHoursPerService ?? 0 ?> hrs</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Task Completion Rate</div>
                        <div class="stat-value"><?= $completionRate ?? 0 ?>%</div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: <?= $completionRate ?? 0 ?>%"></div>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Pending Tasks</div>
                        <div class="stat-value"><?= $worksToDo ?? 0 ?></div>
                    </div>
                </div>
            </div>
            
            <!-- TO-DO List -->
            <div class="dashboard-card todo-list">
                <div class="card-header">
                    <h2><i class="fas fa-clipboard-list"></i> Ongoing Tasks</h2>
                    <a href="<?= ROOT ?>/dashboard/repairRequests?status=Ongoing" class="view-all">View All</a>
                </div>
                
                <div class="card-content" style="max-height:400px; overflow-y:auto;">
                    <?php if (empty($ongoingWorks)): ?>
                        <p class="no-tasks">No ongoing tasks at the moment.</p>
                    <?php else: ?>
                        <ul class="task-list">
                            <?php foreach ($ongoingWorks as $task): ?>
                                <li class="task-item">
                                    <div class="task-header">
                                        <h3><?= htmlspecialchars($task->service_type ?? 'Unknown') ?> - <?= htmlspecialchars($task->property_name ?? 'Unknown') ?></h3>
                                        <span class="task-time"><?= $task->time_info ?? 'N/A' ?></span>
                                    </div>
                                    <div class="task-details">
                                        <p class="task-description"><?= htmlspecialchars($task->service_description ?? 'No description available') ?></p>
                                        <div class="task-meta">
                                            <span class="task-hours"><i class="fas fa-clock"></i> <?= $task->total_hours ?? 0 ?> hours</span>
                                            <span class="task-cost"><i class="fas fa-money-bill"></i> LKR <?= number_format($task->earnings ?? 0, 2) ?></span>
                                        </div>
                                    </div>
                                    <a href="<?= ROOT ?>/serviceprovider/addLogs?service_id=<?= $task->service_id ?? 0 ?>" class="task-action">
                                        <i class="fas fa-pen"></i> Update
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Completed Tasks -->
            <div class="dashboard-card recent-tasks">
                <div class="card-header">
                    <h2><i class="fas fa-check-double"></i> Recent Completed Tasks</h2>
                    <a href="<?= ROOT ?>/dashboard/repairRequests?status=Done" class="view-all">View All</a>
                </div>
                
                <div class="card-content" style="max-height:300px; overflow-y:auto;">
                    <?php if (empty($completedWorks) || $completedWorks == 0): ?>
                        <p class="no-tasks">No completed tasks yet.</p>
                    <?php elseif (empty($recentCompletedTasks)): ?>
                        <p class="no-tasks">Task details not available.</p>
                    <?php else: ?>
                        <ul class="task-list completed-list">
                            <?php foreach ($recentCompletedTasks as $task): ?>
                                <li class="task-item completed-item">
                                    <div class="task-header">
                                        <h3><?= htmlspecialchars($task->service_type ?? 'Unknown') ?> - <?= htmlspecialchars($task->property_name ?? 'Unknown') ?></h3>
                                        <span class="task-badge">Completed</span>
                                    </div>
                                    <div class="task-details">
                                        <div class="task-meta">
                                            <span class="task-date"><i class="fas fa-calendar-check"></i> <?= date('M d, Y', strtotime($task->date)) ?></span>
                                            <span class="task-profit"><i class="fas fa-dollar-sign"></i> LKR <?= number_format($task->cost_per_hour * $task->total_hours, 2) ?></span>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

        </div>

        <!-- Right column -->
        <div class="dashboard-column">
            <!-- Weekly Earnings -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-calendar-week"></i> Weekly Performance</h2>
                </div>
                <div class="chart-container">
                    <canvas id="weeklyEarningsChart"></canvas>
                </div>
            </div>
            
            <!-- Service Type Distribution -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-chart-pie"></i> Service Distribution</h2>
                </div>
                <div class="chart-container pie-chart">
                    <canvas id="serviceDistribution"></canvas>
                </div>
                <div class="service-distribution">
                    <?php if (!empty($serviceTypeDistribution) && is_array($serviceTypeDistribution)): ?>
                        <?php foreach ($serviceTypeDistribution as $type => $count): ?>
                            <div class="service-type">
                                <div class="service-info">
                                    <span class="service-name"><?= htmlspecialchars($type) ?></span>
                                    <span class="service-earnings">
                                        LKR <?= number_format($serviceTypeEarnings[$type] ?? 0, 2) ?>
                                        (<?= round($serviceTypePercentage[$type] ?? 0, 2) ?>%)
                                    </span>
                                </div>
                                <div class="service-bar">
                                    <div class="service-bar-fill"
                                         style="width: <?= round($serviceTypePercentage[$type] ?? 0, 2) ?>%;">
                                    </div>
                                </div>
                                <span class="service-count"><?= $count ?> tasks</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-tasks">No service data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Scripts for the charts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate random colors
    function generateColors(count) {
        const colors = [];
        for (let i = 0; i < count; i++) {
            const hue = (i * 137) % 360; // Use golden angle for even distribution
            colors.push(`hsl(${hue}, 70%, 60%)`);
        }
        return colors;
    }
    
    // Helper function to check if data has actual values
    function hasRealData(data) {
        if (!data || !Array.isArray(data) || data.length === 0) return false;
        return data.some(value => value > 0);
    }
    
    try {
        // Weekly Earnings Chart
        const weeklyCtx = document.getElementById('weeklyEarningsChart').getContext('2d');
        const weeklyData = <?= json_encode(array_values($weeklyEarnings ?? [])) ?>;
        const weeklyLabels = <?= json_encode(array_keys($weeklyEarnings ?? [])) ?>;
        
        if (hasRealData(weeklyData)) {
            new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: weeklyLabels,
                    datasets: [{
                        label: 'Weekly Earnings (LKR)',
                        data: weeklyData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'LKR ' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        } else {
            // Display no data message
            weeklyCtx.font = '14px Arial';
            weeklyCtx.fillStyle = '#999';
            weeklyCtx.textAlign = 'center';
            weeklyCtx.fillText('No weekly earnings data available', weeklyCtx.canvas.width/2, 80);
        }
        
        // Service Distribution Pie Chart
        const distributionCtx = document.getElementById('serviceDistribution').getContext('2d');
        const serviceTypes = <?= json_encode(array_keys($serviceTypeDistribution ?? [])) ?>;
        const serviceCounts = <?= json_encode(array_values($serviceTypeDistribution ?? [])) ?>;
        
        if (serviceTypes.length > 0 && serviceCounts.length > 0 && hasRealData(serviceCounts)) {
            const colors = generateColors(serviceTypes.length);
            
            new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: serviceTypes,
                    datasets: [{
                        data: serviceCounts,
                        backgroundColor: colors,
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
                                boxWidth: 10,
                                font: {
                                    size: 9
                                }
                            }
                        },
                        tooltip: {
                            titleFont: {
                                size: 9
                            },
                            bodyFont: {
                                size: 8
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        } else {
            // Display no data message
            distributionCtx.font = '14px Arial';
            distributionCtx.fillStyle = '#999';
            distributionCtx.textAlign = 'center';
            distributionCtx.fillText('No service distribution data available', distributionCtx.canvas.width/2, 80);
        }
    } catch (error) {
        console.error('Error creating charts:', error);
    }
});
</script>

<style>
.user_view-menu-bar {
    /* Existing styles */
    /* padding-left: 20px; */
    /* width: 100%; */
    /* max-width: 100%; */
    
    /* Reduce or remove bottom margin */
    margin-bottom: 0;
}

/* Reset and base styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    width: 100%;
    overflow-x: hidden;
}

/* Main container */
.dashboard {
    padding: 20px;
    width: 100%;
    max-width: 100%;
    overflow-x: hidden;
    background-color: #f5f7fa;
}

.user_view-menu-bar {
    padding: 15px 20px; /* Added padding on both left and right */
    margin-left: auto;
    margin-right: auto;
    width: 97%;
    max-width: 100%;
    height: 80px; /* Increased height */
    display: flex;
    align-items: center;
}

.user_view-menu-bar h2 {
    font-size: 24px;
    color: #333;
}

/* Top section grid */
.top-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
    width: 100%;
}

/* Cards */
.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    padding: 15px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    border-left: 5px solid transparent;
    min-height: 100px;
    width: 100%;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.card-icon {
    font-size: 20px;
    margin-right: 15px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-content {
    flex: 1;
}

.card-content h3 {
    margin: 0;
    font-size: 13px;
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-content span {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-top: 8px;
    display: block;
}

/* Card specific styles */
.total-earnings {
    border-left-color: #2196F3;
}

.total-earnings .card-icon {
    color: #2196F3;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(33, 150, 243, 0.2) 100%);
}

.total-tasks {
    border-left-color: #f44336;
}

.total-tasks .card-icon {
    color: #f44336;
    background: linear-gradient(135deg, rgba(244, 67, 54, 0.1) 0%, rgba(244, 67, 54, 0.2) 100%);
}

.total-completed {
    border-left-color: #9c27b0;
}

.total-completed .card-icon {
    color: #9c27b0;
    background: linear-gradient(135deg, rgba(156, 39, 176, 0.1) 0%, rgba(156, 39, 176, 0.2) 100%);
}

.total-hours {
    border-left-color: #ff9800;
}

.total-hours .card-icon {
    color: #ff9800;
    background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(255, 152, 0, 0.2) 100%);
}

/* Dashboard content layout */
.dashboard-content {
    display: flex;
    gap: 25px;
    margin-top: 25px;
    width: 100%;
}

.dashboard-column {
    flex: 1;
    min-width: 0;
}

.dashboard-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    padding: 22px;
    margin-bottom: 25px;
    width: 100%;
    overflow: hidden;
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    padding-bottom: 12px;
    border-bottom: 1px solid #eee;
}

.card-header h2 {
    margin: 0;
    font-size: 18px;
    color: #333;
    font-weight: 600;
}

.card-header h2 i {
    margin-right: 10px;
    color: #2196F3;
}

.view-all {
    font-size: 13px;
    color: #2196F3;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 4px;
    background: rgba(33, 150, 243, 0.08);
    transition: all 0.2s ease;
}

.view-all:hover {
    background: rgba(33, 150, 243, 0.15);
}

/* Chart containers */
.chart-container {
    width: 100%;
    height: 200px;
    position: relative;
}

.pie-chart {
    height: 180px; /* Reduced from 250px */
    display: flex;
    justify-content: center;
}

/* Task list styles */
.task-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.task-item {
    border-left: 5px solid #4caf50;
    background: #fff;
    margin-bottom: 15px;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.task-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.task-header h3 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
}

.task-time {
    font-size: 11px;
    color: #777;
    background: #eee;
    padding: 2px 6px;
    border-radius: 10px;
}

.task-description {
    margin-bottom: 8px;
    font-size: 13px;
    color: #555;
    word-break: break-word;
}

.task-meta {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: #555;
}

.task-meta i {
    margin-right: 5px;
    color: #666;
}

.task-action {
    display: inline-block;
    margin-top: 8px;
    padding: 4px 12px;
    background: #2196F3;
    color: white;
    border-radius: 4px;
    text-decoration: none;
    font-size: 12px;
    transition: background 0.2s;
}

.task-action:hover {
    background: #1e88e5;
}

/* Service distribution styles */
.service-distribution {
    margin-top: 15px;
    padding: 8px;
    background: #fafafa;
    border-radius: 8px;
    font-size: 14px; /* Slightly smaller base font */
}

.service-type {
    margin-bottom: 12px;
    padding: 8px;
    border: 1px solid #eee;
    border-radius: 6px;
    background: #fff;
    font-size: 0.9rem; /* Decrease font size for details */
}

.service-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 5px;
}

.service-name {
    font-weight: 500;
}

.service-earnings {
    font-weight: 400;
    color: #333;
}

.service-bar {
    height: 8px;
    background: #f0f0f0;
    border-radius: 4px;
    overflow: hidden;
    margin: 5px 0;
}

.service-bar-fill {
    height: 100%;
    background-color: #4caf50;
    transition: width 0.3s;
}

.service-count {
    font-size: 0.8rem;
    color: #777;
}

/* Efficiency stats */
.efficiency-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
}

.stat-item {
    padding: 12px;
    background: #f9f9f9;
    border-radius: 6px;
    text-align: center;
}

.stat-label {
    font-size: 12px;
    color: #666;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #333;
    margin-bottom: 6px;
}

.progress-bar {
    height: 6px;
    background: #e0e0e0;
    border-radius: 3px;
    overflow: hidden;
    margin-top: 4px;
}

.progress-fill {
    height: 100%;
    background: #4caf50;
}

/* No tasks/empty state */
.no-tasks {
    color: #777;
    font-style: italic;
    text-align: center;
    padding: 15px;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .top-section {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .top-section {
        grid-template-columns: 1fr;
    }
    
    .dashboard-content {
        flex-direction: column;
    }
    
    .dashboard {
        padding: 15px;
    }
    
    .card {
        min-height: 90px;
    }
}

/* Add these styles for the new section */
.task-badge {
    background: #7CFC00;
    color: white;
    font-size: 11px;
    padding: 3px 8px;
    border-radius: 12px;
    font-weight: 500;
}

.completed-item {
    border-left-color: #4caf50;
}

.task-date {
    font-size: 12px;
    color: #666;
}

.task-profit {
    font-size: 12px;
    font-weight: 600;
    color: #2e7d32;
}
</style>

<?php require 'serviceproviderFooter.view.php' ?>