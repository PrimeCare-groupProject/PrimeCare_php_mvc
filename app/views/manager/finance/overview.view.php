<div class="dashboard-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px; background: #f5f7fa; min-height: 100vh; font-family: 'Outfit' , san-serif;">

    <div class="dashboard-card" style="flex: 1 1 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center;">
        <div class="dashboard-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Total Rental Payments</div>
        <p style="font-size: 18px; margin-bottom: 20px; color: #4CAF50;">Rs <?= number_format($totalRentalPayments ?? 0, 2) ?></p>
        <canvas id="rentalPaymentsChart" style="max-width: 100%; height: 250px;"></canvas>
    </div>

    <div class="dashboard-card" style="flex: 1 1 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center;">
        <div class="dashboard-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Total Share Payments</div>
        <p style="font-size: 18px; margin-bottom: 20px; color: #2196F3;">Rs <?= number_format($totalSharePayments ?? 0, 2) ?></p>
        <canvas id="sharePaymentsChart" style="max-width: 100%; height: 250px;"></canvas>
    </div>

    <div class="dashboard-card" style="flex: 1 1 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center;">
        <div class="dashboard-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Total Service Payments</div>
        <p style="font-size: 18px; margin-bottom: 20px; color: #9C27B0;">Rs <?= number_format($totalServicePayments ?? 0, 2) ?></p>
        <canvas id="servicePaymentsChart" style="max-width: 100%; height: 250px;"></canvas>
    </div>

    <div class="dashboard-card" style="flex: 1 1 300px; background: #fff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 20px; text-align: center;">
        <div class="dashboard-title" style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;">Total Salary Payments</div>
        <p style="font-size: 18px; margin-bottom: 20px; color: #FF5722;">Rs <?= number_format($totalSalaryPayments ?? 0, 2) ?></p>
        <canvas id="salaryPaymentsChart" style="max-width: 100%; height: 200px;"></canvas>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rentalLabels = <?= json_encode(array_map(fn($p) => $p->payment_date ?? 'Unknown', $rentalPayments)) ?>;
    const rentalAmounts = <?= json_encode(array_map(fn($p) => (float)($p->amount ?? 0), $rentalPayments)) ?>;

    const sharePaymentLabels = ['Advance Payment', 'Full Payment'];
    const sharePayments = [<?= (float)$totalAdvancePayments ?>, <?= (float)$totalFullPayments ?>];

    const serviceLabels = <?= json_encode(array_map(fn($p) => $p->payment_date ?? 'Unknown', $servicePayments)) ?>;
    const serviceAmounts = <?= json_encode(array_map(fn($p) => (float)($p->amount ?? 0), $servicePayments)) ?>;

    const salaryLabels = <?= json_encode(array_map(fn($p) => $p->paid_month ?? 'Unknown', $salaries)) ?>;
    const salaryAmounts = <?= json_encode(array_map(fn($p) => (float)($p->salary_amount ?? 0), $salaries)) ?>;

    // Rental Payments Bar Chart
    new Chart(document.getElementById('rentalPaymentsChart'), {
        type: 'bar',
        data: {
            labels: rentalLabels,
            datasets: [{
                label: 'Amount (Rs)',
                data: rentalAmounts,
                backgroundColor: '#4CAF50'
            }]
        }
    });

    // Share Payments Pie Chart
    new Chart(document.getElementById('sharePaymentsChart'), {
        type: 'pie',
        data: {
            labels: sharePaymentLabels,
            datasets: [{
                label: 'Amount (Rs)',
                data: sharePayments,
                backgroundColor: ['#2196F3', '#FFC107']
            }]
        }
    });

    // Service Payments Line Chart
    new Chart(document.getElementById('servicePaymentsChart'), {
        type: 'line',
        data: {
            labels: serviceLabels,
            datasets: [{
                label: 'Amount (Rs)',
                data: serviceAmounts,
                backgroundColor: '#9C27B0',
                borderColor: '#9C27B0',
                fill: false,
                tension: 0.3
            }]
        }
    });

    // Salary Payments Bar Chart
    new Chart(document.getElementById('salaryPaymentsChart'), {
        type: 'bar',
        data: {
            labels: salaryLabels,
            datasets: [{
                label: 'Amount (Rs)',
                data: salaryAmounts,
                backgroundColor: salaryLabels.map((_, idx) => ['#FF5722', '#009688', '#3F51B5', '#FFC107'][idx % 4])
            }]
        }
    });
</script>