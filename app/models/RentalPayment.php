<?php

class RentalPayment{
    use Model;

    protected $table = 'rental_payments';
    protected $order_column = "payment_id";
    protected $allowedColumns = [
        'payment_id',
        'person_id',
        'property_id',
        'amount',
        'payment_date',
        'rental_period_start',
        'rental_period_end',
        'transaction_id',
        'created_at'
    ];

    public $errors = [];
}