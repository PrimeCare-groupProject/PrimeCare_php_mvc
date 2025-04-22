<?php
class BookingOrders
{
    use Model;

    protected $table = 'booking_orders';
    protected $order_column = "booking_id";
    protected $allowedColumns = [
        'property_id',
        'person_id',
        'agent_id',
        'start_date',
        'duration',
        'rental_period',
        'rental_price',
        'payment_status',
        'booking_status'
    ];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public $errors = [];

    public function validate($data)
    {
        // Check if required fields are present
        if (empty($data['property_id'])) {
            $this->errors['property_id'] = "Property is required";
        }

        if (empty($data['person_id'])) {
            $this->errors['person_id'] = "Person is required";
        }

        if (empty($data['start_date'])) {
            $this->errors['start_date'] = "Start date is required";
        } else if (!strtotime($data['start_date'])) {
            $this->errors['start_date'] = "Start date is not a valid date";
        }

        if (empty($data['duration']) || !is_numeric($data['duration']) || $data['duration'] <= 0) {
            $this->errors['duration'] = "Duration must be a positive number";
        }

        if (empty($data['rental_period'])) {
            $this->errors['rental_period'] = "Rental period is required";
        } else if (!in_array($data['rental_period'], ['Daily', 'Monthly', 'Annually'])) {
            $this->errors['rental_period'] = "Rental period must be Daily, Monthly, or Annually";
        }

        if (empty($data['rental_price']) || !is_numeric($data['rental_price']) || $data['rental_price'] <= 0) {
            $this->errors['rental_price'] = "Rental price must be a positive number";
        }

        if (!empty($data['payment_status']) && !in_array($data['payment_status'], ['Pending', 'Paid', 'Cancelled'])) {
            $this->errors['payment_status'] = "Payment status must be Pending, Paid, or Cancelled";
        }

        if (!empty($data['booking_status']) && !in_array($data['booking_status'], ['Confirmed', 'Cancelled', 'Completed', 'Pending'])) {
            $this->errors['booking_status'] = "Booking status must be Confirmed, Cancelled, Completed, or Pending";
        }

        return empty($this->errors);
    }

    /**
     * Check if a property is available for a given date range
     * 
     * @param int $property_id The property ID to check
     * @param string $check_in The requested check-in date (YYYY-MM-DD)
     * @param string $check_out The requested check-out date (YYYY-MM-DD)
     * @return bool True if property is available, false if already booked
     */
    public function isPropertyAvailable($property_id, $check_in, $check_out)
    {
        if (!$property_id || !$check_in || !$check_out) {
            return false;
        }
        
        // Validate date formats
        if (!strtotime($check_in) || !strtotime($check_out)) {
            return false;
        }
        
        // Convert to DateTime objects for comparison
        $start_date = date('Y-m-d', strtotime($check_in));
        $end_date = date('Y-m-d', strtotime($check_out));
        
        // Get all bookings for this property that aren't cancelled
        $query = "SELECT * FROM $this->table 
            WHERE property_id = :property_id 
            AND booking_status NOT IN ('Cancelled', 'Completed') 
            AND (
                start_date < :end_date AND
                end_date > :start_date
            )";
                
        $data = [
            'property_id' => $property_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];
        
        $bookings = $this->instance->query($query, $data);
        
        // If no conflicting bookings found, the property is available
        // Check if $bookings is valid
        if (is_bool($bookings) || $bookings === false || $bookings === null) {
            return false; // If query failed, assume property is not available
        }
        
        return count($bookings) === 0;
    }


}
// CREATE TABLE booking_orders (
//     booking_id INT AUTO_INCREMENT PRIMARY KEY, -- Unique ID for each booking

//     property_id INT NOT NULL,                  -- Foreign key to the property being booked
//     person_id INT NOT NULL,                    -- Renter's ID (FK to person)
//     agent_id INT,                              -- Optional: agent handling the booking (also FK to person)

//     start_date DATE NOT NULL,                  -- Check-in date
//     duration INT NOT NULL,                     -- Duration of stay (in days, months, or years)

//     rental_period ENUM('Daily', 'Monthly', 'Annually') NOT NULL, -- Rental frequency type
//     rental_price DECIMAL(12,2) NOT NULL,                         -- Price per unit of rental period

//     end_date DATE GENERATED ALWAYS AS ( -- Automatically computed check-out date
//         CASE 
//             WHEN rental_period = 'Daily' THEN DATE_ADD(start_date, INTERVAL duration DAY)
//             WHEN rental_period = 'Monthly' THEN DATE_ADD(start_date, INTERVAL duration MONTH)
//             WHEN rental_period = 'Annually' THEN DATE_ADD(start_date, INTERVAL duration YEAR)
//         END
//     ) STORED,

//     total_amount DECIMAL(14,2) GENERATED ALWAYS AS ( -- Total booking cost based on duration and rate
//         rental_price * duration
//     ) STORED,

//     payment_status ENUM('Pending', 'Paid', 'Cancelled') DEFAULT 'Pending', -- Payment status
//     booking_status ENUM('Confirmed', 'Cancelled', 'Completed', 'Pending') DEFAULT 'Pending', -- Booking status

//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Time of booking creation
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Last update time

//     FOREIGN KEY (property_id) REFERENCES propertyTable(property_id), -- Link to property
//     FOREIGN KEY (person_id) REFERENCES person(pid),                  -- Link to renter
//     FOREIGN KEY (agent_id) REFERENCES person(pid)                    -- Link to agent (if any)
// );
