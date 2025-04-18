<?php

class PropertyBookings {
    use Model;

    protected $table = 'property_bookings';
    protected $order_column = "booking_id";
    protected $allowedColumns = [
        'booking_id',
        'property_id',
        'person_id',

        //rental data
        'rental_type', //ENUM('short_term', 'commercial', 'monthly')

        //rental periods
        'check_in',
        'check_out',
        'duration_hours',
        'duration_days',

        //Pricing
        'base_rate',
        'rate_unit',
        'cleaning_fee',
        'security_deposit',
        'total_amount',

        //status tracking
        'booking_status', //ENUM('pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled')
        'is_active',
        'cancellation_reason',

        //timestamps
        'created_at',
        'updated_at'
    ];

    public $errors = [];

    public function validateBookingData($data) {
        $this->errors = [];

        if (array_key_exists('property_id', $data) && (!is_int($data['property_id']) || $data['property_id'] <= 0)) {
            $this->errors['property_id'] = 'Property ID must be a positive integer.';
        }

        if (array_key_exists('person_id', $data) && (!is_int($data['person_id']) || $data['person_id'] <= 0)) {
            $this->errors['person_id'] = 'Person ID must be a positive integer.';
        }

        if (array_key_exists('rental_type', $data) && !in_array($data['rental_type'], ['short_term', 'commercial', 'monthly'])) {
            $this->errors['rental_type'] = 'Invalid rental type.';
        }

        if (array_key_exists('check_in', $data)) {
            if (empty($data['check_in'])) {
                $this->errors['check_in'] = 'Check-in date is required.';
            } elseif (!strtotime($data['check_in'])) {
                $this->errors['check_in'] = 'Invalid check-in date format.';
            }
        }

        if (array_key_exists('check_out', $data)) {
            if (empty($data['check_out'])) {
                $this->errors['check_out'] = 'Check-out date is required.';
            } elseif (!empty($data['check_in']) && strtotime($data['check_in']) >= strtotime($data['check_out'])) {
                $this->errors['check_out'] = 'Check-out date must be after check-in date.';
            } elseif (!strtotime($data['check_out'])) {
                $this->errors['check_out'] = 'Invalid check-out date format.';
            }
        }

        if (array_key_exists('base_rate', $data) && (!is_numeric($data['base_rate']) || $data['base_rate'] <= 0)) {
            $this->errors['base_rate'] = 'Base rate must be a positive number.';
        }

        if (array_key_exists('rate_unit', $data) && !in_array($data['rate_unit'], ['per_hour', 'per_day', 'per_month'])) {
            $this->errors['rate_unit'] = 'Invalid rate unit.';
        }

        if (array_key_exists('cleaning_fee', $data) && (!is_numeric($data['cleaning_fee']) || $data['cleaning_fee'] < 0)) {
            $this->errors['cleaning_fee'] = 'Cleaning fee must be a non-negative number.';
        }

        if (array_key_exists('security_deposit', $data) && (!is_numeric($data['security_deposit']) || $data['security_deposit'] < 0)) {
            $this->errors['security_deposit'] = 'Security deposit must be a non-negative number.';
        }

        if (array_key_exists('booking_status', $data) && !in_array($data['booking_status'], ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])) {
            $this->errors['booking_status'] = 'Invalid booking status.';
        }

