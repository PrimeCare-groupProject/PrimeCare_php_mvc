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
            // show($filteredProperties);
            // 2. Filter by availability if check_in and check_out are provided
            // $availableProperties = [];
            // if (!empty($bookingData['check_in']) && !empty($bookingData['check_out']) && is_array($filteredProperties)) {
            //     foreach ($filteredProperties as $property) {
            //         if ($BookingOrders->isPropertyAvailable($property->property_id, $bookingData['check_in'], $bookingData['check_out'])) {
            //             $availableProperties[] = $property;
            //         }
            //     }
            // } else {
            //     $availableProperties = $filteredProperties;
            // }

            $this->view('propertyListing', ['properties' => $filteredProperties]);
            return;
        }

        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'pending']);
        $this->view('propertyListing' , ['properties' => $properties]);
    }

    public function showListingDetail($propertyID){
        if (empty($propertyID) || !is_numeric($propertyID)) {
            redirect('propertyListing/showListing');
            return;
        }
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyID])[0];
        $this->view('propertyUnit' , ['property' => $propertyUnit]);
    }

    public function bookProperty(){
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $relativeUrl = substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '/public') + 7);
            $_SESSION['redirect_url'] = $relativeUrl;

            $_SESSION['flash']['msg'] = "Login to continue.";
            $_SESSION['flash']['type'] = "welcome";

            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $action = $_POST['action'];
            $bookingResults = false;
            $booking = new PropertyBookings;
            
            switch ($action) {
                case 'check_availability':
                    $bookingResults = $booking->checkPropertyAvailability((int)$_POST['property_id'], $_POST['check_in'], $_POST['check_out']);
                    
                    if($bookingResults) {
                        $_SESSION['flash']['msg'] = "Property is available for the selected dates.";
                        $_SESSION['flash']['type'] = "success";
                    } else {
                        $_SESSION['flash']['msg'] = "Property is not available for the selected dates.";
                        $_SESSION['flash']['type'] = "error";
                    }
                    break;
                case 'book_now':
                    $creationResults = $booking->createCommercialBooking((int)$_POST['property_id'], (int)$_SESSION['user']->pid, $_POST['check_in'], $_POST['check_out'], 10000);
                    if ($creationResults) {
                        $_SESSION['flash']['msg'] = "Your booking has been successfully created!";
                        $_SESSION['flash']['type'] = "success";
                        
                        redirect('propertyListing/showListing');
                    } else {
                        $_SESSION['flash']['msg'] = "Your booking was declined. Try again!";
                        $_SESSION['flash']['type'] = "error";
                    }
                    die;
                    break;
                default:
                    redirect('propertyListing/showListing');
                    die;
                    break;
            }
        }

        if (isset($_GET['p_id']) && !empty($_GET['p_id'])) {
            $this->view('bookProperty',['isAvailable'=>$bookingResults ?? false]);

        } else {
            redirect('propertyListing/showListing');
        }
    }
}