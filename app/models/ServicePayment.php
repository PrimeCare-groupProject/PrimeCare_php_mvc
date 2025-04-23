<?php

class ServicePayment{
    use Model;

    protected $table = 'service_payments';
    protected $order_column = "payment_id";
    protected $allowedColumns = [
        'payment_id',
        'service_id',
        'person_id',
        'service_provider_id',
        'amount',
        'payment_date',
        'invoice_number',
        'created_at'
    ];

    public $errors = [];
}