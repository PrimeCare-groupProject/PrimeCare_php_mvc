<?php
// Property concated class
class PropertyConcat
{
    use Model;

    protected $table = 'propertyTable_with_images';
    protected $order_column = "property_id";
    protected $allowedColumns = [
        'property_id',
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
        'property_images',
        'property_deed_images',
    ];

    public $errors = [];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public function getByPropertyIds(array $propertyIds)
    {
        if (empty($propertyIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($propertyIds), '?'));
        $query = "SELECT * FROM {$this->table} WHERE property_id IN ($placeholders)";
        
        return $this->instance->query($query, $propertyIds);
    }
    /**
     * Search properties with optional filters and a search term that matches
     * concatenated name, description, and address fields.
     */
    public function whereWithSearchTerm($data = [], $data_not = [], $searchTerm = "", $order = "DESC", $limit = 100, $offset = 0, $orderBy = null)
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $gteKeys = ['bedrooms', 'bathrooms', 'kitchen', 'living_room', 'parking_slots'];

        $query = "SELECT * FROM {$this->table} ";
        $parameters = [];
        $conditions = [];

        // Always include status = 'Active' condition
        $conditions[] = "status = :status";
        $parameters['status'] = 'Active';

        // Exact match conditions
        foreach ($keys as $key) {
            if (in_array($key, $this->allowedColumns)) {
                if (in_array($key, $gteKeys) && !empty($data[$key])) {
                    $conditions[] = "$key >= :$key";
                } else {
                    $conditions[] = "$key = :$key";
                }
                $parameters[$key] = $data[$key];
            }
        }

        // Not equal conditions
        foreach ($keys_not as $key) {
            if (in_array($key, $this->allowedColumns)) {
                $conditions[] = "$key != :not_$key";
                $parameters["not_$key"] = $data_not[$key];
            }
        }

        // Price range conditions
        if (!empty($data['min_price'])) {
            $conditions[] = "rental_price >= :min_price";
            $parameters['min_price'] = $data['min_price'];
        }
        
        if (!empty($data['max_price'])) {
            $conditions[] = "rental_price <= :max_price";
            $parameters['max_price'] = $data['max_price'];
        }

        // Search term condition (concatenated fields)
        if (!empty($searchTerm)) {
            $conditions[] = "CONCAT_WS(' ', name, description, address) LIKE :searchTerm";
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }

        // Conditions are now guaranteed to exist because of the status condition
        $query .= "WHERE " . implode(' AND ', $conditions) . " ";

        // Use custom order column if provided, otherwise use default
        $order_column = $orderBy && in_array($orderBy, $this->allowedColumns) ? $orderBy : ($this->order_column ?? 'property_id');
        $order_type = in_array(strtoupper($order), ['ASC', 'DESC']) ? strtoupper($order) : 'DESC';
        
        // Use integers directly in the query instead of binding them
        $limit = (int)$limit;
        $offset = (int)$offset;
        $query .= "ORDER BY $order_column $order_type LIMIT $limit OFFSET $offset";

        // show($query, $parameters);

        return $this->instance->query($query, $parameters);
    }
}
