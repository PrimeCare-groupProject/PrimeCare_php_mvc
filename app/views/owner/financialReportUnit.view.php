<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="flex-bar-space-between-row">
        <div class="left-content">
            <a href="<?= ROOT ?>/property/propertylisting"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></a>
            <div>
                <h2>Property Name</h2>
                <p><span>Maintained By: </span>Agent's Name</p>
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
            <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Tenant">

            <a href="#" class="menu-item">
                Contact
            </a>

        </div>

        <div class="savings-card">
            <div class="card-header">
                <span>Tenants</span>
            </div>
            <div class="tenants-list">
                <div class="tenant">
                    <div class="tenant-avatar">
                        <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Tenant Avatar" />
                    </div>
                    <div class="tenant-info">
                        <div class="tenant-main">
                            <h4>John Doe</h4>
                        </div>
                        <div class="tenant-details">
                            <div class="detail-item">
                                <span>Since: Jan 2024</span>
                            </div>
                            <div class="detail-item">
                                <span>Duration: 6 months</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tenant">
                    <div class="tenant-avatar">
                        <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Tenant Avatar" />
                    </div>
                    <div class="tenant-info">
                        <div class="tenant-main">
                            <h4>John Doe</h4>
                        </div>
                        <div class="tenant-details">
                            <div class="detail-item">
                                <span>Since: Jan 2024</span>
                            </div>
                            <div class="detail-item">
                                <span>Duration: 6 months</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tenant">
                    <div class="tenant-avatar">
                        <img src="<?= ROOT ?>/assets/images/serPro1.png" alt="Tenant Avatar" />
                    </div>
                    <div class="tenant-info">
                        <div class="tenant-main">
                            <h4>John Doe</h4>
                        </div>
                        <div class="tenant-details">
                            <div class="detail-item">
                                <span>Since: Jan 2024</span>
                            </div>
                            <div class="detail-item">
                                <span>Duration: 6 months</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <div class="stats-cards">
            <div class="stat-card earnings">
                <h3 class="positive">Earnings</h3>
                <div class="stat-amount">Rs. 32000.00</div>
                <div class="stat-change">
                    <span>since yyyy-mm-dd</span>
                </div>
            </div>

            <div class="stat-card spendings">
                <h3 class="negative">Spendings</h3>
                <div class="stat-amount">Rs. 5300.00</div>
                <div class="stat-change">
                    <span class="change-amount">10 services</span>
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
                    </div>
                    <select class="period-selector">
                        <option>Yearly</option>
                        <option>Monthly</option>
                    </select>
                </div>
            </div>
            <canvas id="statisticsChart"></canvas>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Data
        const chartData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
            earnings: [700, 850, 400, 736.4, 950, 800],
            spendings: [350, 550, 180, 350, 650, 450]
        };

        // Initialize Chart
        const ctx = document.getElementById('statisticsChart').getContext('2d');

        // Set a fixed height for the chart canvas
        ctx.canvas.style.height = '400px'; // Set a fixed height
        ctx.canvas.style.width = '100%'; // Make width responsive

        const statisticsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                        label: 'Earnings',
                        data: chartData.earnings,
                        backgroundColor: '#FCA311',
                        borderRadius: 8,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Spendings',
                        data: chartData.spendings,
                        backgroundColor: '#f9c570d1',
                        borderRadius: 8,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // This allows us to control height
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
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + context.raw;
                            }
                        }
                    }
                }
            }
        });

        // Remove the problematic resize function
        // Instead, add CSS to control the statistics card height

        // Period selector change handler
        document.querySelector('.period-selector').addEventListener('change', function(e) {
            // Handle period change (you can update the chart data here)
            console.log('Period changed to:', e.target.value);
        });
    });
</script>

<?php require_once 'ownerFooter.view.php'; ?>