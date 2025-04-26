<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/reports.css">
    <title>Monthly Finance Overview - <?= date('F Y') ?></title>
</head>

<body style="font-family: 'Outfit', sans-serif; margin: 0; padding: 0; background-color: #f7f9fc; color: #333; line-height: 1.6;">
    <div style="max-width: 800px; margin: 40px auto; background-color: #fff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden; padding: 30px;">

        <!-- Header Section -->
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="<?= ROOT ?>/assets/images/logo.png" alt="Company Logo" style="height: 80px;">
            <h2 style="margin: 0;">PrimeCare Management</h2>
            <p style="font-size: 14px; color: #666;">No 9, Marine Drive, Bambalapitiya | primeCare@gmail.com | 011-1234567</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <h3 style="color: var(--primary-color); margin-bottom: 0;">Monthly Finance Overview</h3>
            <p style="font-size: 16px;"><?= date('F Y') ?></p>
        </div>

        <!-- Dashboard Section -->
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center;">

            <?php
            $cards = [
                ['title' => 'Total Rental Payments', 'amount' => $totalRentalPayments, 'color' => '#4CAF50', 'canvasId' => 'rentalPaymentsChart'],
                ['title' => 'Total Share Payments', 'amount' => $totalSharePayments, 'color' => '#2196F3', 'canvasId' => 'sharePaymentsChart'],
                ['title' => 'Total Service Payments', 'amount' => $totalServicePayments, 'color' => '#9C27B0', 'canvasId' => 'servicePaymentsChart'],
                ['title' => 'Total Salary Payments', 'amount' => $totalSalaryPayments, 'color' => '#FF5722', 'canvasId' => 'salaryPaymentsChart'],
            ];
            foreach ($cards as $card) :
            ?>

            <div style="flex: 1 1 300px; background: #fafafa; border-radius: 12px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); padding: 20px; text-align: center;">
                <div style="font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #333;"><?= $card['title'] ?></div>
                <p style="font-size: 18px; margin-bottom: 20px; color: <?= $card['color'] ?>;">Rs <?= number_format($card['amount'] ?? 0, 2) ?></p>
                <canvas id="<?= $card['canvasId'] ?>" style="max-width: 100%; height: 250px;"></canvas>
            </div>

            <?php endforeach; ?>

        </div>

        <!-- Analysis Section -->
        <div style="margin-top: 40px;">
            <h3 style="color: #333;">Summary Analysis</h3>
            <p style="font-size: 16px;">
                During <?= date('F Y') ?>, PrimeCare has recorded a total rental income of <strong>Rs <?= number_format($totalRentalPayments, 2) ?></strong> and share payments totaling <strong>Rs <?= number_format($totalSharePayments, 2) ?></strong>. 
                Service payments contributed an additional <strong>Rs <?= number_format($totalServicePayments, 2) ?></strong> to the revenue stream. 
                Meanwhile, salary disbursements amounted to <strong>Rs <?= number_format($totalSalaryPayments, 2) ?></strong>, indicating the primary operating expense for this period.
            </p>
            <p style="font-size: 16px;">
                Compared to the income generated, the salary expenses represent about <strong><?= round(($totalSalaryPayments / max(1, ($totalRentalPayments + $totalSharePayments + $totalServicePayments))) * 100, 2) ?>%</strong> of total earnings.
                This shows a <?= ($totalSalaryPayments > ($totalRentalPayments + $totalSharePayments + $totalServicePayments)) ? "<strong>potential cashflow issue</strong>" : "<strong>healthy balance between income and expenses</strong>" ?> for the month.
            </p>
        </div>

    </div>

    <!-- Charts -->
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
                    tension: 0.4
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

</body>

</html>
