<?php
if (isset($_SESSION['customerView']) && $_SESSION['customerView'] == true) {
    require_once 'customer/customerHeader.view.php';
} else {
    $type = $_SESSION['user']->user_lvl;
    if ($type == 0) {
        require_once 'customer/customerHeader.view.php';
    } else if ($type == 1) {
        require_once 'owner/ownerHeader.view.php';
    } else if ($type == 2) {
        require_once 'serviceProvider/serviceProviderHeader.view.php';
    } else if ($type == 3) {
        require_once 'agent/agentHeader.view.php';
    } else if ($type == 4) {
        require_once 'manager/managerHeader.view.php';
    }
}
?>



<?php
$merchant_id = MERCHANT_ID;
$merchant_secret = MERCHANT_SECRET;
$order_id = $payment['order_id'];
$amount = $payment['amount'];
$currency = "LKR";
$amount_formatted = number_format($amount, 2, '.', '');

$hash = strtoupper(
    md5(
        $merchant_id .
            $order_id .
            $amount_formatted .
            $currency .
            strtoupper(md5($merchant_secret))
    )
);
?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/propertyListing/propertyunitowner/<?= $property->property_id ?>'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Payments : <span style="color: var(--green-color);"><?= $payment['payment_name'] ?></span></h2>
</div>

<?php require_once APPROOT . '/reports/advancePaymentProperty.report.php'; ?>



<?php
$type = $_SESSION['user']->user_lvl;
if ($type == 0) {
    require_once 'customer/customerFooter.view.php';
}
if ($type == 1) {
    require_once 'owner/ownerFooter.view.php';
} else if ($type == 2) {
    require_once 'serviceProvider/serviceProviderFooter.view.php';
} else if ($type == 3) {
    require_once 'agent/agentFooter.view.php';
} else if ($type == 4) {
    require_once 'manager/managerFooter.view.php';
}
// show($user);
?>