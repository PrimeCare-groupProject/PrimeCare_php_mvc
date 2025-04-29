<?php
// User class
class User {
    use Model;

    protected $table = 'person';
    protected $order_column = "pid";
    protected $allowedColumns = [
        'pid',
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
        'nic',
        'age'
    ];

    public $errors = [];

    public function validate($data) {
        $this->errors = [];

        if (isset($data['fname'])) {
            if (empty($data['fname']) || !preg_match('/^[a-zA-Z]+$/', $data['fname'])) {
                $this->errors['fname'] = 'First name is not valid';
            }
        }

        if (isset($data['lname'])) {
            if (empty($data['lname']) || !preg_match('/^[a-zA-Z]+$/', $data['lname'])) {
                $this->errors['lname'] = 'Last name is not valid';
            }
        }

        if (isset($data['nic'])) {
            if (!empty($data['nic']) && !preg_match('/^(?:\d{12}|\d{9}[xXvV])$/', $data['nic'])) {
                $this->errors['nic'] = 'NIC is not valid';
            }
        }

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

        if (isset($data['contact'])) {
            if (empty($data['contact']) || !preg_match('/^[0-9]{10}$/', trim($data['contact']))) {
                $this->errors['contact'] = 'Contact number should be 10 digits';
            }
        }

        if (isset($data['password'])) {
            if (empty($data['password']) || strlen($data['password']) < 5) {
                $this->errors['password'] = 'Password should be at least 5 characters long';
            } elseif ($data['password'] != $data['confirmPassword']) {
                $this->errors['password'] = 'Passwords do not match';
            }
        }

        if (isset($data['image_url'])) {
            if (!empty($data['image_url']) && !filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
                $this->errors['image_url'] = 'Image URL is not valid';
            }
        }

        if (isset($data['user_lvl'])) {
            if (!empty($data['user_lvl']) && !filter_var($data['user_lvl'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 10]])) {
                $this->errors['user_lvl'] = 'User level must be an integer between 1 and 10';
            }
        }

        if (isset($data['age'])) {
            if (!empty($data['age']) && !filter_var($data['user_lvl'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 100]])) {
                $this->errors['age'] = 'Age must be a positive number more than 21';
            }
        }

        return empty($this->errors);
    }


    public function findByMultiplePids(array $pids, array $data = []) {
        if (empty($pids)) {
            return [];
        }
    
        $inParams = [];
        foreach ($pids as $i => $pid) {
            $paramName = ":pid_" . $i;
            $inParams[$paramName] = $pid;
        }
        $placeholders = implode(',', array_keys($inParams));
    
        $andConditions = [];
        $parameters = [];
        foreach ($data as $key => $value) {
            $paramName = ":" . $key;
            $andConditions[] = "$key = $paramName";
            $parameters[$paramName] = $value;
        }
    
        $query = "SELECT * FROM {$this->table} WHERE {$this->order_column} IN ($placeholders)";
        if (!empty($andConditions)) {
            $query .= " AND " . implode(' AND ', $andConditions);
        }
    
        $params = array_merge($inParams, $parameters);
    
        return $this->instance->query($query, $params);
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

}
