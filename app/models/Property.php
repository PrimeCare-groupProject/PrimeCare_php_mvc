<?php
// Property class
class Property
{
    use Model;

    protected $table = 'propertyTable';
    protected $order_column = "property_id";
    protected $allowedColumns = [
        'name',
        'type',
        'description',

        'address',
        'zipcode',
        'city',
        'state_province',
        'country',

        'year_built',
        'size_sqr_ft',
        'number_of_floors',
        'floor_plan',

        'units',
        'bedrooms',
        'bathrooms',
        'kitchen',
        'living_room',

        'furnished',
        'furniture_description',

        'parking',
        'parking_slots',
        'type_of_parking',

        'utilities_included',
        'additional_utilities',
        'additional_amenities',
        'security_features',

        'purpose',
        'rental_period',
        'rental_price',

        'owner_name',
        'owner_email',
        'owner_phone',
        'additional_contact',

        'special_instructions',
        'legal_details',

        'status',
        'person_id',
        'agent_id',
        'duration',
        'start_date',
        'end_date',
        'advance_paid'
    ];

    public $errors = [];
    public $success = [];

    public function validateProperty($data)
    {
        $this->errors = [];

        if (empty($data['name']) || !preg_match('/^[a-zA-Z0-9\s]+$/', $data['name'])) {
            $this->errors['name'] = 'Invalid property name.';
        }

        // if (!isset($data['rental_price']) || !is_numeric($data['rental_price']) || $data['rental_price'] < 0) {
        //     $this->errors['rental_price'] = 'Rental price must be a positive number.';
        // }

        if (!isset($data['rental_period'])) {
            $this->errors['rental_period'] = 'Rental period must be a valid number.';
        }

        if (empty($data['address'])) {
            $this->errors['address'] = 'Address is required.';
        }

        if (empty($data['city']) || !preg_match('/^[a-zA-Z\s]+$/', $data['city'])) {
            $this->errors['city'] = 'Invalid city name.';
        }

        if (empty($data['state_province']) || !preg_match('/^[a-zA-Z\s]+$/', $data['state_province'])) {
            $this->errors['state_province'] = 'Invalid state/province name.';
        }

        if (empty($data['country']) || !preg_match('/^[a-zA-Z\s]+$/', $data['country'])) {
            $this->errors['country'] = 'Invalid country name.';
        }

        if (empty($data['zipcode']) || !preg_match('/^[0-9]{4,10}$/', trim($data['zipcode']))) {
            $this->errors['zipcode'] = 'Invalid Zipcode format.';
        }

        if($data['purpose'] == 'Rent'){
            if (!isset($data['year_built']) || !filter_var($data['year_built'], FILTER_VALIDATE_INT) || $data['year_built'] < 1800 || $data['year_built'] > date("Y")) {
                $this->errors['year_built'] = 'Year built must be between 1800 and ' . date("Y") . '.';
            }
    
            if (!isset($data['size_sqr_ft']) || !filter_var($data['size_sqr_ft'], FILTER_VALIDATE_INT) || $data['size_sqr_ft'] <= 0) {
                $this->errors['size_sqr_ft'] = 'Invalid size in square feet.';
            }
    
            if (!isset($data['number_of_floors']) || !filter_var($data['number_of_floors'], FILTER_VALIDATE_INT) || $data['number_of_floors'] < 0) {
                $this->errors['number_of_floors'] = 'Number of floors must be a positive integer.';
            }
    
            if (!isset($data['furnished']) || !in_array($data['furnished'], ['Fully Furnished', 'Semi-Furnished', 'Unfurnished'])) {
                $this->errors['furnished'] = 'Invalid furnished status.';
            }
    
            if (!isset($data['parking']) || ($data['parking'] != '1' && $data['parking'] != '0')) {
                $this->errors['parking'] = 'Invalid parking value.';
            }
    
            if ($data['parking'] == '1' && (!isset($data['parking_slots']) || !filter_var($data['parking_slots'], FILTER_VALIDATE_INT) || $data['parking_slots'] < 0)) {
                $this->errors['parking_slots'] = 'Invalid number of parking slots.';
            }
        }


        if (empty($data['owner_name']) || !preg_match('/^[a-zA-Z\s]+$/', $data['owner_name'])) {
            $this->errors['owner_name'] = 'Invalid owner name.';
        }

        if (!empty($data['owner_email']) && !filter_var($data['owner_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['owner_email'] = 'Invalid owner email.';
        }

        if (!empty($data['owner_phone']) && !preg_match('/^[0-9\-\s]+$/', $data['owner_phone'])) {
            $this->errors['owner_phone'] = 'Invalid owner phone number.';
        }

        // Return true if no errors, otherwise false
        if (empty($this->errors)) {
            return true;
        }
        return false;
    }

    public function getPropertyIdsByAgent($agent_id)
    {
        $property_ids = [];
        $query = "SELECT property_id FROM {$this->table} WHERE agent_id = :agent_id";
        $data = ['agent_id' => $agent_id];
        $result = $this->instance->query($query, $data);
        if ($result) {
            foreach ($result as $row) {
                $property_ids[] = $row->property_id;
            }
        }
        return $property_ids;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
