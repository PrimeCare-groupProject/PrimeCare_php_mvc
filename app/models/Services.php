<?php

class Services {
    use Model;

    public $table = 'services';
    protected $order_column = "service_id";
    protected $allowedColumns = [
        'service_id',
        'name',
        'cost_per_hour',
        'description',
        'service_img'
    ];

    public $errors = [];
    
    /**
     * Fetch all services ordered by service_id descending
     * 
     * @return array Array of service objects
     */
    public function getAllServices() {
        return $this->findAll($this->order_column, 'desc');
    }
    
    /**
     * Get a specific service by ID
     * 
     * @param int $id Service ID
     * @return object|bool Service object or false if not found
     */
    public function getServiceById($id) {
        return $this->first(['service_id' => $id]);
    }
    
    /**
     * Search services by name
     * 
     * @param string $searchTerm Term to search for in service names
     * @return array Matching services
     */
    public function searchByName($searchTerm) {
        return $this->query("SELECT * FROM $this->table WHERE name LIKE ?", ["%$searchTerm%"]);
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
