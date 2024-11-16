<?php

class SerPro {
    use Model;

    protected $table = 'serpro';
    protected $order_column = "serpro_id";
    protected $allowedColumns = [
        'serpro_id',
        'first_name',
        'last_name', 
        'date_of_birth',
        'gender',
        'contact_no1',
        'contact_no2',
        'email',
        'bank_account_No',
        'address',
        'marital_status',
        'NIC_no.'
    ];

    public $errors = [];

    public function validate($data) {
        $this->errors = [];
    
        // Validate first name
        if (empty($data['first_name'])) {
            $this->errors['first_name'] = 'First name is required';
        } elseif (strlen($data['first_name']) > 50) {
            $this->errors['first_name'] = 'First name cannot exceed 50 characters';
        }
    
        // Validate last name
        if (empty($data['last_name'])) {
            $this->errors['last_name'] = 'Last name is required';
        } elseif (strlen($data['last_name']) > 50) {
            $this->errors['last_name'] = 'Last name cannot exceed 50 characters';
        }
    
        // Validate date of birth
        if (empty($data['date_of_birth'])) {
            $this->errors['date_of_birth'] = 'Date of birth is required';
        } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['date_of_birth'])) {
            $this->errors['date_of_birth'] = 'Date of birth must be in YYYY-MM-DD format';
        }
    
        // Validate gender
        $validGenders = ['Male', 'Female', 'Other'];
        if (empty($data['gender']) || !in_array($data['gender'], $validGenders)) {
            $this->errors['gender'] = 'Valid gender is required (Male, Female, or Other)';
        }
    
        // Validate contact numbers
        if (empty($data['contact_no1']) || !preg_match('/^\d{10}$/', $data['contact_no1'])) {
            $this->errors['contact_no1'] = 'Valid primary contact number is required (10 digits)';
        }
        if (!empty($data['contact_no2']) && !preg_match('/^\d{10}$/', $data['contact_no2'])) {
            $this->errors['contact_no2'] = 'Secondary contact number must be 10 digits';
        }
    
        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Valid email address is required';
        }
    
        // Validate bank account number
        if (empty($data['bank_account_No']) || !preg_match('/^\d{10,16}$/', $data['bank_account_No'])) {
            $this->errors['bank_account_No'] = 'Valid bank account number is required (10-16 digits)';
        }
    
        // Validate address
        if (empty($data['address'])) {
            $this->errors['address'] = 'Address is required';
        } elseif (strlen($data['address']) > 255) {
            $this->errors['address'] = 'Address cannot exceed 255 characters';
        }
    
        // Validate marital status
        $validMaritalStatuses = ['Single', 'Married'];
        if (empty($data['marital_status']) || !in_array($data['marital_status'], $validMaritalStatuses)) {
            $this->errors['marital_status'] = 'Valid marital status is required (Single, Married)';
        }
    
        // Validate NIC number
        if (empty($data['NIC_no.']) || !preg_match('/^\d{9}[VX]$/i', $data['NIC_no.'])) {
            $this->errors['NIC_no.'] = 'Valid NIC number is required (9 digits followed by V or X)';
        }
    
        return empty($this->errors);
    }
    
}
