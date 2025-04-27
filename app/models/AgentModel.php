<?php

class AgentModel
{
    use Model;

    protected $table = 'Agent';
    protected $order_column = "pid";
    protected $allowedColumns = [
        'pid',
        'fname',
        'lname',
        'username',
        'email',
        'contact',
        'password',
        'image_url',
        'user_lvl',
        'reset_code',
        'created_date',
        'nic',
        'assign_month',
        'property_count',
        'card_no',
        'card_name',
        'account_no',
        'bank',
        'branch'
    ];

    public $errors = [];

}
