<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/reports.css">
    <title>Pre-Inspection Report Checklist</title>
    <style>
        * {
            font-family: 'Outfit', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    <div class="GR__report-container">
        <div class="GR__report-header">
            <div class="GR__title-area">
                <div class="GR__logo">
                    <img src="<?= ROOT ?>/assets/images/logo.png" alt="Company Logo">
                </div>
                <div class="GR__company-info">
                    <p>No 9 , Marine drive,
                        <br>Bambalapitiya
                    </p>
                    <p>primeCare@gmail.com <i>✉</i></p>
                    <p>011-1234567 <i>☎</i></p>
                </div>
            </div>

            <div class="GR__title-area">
                <div class="GR__report-title">
                    <h1><span style="color: var(--primary-color);">PRE-INSPECTION</span> : <?= $property->name ?></h1>
                </div>
            </div>

            <div class="GR__meta-info">
                <div class="GR__meta-field">
                    <label>Date</label>
                    <span><?= date('Y-m-d') ?></span>
                </div>
                <div class="GR__meta-field">
                    <label>Property ID</label>
                    <span><?= $property->property_id ?></span>
                </div>
                <div class="GR__meta-field">
                    <label>Agent</label>
                    <span><?= $agent->fname . ' ' . $agent->lname ?></span>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <div style="margin-top: 40px; padding: 0 30px;">
            <h3>Condition Overview</h3>
            <canvas id="conditionChart" height="100"></canvas>
        </div>

        <div style="margin-top: 40px; padding: 0 30px;">
            <h3>Checklist Completion</h3>
            <canvas id="completionChart" height="100"></canvas>
        </div>

        <script>
    // Bar chart for property condition
    const conditionCtx = document.getElementById('conditionChart').getContext('2d');
    new Chart(conditionCtx, {
        type: 'bar',
        data: {
            labels: ['Walls', 'Floors', 'Windows', 'Plumbing', 'Electricity'],
            datasets: [{
                label: 'Condition Rating (out of 10)',
                data: [8, 7, 9, 6, 7],
                backgroundColor: 'rgba(33, 150, 243, 0.6)',
                borderColor: 'rgba(33, 150, 243, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 10
                }
            }
        }
    });

    // Doughnut chart for checklist completion
    const completionCtx = document.getElementById('completionChart').getContext('2d');
    new Chart(completionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Pending'],
            datasets: [{
                data: [80, 20],
                backgroundColor: ['#4CAF50', '#FF5722']
            }]
        },
        options: {
            responsive: true,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>



    </div>
</body>

</html>