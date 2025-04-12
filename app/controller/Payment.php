<?php
defined('ROOTPATH') or exit('Access denied');

class Payment
{
    use controller;

    public function index()
    {
        echo 'just index';
    }

    public function checkout()
    {
        // Load view with form
        $this->view('testPayment');
    }

    public function success($a = '', $b = '', $c = '')
    {
        $transaction_id = $_GET['order_id'] ?? null;
        $amount = $_GET['amount'] ?? null;

        if( $a == 'salary') {
            // Handle test case
            echo "Payment success with test case!";
        } elseif ($a == 'rental'){
            echo "Payment success with rental case!";
        } elseif ($a == 'share'){
            echo "Payment success with share case!";
        }elseif ($a == 'service'){
            echo "Payment success with service case!";
        } else {
            // Handle normal case
            echo "Payment success! Transaction ID: $transaction_id, Amount: $amount";
        }
    }


    public function cancel($a = '', $b = '')
    {
        $transaction_id = $_GET['order_id'] ?? null;
        $amount = $_GET['amount'] ?? null;

        if( $a == 'salary') {
            // Handle test case
            echo "Payment failed with test case!";
        } elseif ($a == 'rental'){
            echo "Payment failed with rental case!";
        } elseif ($a == 'share'){
            echo "Payment failed with share case!";
        }elseif ($a == 'service'){
            echo "Payment failed with service case!";
        } else {
            // Handle normal case
            echo "Payment failed! Transaction ID: $transaction_id, Amount: $amount";
        }
    }

    public function notify()
    {
        // Print POST data for debugging
        file_put_contents("notify_log.txt", print_r($_POST, true), FILE_APPEND);

        // PayHere sends POST data here
        // You MUST validate it using hash (more on that below)
        // and update payment status in DB
        $merchant_id = $_POST['merchant_id'];
        $order_id = $_POST['order_id'];
        $payhere_amount = $_POST['payhere_amount'];
        $payhere_currency = $_POST['payhere_currency'];
        $status_code = $_POST['status_code'];
        $md5sig_received = $_POST['md5sig'];

        $merchant_secret = MERCHANT_SECRET; // from PayHere dashboard



        $amount_formatted = number_format($payhere_amount, 2, '.', '');


        $local_md5sig = strtoupper(md5(
            $merchant_id . $order_id . $amount_formatted . $payhere_currency . $status_code . strtoupper(md5($merchant_secret))
        ));

        if ($local_md5sig === $md5sig_received && $status_code == 2) {
            // Payment successful, update DB
            show('Payment success boooyah oooo!');
        } else {
            // Payment failed or invalid data
            show('Payment failed or invalid data!');
        }
    }
}
