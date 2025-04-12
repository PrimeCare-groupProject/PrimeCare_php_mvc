<?php
$merchant_id = MERCHANT_ID;
$merchant_secret = MERCHANT_SECRET;
$order_id = uniqid("ORDER_");
$_SESSION['order_id'] = $order_id; // Store order ID in session for later use
$amount = 1000.00;
$_SESSION['amount'] = $amount; // Store amount in session for later use 
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
<form method="post" action="https://sandbox.payhere.lk/pay/checkout">
    <input type="hidden" name="merchant_id" value="<?= $merchant_id ?>">
    <!-- <input type="hidden" name="return_url" value="http://localhost/php_mvc_backend/public/Payment/success"> -->
    <input type="hidden" name="return_url" value="http://localhost/php_mvc_backend/public/Payment/success?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>">
    <input type="hidden" name="cancel_url" value="http://localhost/php_mvc_backend/public/Payment/cancel?order_id=<?= $order_id ?>&amount=<?= $amount_formatted ?>">
    <input type="hidden" name="notify_url" value="http://localhost/php_mvc_backend/public/Payment/notify">
    <input type="hidden" name="order_id" value="<?= $order_id ?>">
    <input type="hidden" name="items" value="Test Item">
    <input type="hidden" name="currency" value="<?= $currency ?>">
    <input type="hidden" name="amount" value="<?= $amount_formatted ?>">
    <input type="hidden" name="hash" value="<?= $hash ?>">
    <input type="hidden" name="first_name" value="John">
    <input type="hidden" name="last_name" value="Doe">
    <input type="hidden" name="email" value="john.doe@example.com">
    <input type="hidden" name="phone" value="0771234567">
    <input type="hidden" name="address" value="Colombo">
    <input type="hidden" name="city" value="Colombo">
    <input type="hidden" name="country" value="Sri Lanka">
    <button type="submit">Pay Now</button>
</form>