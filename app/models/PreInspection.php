<?php
// Property class
class PreInspection
{
    use Model;

    protected $table = 'pre_inspection';
    protected $order_column = 'pre_inspection_id';
    protected $allowedColumns = [
        'pre_inspection_id',
        'agent_id',
        'property_id',
        'provided_details',
        'title_deed',
        'utility_bills',
        'owner_id_copy',
        'lease_agreement',
        'property_condition',
        'Maintenance_issues',
        'owner_present',
        'notes',
        'recommendation',
        'created_at',
        'updated_at'
    ];

    public $errors = [];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

}
