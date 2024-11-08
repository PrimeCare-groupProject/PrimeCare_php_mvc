<?php

class Service {
    use Model;

    protected $table = 'services';
    protected $order_column = "service_id";
    protected $allowedColumns = [
        'service_type',
        'date',
        'property_id', 
        'property_name',
        'cost_per_hour',
        'total_hours',
        'status',
        'service_provider_id',
        'service_description'
    ];

    public $errors = [];

    public function validate($data) {
        $this->errors = [];

        // Validate service type
        if (empty($data['service_type'])) {
            $this->errors['service_type'] = 'Service type is required';
        }

        // Validate date
        if (empty($data['date'])) {
            $this->errors['date'] = 'Date is required';
        }

        // Validate property ID
        if (empty($data['property_id']) || !is_numeric($data['property_id'])) {
            $this->errors['property_id'] = 'Valid property ID is required';
        }

        // Validate property name
        if (empty($data['property_name'])) {
            $this->errors['property_name'] = 'Property name is required';
        }

        // Validate cost per hour
        if (empty($data['cost_per_hour']) || !is_numeric($data['cost_per_hour']) || $data['cost_per_hour'] <= 0) {
            $this->errors['cost_per_hour'] = 'Valid cost per hour is required';
        }

        // Validate total hours
        if (empty($data['total_hours']) || !is_numeric($data['total_hours']) || $data['total_hours'] <= 0) {
            $this->errors['total_hours'] = 'Valid total hours is required';
        }

        // Validate status
        $validStatuses = ['Done', 'Pending', 'Ongoing'];
        if (empty($data['status']) || !in_array($data['status'], $validStatuses)) {
            $this->errors['status'] = 'Valid status is required (Done, Pending, or Ongoing)';
        }

        // Service provider ID can be null, but if provided must be numeric
        if (!empty($data['service_provider_id']) && !is_numeric($data['service_provider_id'])) {
            $this->errors['service_provider_id'] = 'Service provider ID must be a valid number';
        }

        return empty($this->errors);
    }
}
