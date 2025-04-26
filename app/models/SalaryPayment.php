<?php

class SalaryPayment{
    use Model;

    protected $table = 'salary_payments';
    protected $order_column = "payment_id";
    protected $allowedColumns = [
        'payment_id',
        'employee_id',
        'salary_amount',
        'payment_date',
        'paid_month',
        'created_at',
        'person_id'
    ];

    public $errors = [];
}