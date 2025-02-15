<?php

class Services {
    use Model;

    public $table = 'services';
    protected $order_column = "service_id";
    protected $allowedColumns = [
        'service_id',
        'name',
        'cost_per_hour',
        'description' ,
        'service_img'
    ];

    public $errors = [];

    
}
