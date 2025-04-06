<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>Service Provider Dashboard</h2>
</div>

<div class="dashboard">
    <!-- Summary Cards -->
    <div class="top-section">
        <div class="card total-profit">
            <div class="card-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="card-content">
                <h3>Total Profit</h3>
                <span><?= $totalProfit ?? 0 ?></span>
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
        <div class="card total-profit">
            <div class="card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="card-content">
                <h3>Total Earnings</h3>
                <span>LKR <?= number_format($totalProfit ?? 0, 2) ?></span>
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
            <!-- TO-DO List -->
            <div class="dashboard-card todo-list">
                <div class="card-header">
                    <h2><i class="fas fa-clipboard-list"></i> Ongoing Tasks</h2>
                    <a href="<?= ROOT ?>serviceprovider/repairRequests?status=Ongoing" class="view-all">View All</a>
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
                                    <a href="<?= ROOT ?>serviceprovider/addLogs?service_id=<?= $task->service_id ?? 0 ?>" class="task-action">
                                        <i class="fas fa-pen"></i> Update
                                    </a>
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
                <div class="chart-container" style="height: 200px; position: relative;">
                    <canvas id="weeklyEarningsChart"></canvas>
                </div>
            </div>
            
            <!-- Service Type Distribution -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-chart-pie"></i> Service Distribution</h2>
                </div>
                <div class="chart-container" style="height: 250px; position: relative; display: flex; justify-content: center;">
                    <canvas id="serviceDistribution" style="max-width: 250px;"></canvas>
                </div>
                <div class="service-distribution" style="max-height: 250px; overflow-y: auto;">
                    <?php if (isset($serviceTypeDistribution) && is_array($serviceTypeDistribution) && !empty($serviceTypeDistribution)): ?>
                        <?php foreach ($serviceTypeDistribution as $type => $count): ?>
                            <div class="service-type">
                                <div class="service-info">
                                    <span class="service-name"><?= htmlspecialchars($type) ?></span>
                                    <span class="service-earnings">
                                        LKR <?= number_format($serviceTypeEarnings[$type] ?? 0, 2) ?> 
                                        (<?= $serviceTypePercentage[$type] ?? 0 ?>%)
                                    </span>
                                </div>
                                <div class="service-bar">
                                    <div class="service-bar-fill" style="width: <?= ($count / ($totalWorks ?: 1)) * 100 ?>%"></div>
                                </div>
                                <span class="service-count"><?= $count ?> tasks</span>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-tasks">No service data available.</p>
                    <?php endif; ?>
                </div>
            </div>

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
                        <div class="stat-value"><?= $pendingWorks ?? 0 ?></div>
                    </div>
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
                                boxWidth: 12,
                                font: {
                                    size: 10
                                }
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

<!-- Add some CSS to make it look nice -->
<style>
.dashboard {
    padding: 20px;
    max-width: 100%;
    overflow-x: hidden;
    background-color: #f5f7fa;
}

.top-section {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 18px;
    margin-bottom: 25px;
}

.card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    padding: 18px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    border-left: 5px solid transparent;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
}

.total-profit {
    border-left-color: #2196F3;
}

.total-tasks {
    border-left-color: #f44336;
}

.total-completed {
    border-left-color: #9c27b0;
}

.total-hours {
    border-left-color: #ff9800;
}

.card-icon {
    font-size: 22px;
    margin-right: 15px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(33, 150, 243, 0.2) 100%);
}

.total-profit .card-icon {
    color: #2196F3;
    background: linear-gradient(135deg, rgba(33, 150, 243, 0.1) 0%, rgba(33, 150, 243, 0.2) 100%);
}

.total-completed .card-icon {
    color: #9c27b0;
    background: linear-gradient(135deg, rgba(156, 39, 176, 0.1) 0%, rgba(156, 39, 176, 0.2) 100%);
}

.total-hours .card-icon {
    color: #ff9800;
    background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(255, 152, 0, 0.2) 100%);
}

.total-tasks .card-icon {
    color: #f44336;
    background: linear-gradient(135deg, rgba(244, 67, 54, 0.1) 0%, rgba(244, 67, 54, 0.2) 100%);
}

.card-content h3 {
    margin: 0;
    font-size: 14px;
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.card-content span {
    font-size: 20px;
    font-weight: 700;
    color: #333;
    margin-top: 5px;
    display: block;
}

.dashboard-content {
    display: flex;
    gap: 25px;
    margin-top: 25px;
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
    overflow: hidden;
    transition: box-shadow 0.3s ease;
}

.dashboard-card:hover {
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
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

.task-list {
    list-style: none;
    padding: 0;
    margin: 0;
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

.service-distribution {
    margin-top: 15px;
}

.service-type {
    margin-bottom: 12px;
}

.service-info {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.service-name {
    font-weight: bold;
    font-size: 13px;
}

.service-earnings {
    font-size: 12px;
    color: #666;
}

.service-bar {
    height: 6px;
    background: #f0f0f0;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 4px;
}

.service-bar-fill {
    height: 100%;
    background: #4caf50;
}

.service-count {
    font-size: 11px;
    color: #777;
}

.no-tasks {
    color: #777;
    font-style: italic;
    text-align: center;
    padding: 15px;
}

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

@media (max-width: 768px) {
    .dashboard-content {
        flex-direction: column;
    }
    
    .top-section {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    }
}
</style>

<?php require 'serviceproviderFooter.view.php' ?>
