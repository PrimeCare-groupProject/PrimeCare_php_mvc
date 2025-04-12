<?php

class RandomPerson {
    use Model;

    protected $table = 'randomPerson';
    protected $order_column = "pid";
    protected $allowedColumns = [
        'pid',
        'name',
        'email',
        'contactNo'
    ];

    public $errors = [];

    public function validate($data) {
        $this->errors = []; // Initialize errors array
    
        // Validate name
        if (empty($data['name']) || !preg_match('/^[a-zA-Z\s\'-]+$/', trim($data['name']))) {
            $this->errors['name'] = 'Name is not valid. Only alphabets, spaces, hyphens, and apostrophes are allowed.';
        }
    
        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email is not valid.';
        } else {
            // Check allowed domains (optional)
            $domain = substr(strrchr($data['email'], '@'), 1); // Get domain after '@'
            $allowedDomains = [
                'gmail.com', 'outlook.com', 'yahoo.com', 'hotmail.com', 'protonmail.com',
                'icloud.com', 'google.com', 'microsoft.com', 'apple.com', 'amazon.com'
                // Add other domains as necessary
            ];
            if (!in_array($domain, $allowedDomains)) {
                $this->errors['email'] = 'Email domain is not allowed.';
            }
        }
    
        // Validate contact number (phone number)
        if (empty($data['contactNo'])) {
            $this->errors['contactNo'] = 'Contact number is required.';
        } elseif (!preg_match('/^[0-9]{10}$/', trim($data['contactNo']))) {
            $this->errors['contactNo'] = 'Contact number must be exactly 10 digits.';
        }
    
        // Return true if no errors, otherwise false
        return empty($this->errors);
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
    
}
