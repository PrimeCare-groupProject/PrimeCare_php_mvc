<div style="width: 100%; height: calc(95vh - 200px); overflow-y: auto; overflow-x: hidden; box-sizing: border-box;">
    <div class="financial-details-container">
        <table class="listing-table-for-salary-payments">
            <thead>
                <tr>
                    <th class="extra-space sortable first">Date</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th class="sortable">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($salaries) {
                    foreach ($salaries as $salaryItem): ?>
                        <tr data-href="">
                            <td><?= date('Y-m-d', strtotime($salaryItem->created_at)) ?></td>
                            <td><?= $salaryItem->employee_id ?></td>
                            <td><?= getUserName($salaryItem->employee_id) ?></td>
                            <td><?= $salaryItem->salary_amount ?></td>
                        </tr>
                <?php endforeach;
                } ?>
            </tbody>
        </table>
    </div>
</div>

