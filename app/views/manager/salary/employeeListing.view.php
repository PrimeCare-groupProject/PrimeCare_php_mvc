<?php require_once __DIR__ . '\..\managerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2>Salary : <span style="color: var(--dark-grey-color);"><?= date('Y-m') ?></span></h2>
    <div class="flex-bar">
        <form class="search-container" method="GET">
            <input
                type="text"
                class="search-input"
                name="searchterm"
                id="searchInput"
                placeholder="Search contact message ...">
            <button class="search-btn" type="submit">
                <img src="<?= ROOT ?>/assets/images/search.png" alt="Search Icon" class="small-icons">
            </button>
        </form>
    </div>
</div>

<div>

    <div class="content_wrapper" id='formContainer'>
        <div class="employee-details-container" style="width: 100%; overflow-x: auto;">
            <table id="agentTable" class="listing-table-for-customer-payments" style="width: 100%; table-layout: auto; border-collapse: collapse;">
                <?php if (isset($agents) && is_array($agents) && count($agents) > 0) : ?>
                    <thead>
                        <tr>
                            <th class='first' style='width: 5%; text-align: center;'>Image</th>
                            <th style='width: 20px; text-align: center;' id="date-header">ID</th>
                            <th style='width: 180px; text-align: center;'>Name</th>
                            <th style="width: 40px; text-align: center;">Assigns</th>
                            <th style='max-width: 60px; text-align: center;'>Allovance</th>
                            <th style='max-width: 30%; text-align: center;'>Salary</th>
                            <th style='min-width: 60px; text-align: center;'>Month</th>
                            <th style='width: 35px; text-align: center;'>Status</th>
                            <th class='last' style="min-width: 100px; text-align: center;"><span class="small-btn grey" onclick="window.location.href='<?= ROOT ?>/dashboard/managementhome/payAll'">Pay</span></th>
                        </tr>
                    </thead>
                <?php endif ?>

                <tbody>
                    <?php
                    if (isset($agents) && is_array($agents) && count($agents) > 0) {
                        foreach ($agents as $agent) { ?>
                        <?php $paidState = getPaidStatusOfAgent($agent->pid, $agent->assign_month); ?>
                            <tr onclick='showUserDetailBox(this)'>
                                <td class='first'><img class='header-profile-picture' style='margin:0px' src='<?= get_img($agent->image_url) ?>'></td>
                                <td  style="text-align: center;"><input type='text' name='id' value='<?= $agent->pid ?>' disabled></td>
                                <td style="text-align: center;"><input type='text' name='name' value='<?= $agent->fname . " " . $agent->lname ?>' disabled></td>
                                <td style="text-align: center;"><input type='text' value='<?= $agent->property_count ?>' disabled></td>
                                <td style="text-align: center;"><input type='text' name='nic' value='<?= $agent->property_count * AGENT_INCREMENT ?>' disabled></td>
                                <td style="text-align: center;"><input type='salary' name='salary' value='<?= AGENT_BASIC_SALARY + $agent->property_count * AGENT_INCREMENT ?>' disabled></td>
                                <td style="text-align: center;"><input type='text' name='month' value='<?= $agent->assign_month ?>' disabled></td>
                                <td style="text-align: center;"><input type='text' value='<?= $paidState ? 'Paid' : 'Unpaid' ?>' class="small-btn <?= getPaidStatusOfAgent($agent->pid, $agent->assign_month) ? 'green' : 'orange' ?>" disabled></td>
                                <td class='last' style="text-align: center;">
                                    <?php if(!$paidState): ?>
                                    <span class="small-btn blue" onclick="window.location.href='<?= ROOT ?>/dashboard/managementhome/salaryView/<?= $agent->pid ?>'">Pay</span>
                                    <?php else: ?>
                                    <span style="color: var(--green-color); font-size: 16px; text-align: center;" disabled><i class="fa fa-check"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='9'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.getElementById("searchInput").addEventListener("keyup", function() {
        let input = this.value.toLowerCase();
        let table = document.getElementById("agentTable");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let nameCell = rows[i].querySelector('input[name="name"]');
            if (!nameCell) continue; // skip rows without input (like thead)

            let name = nameCell.value.toLowerCase();
            if (name.includes(input)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
</script>

<?php require_once __DIR__ . '\..\managerFooter.view.php'; ?>