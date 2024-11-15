<?php
// Property class
class PropertyModel {
    use Model;

    protected $table = 'property';
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
        'rent_on_basis',
        'units',
        'size_sqr_ft',
        'bedrooms',
        'bathrooms',
        'floor_plan',
        'parking',
        'furnished',
        'status',
        'person_id'
    ];

    public $errors = [];
    public $success = [];

    public function validateProperty($data) {
        $this->errors = [];
        //preg_match() takes varibale and int value of the filter type to be done
        // Validate property Name
        if (empty($data['name']) || !preg_match('/^[a-zA-Z\s]+$/', $data['name'])) {
            $this->errors['name'] = 'Name is not valid';
        }

        // Validate type
        if (empty($data['type']) || ($data['type'] != 'shortTerm' && $data['type'] != 'monthly' && $data['type'] != 'serviceOnly')) {
            $this->errors['type'] = 'Type is not valid';
        }

        // Validate description
        if (empty($data['description'])) {
            $this->errors['description'] = 'Description is required';
        }

        // Validate state
        if (empty($data['state_province']) || !preg_match('/^[a-zA-Z\s]+$/', $data['state_province'])) {
            $this->errors['state_province'] = 'State is not valid';
        }

        // validate zipcode
        if (empty($data['zipcode']) || !preg_match('/^[0-9]{5}$/', trim($data['zipcode']))) {
            $this->errors['zipcode'] = 'Zipcode should be 5 digits';
        }

        // Validate city
        if (empty($data['city']) || !preg_match('/^[a-zA-Z\s]+$/', $data['city'])) {
            $this->errors['city'] = 'City is not valid';
        }

        // Validate Status
        // if (empty($data['status']) || ($data['status'] != 'active' && $data['status'] != 'inactive' && $data['status'] != 'pending' && $data['status'] != 'sold' && $data['status'] != 'under_maintenance')) {
        //     $this->errors['status'] = 'Status is not valid';
        // }

        // Validate country
        if (empty($data['country']) || !preg_match('/^[a-zA-Z\s]+$/', $data['country'])) {
            $this->errors['country'] = 'Country is not valid';
        }

        // Validate address
        if (empty($data['address'])) {
            $this->errors['address'] = 'Address is required';
        }

        // Validate bedrooms
        if (empty($data['bedrooms']) || !filter_var($data['bedrooms'], FILTER_VALIDATE_INT)) {
            $this->errors['bedrooms'] = 'Bedrooms is not valid';
        }

        // Validate units
        if (empty($data['units']) || !filter_var($data['units'], FILTER_VALIDATE_INT)) {
            $this->errors['units'] = 'Units is not valid';
        }

        // Validate bathrooms
        if (empty($data['bathrooms']) || !filter_var($data['bathrooms'], FILTER_VALIDATE_INT)) {
            $this->errors['bathrooms'] = 'Bathrooms is not valid';
        }

        // Validate year_built
        if (empty($data['year_built']) || !filter_var($data['year_built'], FILTER_VALIDATE_INT)) {
            $currentYear = date("Y");
            if($data['year_built'] < 1900 || $data['year_built'] > $currentYear){
                $this->errors['year_built'] = 'Year Built is not valid';
            }
        }

        // Validate floor_plan
        if (empty($data['floor_plan'])) {
            $this->errors['floor_plan'] = 'Floor Plan is required';
        }

        // Validate size
        if (empty($data['size_sqr_ft']) || !filter_var($data['size_sqr_ft'], FILTER_VALIDATE_INT)) {
            $this->errors['size_sqr_ft'] = 'Size is not valid';
        }

        // Validate parking
        if (empty($data['parking']) || ($data['parking'] != 'yes' && $data['parking'] != 'no')) {
            $this->errors['parking'] = 'Parking is not valid';
        }

        // Validate furnished
        if (empty($data['furnished']) || ($data['furnished'] != 'yes' && $data['furnished'] != 'no')) {
            $this->errors['furnished'] = 'Furnished is not valid';
        }

        // Validate rent
        if($data['type'] == 'monthly'){
            if (empty($data['rent_on_basis']) || $data['rent_on_basis'] < 0) {
                $this->errors['rent_on_basis'] = 'Rent is not valid';
            }
        }

        // Return true if no errors, otherwise false
        if (empty($this->errors)) {
            return true;
        }
        return false;
    }

}
