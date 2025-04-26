<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/reports.css">
    <title>Salary Sheet - <?= $agent->assign_month ?></title>
</head>

<body style="font-family: 'Outfit', sans-serif; margin: 0; padding: 0; background-color: #f7f9fc; color: #333; line-height: 1.6;">
    <div style="max-width: 650px; margin: 40px auto; background-color: #fff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); overflow: hidden;">
        <!-- Header Section -->
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

            <div style="width: 100%; padding-top: 10px 0; border-top: 1px solid #eee; margin: 0;">
                <h3 style="color: var(--dark-grey-color); text-align: center; margin-top: 10px; padding: 0;">Pay Sheet</h3>
            </div>
        </div>

        <!-- Employee Information -->
        <div style="padding: 25px 30px; background-color: #f8f9fa; border-bottom: 1px solid #eee;">
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between;">
                <div style="margin-bottom: 15px; flex: 0 0 48%;">
                    <p style="margin: 0; font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Employee Name</p>
                    <p style="margin: 5px 0 0; font-size: 16px; font-weight: 500;"><?= $agent->fname . ' ' . $agent->lname ?></p>
                </div>
                <div style="margin-bottom: 15px; flex: 0 0 48%;">
                    <p style="margin: 0; font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Employee ID</p>
                    <p style="margin: 5px 0 0; font-size: 16px; font-weight: 500;">EMP-<?= $agent->pid ?></p>
                </div>
                <div style="margin-bottom: 15px; flex: 0 0 48%;">
                    <p style="margin: 0; font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Employee Role</p>
                    <p style="margin: 5px 0 0; font-size: 16px; font-weight: 500;"><?= getRole($agent->pid) ?></p>
                </div>
                <div style="margin-bottom: 15px; flex: 0 0 48%;">
                    <p style="margin: 0; font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Position</p>
                    <p style="margin: 5px 0 0; font-size: 16px; font-weight: 500;">Senior Designer</p>
                </div>
                <div style="margin-bottom: 15px; flex: 0 0 48%;">
                    <p style="margin: 0; font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Pay Period</p>
                    <p style="margin: 5px 0 0; font-size: 16px; font-weight: 500;"><?= getMonthRange($agent->assign_month) ?></p>
                </div>
                <div style="margin-bottom: 15px; flex: 0 0 48%;">
                    <p style="margin: 0; font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 600;">Payment Date</p>
                    <p style="margin: 5px 0 0; font-size: 16px; font-weight: 500;"><?= date('Y-m-d') ?></p>
                </div>
            </div>
        </div>

        <!-- Salary Breakdown -->
        <div style="padding: 30px;">
            <div style="display: flex; margin-bottom: 30px;">
                <!-- Earnings Section -->
                <div style="flex: 1; padding-right: 15px;">
                    <div style="margin-bottom: 12px; display: flex; justify-content: space-between;">
                        <span style="font-size: 14px; color: #555;">Basic Salary</span>
                        <span style="font-size: 14px; font-weight: 500;">Rs. <?= AGENT_BASIC_SALARY ?></span>
                    </div>
                    <div style="margin-bottom: 12px; display: flex; justify-content: space-between;">
                        <span style="font-size: 14px; color: #555;">Assigns Allowance</span>
                        <span style="font-size: 14px; font-weight: 500;">Rs. <?= $agent->property_count * AGENT_INCREMENT ?></span>
                    </div>
                    <div style="margin-top: 15px; padding-top: 12px; border-top: 1px dashed #ddd; display: flex; justify-content: space-between;">
                        <span style="font-size: 15px; font-weight: 600; color: #2c3e50;">Total Earnings</span>
                        <span style="font-size: 15px; font-weight: 600; color: #2c3e50;">Rs. <?= AGENT_BASIC_SALARY + $agent->property_count * AGENT_INCREMENT ?></span>
                    </div>
                </div>
            </div>

            <!-- Net Salary Section -->
            <div style="background-color: #f1f8ff; border-radius: 10px; padding: 20px; margin-top: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="margin: 0; font-size: 18px; color: #2c3e50; font-weight: 600;">NET SALARY</h3>
                    <div style="font-size: 22px; font-weight: 700; color: #2c3e50;">Rs. <?= AGENT_BASIC_SALARY + $agent->property_count * AGENT_INCREMENT ?></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="padding: 20px 30px; background-color: #f8f9fa; border-top: 1px solid #eee; font-size: 12px; color: #6c757d; text-align: center;">
            <p style="margin: 0;">This is an electronically generated salary slip and does not require signature.</p>
            <p style="margin: 5px 0 0;">For any questions, please contact primeCare@gmail.com</p>
        </div>
    </div>
</body>

</html>