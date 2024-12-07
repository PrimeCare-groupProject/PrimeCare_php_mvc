<?php require 'serviceproviderHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>Earnings</h2>
    <div class="flex-bar">
        <div class="search-container">
            <input type="text" class="search-input" placeholder="Search Anything...">
            <button class="search-btn">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search" class="small-icons">
            </button>
        </div>
    </div>
</div>

<div class="chart-container" style="width: 90%; max-width: 800px; margin: 30px auto; padding: 20px; background: #ffffff; border-radius: 10px; box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; margin-bottom: 20px; font-family: 'Roboto', sans-serif; font-size: 24px; color: #444;">Earnings Overview (LKR)</h2>
    <canvas id="earningsChart" style="max-height: 400px;"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // PHP Chart Data
    const chartData = <?= json_encode($chartData) ?>;

    // Process Data for Chart.js
    const labels = chartData.map(data => data.name);
    const data = chartData.map(data => data.totalEarnings);

    // Create Chart
    const ctx = document.getElementById('earningsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Earnings (LKR)',
                data: data,
                backgroundColor: [
                    'rgba(255, 235, 59, 0.9)', 
                    'rgba(255, 241, 118, 0.9)', 
                    'rgba(255, 249, 196, 0.9)', 
                    'rgba(255, 213, 79, 0.9)', 
                    'rgba(255, 238, 88, 0.9)'
                ],
                borderColor: [
                    'rgba(255, 235, 59, 1)', 
                    'rgba(255, 241, 118, 1)', 
                    'rgba(255, 249, 196, 1)', 
                    'rgba(255, 213, 79, 1)', 
                    'rgba(255, 238, 88, 1)'
                ],
                borderWidth: 1,
                borderRadius: 8,
                hoverBackgroundColor: [
                    'rgba(255, 235, 59, 1)', 
                    'rgba(255, 241, 118, 1)', 
                    'rgba(255, 249, 196, 1)', 
                    'rgba(255, 213, 79, 1)', 
                    'rgba(255, 238, 88, 1)'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#555',
                        font: {
                            family: 'Roboto',
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#444',
                    titleFont: { size: 14, weight: 'bold', family: 'Roboto' },
                    bodyFont: { size: 12, family: 'Roboto' },
                    callbacks: {
                        label: function (context) {
                            return `Earnings: LKR ${context.raw.toLocaleString()}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#666',
                        font: { family: 'Roboto', size: 12, weight: '500' }
                    },
                    title: {
                        display: true,
                        text: 'Services',
                        font: { size: 16, family: 'Roboto', weight: 'bold' },
                        color: '#444'
                    }
                },
                y: {
                    grid: { color: 'rgba(200, 200, 200, 0.5)' },
                    ticks: {
                        color: '#666',
                        font: { family: 'Roboto', size: 12, weight: '500' },
                        callback: function (value) {
                            return 'LKR ' + value.toLocaleString(); // Adds "LKR" with formatted numbers
                        }
                    },
                    title: {
                        display: true,
                        text: 'Earnings (LKR)',
                        font: { size: 16, family: 'Roboto', weight: 'bold' },
                        color: '#444'
                    }
                }
            },
            layout: {
                padding: {
                    left: 15,
                    right: 15,
                    top: 15,
                    bottom: 15
                }
            }
        }
    });
</script>

<?php require 'serviceproviderFooter.view.php' ?>
