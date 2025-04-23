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

<?php
function setVisibility($data)
{
    if (!isset($data) || empty($data) || $data == 0) {
        return 'style="display:none;"';
    } else {
        return 'style="display:block;"';
    }
}

?>

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

        <div class="GR__report-section">
            <div class="GR__section-title">
                <h2>PROPERTY DETAILS <span class="GR__section-icon">✓</span></h2>
            </div>

            <div class="GR__property-details">
                <div class="GR__detail-item">
                    <label>Name</label>
                    <p><?= $property->name ?></p>
                </div>
                <div class="GR__detail-item">
                    <label>Type</label>
                    <p><?= $property->type ?></p>
                </div>
            </div>

            <div class="GR__detail-item">
                <label>Description</label>
                <p><?= $property->description ?></p>
            </div>

            <div class="GR__property-details">
                <div class="GR__detail-item" <?= setVisibility($property->address) ?>>
                    <label>Address</label>
                    <p><?= $property->address ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->zipcode) ?>>
                    <label>Zipcode</label>
                    <p><?= $property->zipcode ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->city) ?>>
                    <label>City</label>
                    <p><?= $property->city ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->state_province) ?>>
                    <label>State/Province</label>
                    <p><?= $property->state_province ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->country) ?>>
                    <label>Country</label>
                    <p><?= $property->country ?></p>
                </div>
            </div>
            <div class="GR__property-details">
                <div class="GR__detail-item" <?= setVisibility($property->year_built) ?>>
                    <label>Year Built</label>
                    <p><?= $property->year_built ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->size_sqr_ft) ?>>
                    <label>Size (sq ft)</label>
                    <p><?= $property->size_sqr_ft ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->number_of_floors) ?>>
                    <label>Floors</label>
                    <p><?= $property->number_of_floors ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->units) ?>>
                    <label>Units</label>
                    <p><?= $property->units ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->bedrooms) ?>>
                    <label>Bedrooms</label>
                    <p><?= $property->bedrooms ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->bathrooms) ?>>
                    <label>Bathrooms</label>
                    <p><?= $property->bathrooms ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->kitchen) ?>>
                    <label>Kitchen</label>
                    <p><?= $property->kitchen ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->living_room) ?>>
                    <label>Living Room</label>
                    <p><?= $property->living_room ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->furnished) ?>>
                    <label>Furnished</label>
                    <p><?= $property->furnished ?></p>
                </div>
            </div>
            <div class="GR__property-details" <?= setVisibility($property->furniture_description) ?>>
                <div class="GR__detail-item">
                    <label>Furniture Description</label>
                    <p><?= $property->furniture_description ?></p>
                </div>
            </div>
            <div class="GR__property-details" <?= setVisibility($property->type_of_parking) ?>>
                <div class="GR__detail-item">
                    <label>Parking Type</label>
                    <p><?= $property->type_of_parking ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->parking_slots) ?>>
                    <label>Parking Slots</label>
                    <p><?= $property->parking_slots ?></p>
                </div>
            </div>
            <div class="GR__property-details" <?= setVisibility($property->utilities_included) ?>>
                <div class="GR__detail-item">
                    <label>Utilities Included</label>
                    <p><?= $property->utilities_included ?></p>
                </div>
            </div>
            <div class="GR__property-details" <?= setVisibility($property->security_features) ?>>
                <div class="GR__detail-item">
                    <label>Security Features</label>
                    <p><?= $property->security_features ?></p>
                </div>
            </div>
            <div class="GR__property-details" <?= setVisibility($property->purpose) ?>>
                <div class="GR__detail-item">
                    <label>Purpose</label>
                    <p><?= $property->purpose ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->rental_period) ?>>
                    <label>Rental Period</label>
                    <p><?= $property->rental_period ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->rental_price) ?>>
                    <label>Rental Price</label>
                    <p><?= $property->rental_price ?></p>
                </div>
            </div>
            <div class="GR__property-details">
                <div class="GR__detail-item">
                    <label>Owner Name</label>
                    <p><?= $property->owner_name ?></p>
                </div>
                <div class="GR__detail-item" <?= setVisibility($property->owner_email) ?>>
                    <label>Owner Email</label>
                    <p><?= $property->owner_email ?></p>
                </div>
                <div class="GR__detail-item">
                    <label>Owner Phone</label>
                    <p><?= $property->owner_phone ?></p>
                </div>
            </div>
        </div>

        <div class="GR__report-section">
            <div class="GR__section-title">
                <h2>PRE-INSPECTION DETAILS <span class="GR__section-icon">✓</span></h2>
            </div>

            <div class="GR__form-group">
                <label>Documents Checked:</label>
                <div class="GR__checkbox-group">
                    <div class="GR__checkbox-item">
                        <input type="checkbox" id="title_deed">
                        <label for="title_deed">Title deed</label>
                    </div>
                    <div class="GR__checkbox-item">
                        <input type="checkbox" id="utility_bills">
                        <label for="utility_bills">Utility bills</label>
                    </div>
                    <div class="GR__checkbox-item">
                        <input type="checkbox" id="owner_id">
                        <label for="owner_id">Owner ID copy</label>
                    </div>
                    <div class="GR__checkbox-item">
                        <input type="checkbox" id="lease_agreement">
                        <label for="lease_agreement">Lease agreement</label>
                    </div>
                </div>
            </div>

            <div class="GR__form-group">
                <label>Property condition:</label>
                <div class="GR__radio-group">
                    <div class="GR__radio-item">
                        <input type="radio" id="condition_good" name="condition">
                        <label for="condition_good">Good</label>
                    </div>
                    <div class="GR__radio-item">
                        <input type="radio" id="condition_average" name="condition">
                        <label for="condition_average">Average</label>
                    </div>
                    <div class="GR__radio-item">
                        <input type="radio" id="condition_poor" name="condition">
                        <label for="condition_poor">Poor</label>
                    </div>
                </div>
            </div>

            <div class="GR__form-group">
                <label>Maintenance Issues:</label>
                <textarea rows="10"></textarea>
            </div>

            <div class="GR__form-group">
                <label>Owner present?</label>
                <div class="GR__radio-group">
                    <div class="GR__radio-item">
                        <input type="radio" id="owner_present_yes" name="owner_present">
                        <label for="owner_present_yes">Yes</label>
                    </div>
                    <div class="GR__radio-item">
                        <input type="radio" id="owner_present_no" name="owner_present">
                        <label for="owner_present_no">No</label>
                    </div>
                </div>
            </div>

            <div class="GR__form-group">
                <label>Notes & Observations:</label>
                <textarea rows="10"></textarea>
            </div>

            <div class="GR__form-group">
                <label>✔️ Recommendation:</label>
                <div class="GR__radio-group">
                    <div class="GR__radio-item">
                        <input type="radio" id="rec_approved" name="recommendation">
                        <label for="rec_approved">Approved</label>
                    </div>
                    <div class="GR__radio-item">
                        <input type="radio" id="rec_fixes" name="recommendation">
                        <label for="rec_fixes">Requires Fixes</label>
                    </div>
                    <div class="GR__radio-item">
                        <input type="radio" id="rec_rejected" name="recommendation">
                        <label for="rec_rejected">Rejected</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="GR__report-footer">
            <div>primeCare@gmail.com</div>
            <div>All rights reserved.</div>
        </div>
    </div>
</body>

</html>