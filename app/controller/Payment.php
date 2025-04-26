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


        $merchant_id = $_POST['merchant_id'];
        $order_id = $_POST['order_id'];
        $payhere_amount = $_POST['payhere_amount'];
        $payhere_currency = $_POST['payhere_currency'];
        $status_code = $_POST['status_code'];
        $md5sig_received = $_POST['md5sig'];

        $merchant_secret = MERCHANT_SECRET; 


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
    

    /* New api method for PayHere integration */
    public function api($action = null) 
    {
        // Disable all error reporting and output buffering
        error_reporting(0);
        ini_set('display_errors', 0);
        while(ob_get_level()) ob_end_clean();
        
        // Essential headers to prevent caching and set content type
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Create a log for debugging
        $log_file = ROOTPATH . '/payhere_api.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - API accessed: ' . $action . PHP_EOL, FILE_APPEND);
        
        // Get raw input
        $json_data = file_get_contents('php://input');
        $request = json_decode($json_data, true) ?: [];
        file_put_contents($log_file, "Request data: " . json_encode($request) . PHP_EOL, FILE_APPEND);
        
        // Basic test endpoint
        if ($action == 'test') {
            echo json_encode([
                'success' => true,
                'message' => 'API is working correctly',
                'timestamp' => time()
            ]);
            exit;
        }
        
        if ($action == 'prepare' && isset($request['service_id'])) {
            $serviceId = $request['service_id'];
            
            try {
                if (!isset($_SESSION['user'])) {
                    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
                    exit;
                }
                
                // Get the service details
                require_once ROOTPATH . '/app/models/ExternalService.php';
                $externalService = new ExternalService();
                $service = $externalService->first(['id' => $serviceId]);
                
                if (!$service) {
                    echo json_encode(['success' => false, 'message' => 'Service not found']);
                    exit;
                }
                
                // Calculate total amount
                $usual_cost = ($service->cost_per_hour ?? 0) * ($service->total_hours ?? 0);
                $additional_charges = $service->additional_charges ?? 0;
                $service_cost = $usual_cost + $additional_charges;
                $service_charge = $service_cost * 0.10; // 10% service charge
                $total_amount = $service_cost + $service_charge;
                
                // Create payment record SAFELY with error handling
                try {
                    require_once ROOTPATH . '/app/models/ServicePayment.php';
                    $servicePayment = new ServicePayment();
                    
                    // Generate invoice number
                    $invoice_number = $servicePayment->generateInvoiceNumber();
                    
                    // Create payment data array
                    $paymentData = [
                        'service_id' => $serviceId,
                        'amount' => $total_amount,
                        'payment_date' => date('Y-m-d H:i:s'),
                        'invoice_number' => $invoice_number,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    $paymentId = $servicePayment->createPayment($paymentData);
                    
                    if (!$paymentId) {
                        throw new Exception('Payment creation failed - no payment ID returned');
                    }
                    
                } catch (Exception $e) {
                    // Log the database error
                    file_put_contents($log_file, "Database error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                    echo json_encode([
                        'success' => false, 
                        'message' => 'Database error: ' . $e->getMessage()
                    ]);
                    exit;
                }
                
                // Get user details
                $user = $_SESSION['user'];
                $names = explode(' ', trim($user->fname . ' ' . $user->lname));
                $firstName = $names[0] ?? '';
                $lastName = (count($names) > 1) ? end($names) : '';
                
                // Create response data with MOCK payment ID if real one not available
                $response = [
                    'success' => true,
                    'merchant_id' => '1221145',
                    'return_url' => ROOT . "/customer/payhereReturn/" . ($paymentId ?? 'temp-'.time()) . "/{$serviceId}/1",
                    'cancel_url' => ROOT . "/customer/payhereCancel/" . ($paymentId ?? 'temp-'.time()) . "/{$serviceId}/1", 
                    'notify_url' => ROOT . "/customer/payhereNotify",
                    'order_id' => $invoice_number,
                    'amount' => number_format($total_amount, 2, '.', ''),
                    'currency' => 'LKR',
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $request['email'] ?? $user->email,
                    'phone' => $request['phone'] ?? $user->phone,
                    'address' => $user->address ?? 'Not provided',
                    'city' => $user->city ?? 'Not provided',
                    'country' => 'Sri Lanka',
                    'delivery_address' => $service->property_address ?? 'Not applicable',
                    'delivery_city' => 'Not applicable',
                    'delivery_country' => 'Sri Lanka',
                    'service_id' => $serviceId,
                    'user_id' => $user->pid
                ];
                
                file_put_contents($log_file, "Sending response: " . json_encode($response) . PHP_EOL, FILE_APPEND);
                echo json_encode($response);
                exit;
                
            } catch (Exception $e) {
                file_put_contents($log_file, "Error: " . $e->getMessage() . PHP_EOL, FILE_APPEND);
                file_put_contents($log_file, $e->getTraceAsString() . PHP_EOL, FILE_APPEND);
                
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage()
                ]);
                exit;
            }
        }
        
        // Default response for unknown actions
        echo json_encode([
            'success' => false,
            'message' => 'Unknown API action'
        ]);
        exit;
    }
}
