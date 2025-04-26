<?php

class DeleteRequests
{
    use Model;

    protected $table = 'deleteRequests';
    protected $order_column = "property_id";
    protected $allowedColumns = [
        'property_id',
        'owner_id',
        'agent_id',
        'request_status',
        'created_at'
    ];

    public $errors = [];

}
