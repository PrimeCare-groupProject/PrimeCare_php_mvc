<?php
defined('ROOTPATH') or exit('Access denied');

class propertyListing{
    use controller;

    public function index(){
        $this->showListing();
    }

    public function showListing(){
        if(isset($_POST) && !empty($_POST)){
            $searchTerm = '';
            if(!empty($_POST['searchTerm'])){
                $searchTerm = $_POST['searchTerm'];
                unset($_POST['searchTerm']);
            }
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

            $propertyData = [
                'min_price' => $_POST['min_price'] ?? '',
                'max_price' => $_POST['max_price'] ?? '',
                'rental_period' => $_POST['rental_period'] ?? 'Daily',
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
            $bookingData = [
                'check_in' => $_POST['check_in'] ?? '',
                'check_out' => $_POST['check_out'] ?? '',
            ];
            $propertyData = array_filter($propertyData, function($value) {
                return $value !== '' && $value !== null;
            });
            $bookingData = array_filter($bookingData, function($value) {
                return $value !== '' && $value !== null;
            });

            $PropertyConcat = new PropertyConcat;
            $BookingOrders = new BookingOrders;

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

            $this->view('propertyListing', ['properties' => $propertiesToShow]);
            return;
        }

        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'Active']);
        $this->view('propertyListing' , ['properties' => $properties]);
    }

    public function showListingDetail($propertyID){
        if (empty($propertyID) || !is_numeric($propertyID)) {
            redirect('propertyListing/showListing');
            return;
        }
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyID])[0];
        $BookingOrders = new BookingOrders;

        // Default dates
        $check_in = isset($_GET['check_in']) ? $_GET['check_in'] : date('Y-m-d');
        $check_out = isset($_GET['check_out']) ? $_GET['check_out'] : date('Y-m-d', strtotime('+1 day'));
        $rental_period = isset($_GET['rental_period']) ? $_GET['rental_period'] : $propertyUnit->rental_period;
        $period_duration = isset($_GET['period_duration']) ? $_GET['period_duration'] : null;

        // If form submitted, check availability
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_booking') {
            $new_check_in = $_POST['check_in'];
            $new_check_out = $_POST['check_out'];
            $rental_period = $_POST['rental_period'] ?? 'Daily';
            
            // Calculate duration based on dates
            $check_in_date = new DateTime($new_check_in);
            $check_out_date = new DateTime($new_check_out);
            $days = $check_in_date->diff($check_out_date)->days;
            
            // Calculate period duration based on rental period
            $new_period_duration = (strtolower($rental_period) == 'monthly') ? ceil($days / 30) : $days;
            
            $isAvailable = $BookingOrders->isPropertyAvailable($propertyID, $new_check_in, $new_check_out);
            
            // Build params from POST (hidden fields) to preserve all relevant data
            $params = [
                'check_in' => $isAvailable ? $new_check_in : ($_POST['check_in'] ?? $new_check_in),
                'check_out' => $isAvailable ? $new_check_out : ($_POST['check_out'] ?? $new_check_out),
                'rental_period' => $rental_period,
                'period_duration' => $isAvailable ? $new_period_duration : ($_POST['period_duration'] ?? 1),
            ];
            $query = http_build_query($params);
            // echo($query);
            // die;
            $_SESSION['flash']['msg'] = $isAvailable ? "Dates updated successfully." : "Property is not available for the selected dates.";
            $_SESSION['flash']['type'] = $isAvailable ? "success" : "error";

            redirect("propertyListing/showListingDetail/{$propertyID}?{$query}");
            return;
        }

        // Calculate booking summary
        $check_in_date = new DateTime($check_in);
        $check_out_date = new DateTime($check_out);
        $days = (int)$check_in_date->diff($check_out_date)->days;
        $price_per_period = (float)$propertyUnit->rental_price;

        if (strtolower($propertyUnit->rental_period) == 'monthly') {
            $months = $period_duration !== null ? (int)$period_duration : (int)ceil($days / 30);
            $total_price = $price_per_period * $months;
        } else {
            $months = null;
            $total_price = $price_per_period * $days;
        }

        $bookingSummary = [
            'check_in' => $check_in,
            'check_out' => $check_out,
            'days' => $days,
            'months' => $months,
            'total_price' => $total_price,
        ];

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
            $relativeUrl = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '/public') + 7);
            $_SESSION['redirect_url'] = $relativeUrl;

            $_SESSION['flash']['msg'] = "Login to continue.";
            $_SESSION['flash']['type'] = "welcome";

            redirect('login');
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
                $_SESSION['flash']['msg'] = "Your booking was declined. Try again!";
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