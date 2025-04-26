<?php

class ServiceApplication {
    use Model;

    protected $table = 'service_applications';
    protected $order_column = "created_at"; 
    protected $order_direction = "desc";    
    
    protected $allowedColumns = [
        'service_id',
        'service_provider_id',
        'proof',
        'status',
        'created_at',
        'updated_at'
    ];
    
    public $errors = [];

    /**
     * Validate application data
     *
     * @param array $data The data to validate
     * @return bool True if valid, false otherwise
     */
    public function validate($data) {
        $this->errors = [];
        
        if(empty($data['service_id'])) {
            $this->errors['service_id'] = "Service ID is required";
        } else {
            // Verify that the service exists in services table (not serviceLog)
            $serviceModel = new Services();
            $service = $serviceModel->getServiceById($data['service_id']);
            
            if (!$service) {
                $this->errors['service_id'] = "The selected service does not exist";
            }
        }
        
        if(empty($data['service_provider_id'])) {
            $this->errors['service_provider_id'] = "Service provider ID is required";
        } else {
            // Verify the service provider exists
            $person = new User();
            $provider = $person->first(['pid' => $data['service_provider_id']]);
            
            if (!$provider) {
                $this->errors['service_provider_id'] = "The selected service provider does not exist";
            }
        }
        
        if(empty($data['proof'])) {
            $this->errors['proof'] = "Proof document is required";
        }
        
        if(!empty($data['status'])) {
            $valid_statuses = ['Pending', 'Approved', 'Rejected'];
            if(!in_array($data['status'], $valid_statuses)) {
                $this->errors['status'] = "Invalid status value";
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Check if service provider already applied for a service
     */
    public function hasApplied($service_id, $provider_id) {
        $application = $this->first([
            'service_id' => $service_id,
            'service_provider_id' => $provider_id
        ]);
        
        return !empty($application);
    }
    
    /**
     * Get applications for a specific service
     *
     * @param int $service_id The service ID
     * @return array Array of applications
     */
    public function getServiceApplications($service_id) {
        return $this->where(['service_id' => $service_id]);
    }
    
    /**
     * Get all applications from a specific service provider
     *
     * @param int $provider_id The service provider ID
     * @return array Array of applications
     */
    public function getProviderApplications($provider_id) {
        return $this->where(['service_provider_id' => $provider_id]);
    }
}