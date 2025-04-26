<?php

class Ledger
{
    use Model;

    protected $table = 'ledger';
    protected $order_column = "id";
    protected $allowedColumns = [
        'transaction_type', // 'rent_income', 'share_income', 'service_payment', 'salary_payment'
        'reference_id', // person_id or property_id
        'reference_type', // 'property', 'person', 'service', 'employee'
        'amount',
        'description',
        'created_at'
    ];

    public $errors = [];

}
