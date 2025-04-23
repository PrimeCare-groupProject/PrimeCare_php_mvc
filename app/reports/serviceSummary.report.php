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
                    <h1 style="color: var(--primary-color); align-self: center;">External Service Summary</h1>
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
                    <label>Service Provider</label>
                    <span>Provider Name</span>
                </div>
            </div>
        </div>

    <!-- Mekata Ube report ek dpn  -->


    </div>
</body>
</html>