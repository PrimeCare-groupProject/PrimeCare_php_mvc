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
            // echo "Property ID, check-in, and check-out dates are required.";
            return false;
        }
        
        // Validate date formats
        if (!strtotime($check_in) || !strtotime($check_out)) {
            // echo "Invalid date format. Please use YYYY-MM-DD.";
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
            // echo "retured false as No bookings found.";
            return true; // If query failed, assume property is not available
        }
        
        return count($bookings) === 0;
    }

    public function createBooking($data)
    {
        // Validate data before insert
        if (!$this->validate($data)) {
            return false; // $this->errors will contain validation errors
        }

        // Insert booking (returns inserted ID or false)
        return $this->insert($data);
    }

    /**
     * Get the booking status for a property, owner, and date range.
     *
     * @param int $property_id
     * @param int $owner_id
     * @param string $check_in  (YYYY-MM-DD)
     * @param string $check_out (YYYY-MM-DD)
     * @return array|null  ['booking_status' => ..., 'check_in' => ..., 'check_out' => ...] or null if not found
     */
    public function getPropertyStatusByOwnerAndDates($property_id, $owner_id, $check_in, $check_out)
    {
        // Validate inputs
        if (!$property_id || !$owner_id || !$check_in || !$check_out) {
            return null; // Missing required parameters
        }
        
        // Validate date formats
        if (!strtotime($check_in) || !strtotime($check_out)) {
            return null; // Invalid date format
        }
        
        // Ensure check_out is after check_in
        if (strtotime($check_out) <= strtotime($check_in)) {
            return null; // Invalid date range
        }
        
        $query = "SELECT booking_status, start_date AS check_in, end_date AS check_out
                  FROM $this->table
                  WHERE property_id = :property_id
                    AND person_id = :owner_id
                    AND start_date = :check_in
                    AND end_date = :check_out
                  ORDER BY booking_id DESC
                  LIMIT 1";
        $data = [
            'property_id' => $property_id,
            'owner_id' => $owner_id,
            'check_in' => $check_in,
            'check_out' => $check_out
        ];
        $result = $this->instance->query($query, $data);

        if (is_array($result) && !empty($result)) {
            return [
                'booking_status' => $result[0]->booking_status,
                'check_in' => $result[0]->check_in,
                'check_out' => $result[0]->check_out,
            ];
        }
        return null;
    }

    /**
     * Update the booking status for a property, owner, and date range.
     *
     * @param int $property_id
     * @param int $owner_id
     * @param string $check_in  (YYYY-MM-DD)
     * @param string $check_out (YYYY-MM-DD)
     * @param string $new_status  (e.g. 'Confirmed', 'Cancelled', 'Completed', 'Pending')
     * @return bool True if update was successful, false otherwise
     */

    public function updateBookingStatusByOwnerAndDates($property_id, $owner_id, $check_in, $check_out, $new_status)
    {
        // Validate inputs
        if (!$property_id || !is_numeric($property_id)) {
            $this->errors['property_id'] = "Valid property ID is required";
            return false;
        }
        
        if (!$owner_id || !is_numeric($owner_id)) {
            $this->errors['owner_id'] = "Valid owner ID is required";
            return false;
        }
        
        if (!$check_in) {
            $this->errors['check_in'] = "Check-in date is required";
            return false;
        } else if (!strtotime($check_in)) {
            $this->errors['check_in'] = "Check-in date is not a valid date";
            return false;
        }
        
        if (!$check_out) {
            $this->errors['check_out'] = "Check-out date is required";
            return false;
        } else if (!strtotime($check_out)) {
            $this->errors['check_out'] = "Check-out date is not a valid date";
            return false;
        }
        
        // Validate the status is one of the allowed values
        $allowed_statuses = ['Confirmed', 'Cancelled', 'Completed', 'Pending'];
        if (!in_array($new_status, $allowed_statuses)) {
            $this->errors['booking_status'] = "Booking status must be Confirmed, Cancelled, Completed, or Pending";
            return false;
        }
        
        $query = "UPDATE $this->table
                  SET booking_status = :new_status
                  WHERE property_id = :property_id
                    AND person_id = :owner_id
                    AND start_date = :check_in
                    AND end_date = :check_out
                  LIMIT 1";
        $data = [
            'new_status'  => $new_status,
            'property_id' => $property_id,
            'owner_id'    => $owner_id,
            'check_in'    => $check_in,
            'check_out'   => $check_out
        ];
        $result = $this->instance->query($query, $data);
        return $result !== false;
    }

    /**
     * Get all booking orders for a given owner (person).
     *
     * @param int $owner_id
     * @return array|null  Array of booking orders or null if none found
     */
    public function getOrdersByOwner($owner_id)
    {
        if (!$owner_id || !is_numeric($owner_id)) {
            $this->errors['owner_id'] = "Valid owner ID is required";
            return null;
        }

        $query = "SELECT * FROM $this->table WHERE person_id = :owner_id ORDER BY booking_id DESC";
        $data = ['owner_id' => $owner_id];
        $result = $this->instance->query($query, $data);

        return (is_array($result) && !empty($result)) ? $result : null;
    }

    public function findById($booking_id)
    {
        $query = "SELECT * FROM $this->table WHERE booking_id = :booking_id LIMIT 1";
        $data = ['booking_id' => $booking_id];
        $result = $this->instance->query($query, $data);
        return (is_array($result) && !empty($result)) ? $result[0] : null;
    }

    public function updatePaymentStatus($booking_id, $status = 'Paid')
    {
        $query = "UPDATE $this->table SET payment_status = :status WHERE booking_id = :booking_id LIMIT 1";
        $data = [
            'status' => $status,
            'booking_id' => $booking_id
        ];
        return $this->instance->query($query, $data);
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
