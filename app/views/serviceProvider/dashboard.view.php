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
                <h3>Total Profit</h3>
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
            
            <!-- SECTION 1: Ongoing Repair Tasks (ServiceLog) -->
            <div class="dashboard-card todo-list">
                <div class="card-header">
                    <h2><i class="fas fa-clipboard-list"></i> Ongoing Repair Tasks</h2>
                    <a href="<?= ROOT ?>/serviceprovider/repairRequests?status=Ongoing" class="view-all">View All</a>
                </div>
                
                <div class="card-content" style="max-height:300px; overflow-y:auto;">
                    <?php 
                    $hasOngoingRepairs = false;
                    if (!empty($ongoingWorks)) {
                        $repairTasks = array_filter($ongoingWorks, function($task) {
                            return empty($task->is_external);
                        });
                        
                        if (!empty($repairTasks)):
                            $hasOngoingRepairs = true;
                    ?>
                        <ul class="task-list">
                            <?php foreach ($repairTasks as $task): ?>
                                <li class="task-item repair-task">
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
                    <?php 
                        endif;
                    }
                    
                    if (!$hasOngoingRepairs): 
                    ?>
                        <p class="no-tasks">No ongoing repair tasks at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SECTION 2: Ongoing External Tasks -->
            <div class="dashboard-card todo-list">
                <div class="card-header">
                    <h2><i class="fas fa-tools"></i> Ongoing External Tasks</h2>
                    <a href="<?= ROOT ?>/serviceprovider/externalServices?status=ongoing" class="view-all">View All</a>
                </div>
                
                <div class="card-content" style="max-height:300px; overflow-y:auto;">
                    <?php 
                    $hasOngoingExternal = false;
                    if (!empty($ongoingWorks)) {
                        $externalTasks = array_filter($ongoingWorks, function($task) {
                            return !empty($task->is_external);
                        });
                        
                        if (!empty($externalTasks)):
                            $hasOngoingExternal = true;
                    ?>
                        <ul class="task-list">
                            <?php foreach ($externalTasks as $task): ?>
                                <li class="task-item external-task">
                                    <div class="task-header">
                                        <h3><?= htmlspecialchars($task->service_type ?? 'Unknown') ?> - <?= htmlspecialchars($task->property_name ?? 'External Location') ?></h3>
                                        <span class="task-time"><?= $task->time_info ?? 'N/A' ?></span>
                                    </div>
                                    <div class="task-details">
                                        <p class="task-description"><?= htmlspecialchars($task->service_description ?? 'No description available') ?></p>
                                        <div class="task-meta">
                                            <span class="task-hours"><i class="fas fa-clock"></i> <?= $task->total_hours ?? 0 ?> hours</span>
                                            <span class="task-cost"><i class="fas fa-money-bill"></i> LKR <?= number_format($task->earnings ?? 0, 2) ?></span>
                                        </div>
                                    </div>
                                    <a href="<?= ROOT ?>/serviceprovider/updateExternalService?id=<?= $task->service_id ?? 0 ?>" class="task-action">
                                        <i class="fas fa-pen"></i> Update
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php 
                        endif;
                    }
                    
                    if (!$hasOngoingExternal): 
                    ?>
                        <p class="no-tasks">No ongoing external tasks at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SECTION 3: Completed Repair Tasks -->
            <div class="dashboard-card recent-tasks">
                <div class="card-header">
                    <h2><i class="fas fa-check-double"></i> Recent Completed Repairs</h2>
                    <a href="<?= ROOT ?>/serviceprovider/repairRequests?status=Done" class="view-all">View All</a>
                </div>
                
                <div class="card-content" style="max-height:250px; overflow-y:auto;">
                    <?php 
                    $hasCompletedRepairs = false;
                    if (!empty($recentCompletedTasks)) {
                        $completedRepairs = array_filter($recentCompletedTasks, function($task) {
                            return empty($task->is_external);
                        });
                        
                        if (!empty($completedRepairs)):
                            $hasCompletedRepairs = true;
                    ?>
                        <ul class="task-list completed-list">
                            <?php foreach ($completedRepairs as $task): ?>
                                <li class="task-item completed-item repair-completed">
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
                    <?php 
                        endif;
                    }
                    
                    if (!$hasCompletedRepairs): 
                    ?>
                        <p class="no-tasks">No completed repair tasks yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- SECTION 4: Completed External Tasks -->
            <div class="dashboard-card recent-tasks">
                <div class="card-header">
                    <h2><i class="fas fa-check-circle"></i> Recent Completed External Tasks</h2>
                    <a href="<?= ROOT ?>/serviceprovider/externalServices?status=done" class="view-all">View All</a>
                </div>
                
                <div class="card-content" style="max-height:250px; overflow-y:auto;">
                    <?php 
                    $hasCompletedExternal = false;
                    if (!empty($recentCompletedTasks)) {
                        $completedExternal = array_filter($recentCompletedTasks, function($task) {
                            return !empty($task->is_external);
                        });
                        
                        if (!empty($completedExternal)):
                            $hasCompletedExternal = true;
                    ?>
                        <ul class="task-list completed-list">
                            <?php foreach ($completedExternal as $task): ?>
                                <li class="task-item completed-item external-completed">
                                    <div class="task-header">
                                        <h3><?= htmlspecialchars($task->service_type ?? 'Unknown') ?> - <?= htmlspecialchars($task->property_name ?? 'External Location') ?></h3>
                                        <span class="task-badge external-badge">Completed</span>
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
                    <?php 
                        endif;
                    }
                    
                    if (!$hasCompletedExternal): 
                    ?>
                        <p class="no-tasks">No completed external tasks yet.</p>
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
                                boxWidth: 8, 
                                font: {
                                    size: 8 
                                },
                                padding: 3 
                            }
                        },
                        tooltip: {
                            titleFont: {
                                size: 8
                            },
                            bodyFont: {
                                size: 6
                            },
                            padding: {
                                top: 3,
                                bottom: 3,
                                left: 4,
                                right: 4
                            }
                        }
                    },
                    cutout: '65%' 
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

