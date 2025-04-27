<div style="width: 100%; height: calc(95vh - 200px); overflow-y: auto; overflow-x: hidden; box-sizing: border-box;">
    <div class="financial-details-container">
        <table class="listing-table-for-salary-payments">
            <thead>
                <tr>
                    <th class="extra-space sortable first">Date</th>
                    <th>Name</th>
                    <th>Month</th>
                    <th class="sortable">Amount</th>
                    <th class="extra-space"></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($salary) {
                    foreach ($salary as $salaryItem): ?>
                        <tr data-href="">
                            <td><?= date('Y-m-d', strtotime($salaryItem->created_at)) ?></td>
                            <td><?= getUserName($salaryItem->employee_id) ?></td>
                            <td><?= $salaryItem->paid_month ?></td>
                            <td><?= $salaryItem->salary_amount ?></td>
                            <td class="extra-space"><span class="small-btn blue" style="display: inline-block;" onclick="window.location.href='<?= ROOT ?>/dashboard/finance/payslip?month=<?= $salaryItem->paid_month ?>'">Pay Slip <i class="fa fa-download" style="font-size: 12px;"></i></span></td>
                        </tr>
                <?php endforeach;
                } ?>
            </tbody>
        </table>
    </div>
</div>

