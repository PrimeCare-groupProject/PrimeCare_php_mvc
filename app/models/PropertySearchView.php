<?php
// PropertySearchViewModel class
class PropertySearchView
{
    use Model;

    protected $table = 'property_search_view';
    protected $order_column = "property_id";
    protected $allowedColumns = [
        'property_id',
        'property_name',
        'property_type',
        'description',
        'address',
        'zipcode',
        'city',
        'state_province',
        'country',
        'year_built',
        'size_sqr_ft',
        'number_of_floors',
        'units',
        'bedrooms',
        'bathrooms',
        'kitchen',
        'living_room',
        'furnished',
        'parking',
        'parking_slots',
        'type_of_parking',
        'security_features',
        'utilities_included',
        'additional_utilities',
        'additional_amenities',
        'purpose',
        'rental_period',
        'rental_price',
        'available_date',
        'owner_name',
        'owner_email',
        'owner_phone',
        'special_instructions',
        'legal_details',
        'property_status',
        'owner_id',
        'agent_id',
        'availability_status',
        'booking_id',
        'rental_type',
        'check_in',
        'check_out',
        'duration_days',
        'booking_status',
        'booked_by_id'
    ];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public $errors = [];

    public function searchProperty($searchTerm)
    {
        $query = "SELECT * FROM {$this->table} 
                  WHERE LOWER(CONCAT_WS(' ', property_name, address, city, state_province, country)) 
                  LIKE LOWER(:searchTerm)";

        $params = [
            'searchTerm' => '%' . strtolower($searchTerm) . '%'
        ];

        return $this->instance->query($query, $params);
    }

    public function advancedSearch($params, $fields = '*')
    {
        // Set defaults for all possible parameters
        $defaultParams = [
            'searchTerm' => null,
            'sort_by' => null,
            'min_price' => null,
            'max_price' => null,
            'check_in' => null,
            'check_out' => null,
            'city' => null,
            'state' => null
        ];

        // Merge with provided parameters
        $searchParams = array_merge($defaultParams, array_filter($params, function($value) {
            return $value !== '';
        }));

        // Validate fields parameter
        if (is_array($fields)) {
            $fields = implode(', ', array_intersect($fields, $this->allowedColumns));
        } elseif ($fields !== '*') {
            $fields = '*';
        }

        $query = "SELECT {$fields}
            FROM {$this->table}
            WHERE 
                -- Only show available ones
                property_status = 'Active'
                -- Search term filter (matches against multiple fields)
                AND (:searchTerm IS NULL OR :searchTerm = '' OR 
                LOWER(CONCAT_WS(' ', property_name, address, city, state_province, country)) LIKE LOWER(CONCAT('%', :searchTerm, '%')))
                
                -- Price range filter
                AND (:min_price IS NULL OR rental_price >= :min_price)
                AND (:max_price IS NULL OR rental_price <= :max_price)
                
                -- Location filters
                AND (:city IS NULL OR :city = '' OR LOWER(city) COLLATE utf8mb4_general_ci = LOWER(:city) COLLATE utf8mb4_general_ci)
                AND (:state IS NULL OR :state = '' OR LOWER(state_province) COLLATE utf8mb4_general_ci = LOWER(:state) COLLATE utf8mb4_general_ci)
                
                -- Availability filter (check if property is available for given dates)
                AND (
                    -- If no dates provided, show all properties
                    (:check_in IS NULL OR :check_out IS NULL) 
                    
                    -- If dates provided, show only available properties
                    OR (
                        -- Property is marked as available
                        availability_status = 'Available' COLLATE utf8mb4_unicode_ci
                        
                        -- Or check if there are no overlapping bookings
                        OR NOT EXISTS (
                            SELECT 1 
                            FROM property_bookings pb
                            WHERE pb.property_id = property_search_view.property_id
                            AND pb.is_active = TRUE
                            AND pb.booking_status IN ('pending', 'confirmed', 'checked_in')
                            AND (
                                (pb.check_in <= :check_out AND pb.check_out >= :check_in)
                            )
                        )
                    )
                )
                
            ORDER BY
                CASE WHEN :sort_by = 'price_asc' THEN rental_price END ASC,
                CASE WHEN :sort_by = 'price_desc' THEN rental_price END DESC,
                CASE WHEN :sort_by = 'newest' THEN year_built END DESC,
                CASE WHEN :sort_by IS NULL OR :sort_by = '' THEN property_name END ASC";

        return $this->instance->query($query, $searchParams);
    }

}
