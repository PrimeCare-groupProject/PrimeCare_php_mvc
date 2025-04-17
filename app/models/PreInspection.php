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

    public function isValidForRegister(){
        $this->errors = [];

        if(isset($this->provided_details) && $this->provided_detaile == 'False'){
            $this->errors['provided_details'] = 'Provided Details is Must be true.';
        }
        if(isset($this->title_deed) && $this->title_deed == 'False'){
            $this->errors['title_deed'] = 'Title Deed is Must be true.';
        }
        if(isset($this->utility_bills) && $this->utility_bills == 'False'){
            $this->errors['utility_bills'] = 'Utility Bills is Must be true.';
        }
        if(isset($this->owner_id_copy) && $this->owner_id_copy == 'False'){
            $this->errors['owner_id_copy'] = 'Owner ID Copy is Must be true.';
        }
        if(isset($this->lease_agreement) && $this->lease_agreement == 'False'){
            $this->errors['lease_agreement'] = 'Lease Agreement is Must be true.';
        }
        if(isset($this->property_condition) && $this->property_condition == 'Bad'){
            $this->errors['property_condition'] = 'Property Condition is Must be true.';
        }
        if(isset($this->recommendation) && $this->recommendation != 'Approved'){
            $this->errors['recommendation'] = 'Recommendation is Must be true.';
        }
        return empty($this->errors);
    }

}
