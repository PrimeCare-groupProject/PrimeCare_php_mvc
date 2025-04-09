<?php
// Property class
class agentAssignment
{
    use Model;

    protected $table = 'agent_property';
    protected $order_column = "assign_id";
    protected $allowedColumns = [
        'assign_id',
        'property_id',
        'agent_id',
        'property_status',
        'pre_inspection',
        'created_at',
        'updated_at'
    ];

    public $errors = [];

}
