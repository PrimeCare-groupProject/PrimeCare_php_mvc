<?php

class Services {
    use Model;

    protected $table = 'services';
    protected $order_column = "service_id";
    protected $allowedColumns = [
        'service_id',
        'name',
        'cost_per_hour',
        'description' 
    ];

    public $errors = [];

    
}
