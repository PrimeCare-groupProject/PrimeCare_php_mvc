<?php

class ProblemReport {
    use Model;

    protected $table = 'ProblemReports';
    protected $order_column = "report_id";
    protected $allowedColumns = [
        'report_id',
        'problem_description',
        'problem_location',
        'urgency_level',
        'property_id',
        'submitted_at',
        'status', // Added status
        'created_by' // New field for user PID
    ];

    public $errors = [];

    // Define allowed statuses
    protected $allowedStatuses = ['pending', 'in_progress', 'resolved'];

    public function validate($data) 
    {
        $this->errors = [];
        if(empty($data['problem_description'])) {
            $this->errors['problem_description'] = "Problem description is required";
        }
        if(empty($data['urgency_level'])) {
            $this->errors['urgency_level'] = "Urgency level is required";
        } else {
            $allowed_levels = [1, 2, 3];
            if(!in_array($data['urgency_level'], $allowed_levels)) {
                $this->errors['urgency_level'] = "Invalid urgency level";
            }
        }
        if(empty($data['property_id']) || !is_numeric($data['property_id'])) {
            $this->errors['property_id'] = "Valid property ID is required";
        }
        // Validate created_by
        if(empty($data['created_by']) || !is_numeric($data['created_by'])) {
            $this->errors['created_by'] = "Valid user ID (created_by) is required";
        }
        // Validate status
        if(empty($data['status'])) {
            $data['status'] = 'pending'; // Default status
        } else {
            if(!in_array($data['status'], $this->allowedStatuses)) {
                $this->errors['status'] = "Invalid status";
            }
        }

        return empty($this->errors);
    }


    public function getReportsByProperty($property_id) 
    {
        $query = "SELECT * FROM {$this->table} WHERE property_id = :property_id ORDER BY submitted_at DESC";
        return $this->instance->query($query, ['property_id' => $property_id]);
    }

    public function getReportsByUrgency($urgency_level) 
    {
        $query = "SELECT * FROM {$this->table} WHERE urgency_level = :urgency_level ORDER BY submitted_at DESC";
        return $this->instance->query($query, ['urgency_level' => $urgency_level]);
    }

    public function getReportsByStatus($status) {
        $query = "SELECT * FROM {$this->table} WHERE status = :status ORDER BY submitted_at DESC";
        return $this->instance->query($query, ['status' => $status]);
    }

    public function getRecentReports($limit = 10) {
        $query = "SELECT r.*, p.name as property_name 
                 FROM {$this->table} r 
                 JOIN propertyTable p ON r.property_id = p.property_id 
                 ORDER BY submitted_at DESC 
                 LIMIT :limit";
        return $this->instance->query($query, ['limit' => $limit]);
    }

    public function search($keyword, $agent_id = null) {
        $query = "SELECT r.* 
                  FROM {$this->table} r
                  JOIN propertyTable p ON r.property_id = p.property_id
                  WHERE (r.problem_description LIKE :keyword 
                         OR r.problem_location LIKE :keyword)";
        $params = ['keyword' => '%' . $keyword . '%'];
    
        if ($agent_id !== null) {
            $query .= " AND p.agent_id = :agent_id";
            $params['agent_id'] = $agent_id;
        }
    
        $query .= " ORDER BY r.submitted_at DESC";
        return $this->instance->query($query, $params);
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}