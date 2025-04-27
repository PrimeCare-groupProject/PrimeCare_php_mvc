<div class="dashboard-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px; background: #f5f7fa; font-family: 'Outfit' , san-serif;">

    <div class="dashboard-card" style="flex: 1 1 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center;">
        <div class="dashboard-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Salary Trend</div>
        <canvas id="salaryChart" style="max-width: 100%; height: 250px;"></canvas>
    </div>

    <div class="dashboard-card" style="flex: 1 1 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center;">
        <div class="dashboard-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Property Assignment</div>
        <canvas id="propertyChart" style="max-width: 100%; height: 250px;"></canvas>
    </div>

    <div class="dashboard-card" style="flex: 1 1 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center;">
        <div class="dashboard-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Pre-Inspection Results</div>
        <canvas id="inspectionChart" style="max-width: 100%; height: 250px;"></canvas>
    </div>
</div>

<script>
    const salaryChartData = <?= json_encode($salaryChartData) ?>;
    const propertyAssignmentData = <?= json_encode($propertyAssignmentData) ?>;
</script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salaryChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: salaryChartData.map(item => item.month),
            datasets: [{
                label: 'Salary Received',
                data: salaryChartData.map(item => item.amount),
                borderColor: 'green',
                backgroundColor: 'lightgreen',
                fill: true,
                tension: 0.4
            }]
        }
    });
</script>

<script>
    const pctx = document.getElementById('propertyChart').getContext('2d');
    new Chart(pctx, {
        type: 'pie',
        data: {
            labels: ['Pending', 'Approved'],
            datasets: [{
                label: 'Property Status',
                data: [
                    propertyAssignmentData.pending, 
                    propertyAssignmentData.approved
                ],
                backgroundColor: ['orange', 'green'],
            }]
        }
    });
</script>

<script>
    const ictx = document.getElementById('inspectionChart').getContext('2d');
    new Chart(ictx, {
        type: 'doughnut',
        data: {
            labels: ['Pass', 'Failed'],
            datasets: [{
                label: 'Pre-inspection Results',
                data: [
                    propertyAssignmentData.pre_inspection_pass, 
                    propertyAssignmentData.pre_inspection_failed
                ],
                backgroundColor: ['blue', 'red'],
            }]
        }
    });
</script>
