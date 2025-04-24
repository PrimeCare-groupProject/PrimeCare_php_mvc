<?php
defined('ROOTPATH') or exit('Access denied');

class propertyListing{
    use controller;

    public function index(){
        $this->showListing();
    }

    public function showListing(){

        if(isset($_POST) && !empty($_POST)){
            // show($_POST);
            // show($_GET);
            // die;
            $searchTerm = '';
            if(!empty($_POST['searchTerm'])){
                $searchTerm = $_POST['searchTerm'];
                unset($_POST['searchTerm']);
            }

            // Handle sorting
            $sort_by = $_POST['sort_by'] ?? '';
            $sort_direction = 'DESC'; // Default sort direction
            $sort_column = ''; // Default no specific column sorting
            
            if ($sort_by === 'price-desc') {
                $sort_direction = 'DESC';
                $sort_column = 'rental_price';
            } elseif ($sort_by === 'price-asc') {
                $sort_direction = 'ASC';
                $sort_column = 'rental_price';
            }

            // Calculate the number of days between check-in and check-out dates
            $months = 0;
            $days = 0;
            $days_remaining = 0;

            if (!empty($_POST['check_in']) && !empty($_POST['check_out'])) {
                $check_in_date = new DateTime($_POST['check_in']);
                $check_out_date = new DateTime($_POST['check_out']);
                
                // Calculate total days between dates
                $interval = $check_in_date->diff($check_out_date);
                $days = $interval->days;
                
                // Calculate months and remaining days
                $months = ceil($days / 30);
                $days_remaining = $days % 30;
                

                // If days are greater than 1 month (30 days), unset rental_period to show all properties
                if ($days > 30) {
                    if (isset($_POST['rental_period'])) {
                        unset($_POST['rental_period']); // Show all properties regardless of rental period
                    }
                } else {
                    // Keep the given rental period if days are less than or equal to 30
                    // If rental_period is not set, default to 'Daily'
                    $_POST['rental_period'] = $_POST['rental_period'] ?? 'Daily';
                }
            }
            
            $bookingData = [
                'check_in' => $_POST['check_in'] ?? '',
                'check_out' => $_POST['check_out'] ?? '',
                'rental_period' => $_POST['rental_period'] ?? '',
                'months' => $months,
                'days' => $days,
                'days_remaining' => $days_remaining,
            ];
            
            
            $query_string = !empty($bookingData) ? http_build_query($bookingData) : '';

            // Add booking data to GET parameters for URLs/redirects
            // $_GET['check_in'] = $_POST['check_in'] ?? '';
            // $_GET['check_out'] = $_POST['check_out'] ?? '';
            // $_GET['rental_period'] = $_POST['rental_period'] ?? '';
            // $_GET['months'] = $months;
            // $_GET['days'] = $days;
            // $_GET['days_remaining'] = $days_remaining;
            // $_GET['query_string'] = $query_string ?? '';

            $propertyData = [
                'min_price' => $_POST['min_price'] ?? '',
                'max_price' => $_POST['max_price'] ?? '',
                'rental_period' => $_POST['rental_period'] ?? '' ,
                'type' => $_POST['property_type'] ?? '',
                'state_province' => $_POST['province'] ?? '',
                'city' => $_POST['city'] ?? '',
                'type_of_parking' => $_POST['parking_type'] ?? '',
                'furnished' => $_POST['furnishing'] ?? '',

                'bedrooms' => $_POST['bedrooms'] ?? '',
                'bathrooms' => $_POST['bathrooms'] ?? '',
                'kitchen' => $_POST['kitchens'] ?? '',
                'living_room' => $_POST['living_rooms'] ?? '',
                'parking_slots' => $_POST['parking_slots'] ?? '',
            ];

            $propertyData = array_filter($propertyData, function($value) {
                return $value !== '' && $value !== null;
            });
            $bookingData = array_filter($bookingData, function($value) {
                return $value !== '' && $value !== null;
            });
            // show($propertyData);
            // show($bookingData);
            // show($query_string);

            $PropertyConcat = new PropertyConcat;
            $BookingOrders = new BookingOrders;

            // Validate booking data before proceeding
            if (!empty($bookingData) && !$BookingOrders->validate($bookingData)) {
                $_SESSION['flash']['msg'] = "Invalid booking/filter data: " . implode(', ', $BookingOrders->errors);
                $_SESSION['flash']['type'] = "error";

                $property = new PropertyConcat;
                $properties = $property->where(['status' => 'Active']);

                $this->view('propertyListing', [
                    'properties' => ['properties' => $properties],
                    'bookingData' => $bookingData,
                    'query_string' => '',
                ]);
                return;
            }
            
            // 1. Get filtered properties
            $filteredProperties = $PropertyConcat->whereWithSearchTerm($propertyData, [], $searchTerm, $sort_direction , 100, 0, $sort_column);

            // 2. Filter by availability if check_in and check_out are provided
            $propertiesToShow = $filteredProperties;
            if (!empty($bookingData['check_in']) && !empty($bookingData['check_out']) && is_array($filteredProperties)) {
                $availableProperties = [];
                foreach ($filteredProperties as $property) {
                    if ($BookingOrders->isPropertyAvailable($property->property_id, $bookingData['check_in'], $bookingData['check_out'])) {
                        $availableProperties[] = $property;
                    }
                }
                $propertiesToShow = $availableProperties;
            }

            // Show flash message if no properties found
            if (empty($propertiesToShow)) {
                $_SESSION['flash']['msg'] = "No properties found for the selected criteria.";
                $_SESSION['flash']['type'] = "error";
            }

            $this->view('propertyListing', [
                'properties' => $propertiesToShow, 
                'bookingData' => $bookingData, 
                'query_string' => $query_string,
            ]);
            return;
        }

        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'Active']);
        $this->view('propertyListing' , ['properties' => $properties]);
    }

    public function showListingDetail($propertyID){
        // show($_GET);
        // show($_POST);
        // die;
        if (empty($propertyID) || !is_numeric($propertyID)) {
            redirect('propertyListing/showListing');
            return;
        }
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyID])[0];
        $BookingOrders = new BookingOrders;

        // Default dates from GET or fallback
        // Get rental period from GET or use property's default
        $rental_period = $_GET['rental_period'] ?? $propertyUnit->rental_period;
        
        // Set default dates based on rental period if not provided in GET
        if (isset($_GET['check_in']) && isset($_GET['check_out'])) {
            $check_in = $_GET['check_in'];
            $check_out = $_GET['check_out'];
        } else {
            $check_in = date('Y-m-d'); // Today as default check-in
            
            // Set check-out based on rental period
            switch (strtolower($rental_period)) {
            case 'monthly':
                $check_out = date('Y-m-d', strtotime('+1 month'));
                break;
            case 'weekly':
                $check_out = date('Y-m-d', strtotime('+1 week'));
                break;
            default: // Daily or any other period
                $check_out = date('Y-m-d', strtotime('+1 day'));
                break;
            }
        }
        $period_duration = $_GET['period_duration'] ?? null;
        $months = $_GET['months'] ?? null;
        $days = $_GET['days'] ?? null;
        $days_remaining = $_GET['days_remaining'] ?? null;

        // If form submitted, check availability
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_booking') {
            $new_check_in = $_POST['check_in'];
            $new_check_out = $_POST['check_out'];
            $new_rental_period = $_POST['rental_period'] ?? $rental_period;
            $new_period_duration = $_POST['period_duration'] ?? $period_duration;

            // Calculate new days/months
            $months = 0;
            $days = 0;
            $days_remaining = 0;
            if (!empty($new_check_in) && !empty($new_check_out)) {
                $check_in_date = new DateTime($new_check_in);
                $check_out_date = new DateTime($new_check_out);
                $interval = $check_in_date->diff($check_out_date);
                $days = $interval->days;
                $months = ceil($days / 30);
                $days_remaining = $days % 30;
            }
            
            $isAvailable = $BookingOrders->isPropertyAvailable($propertyID, $new_check_in, $new_check_out);

            if (!$isAvailable) {
                if (!empty($BookingOrders->errors)) {
                    $_SESSION['flash']['msg'] = "Booking error: " . implode(', ', $BookingOrders->errors);
                } else {
                    $_SESSION['flash']['msg'] = "Property is not available for the selected dates.";
                }
                $_SESSION['flash']['type'] = "error";
                // Return to original page with original query string
                $query_string = $_POST['query_string'] ?? '';
                // Convert query string to array if not empty
                if (!empty($query_string)) {
                    parse_str($query_string, $query_array);
                    unset($query_array['url']);
                    $query_string = http_build_query($query_array);
                }
                redirect("propertyListing/showListingDetail/{$propertyID}" . ($query_string ? "?{$query_string}" : ''));
                return;
            } else {
                // Update parameters with new values
                $bookingData = [
                    'check_in' => $new_check_in,
                    'check_out' => $new_check_out,
                    'rental_period' => $new_rental_period,
                    'period_duration' => $new_period_duration,
                    'months' => $months,
                    'days' => $days,
                    'days_remaining' => $days_remaining,
                ];
                $bookingData = array_filter($bookingData, function($value) {
                    return $value !== '' && $value !== null;
                });
                $query_string = http_build_query($bookingData);

                $_SESSION['flash']['msg'] = "Dates updated successfully.";
                $_SESSION['flash']['type'] = "success";
                redirect("propertyListing/showListingDetail/{$propertyID}?{$query_string}");
                return;
            }
        }

        // Build bookingData and query_string just like in showListing
        $months = 0;
        $days = 0;
        $days_remaining = 0;

        if (!empty($check_in) && !empty($check_out)) {
            $check_in_date = new DateTime($check_in);
            $check_out_date = new DateTime($check_out);

            // Calculate total days between dates
            $interval = $check_in_date->diff($check_out_date);
            $days = $interval->days;

            // Calculate months and remaining days
            $months = ceil($days / 30);
            $days_remaining = $days % 30;
        }
       
        // Calculate booking summary
        $price_per_period = (float)$propertyUnit->rental_price;
        $booking_type = $propertyUnit->rental_period;

        if (strtolower($booking_type) == 'monthly') {
            $total_price = $price_per_period * $months;
        } else {
            $total_price = $price_per_period * $days;
        }

        $bookingSummary = [
            'check_in' => $check_in,
            'check_out' => $check_out,
            'days' => $days,
            'months' => $months,
            'total_price' => $total_price,
        ];

        // setting status optionally
        $currentBookingStatus = null;
        if (isset($_SESSION['user'])) {
            $BookingOrders = new BookingOrders();
            $currentBooking = $BookingOrders->getPropertyStatusByOwnerAndDates(
                $propertyUnit->property_id,
                $_SESSION['user']->pid,
                $bookingSummary['check_in'],
                $bookingSummary['check_out']
            );

            if ($currentBooking) {
                $currentBookingStatus = $currentBooking['booking_status'];
            }
        }

        $this->view('propertyUnit', [
            'property' => $propertyUnit,
            'bookingSummary' => $bookingSummary,
            'currentBookingStatus' => $currentBookingStatus
        ]);
    }

    public function bookProperty(){
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            // Convert query string to array if not empty
            if (!empty($query_string)) {
                parse_str($query_string, $query_array);
                unset($query_array['url']);
                $query_string = http_build_query($query_array);
            }
            $_SESSION['flash']['msg'] = "Login to continue.";
            $_SESSION['flash']['type'] = "welcome";

            // Check session user type and set to 0 if not 0
            if (isset($_SESSION['customerView'])) {
                $_SESSION['customerView'] = !$_SESSION['customerView'];
            } else {
                $_SESSION['customerView'] = true;
            }
            
            $_SESSION['redirect_url'] = "propertyListing/showListingDetail/{$_POST['p_id']}" . ($query_string ? "?{$query_string}" : '');

            redirect('login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['p_id'])) {
            $property_id = (int)$_POST['p_id'];
            $person_id = (int)$_SESSION['user']->pid;
            $agent_id = null; // Set if you have agent logic
            $check_in = $_POST['check_in'] ?? null;
            $check_out = $_POST['check_out'] ?? null;
            $rental_period = $_POST['rental_period'] ?? 'Daily';
            $period_duration = $_POST['period_duration'] ?? null;

            // Get property details for price
            $propertyModel = new PropertyConcat();
            $property = $propertyModel->where(['property_id' => $property_id])[0] ?? null;
            if (!$property) {
                $_SESSION['flash']['msg'] = "Invalid property.";
                $_SESSION['flash']['type'] = "error";
                redirect("propertyListing/showListingDetail/{$property_id}");
                return;
            }

            // Calculate duration
            $check_in_date = new DateTime($check_in);
            $check_out_date = new DateTime($check_out);
            $days = $check_in_date->diff($check_out_date)->days;
            $duration = (strtolower($rental_period) == 'monthly') ? ceil($days / 30) : $days;

            $BookingOrders = new BookingOrders();

            // Check if property is available for the selected dates
            if (!$BookingOrders->isPropertyAvailable($property_id, $check_in, $check_out)) {
                $_SESSION['flash']['msg'] = "Property is not available for the selected dates.";
                $_SESSION['flash']['type'] = "error";

                // Manually build the query string for redirect
                $params = [
                    'check_in'      => $check_in,
                    'check_out'     => $check_out,
                    'rental_period' => $rental_period,
                    'period_duration' => $period_duration,
                ];
                $query = '';
                foreach ($params as $key => $value) {
                    if ($value !== null && $value !== '') {
                        $query .= ($query === '' ? '' : '&') . $key . '=' . urlencode($value);
                    }
                }

                redirect("propertyListing/showListingDetail/{$property_id}" . ($query ? "?{$query}" : ''));
                return;
            }

            // Prepare booking data
            $bookingData = [
                'property_id'    => $property_id,
                'person_id'      => $person_id,
                'agent_id'       => $agent_id,
                'start_date'     => $check_in,
                'duration'       => $duration,
                'rental_period'  => $rental_period,
                'rental_price'   => $property->rental_price,
                'payment_status' => 'Pending',
                'booking_status' => 'Pending'
            ];

            $booking_id = $BookingOrders->createBooking($bookingData);

            if ($booking_id) {
                $_SESSION['flash']['msg'] = "Your booking has been successfully created!";
                $_SESSION['flash']['type'] = "success";
                redirect('dashboard/occupiedProperties');
                return;
            } else {
                $error_message = "Your booking was declined. Try again!";
                // Check if there are any specific errors from the model
                if (!empty($BookingOrders->errors)) {
                    $error_message .= " Reason: " . implode(', ', $BookingOrders->errors);
                }
                $_SESSION['flash']['msg'] = $error_message;
                $_SESSION['flash']['type'] = "error";
                // Reload current page with POST params as GET
                $params = [
                    'check_in'      => $check_in,
                    'check_out'     => $check_out,
                    'rental_period' => $rental_period,
                    'period_duration' => $period_duration,
                ];
                $query = '';
                foreach ($params as $key => $value) {
                    if ($value !== null && $value !== '') {
                        $query .= ($query === '' ? '' : '&') . $key . '=' . urlencode($value);
                    }
                }
                redirect("propertyListing/showListingDetail/{$property_id}" . ($query ? "?{$query}" : ''));
                return;
            }
        } else {
            redirect('propertyListing/showListing');
        }
    }
}