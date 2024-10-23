<?php
// User class
class User {
    use Model;

    protected $table = 'person';
    protected $order_column = "pid";
    protected $allowedColumns = [
        'fname',
        'lname',
        'username',
        'email',
        'contact',
        'password',
        'image_url',
        'user_lvl',
    ];

    public $errors = [];

    public function validate($data) {
        $this->errors = [];
        //preg_match() takes varibale and int value of the filter type to be done
        // Validate first name
        if (empty($data['fname']) || !preg_match('/^[a-zA-Z]+$/', $data['fname'])) {
            $this->errors['fname'] = 'First name is not valid';
        }

        // Validate last name
        if (empty($data['lname']) || !preg_match('/^[a-zA-Z]+$/', $data['lname'])) {
            $this->errors['lname'] = 'Last name is not valid';
        }

        // Validate username
        // if (empty($data['username']) || strlen($data['username']) < 5) {
        //     $this->errors['username'] = 'Username should be at least 5 characters long';
        // }

        // Validate email
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email is not valid';
        }

        // Validate contact (phone number)
        if (empty($data['contact'])) {
            // var_dump($data['contact']);
            $this->errors['contact'] = 'Contact number is required';
        } elseif (!preg_match('/^[0-9]{10}$/', trim($data['contact']))) {
            $this->errors['contact'] = 'Contact number should be 10 digits';
        }
        
        // Validate password (e.g., minimum 5 characters)
        if (empty($data['password']) || strlen($data['password']) < 5) {
            $this->errors['password'] = 'Password should be at least 5 characters long';
        }
        // if confirmation is correct
        if($data['password'] != $data['confirmPassword']){
            $this->errors['password'] = 'Passwords do not match';
        }

        // Optionally validate image_URL (if needed)
        if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
            $this->errors['image_url'] = 'Image URL is not valid';
        }

        // Optionally validate user_lvl (e.g., must be an integer between 1 and 10)
        if (!empty($data['user_lvl']) && !filter_var($data['user_lvl'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 10]])) {
            $this->errors['user_lvl'] = 'User level must be an integer between 1 and 10';
        }

        // Return true if no errors, otherwise false
        if (empty($this->errors)) {
            return true;
        }
        return false;
    }

    // public function findAll(){//search rows depending on the data passed
    //     $columnsString = "" . implode(", ", $this->allowedColumns) . "";
    //     $columnsString = rtrim($columnsString, ", ");
    //     $query = "
    //         select $columnsString
    //         from $this->table 
    //         order by $this->order_column $this->order_type 
    //         limit $this->limit 
    //         offset $this->offset
    //         ";
    //     // show($query);
    //     return $this->query($query);
    // }
}
