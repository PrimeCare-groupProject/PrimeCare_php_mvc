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
                <h1><span style="color: var(--primary-color);">Advance Money Payslip: </span> <?= $property->name ?></h1>
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
                <label>Transaction ID</label>
                <span><?= $payment['order_id'] ?></span>
            </div>
        </div>
    </div>

    <div>
        <form method="post" action="https://sandbox.payhere.lk/pay/checkout">
            <div class="owner-addProp-form">

                <input type="hidden" name="merchant_id" value="<?= $merchant_id ?>">
                <input type="hidden" name="return_url" value="<?= $payment['return_url'] ?>">
                <input type="hidden" name="cancel_url" value="<?= $payment['cancel_url'] ?>">
                <input type="hidden" name="notify_url" value="">
                <input type="hidden" name="order_id" value="<?= $payment['order_id'] ?>">


                <label class="input-label">Payment Name</label>
                <input type="text" name="items" value="<?= $payment['item'] ?>" class="input-field" readonly>

                <input type="hidden" name="currency" value="<?= $payment['currency'] ?>" readonly>

                <label class="input-label">Amount</label>
                <input type="text" name="amount" value="<?= $payment['amount'] ?>" class="input-field" style="border: 1px solid var(--primary-color);" readonly>

                <input type="hidden" name="hash" value="<?= $hash ?>">

                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">First Name</label>
                        <input type="text" name="first_name" value="<?= $payment['fname'] ?>" class="input-field" readonly>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Last Name</label>
                        <input type="text" name="last_name" value="<?= $payment['lname'] ?>" class="input-field" readonly>
                    </div>
                </div>
                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Email</label>
                        <input type="text" name="email" value="<?= $payment['email'] ?>" class="input-field" readonly>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">Contact Number</label>
                        <input type="text" name="phone" value="<?= $payment['phone'] ?>" class="input-field" readonly>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-group-group">
                        <label class="input-label">Address</label>
                        <input type="text" name="address" value="<?= $payment['address'] ?>" class="input-field" readonly>
                    </div>
                    <div class="input-group-group">
                        <label class="input-label">City</label>
                        <input type="text" name="city" value="<?= $payment['city'] ?>" class="input-field" readonly>
                    </div>
                </div>

                <input type="hidden" name="country" value="Sri Lanka" class="input-field" readonly>

                <button type="submit" class="primary-btn" style="align-self: center;">Pay Now</button>
            </div>
        </form>
    </div>
</div>
