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
    public $messages = [];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public function isValidForRegister($data){
        $this->errors = [];

        if (isset($data['provided_details']) && $data['provided_details'] == 'False') {
            $this->errors['provided_details'] = 'Provided Details must be true.';
        }
        if (isset($data['title_deed']) && $data['title_deed'] == 'False') {
            $this->errors['title_deed'] = 'Title Deed must be true.';
        }
        if (isset($data['utility_bills']) && $data['utility_bills'] == 'False') {
            $this->errors['utility_bills'] = 'Utility Bills must be true.';
        }
        if (isset($data['owner_id_copy']) && $data['owner_id_copy'] == 'False') {
            $this->errors['owner_id_copy'] = 'Owner ID Copy must be true.';
        }
        if (isset($data['lease_agreement']) && $data['lease_agreement'] == 'False') {
            $this->errors['lease_agreement'] = 'Lease Agreement must be true.';
        }
        if (isset($data['property_condition']) && $data['property_condition'] == 'Poor') {
            $this->errors['property_condition'] = 'Property Condition must be good.';
        }
        if (isset($data['property_condition']) && $data['property_condition'] == 'Average') {
            $this->errors['property_condition'] = 'Property Needs to be Maintained.';
        }
        if (isset($data['recommendation']) && $data['recommendation'] == 'Rejected') {
            $this->errors['recommendation'] = 'Recommendation not be Failed.';
        }
        if(isset($data['recommendation']) && $data['recommendation'] == 'Requires_Fixes'){
            $this->messages['recommendation'] = 'Recommendation is Requires Fixes.';
        }
        //show($this->errors);
        return empty($this->errors);
    }

    public function getValidationMessages() {
        if (empty($this->errors)) {
            return "All validations passed successfully.";
        }

        $messages = "The following issues need to be fixed before submission:\n";
        foreach ($this->errors as $field => $message) {
            $messages .= "- " . ucfirst(str_replace('_', ' ', $field)) . ": $message\n";
        }
        return nl2br($messages); // or just return $messages if used in plain text context
    }

}
