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

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public $errors = [];

    public function validate($data)
    {
        $this->errors = []; // Reset errors

        if (isset($data['property_id']) && (empty($data['property_id']) || !is_numeric($data['property_id']))) {
            $this->errors['property_id'] = "Valid property ID is required";
        }

        if (isset($data['person_id']) && (empty($data['person_id']) || !is_numeric($data['person_id']))) {
            $this->errors['person_id'] = "Valid person ID is required";
        }

        if (isset($data['start_date'])) {
            if (empty($data['start_date'])) {
                $this->errors['start_date'] = "Start date is required";
            } else if (!strtotime($data['start_date'])) {
                $this->errors['start_date'] = "Start date is not a valid date";
            }
        }

        if (isset($data['duration'])) {
            if (empty($data['duration']) || !is_numeric($data['duration']) || $data['duration'] <= 0) {
                $this->errors['duration'] = "Duration must be a positive number";
            }
        }

        if (isset($data['rental_period'])) {
            if (empty($data['rental_period'])) {
                $this->errors['rental_period'] = "Rental period is required";
            } else if (!in_array($data['rental_period'], ['Daily', 'Monthly', 'Annually'])) {
                $this->errors['rental_period'] = "Rental period must be Daily, Monthly, or Annually";
            }
        }

        if (isset($data['rental_price'])) {
            if (empty($data['rental_price']) || !is_numeric($data['rental_price']) || $data['rental_price'] <= 0) {
                $this->errors['rental_price'] = "Rental price must be a positive number";
            }
        }

        if (isset($data['payment_status'])) {
            if (!in_array($data['payment_status'], ['Pending', 'Paid', 'Cancelled'])) {
                $this->errors['payment_status'] = "Payment status must be Pending, Paid, or Cancelled";
            }
        }

        if (isset($data['booking_status'])) {
            if (!in_array($data['booking_status'], ['Confirmed', 'Cancelled', 'Completed', 'Pending', 'Cancel Requested'])) {
                $this->errors['booking_status'] = "Booking status must be Confirmed, Cancelled, Completed, Pending, or Cancel Requested";
            }
        }

        if (isset($data['booking_id']) && (empty($data['booking_id']) || !is_numeric($data['booking_id']))) {
            $this->errors['booking_id'] = "Valid booking ID is required";
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
        if (!$this->validate($data)) {
            return false;
        }
        return $this->insert($data);
    }

    /**
     * Get the booking status for a property, owner, and date range.
     *
     * @param int $property_id
     * @param int $owner_id
     * @param string $check_in  (YYYY-MM-DD)
     * @param string $check_out (YYYY-MM-DD)
     * @return array|bool  ['booking_status' => ..., 'check_in' => ..., 'check_out' => ...] or null if not found
     */
    public function getPropertyStatusByOwnerAndDates($property_id, $owner_id, $check_in, $check_out)
    {
        // Use validation for only the provided fields
        $data = [
            'property_id' => $property_id,
            'person_id' => $owner_id,
            'start_date' => $check_in,
            'end_date' => $check_out
        ];
        if (!$this->validate($data)) {
            return false;
        }

        // Validate date logic
        if (strtotime($check_out) <= strtotime($check_in)) {
            $this->errors['date'] = "Check-out must be after check-in";
            return false;
        }

        $query = "SELECT booking_status, start_date AS check_in, end_date AS check_out
                  FROM $this->table
                  WHERE property_id = :property_id
                    AND person_id = :owner_id
                    AND start_date = :check_in
                    AND end_date = :check_out
                  ORDER BY booking_id DESC
                  LIMIT 1";
        $queryData = [
            'property_id' => $property_id,
            'owner_id' => $owner_id,
            'check_in' => $check_in,
            'check_out' => $check_out
        ];
        $result = $this->instance->query($query, $queryData);

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
        // Use validation for only the provided fields
        $data = [
            'property_id' => $property_id,
            'person_id' => $owner_id,
            'start_date' => $check_in,
            'end_date' => $check_out,
            'booking_status' => $new_status
        ];
        if (!$this->validate($data)) {
            return false;
        }

        // Validate date logic
        if (strtotime($check_out) <= strtotime($check_in)) {
            $this->errors['date'] = "Check-out must be after check-in";
            return false;
        }

        $query = "UPDATE $this->table
                  SET booking_status = :new_status";

        // If cancelling, update the end_date to current date
        if ($new_status == 'Cancelled') {
            $query .= ", end_date = CURRENT_DATE()";
        }

        $query .= " WHERE property_id = :property_id
                    AND person_id = :owner_id
                    AND start_date = :check_in
                    AND end_date = :check_out
                    LIMIT 1";

        $queryData = [
            'new_status'  => $new_status,
            'property_id' => $property_id,
            'owner_id'    => $owner_id,
            'check_in'    => $check_in,
            'check_out'   => $check_out
        ];
        $result = $this->instance->query($query, $queryData);
        return $result !== false;
    }

    /**
     * Get all booking orders for a given owner (person).
     *
     * @param int $owner_id
     * @return array|bool  Array of booking orders or null if none found
     */
    public function getOrdersByOwner($owner_id)
    {
        $data = ['person_id' => $owner_id];
        if (!$this->validate($data)) {
            return false;
        }
        $query = "SELECT * FROM $this->table WHERE person_id = :owner_id ORDER BY booking_id DESC";
        $result = $this->instance->query($query, ['owner_id' => $owner_id]);
        return (is_array($result) && !empty($result)) ? $result : null;
    }

    public function findById($booking_id)
    {
        // Validate booking_id
        if (!$this->validate(['booking_id' => $booking_id])) {
            return null;
        }
        $query = "SELECT * FROM $this->table WHERE booking_id = :booking_id LIMIT 1";
        $data = ['booking_id' => $booking_id];
        $result = $this->instance->query($query, $data);
        return (is_array($result) && !empty($result)) ? $result[0] : null;
    }

    public function updatePaymentStatus($booking_id, $status = 'Paid')
    {
        // Validate booking_id and payment_status
        if (!$this->validate(['booking_id' => $booking_id, 'payment_status' => $status])) {
            return false;
        }
        $query = "UPDATE $this->table SET payment_status = :status WHERE booking_id = :booking_id LIMIT 1";
        $data = [
            'status' => $status,
            'booking_id' => $booking_id
        ];
        return $this->instance->query($query, $data);
    }

    /**
     * Get pending bookings for a property with optional filters
     *
     * @param int $property_id The ID of the property
     * @param array $filters Optional additional filters (e.g., ['payment_status' => 'Pending'])
     * @return array|null Array of pending bookings or false if none found
     */
    public function getBookingsByPropertyId($property_id, $status = 'Pending', array $filters = [])
    {
        $data = ['property_id' => $property_id, 'booking_status' => $status];
        if (!$this->validate($data)) {
            return null;
        }
        // Validate the status parameter
        if (!in_array($status, ['Confirmed', 'Cancelled', 'Completed', 'Pending', 'Cancel Requested'])) {
            $this->errors['status'] = "Invalid booking status";
            return null;
        }

        $query = "SELECT * FROM $this->table WHERE property_id = :property_id AND booking_status = :status";

        // Add additional filters if provided
        foreach ($filters as $key => $value) {
            if (in_array($key, $this->allowedColumns)) {
                $query .= " AND $key = :$key";
                $data[$key] = $value;
            }
        }

        $query .= " ORDER BY booking_id DESC";
        $result = $this->instance->query($query, $data);

        return $result;
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
//     booking_status ENUM('Confirmed', 'Cancelled', 'Completed', 'Pending', 'Cancel Requested') DEFAULT 'Pending', -- Booking status

//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Time of booking creation
//     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, -- Last update time

//     FOREIGN KEY (property_id) REFERENCES propertyTable(property_id), -- Link to property
//     FOREIGN KEY (person_id) REFERENCES person(pid),                  -- Link to renter
//     FOREIGN KEY (agent_id) REFERENCES person(pid)                    -- Link to agent (if any)
// );