        return empty($this->errors);
    }

    // public function createShortTermBooking($propertyId, $personId, $checkIn = null, $checkOut = null, $baseRate, $rateUnit = 'per_hour') {
    //     // Set default check-in to current time if not provided
    //     if ($checkIn === null) {
    //         $checkIn = date('Y-m-d H:i:s');
    //     }
        
    //     // Set default check-out to 3 hours after check-in if not provided
    //     if ($checkOut === null) {
    //         $checkOut = date('Y-m-d H:i:s', strtotime($checkIn) + (3 * 60 * 60));
    //     }
        
    //     $data = [
    //         'property_id' => $propertyId,
    //         'person_id' => $personId,
    //         'rental_type' => 'short_term',
    //         'check_in' => $checkIn,
    //         'check_out' => $checkOut,
    //         'base_rate' => $baseRate,
    //         'rate_unit' => $rateUnit,
    //         'duration_hours' => 3, // Default duration
    //     ];

    //     if (!$this->validateBookingData($data)) {
    //         return $this->errors;
    //     }

    //     return $this->insert($data);
    // }
    // ok
    public function createCommercialBooking($propertyId, $personId, $checkIn = null, $checkOut = null, $baseRate, $rateUnit = 'per_day', $cleaningFee = 0.00) {
        // Set default check-in to current time if not provided
        if ($checkIn === null) {
            $checkIn = date('Y-m-d H:i:s');
        }
        
        // Set default check-out to 1 day after check-in if not provided
        if ($checkOut === null) {
            $checkOut = date('Y-m-d H:i:s', strtotime($checkIn) + (24 * 60 * 60));
        }
        
        $data = [
            'property_id' => $propertyId,
            'person_id' => $personId,
            'rental_type' => 'commercial',
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'base_rate' => $baseRate,
            'rate_unit' => $rateUnit,
            'cleaning_fee' => $cleaningFee,
        ];

        if (!$this->validateBookingData($data)) {
            return $this->errors;
        }

        return $this->insert($data);
    }
    //ok
    public function createMonthlyBooking($propertyId, $personId, $checkIn = null, $checkOut = null, $baseRate, $rateUnit = 'per_month', $securityDeposit = 0.00) {
        // Set default check-in to current time if not provided
        if ($checkIn === null) {
            $checkIn = date('Y-m-d H:i:s');
        }
        
        // Set default check-out to 3 months after check-in if not provided
        if ($checkOut === null) {
            $checkOut = date('Y-m-d H:i:s', strtotime($checkIn . ' + 3 months'));
        }
        
        $data = [
            'property_id' => $propertyId,
            'person_id' => $personId,
            'rental_type' => 'monthly',
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'base_rate' => $baseRate,
            'rate_unit' => $rateUnit,
            'security_deposit' => $securityDeposit,
            'duration_days' => 90, // Approximately 3 months
        ];

        if (!$this->validateBookingData($data)) {
            return $this->errors;
        }

        return $this->insert($data);
    }
    // ok
    public function getActiveBookingsForProperty($propertyId) {
        $params = ['property_id' => $propertyId];

        if (!$this->validateBookingData($params)) {
            return $this->errors;
        }

        $query = "SELECT b.*, p.name AS property_name, per.fname, per.lname
                  FROM property_bookings b
                  JOIN propertyTable p ON b.property_id = p.property_id
                  JOIN person per ON b.person_id = per.pid
                  WHERE b.property_id = :property_id
                  AND b.is_active = TRUE
                  ORDER BY b.check_in";


        return $this->instance->query($query, $params);
    }
    // ok
    public function getAllBookingsForProperty($propertyId) {
        $params = ['property_id' => $propertyId];

        if (!$this->validateBookingData($params)) {
            return $this->errors;
        }

        $query = "SELECT b.*, p.name AS property_name, per.fname, per.lname
                  FROM property_bookings b
                  JOIN propertyTable p ON b.property_id = p.property_id
                  JOIN person per ON b.person_id = per.pid
                  WHERE b.property_id = :property_id
                  ORDER BY b.check_in";


        return $this->instance->query($query, $params);
    }
    // ok 
    public function getPersonBookingHistory($personId) {
        $params = ['person_id' => $personId];

        if (!$this->validateBookingData($params)) {
            return $this->errors;
        }
        $query = "SELECT b.*
                  FROM property_bookings b
                  JOIN propertyTable p ON b.property_id = p.property_id
                  WHERE b.person_id = :person_id
                  ORDER BY b.check_in DESC";

        return $this->instance->query($query, $params);
    }
    // ok
    public function checkPropertyAvailability($propertyId, $startDate, $endDate) {
        $params = [
            'property_id' => $propertyId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        if (!$this->validateBookingData($params)) {
            return $this->errors;
        }
        
        $query = "SELECT * FROM property_bookings
                  WHERE property_id = :property_id
                  AND (
                      (check_in BETWEEN :start_date AND :end_date) OR
                      (check_out BETWEEN :start_date AND :end_date) OR
                      (check_in <= :start_date AND check_out >= :end_date)
                  )
                  AND is_active = TRUE";

        $result = $this->instance->query($query, $params);
        
        // If no bookings are found, the property is available
        return empty($result) ? true : false;
    }
    // ok
    public function cancelBooking($bookingId, $personId, $reason = 'Changed plans') {
        $params = [
            'reason' => $reason,
            'booking_id' => $bookingId,
            'person_id' => $personId
        ];

        if (!$this->validateBookingData($params)) {
            return $this->errors;
        }

        $query = "UPDATE property_bookings
                  SET is_active = FALSE,
                      booking_status = 'cancelled',
                      cancellation_reason = :reason,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE booking_id = :booking_id
                  AND person_id = :person_id";

        return $this->instance->query($query, $params);
    }
    // ok
    public function checkoutBooking($bookingId) {
        $params = [
            'booking_id' => $bookingId
        ];

        if (!$this->validateBookingData($params)) {
            return $this->errors;
        }

        $query = "UPDATE property_bookings
                  SET is_active = FALSE,
                      booking_status = 'checked_out',
                      check_out = CURDATE()
                  WHERE booking_id = :booking_id";
            // echo $query;
            // show($params);
        return $this->instance->query($query, $params);
    }

    public function terminateLeaseEarly($bookingId, $reason = 'Early termination request') {
        $params = [
            'reason' => $reason,
            'booking_id' => $bookingId
        ];

        if (!$this->validateBookingData($params)) {
            return $this->errors;
        }

        $query = "UPDATE property_bookings
                  SET is_active = FALSE,
                      booking_status = 'cancelled',
                      check_out = CURDATE(),
                      cancellation_reason = :reason
                  WHERE booking_id = :booking_id";
                  

        return $this->instance->query($query, $params);
    }

    // public function updateBookingStatus($bookingId, $newStatus) {
    //     $data = [
    //         'booking_id' => $bookingId,
    //         'booking_status' => $newStatus
    //     ];

    //     if (!$this->validateBookingData($data)) {
    //         return $this->errors;
    //     }

    //     $query = "UPDATE property_bookings
    //               SET booking_status = :booking_status,
    //                   updated_at = CURRENT_TIMESTAMP
    //               WHERE booking_id = :booking_id";

    //     return $this->instance->query($query, $data);
    // }
    private function testing(){
        $booking = new PropertyBookings;
        // Example usage of PropertyBookings methods
        $propertyId = 22;
        $personId = 84;


        // // Create a monthly booking
        // $monthlyResult = $booking->createMonthlyBooking($propertyId, $personId, '2023-11-01', '2023-11-30', 2000.00);
        // if (is_array($monthlyResult)) {
        //     echo "Errors: ";
        //     print_r($monthlyResult);
        // } else {
        //     echo "Monthly booking created successfully!";
        // }

        // Create a commercial booking
        // $commercialResult = $booking->createCommercialBooking($propertyId, $personId, '2023-10-01', '2023-10-31', 5000.00);
        // if (is_array($commercialResult)) {
        //     echo "Errors: ";
        //     print_r($commercialResult);
        // } else {
        //     echo "Commercial booking created successfully!<br>";
        // }

        // Check property availability
        // $availability = $booking->checkPropertyAvailability($propertyId, '2023-10-01', '2023-10-05');
        // if ($availability) {
        //     echo "Property is available for the selected dates.<br>";
        // } else {
        //     echo "Property is not available for the selected dates.<br>";

        // }

        // Get all bookings for a property
        // $activeBookings = $booking->getAllBookingsForProperty($propertyId);
        // if (is_array($activeBookings)) {
        //     echo "Errors: ";
        //     show($activeBookings);
        // } else {
        //     echo "Active bookings: ";
        //     print_r($activeBookings);
        // }
        
        // cancel booking
        // $cancelResult = $booking->cancelBooking(11,84);
        // if (is_array($cancelResult)) {
        //     echo "Errors: ";
        //     print_r($cancelResult);
        // } else {
        //     echo "Booking cancelled successfully!<br>";
        //     var_dump($cancelResult);
        // }

         // Get active bookings for a property
        // $activeBookings = $booking->getActiveBookingsForProperty($propertyId);
        // if (is_array($activeBookings)) {
        //     echo "Errors: ";
        //     show($activeBookings);
        // } else {
        //     echo "Active bookings: ";
        //     print_r($activeBookings);
        // }

        // Archive a completed booking
        // $bookingId = 10; // Example booking ID
        // $archiveResult = $booking->checkoutBooking($bookingId);
        // if (($archiveResult)) {
        //     echo "Booking with ID $bookingId archived successfully!<br>";
        // } else {
        //     echo "Booking with ID $bookingId failed successfully!<br>";
        // }

        // Terminate a lease early
        // $bookingId = 10; // Example booking ID
        // $reason = 'Tenant requested early termination due to relocation';
        // $terminationResult = $booking->terminateLeaseEarly($bookingId, $reason);
        // if ($terminationResult) {
        //     echo "Errors: ";
        //     print_r($terminationResult);
        // } else {
        //     echo "Lease terminated early successfully!<br>";
        // }

        // Get booking history for a person
        // $personBookingHistory = $booking->getPersonBookingHistory($personId);
        // if (is_array($personBookingHistory)) {
        //     echo "Errors: ";
        //     show($personBookingHistory);
        // } else {
        //     echo "Booking history for person ID $personId: ";
        //     print_r($personBookingHistory);
        // }
    }
}