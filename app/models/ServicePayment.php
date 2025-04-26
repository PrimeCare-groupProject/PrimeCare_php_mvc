<?php

class ServicePayment {
    use Model;

    protected $table = 'service_payments';
    protected $order_column = "payment_id";
    protected $allowedColumns = [
        'payment_id',
        'service_id',
        'amount',
        'payment_date',
        'invoice_number',
        'created_at',
        'service_provider_id',
        'serviceType',
        'person_id' 
    ];
    
    /**
     * Create a new payment record
     */
    public function createPayment($data) {
        // Make sure we're including a person_id from the current session
        if (!isset($data['person_id']) && isset($_SESSION['user']->pid)) {
            $data['person_id'] = $_SESSION['user']->pid;
        }
        
        try {
            return $this->insert($data);
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Payment creation error: " . $e->getMessage());
            return false;
        }
    }
}