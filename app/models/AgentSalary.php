<?php

class AgentSalary
{
    use Model;

    protected $table = 'agent_salary';
    protected $order_column = "transaction_id";
    protected $allowedColumns = [
        'agent_id',
        'month'
    ];

    public $errors = [];

}