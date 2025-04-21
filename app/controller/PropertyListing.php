<?php
defined('ROOTPATH') or exit('Access denied');

class propertyListing{
    use controller;

    public function index(){
        $this->showListing();
    }

    public function showListing(){
        if(isset($_POST) && !empty($_POST)){
            show($_POST);
            die;
            $propertiesFromView = new PropertySearchView;
            $propertiesids = $propertiesFromView->advancedSearch($_POST,  ['property_id']);
            
            if (!is_bool($propertiesids) && !empty($propertiesids)) {
                $propertiesids = array_unique(array_map(function($obj) {
                    return $obj->property_id;
                }, $propertiesids));
                
                if(!empty($propertiesids)){
                    $property = new PropertyConcat;
                    $properties = $property->getByPropertyIds($propertiesids);
                    $this->view('propertyListing' , ['properties' => $properties]);
                    return;
                }
            } else {
                $_SESSION['flash']['msg'] = "No properties found for the selected criteria.";
                $_SESSION['flash']['type'] = "error";
            }

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
        // show($propertyUnit);
        // die;
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
                    // echo "Check Availability request received.<br>";
                    $bookingResults = $booking->checkPropertyAvailability((int)$_POST['property_id'], $_POST['check_in'], $_POST['check_out']);
                    
                    if($bookingResults) {
                        $_SESSION['flash']['msg'] = "Property is available for the selected dates.";
                        $_SESSION['flash']['type'] = "success";
                        // echo "Property is available for the selected dates.<br>";
                    } else {
                        $_SESSION['flash']['msg'] = "Property is not available for the selected dates.";
                        $_SESSION['flash']['type'] = "error";
                        // echo "Property is not available for the selected dates.<br>";
                    }

                    // show($_POST);
                    // show($_GET);
                    break;
                case 'book_now':
                    // echo "Book Now request received.";
                    // show($_POST);
                    // show($_GET);
                    $creationResults = $booking->createCommercialBooking((int)$_POST['property_id'], (int)$_SESSION['user']->pid, $_POST['check_in'], $_POST['check_out'], 10000);
                    if ($creationResults) {
                        $_SESSION['flash']['msg'] = "Your booking has been successfully created!";
                        $_SESSION['flash']['type'] = "success";
                        
                        redirect('propertyListing/showListing');

                        // echo "Booking created successfully!<br>";
                        // var_dump($creationResults);
                    } else {
                        $_SESSION['flash']['msg'] = "Your booking was declined. Try again!";
                        $_SESSION['flash']['type'] = "error";
                        // echo "Errors: ";
                        // print_r($creationResults);
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