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
        'reset_code',
        'created_date',
        'nic'
    ];

    public $errors = [];

    public function validate($data) {
        $this->errors = [];

        // Validate first name
        if (isset($data['fname'])) {
            if (empty($data['fname']) || !preg_match('/^[a-zA-Z]+$/', $data['fname'])) {
                $this->errors['fname'] = 'First name is not valid';
            }
        }

        // Validate last name
        if (isset($data['lname'])) {
            if (empty($data['lname']) || !preg_match('/^[a-zA-Z]+$/', $data['lname'])) {
                $this->errors['lname'] = 'Last name is not valid';
            }
        }

        // Validate NIC
        if (isset($data['nic'])) {
            if (!empty($data['nic']) && !preg_match('/^(?:\d{12}|\d{9}[xXvV])$/', $data['nic'])) {
                $this->errors['nic'] = 'NIC is not valid';
            }
        }

        // Validate email
        if (isset($data['email'])) {
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = 'Email is not valid';
            } else {
                $domain = substr($data['email'], strpos($data['email'], '@') + 1);
                $allowedDomains = [
                    'gmail.com', 'outlook.com', 'yahoo.com', 'hotmail.com', 'protonmail.com', 'icloud.com',
                    'google.com', 'microsoft.com', 'apple.com', 'amazon.com', 'facebook.com', 'twitter.com', 'linkedin.com',
                    'company.com', 'corp.com', 'business.com', 'enterprise.com',
                    'university.edu', 'college.edu', 'school.edu', 'campus.edu',
                    'gov.com', 'public.org', 'municipality.gov',
                    'startup.com', 'techcompany.com', 'innovate.com',
                    'freelancer.com', 'consultant.com', 'remote.work',
                    'localbank.com', 'regional.org', 'cityservice.com',
                    'hospital.org', 'clinic.com', 'medical.net',
                    'nonprofit.org', 'charity.org', 'ngo.com',
                    'design.com', 'creative.org', 'agency.com',
                    'me.com', 'personal.com', 'home.net',
                    'mail.ru', 'yandex.com', 'gmx.com', 'web.de'
                ];
                if (!in_array($domain, $allowedDomains)) {
                    $this->errors['email'] = 'Email domain is not allowed';
                }
            }
        }

        // Validate contact (phone number)
        if (isset($data['contact'])) {
            if (empty($data['contact']) || !preg_match('/^[0-9]{10}$/', trim($data['contact']))) {
                $this->errors['contact'] = 'Contact number should be 10 digits';
            }
        }

        // Validate password
        if (isset($data['password'])) {
            if (empty($data['password']) || strlen($data['password']) < 5) {
                $this->errors['password'] = 'Password should be at least 5 characters long';
            } elseif ($data['password'] != $data['confirmPassword']) {
                $this->errors['password'] = 'Passwords do not match';
            }
        }

        // Validate image URL
        if (isset($data['image_url'])) {
            if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
                $this->errors['image_url'] = 'Image URL is not valid';
            }
        }

        // Validate user level
        if (isset($data['user_lvl'])) {
            if (!empty($data['user_lvl']) && !filter_var($data['user_lvl'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 10]])) {
                $this->errors['user_lvl'] = 'User level must be an integer between 1 and 10';
            }
        }

        // Return true if no errors, otherwise false
        return empty($this->errors);
    }

    public function findByMultiplePids(array $pids) {
        if (empty($pids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($pids), '?'));
        $query = "SELECT * FROM {$this->table} WHERE {$this->order_column} IN ($placeholders)";
        
        return $this->instance->query($query, $pids);
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
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
