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
        
        <div class="confirmation-letter">
            <h2 class="confirmation-title">Owner Property Handover Confirmation</h2>

            <?php if ($property->purpose == 'Rent') : ?>
                <p>
                    I, <strong><?= $property->owner_name ?></strong>, the legal owner of the property located at
                    <strong><?= $property->address ?></strong>, hereby confirm that I am officially handing over the above-mentioned property to
                    <strong>PrimeCare Property Management</strong> for the purpose of <strong>professional rental management and tenant facilitation</strong>.
                </p>

                <p>
                    I authorize PrimeCare to manage all aspects of this property including, but not limited to:
                </p>

                <ul>
                    <li>Tenant screening and placement</li>
                    <li>Rent collection and deposit</li>
                    <li>Property maintenance and repairs</li>
                    <li>Legal documentation and reporting</li>
                    <li>Pre and post-inspections</li>
                </ul>

                <p>
                    I confirm that all details provided regarding the property are accurate and true to the best of my knowledge.
                    I understand and agree that PrimeCare will act on my behalf in managing the rental process of this property in accordance with the terms and policies discussed.
                </p>
            <?php else : ?>
                <p>
                    I, <strong><?= $property->owner_name ?></strong>, the legal owner of the property located at
                    <strong><?= $property->address ?></strong>, hereby confirm that I am officially handing over the above-mentioned property to
                    <strong>PrimeCare Property Management</strong> for the purpose of <strong>property safeguarding and caretaking</strong>.
                </p>

                <p>
                    I authorize PrimeCare to carry out the following responsibilities for my property:
                </p>

                <ul>
                    <li>Regular property inspections and security checks</li>
                    <li>Upkeep of cleanliness and structural safety</li>
                    <li>Monitoring for unauthorized access or damages</li>
                    <li>Emergency response and minor repairs</li>
                    <li>Maintenance reports and communication updates</li>
                </ul>

                <p>
                    I confirm that the property is currently unoccupied and is being handed over solely for protection and supervision purposes.
                    I understand that PrimeCare will not engage in rental activities unless explicitly instructed in the future.
                </p>
            <?php endif; ?>

            <p>
                This handover is effective as of <strong><?= date('Y-m-d') ?></strong>, and remains valid until otherwise revoked in writing by me or in accordance with the management agreement.
            </p>

            <div class="signature-block">
                <div class="signature-line">
                    <span style="display: flex; justify-content: center;"><?= $property->owner_name ?></span>
                    <label style=" border-top: 1px dashed #888; padding-top: 10px; display: flex; justify-content: center;">Owner's Name</label>
                </div>
                <div class="signature-line">
                    <div style="color: var(--white-color);">Signature</div>
                    <label style=" border-top: 1px dashed #888; padding-top: 10px; display: flex; justify-content: center;">Signature</label>
                </div>
                <div class="signature-line">
                    <span style="display: flex; justify-content: center;"><?= date('Y-m-d') ?></span>
                    <label style=" border-top: 1px dashed #888; padding-top: 10px; display: flex; justify-content: center;">Date</label>
                </div>
            </div>
        </div>


</body>

</html>