.dashboard-card .service-distribution .service-type .service-info .service-name {
    font-size: 14px !important;
}

.dashboard-card .service-distribution .service-type .service-info .service-earnings {
    font-size: 14px !important;
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

.p.pie-chart {
    height: 150px !important; /* Reduced from 180px */
    max-height: 150px !important;
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
    margin-top: 8px;
    padding: 5px;
    background: #fafafa;
    border-radius: 5px;
    font-size: 9px !important; 
}

.service-type {
    margin-bottom: 6px !important; 
    padding: 5px !important; 
    border: 1px solid #eee;
    border-radius: 4px;
    background: #fff;
    font-size: 14px !important; 
}

.service-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2px !important; 
    font-size: 13px !important; 
}

.service-name {
    font-weight: 500;
    font-size: 12px !important; 
    max-width: 70%; 
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.service-earnings {
    font-weight: 400;
    color: #333;
    font-size: 12px !important; 
    text-align: right;
}

.service-bar {
    height: 4px !important; 
    background: #f0f0f0;
    border-radius: 2px; 
    overflow: hidden;
    margin: 3px 0 !important; 
}

.service-bar-fill {
    height: 100%;
    background-color: #4caf50;
    transition: width 0.3s;
}

.service-count {
    font-size: 10px !important;
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

/* Enhanced hover effects for top-section cards */
.total-earnings:hover {
    background: linear-gradient(to right, #ffffff, #e3f2fd);
    border-left-width: 8px;
    border-left-color: #1976D2; /* Darker blue */
}

.total-earnings:hover .card-icon {
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.2) 0%, rgba(33, 150, 243, 0.4) 100%);
    color: #1565C0; /* Deeper blue */
    transform: scale(1.1);
}

.total-tasks:hover {
    background: linear-gradient(to right, #ffffff, #ffebee);
    border-left-width: 8px;
    border-left-color: #D32F2F; /* Darker red */
}

.total-tasks:hover .card-icon {
    background: linear-gradient(135deg, rgba(244, 67, 54, 0.2) 0%, rgba(244, 67, 54, 0.4) 100%);
    color: #C62828; /* Deeper red */
    transform: scale(1.1);
}

.total-completed:hover {
    background: linear-gradient(to right, #ffffff, #f3e5f5);
    border-left-width: 8px;
    border-left-color: #7B1FA2; /* Darker purple */
}

.total-completed:hover .card-icon {
    background: linear-gradient(135deg, rgba(156, 39, 176, 0.2) 0%, rgba(156, 39, 176, 0.4) 100%);
    color: #6A1B9A; /* Deeper purple */
    transform: scale(1.1);
}

.total-hours:hover {
    background: linear-gradient(to right, #ffffff, #fff3e0);
    border-left-width: 8px;
    border-left-color: #EF6C00; /* Darker orange */
}

.total-hours:hover .card-icon {
    background: linear-gradient(135deg, rgba(255, 152, 0, 0.2) 0%, rgba(255, 152, 0, 0.4) 100%);
    color: #E65100; /* Deeper orange */
    transform: scale(1.1);
}

.card, .card-icon, .card-icon i {
    transition: all 0.3s ease;
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

.service-name, 
.service-earnings, 
.service-count {
    line-height: 1.2 !important;
}

/* No tasks/empty state */
.no-tasks {
    color: #777;
    font-style: italic;
    text-align: center;
    padding: 10px;
    font-size: 14px !important;
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

/* Add these styles to differentiate between repair and external tasks */
.repair-task {
    border-left-color: #2196F3; /* Blue for repair tasks */
}

.external-task {
    border-left-color: #FF9800; /* Orange for external tasks */
}

.repair-completed {
    border-left-color: #4CAF50; /* Green for completed repair tasks */
}

.external-completed {
    border-left-color: #9C27B0; /* Purple for completed external tasks */
}

.task-badge.external-badge {
    background: #FF9800; /* Orange badge for external completions */
}

/* Card headers with different colors */
.dashboard-card:nth-of-type(1) .card-header h2 i {
    color: #2196F3; /* Blue */
}

.dashboard-card:nth-of-type(2) .card-header h2 i {
    color: #FF9800; /* Orange */
}

.dashboard-card:nth-of-type(3) .card-header h2 i {
    color: #4CAF50; /* Green */
}

.dashboard-card:nth-of-type(4) .card-header h2 i {
    color: #9C27B0; /* Purple */
}
</style>

<?php require 'serviceproviderFooter.view.php' ?>