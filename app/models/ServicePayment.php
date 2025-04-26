<?php

class ServicePayment{
    use Model;

    protected $table = 'service_payments';
    protected $order_column = "payment_id";
    protected $allowedColumns = [
        'payment_id',
        'service_id',
        'service_provider_id',
        'amount',
        'payment_date',
        'invoice_number',
        'created_at',
        'serviceType'
    ];

    public $errors = [];
    
    /**
     * Create a payment record with correct serviceType based on service source
     * @param array $data Payment data
     * @return int|bool Payment ID if successful, false if failed
     */
    public function createPayment($data) {
        // Determine service type and provider
        $serviceInfo = $this->determineServiceType($data['service_id']);
        $data['serviceType'] = $serviceInfo['type'];
        if (empty($data['service_provider_id'])) {
            $data['service_provider_id'] = $serviceInfo['provider_id'];
        }
        // Only keep allowed columns
        $filtered = [];
        foreach ($this->allowedColumns as $col) {
            if (isset($data[$col])) $filtered[$col] = $data[$col];
        }
        // Insert the payment
        $result = $this->insert($filtered);
        // Fix: Return last inserted ID if insert succeeded
        if ($result && isset($this->instance) && method_exists($this->instance, 'lastInsertId')) {
            return $this->instance->lastInsertId();
        }
        // Fallback: If $this->instance is not set, try global Database instance
        if ($result && class_exists('Database')) {
            $db = Database::getInstance();
            if (method_exists($db, 'lastInsertId')) {
                return $db->lastInsertId();
            }
        }
        return false;
    }
    
    /**
     * Determine if a service is regular or external and get provider ID
     * @param int $serviceId Service ID
     * @return array Array with 'type' and 'provider_id' keys
     */
    private function determineServiceType($serviceId) {
        // Check if it's a regular service from serviceLog
        $serviceLog = new ServiceLog();
        $regularService = $serviceLog->first(['service_id' => $serviceId]);
        
        if ($regularService) {
            return [
                'type' => 'regular',
                'provider_id' => $regularService->provider_id ?? null
            ];
        }
        
        // Check if it's an external service
        $externalService = new ExternalService();
        $extService = $externalService->first(['id' => $serviceId]);
        
        if ($extService) {
            return [
                'type' => 'external',
                'provider_id' => $extService->service_provider_id ?? null
            ];
        }
        
        // Default if not found
        return [
            'type' => 'unknown',
            'provider_id' => null
        ];
    }
    
    /**
     * Generate an invoice number
     * @return string Generated invoice number
     */
    public function generateInvoiceNumber() {
        return 'INV-'.date('Ymd').'-'.rand(1000, 9999);
    }
}

// $paymentData = [
//     'service_id' => $serviceId,
//     'amount' => $total_amount,
//     'payment_date' => date('Y-m-d H:i:s'),
//     'invoice_number' => $invoice_number,
//     'created_at' => date('Y-m-d H:i:s')
//     // service_provider_id and serviceType are set by createPayment()
// ];