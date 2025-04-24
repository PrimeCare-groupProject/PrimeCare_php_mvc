<?php
defined('ROOTPATH') or exit('Access denied');

class Customer
{
    use controller;

    public function index()
    {   
        $this->dashboard();
        // redirect('dashboard/profile');
        // $this->view('profile', [
        //     'user' => $_SESSION['user'],
        //     'errors' => $_SESSION['errors'] ?? [],
        //     'status' => $_SESSION['status'] ?? ''
        // ]);
    }

    public function dashboard()
    {
        // Get user ID
        $userId = $_SESSION['user']->pid;
        
        // Initialize models
        $bookingOrdersModel = new BookingOrders();
        $serviceRequestModel = new ServiceLog();
        $externalServiceModel = new ExternalService();
        $propertyModel = new PropertyConcat();
        
        // Get all bookings for this customer
        $bookings = $bookingOrdersModel->getOrdersByOwner($userId);
        
        // Filter for active bookings and get current property
        $activeBookings = [];
        $currentBooking = null;
        $currentProperty = null;
        
        if ($bookings) {
            foreach ($bookings as $booking) {
                // Convert status to lowercase for case-insensitive comparison
                $bookingStatus = strtolower($booking->booking_status ?? '');
                $paymentStatus = strtolower($booking->payment_status ?? '');
                
                // Consider a booking active if it's confirmed/active and not cancelled
                if ($bookingStatus === 'confirmed' || $bookingStatus === 'active') {
                    // Check that the booking period includes the current date
                    $today = strtotime('now');
                    $startDate = strtotime($booking->start_date ?? 'now');
                    $endDate = strtotime($booking->end_date ?? 'now');
                    
                    if ($today >= $startDate && $today <= $endDate) {
                        $activeBookings[] = $booking;
                        
                        // Set the most recent active booking for current property display
                        if (!$currentBooking || strtotime($booking->start_date) > strtotime($currentBooking->start_date)) {
                            $currentBooking = $booking;
                        }
                    }
                }
            }
        }
        
        // Get current property details if there's an active booking
        if ($currentBooking) {
            
            $currentProperty = $propertyModel->first(['property_id' => $currentBooking->property_id]);
            
            // If property_images exists but needs decoding
            if ($currentProperty && !empty($currentProperty->property_images) && is_string($currentProperty->property_images)) {
                // Try to decode JSON property_images
                $decodedImages = json_decode($currentProperty->property_images);
                if (is_array($decodedImages)) {
                    $currentProperty->property_images = $decodedImages;
                }
            }
        }
        
        // Get service requests
        try {
            $serviceRequests = $serviceRequestModel->where(['requested_person_id' => $userId]);
        } catch (Exception $e) {
            try {
                $serviceRequests = $serviceRequestModel->where(['customer_id' => $userId]);
            } catch (Exception $e) {
                $serviceRequests = [];
            }
        }
        
        // Calculate regular service costs
        $totalRegularServiceCost = 0;
        if ($serviceRequests) {
            foreach ($serviceRequests as $service) {
                $totalRegularServiceCost += ($service->total_cost ?? $service->cost ?? 0);
            }
        }
        
        // Get external service requests
        $externalRequests = $externalServiceModel->where(['requested_person_id' => $userId]);
        $totalExternalServiceCost = 0;
        if ($externalRequests) {
            foreach ($externalRequests as $service) {
                $hoursCost = ($service->total_hours ?? 0) * ($service->cost_per_hour ?? 0);
                $additionalCost = $service->additional_charges ?? 0;
                $totalExternalServiceCost += ($hoursCost + $additionalCost);
            }
        }
        
        // Total expenses
        $totalExpenses = $totalRegularServiceCost + $totalExternalServiceCost;
        
        // Process service requests arrays
        $allServiceRequestsRegular = [];
        $allServiceRequestsExternal = [];
        
        if ($serviceRequests) {
            foreach ($serviceRequests as $service) {
                $allServiceRequestsRegular[] = (object)[
                    'id' => $service->service_id ?? $service->id ?? null,
                    'type' => 'regular',
                    'date' => $service->date ?? $service->created_at ?? date('Y-m-d'),
                    'service_type' => $service->service_type ?? $service->type ?? 'Maintenance',
                    'service_description' => $service->service_description ?? $service->description ?? '',
                    'status' => $service->status ?? 'Pending',
                    'cost' => $service->total_cost ?? $service->cost ?? 0
                ];
            }
        }
        
        if ($externalRequests) {
            foreach ($externalRequests as $service) {
                $allServiceRequestsExternal[] = (object)[
                    'id' => $service->id ?? null,
                    'type' => 'external',
                    'date' => $service->date ?? $service->created_at ?? date('Y-m-d'),
                    'service_type' => $service->service_type ?? 'External Service',
                    'service_description' => $service->property_description ?? '',
                    'property_address' => $service->property_address ?? '',
                    'status' => $service->status ?? 'Pending',
                    'cost' => (($service->total_hours ?? 0) * ($service->cost_per_hour ?? 0)) + ($service->additional_charges ?? 0),
                    'service_images' => $service->service_images ?? null
                ];
            }
        }
        
        // Sort service requests
        if (!empty($allServiceRequestsRegular)) {
            usort($allServiceRequestsRegular, function($a, $b) {
                $dateA = isset($a->date) && $a->date ? strtotime($a->date) : strtotime('now');
                $dateB = isset($b->date) && $b->date ? strtotime($b->date) : strtotime('now');
                return $dateB - $dateA;
            });
        }
    
        if (!empty($allServiceRequestsExternal)) {
            usort($allServiceRequestsExternal, function($a, $b) {
                $dateA = isset($a->date) && $a->date ? strtotime($a->date) : strtotime('now');
                $dateB = isset($b->date) && $b->date ? strtotime($b->date) : strtotime('now');
                return $dateB - $dateA;
            });
        }
        
        // Get rental history with enhanced details using BookingOrders
        $rentalHistory = [];
        if ($bookings) {
            foreach ($bookings as $booking) {
                // Get property details for each booking using PropertyConcat for images
                $property = $propertyModel->first(['property_id' => $booking->property_id]);
                
                // Calculate rental status based on dates and booking status
                $rentalStatus = $booking->booking_status ?? 'Unknown';
                $today = strtotime('now');
                $startDate = strtotime($booking->start_date ?? 'now');
                $endDate = strtotime($booking->end_date ?? 'now');
                
                // Override with more descriptive status
                if (strtolower($rentalStatus) === 'confirmed' && $today >= $startDate && $today <= $endDate) {
                    $rentalStatus = 'Active';
                } else if (strtolower($rentalStatus) === 'confirmed' && $today > $endDate) {
                    $rentalStatus = 'Completed';
                } else if (strtolower($rentalStatus) === 'confirmed' && $today < $startDate) {
                    $rentalStatus = 'Upcoming';
                }
                
                $rentalHistory[] = (object)[
                    'property_id' => $booking->property_id,
                    'booking_id' => $booking->booking_id,
                    'property_name' => $property ? ($property->name ?? "Property #" . $booking->property_id) : "Property #" . $booking->property_id,
                    'property_images' => $property ? $property->property_images : null,
                    'start_date' => $booking->start_date ?? '',
                    'end_date' => $booking->end_date ?? '',
                    'price' => $booking->total_amount ?? $booking->rental_price ?? 0,
                    'payment_status' => $booking->payment_status ?? 'Unknown',
                    'status' => $rentalStatus
                ];
            }
        }
        
        // Generate monthly expense data for chart
        $monthlyExpenses = [];
        $sixMonthsAgo = strtotime('-6 months');
        
        for ($i = 5; $i >= 0; $i--) {
            $month = date('M', strtotime("-$i months"));
            $monthlyExpenses[$month] = 0;
        }
        
        if ($serviceRequests) {
            foreach ($serviceRequests as $service) {
                if (isset($service->date)) {
                    $serviceTime = strtotime($service->date);
                    if ($serviceTime >= $sixMonthsAgo) {
                        $month = date('M', $serviceTime);
                        if (isset($monthlyExpenses[$month])) {
                            $monthlyExpenses[$month] += ($service->total_cost ?? $service->cost ?? 0);
                        }
                    }
                }
            }
        }
        
        if ($externalRequests) {
            foreach ($externalRequests as $service) {
                if (isset($service->date)) {
                    $serviceTime = strtotime($service->date);
                    if ($serviceTime >= $sixMonthsAgo) {
                        $month = date('M', $serviceTime);
                        if (isset($monthlyExpenses[$month])) {
                            $cost = (($service->total_hours ?? 0) * ($service->cost_per_hour ?? 0)) + ($service->additional_charges ?? 0);
                            $monthlyExpenses[$month] += $cost;
                        }
                    }
                }
            }
        }
        
        $monthlyExpensesArray = array_values($monthlyExpenses);
        
        // Pass all data to the view
        $this->view('customer/dashboard', [
            'user' => $_SESSION['user'],
            'bookings' => $bookings ?? [],
            'activeBookings' => $activeBookings,
            'currentProperty' => $currentProperty,
            'currentBooking' => $currentBooking,
            'totalExpenses' => $totalExpenses,
            'serviceRequestsRegular' => $allServiceRequestsRegular,
            'serviceRequestsExternal' => $allServiceRequestsExternal,
            'rentalHistory' => $rentalHistory,
            'monthlyExpenses' => $monthlyExpenses,
            'monthlyExpensesArray' => $monthlyExpensesArray,
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
        
        // Clear session data after rendering the view
        unset($_SESSION['errors']);
        unset($_SESSION['status']);
    }

    public function propertyUnit($propertyId)
    {
        // $property = new PropertyConcat;
        // $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        // //show($propertyUnit);

        // $this->view('customer/propertyUnit', ['property' => $propertyUnit]);
    }

    public function profile(){
        $user = new User();

        if ($_SESSION['user']->AccountStatus == -4) {// Approve delete
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if($updateAcc){
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Welcome back to Primecare.";
            $_SESSION['flash']['type'] = "welcome";
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_account'])) {
                $errors = [];
                $status = '';
                //AccountStatus
                $userId = $_SESSION['user']->pid; // Replace with actual user ID from session
                
                // Update the user's Account Status to 0 instead od deleting accounnt
                $updated = $user->update($userId, [
                    'AccountStatus' => 0
                ], 'pid');
                if ($updated) {
                    // Clear the user session data
                    session_unset();
                    session_destroy();
                    // Redirect to the home page or login page
                    redirect('home');
                    exit;
                } else {
                    $_SESSION['flash']['msg'] = "Failed to delete account. Please try again.";
                    $_SESSION['flash']['type'] = "error";
                    // $errors[] = "Failed to delete account. Please try again.";
                }
                // Delete the user from the database
                // $user = new User();
                // $deleted = $user->delete($userId, 'pid'); // Implement a delete method in your User model
    
                // if ($deleted) {
                //     // Delete the user's profile picture if it exists
                //     $profilePicturePath = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR . $_SESSION['user']->image_url;
                //     if (!empty($_SESSION['user']->image_url) && file_exists($profilePicturePath)) {
                //         unlink($profilePicturePath);
                //     }
    
                //     // Clear the user session data
                //     session_unset();
                //     session_destroy();
    
                //     // Redirect to the home page or login page
                //     redirect('home');
                //     exit;
                // } else {
                //     $errors[] = "Failed to delete account. Please try again.";
                // }
    
                // Store errors in session and redirect back
                $_SESSION['errors'] = $errors;
                redirect('dashboard/profile');
                exit;
                
            }else if(isset($_POST['logout'])){
                $this->logout();
            }
        $this->handleProfileSubmission();
        // return;
        }
        $this->view('profile', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);

        // Clear session data after rendering the view
        unset($_SESSION['errors']);
        unset($_SESSION['status']);
        return;
    }

    private function handleProfileSubmission(){
        $errors = [];
        $status = '';

        // Get form data and sanitize inputs
        $firstName = esc($_POST['fname'] ?? null);
        $lastName = esc($_POST['lname'] ?? null);
        $contactNumber = esc($_POST['contact'] ?? null);

        $email = filter_var($_POST['email'] ?? null, FILTER_VALIDATE_EMAIL);
        // Validate email
        if (!$email) {
            $errors[] = "Invalid email format.";
        } else {
            // Optional: Check against allowed domains
            $domain = substr($email, strpos($email, '@') + 1);

            $allowedDomains = [
                // Professional Email Providers
                'gmail.com',
                'outlook.com',
                'yahoo.com',
                'hotmail.com',
                'protonmail.com',
                'icloud.com',
            
                // Tech Companies
                'google.com',
                'microsoft.com',
                'apple.com',
                'amazon.com',
                'facebook.com',
                'twitter.com',
                'linkedin.com',
            
                // Common Workplace Domains
                'company.com',
                'corp.com',
                'business.com',
                'enterprise.com',
            
                // Educational Institutions
                'university.edu',
                'college.edu',
                'school.edu',
                'campus.edu',
            
                // Government and Public Sector
                'gov.com',
                'public.org',
                'municipality.gov',
            
                // Startup and Tech Ecosystem
                'startup.com',
                'techcompany.com',
                'innovate.com',
            
                // Freelance and Remote Work
                'freelancer.com',
                'consultant.com',
                'remote.work',
            
                // Regional and Local Businesses
                'localbank.com',
                'regional.org',
                'cityservice.com',
            
                // Healthcare and Medical
                'hospital.org',
                'clinic.com',
                'medical.net',
            
                // Non-Profit and NGO
                'nonprofit.org',
                'charity.org',
                'ngo.com',
            
                // Creative Industries
                'design.com',
                'creative.org',
                'agency.com',
            
                // Personal Domains
                'me.com',
                'personal.com',
                'home.net',
            
                // International Email Providers
                'mail.ru',
                'yandex.com',
                'gmx.com',
                'web.de'
            ];
            
            if (!in_array($domain, $allowedDomains)) {
                $errors[] = 'Email domain is not allowed';
            } else {
                $user = new User();
                $availableUser = $user->first(['email' => $email]);
                if ($availableUser && $availableUser->pid != $_SESSION['user']->pid) {
                    $errors[] = "Email already exists. Use another one.";
                }
            }
        }

        if (!empty($errors)) {
            $errorString = implode("<br>", $errors);
            $_SESSION['flash']['msg'] = $errorString;
            $_SESSION['flash']['type'] = "error";
            // $_SESSION['errors'] = $errors;
            $_SESSION['status'] = $status;
            redirect('dashboard/profile');
            exit;
        }

        if (!$user->validate($_POST)) {
            $validationErrors = [];
            foreach ($user->errors as $error) {
                $validationErrors[] = $error;
            }
            $errorString = implode("<br>", $validationErrors);
            $_SESSION['flash']['msg'] = $errorString;
            $_SESSION['flash']['type'] = "error";
            $_SESSION['status'] = $status;

            redirect('dashboard/profile');
            exit;
        }
        // Check if profile picture is uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $profilePicture = $_FILES['profile_picture'];

            // if profilepicsize is larger than 4 mb set error
            if ($profilePicture['size'] > 2 *  1024 * 1024) {
                $errors[] = "Profile picture size must be less than 2MB.";
            }

            // Define the target directory and create it if it doesn't exist
            $targetDir = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR;
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Define the target file name using uniqid()
            $imageFileType = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($imageFileType, $validExtensions)) {
                $targetFile = uniqid() . "__" .  $email . '.' . $imageFileType;

                // Move the uploaded file
                if (move_uploaded_file($profilePicture['tmp_name'], $targetDir . $targetFile)) {
                    $status = "Profile picture uploaded successfully!" . $targetFile;
                } else {
                    $errors[] = "Error uploading profile picture. Try changing the file name." . $profilePicture['tmp_name'] . $targetFile;
                }
            } else {
                $errors[] = "Invalid file type. Please upload an image file.";
            }
        }

        // Update user profile in the database
        if (empty($errors) && $_SESSION['user']->pid) {
            $userId = $_SESSION['user']->pid; 
            $user = new User();
            $updated = $user->update($userId, [
                'fname' => $firstName,
                'lname' => $lastName,
                'email' => $email,
                'contact' => $contactNumber,
                'image_url' => $targetFile ?? 'user.png'
            ], 'pid');

            if ($updated) {
                // Delete old profile picture if a new one is uploaded
                if (isset($targetFile) && !empty($_SESSION['user']->image_url)) {
                    $oldPicPath = $targetDir . $_SESSION['user']->image_url;
                    try {
                        if (file_exists($oldPicPath)) {
                            unlink($oldPicPath);
                        }
                    } catch (Exception $e) {
                        $status = "Profile updated, but failed to delete old profile picture: " . $e->getMessage();
                    }
                }
                // Update session data
                $_SESSION['user']->fname = $firstName;
                $_SESSION['user']->lname = $lastName;
                $_SESSION['user']->email = $email;
                $_SESSION['user']->contact = $contactNumber;
                if (isset($targetFile)) {
                    $_SESSION['user']->image_url = $targetFile;
                }
                $status = "Profile updated successfully!";
            } else {
                $errors[] = "Failed to update profile. Please try again.";
            }
        }

        // Store errors or success in session and redirect
        if (!empty($errors)) {
            $errorString = implode("<br>", $errors);
            $_SESSION['flash']['msg'] = $errorString;
            $_SESSION['flash']['type'] = "error";
            // $_SESSION['errors'] = $errors;
            // $_SESSION['status'] = $status;
            redirect('dashboard/profile');
            exit;
        }
        $_SESSION['flash']['msg'] = $status;
        $_SESSION['flash']['type'] = "success";
        // $_SESSION['errors'] = $errors;
        // $_SESSION['status'] = $status;
        redirect('dashboard/profile');
        exit;
    }

    private function logout()
    {
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }

    public function occupiedProperties()
    {
        $orders = [];
        if (isset($_SESSION['user']) && !empty($_SESSION['user']->pid)) {
            $BookingOrders = new BookingOrders();
            $orders = $BookingOrders->getOrdersByOwner($_SESSION['user']->pid);

            $property = new PropertyConcat();
            foreach ($orders as $key => &$order) {
                // include property details
                $propertyData = $property->where(['property_id' => $order->property_id]);
                if (!empty($propertyData)) {
                    $order->property_images = $propertyData[0]->property_images;
                }
                //include hash
                if (
                    strtolower($order->payment_status) !== 'paid' &&
                    strtolower($order->booking_status) === 'confirmed'
                ) {
                    $merchant_id = MERCHANT_ID;
                    $merchant_secret = MERCHANT_SECRET;
                    $order_id = $order->booking_id;
                    $amount = number_format($order->total_amount, 2, '.', '');
                    $currency = "LKR";
                    $hash = strtoupper(
                        md5(
                            $merchant_id .
                            $order_id .
                            $amount .
                            $currency .
                            strtoupper(md5($merchant_secret))
                        )
                    );
                    $order->payhere_hash = $hash;
                }
            }
        }

        $this->view('customer/occupiedProperties', [
            'orders' => $orders
        ]);
    }

    public function search()
    {
        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'pending']);
        $this->view('customer/search', ['properties' => $properties]);
    }

    public function maintenanceRequests()
    {
        $this->view('customer/maintainanceRequest');
    }

    public function payments()
    {
        $this->view('customer/payments');
    }

    public function reportProblem()
    {
        $this->view('customer/reportProblem', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function leaveProperty($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        $this->view('customer/leaveProperty', ['property' => $propertyUnit]);
    }

    public function bookProperty($propertyId)
    {
        $property = new PropertyConcat;
        $owner = new User();
        $agent = new User();

        $owner = $owner->where(['pid' => $property->where(['property_id' => $propertyId])[0]->person_id])[0];
        //$agent = $agent->where(['pid' => $property->where(['property_id' => $propertyId])[0]->agent_id])[0];
        $agent = $agent->where(['pid' => 62])[0];
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        // show($propertyUnit);
        // show($owner);
        // show($agent);
        $this->view('customer/bookProperty', ['property' => $propertyUnit , 'owner' => $owner, 'agent' => $agent]);
    }

    public function repairListing(){
        $service = new Services;
        $services = $service->findAll();

        $this->view('customer/repairListing' , ['services' => $services]);
    }

    public function updateRole(){
        $services = new Services;
        $services = $services->findAll();
        $this->view('customer/updateRole', ['services' => $services]);
    }

    public function updateToOwner(){
        $user = new User();
        if ($_SESSION['user']->user_lvl == 0) {
            if ($user->update($_SESSION['user']->pid, ['user_lvl' => '1'], 'pid')) {
            $_SESSION['user']->user_lvl = '1';
            $_SESSION['flash']['msg'] = "Role updated successfully";
            $_SESSION['flash']['type'] = "success";
            redirect('dashboard');
            exit;
            } else {
            $_SESSION['flash']['msg'] = "Failed to update role. Please try again.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/updateRole');
            exit;
            }
        } else {
            $_SESSION['flash']['msg'] = "Only customers are allowed to update role.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/updateRole');
            exit;
        }
    }

    public function updateToSerPro(){
        $user = new User();
        if ($_SESSION['user']->user_lvl == 0) {
            if ($user->update($_SESSION['user']->pid, ['user_lvl' => '2'], 'pid')) {
            $_SESSION['user']->user_lvl = '2';
            $_SESSION['flash']['msg'] = "Role updated successfully";
            $_SESSION['flash']['type'] = "success";
            redirect('dashboard');
            exit;
            } else {
            $_SESSION['flash']['msg'] = "Failed to update role. Please try again.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/updateRole');
            exit;
            }
        } else {
            $_SESSION['flash']['msg'] = "Only customers are allowed to update role.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/updateRole');
            exit;
        }
    }

    public function requestService()
    {
        $this->view('customer/requestServiceChoice');
    }

    public function requestServiceOccupiedOld()
    {
        // Load active bookings and service types for the form
        $userId = $_SESSION['user']->pid;
        $bookingOrdersModel = new BookingOrders();
        $servicesModel = new Services();
        $propertyModel = new PropertyConcat();
        $serviceLogModel = new ServiceLog(); // Instantiate ServiceLog model

        // Get all bookings for this customer
        $bookings = $bookingOrdersModel->getOrdersByOwner($userId);

        // Filter for active bookings
        $activeBookings = [];
        if ($bookings) {
            foreach ($bookings as $booking) {
                // Assuming 'end_date' exists and is comparable
                if (isset($booking->end_date) && strtotime($booking->end_date) >= time()) {
                    // Fetch property details including images for active bookings
                    $propertyDetails = $propertyModel->first(['property_id' => $booking->property_id]);
                    if ($propertyDetails) {
                        $booking->property_name = $propertyDetails->name ?? 'N/A';
                        $booking->property_address = $propertyDetails->address ?? 'No address';
                        $booking->property_images = $propertyDetails->property_images ?? null; // Ensure images are fetched
                    } else {
                        $booking->property_name = 'N/A';
                        $booking->property_address = 'No address';
                        $booking->property_images = null;
                    }
                    $activeBookings[] = $booking;
                }
            }
        }


        // Get all available service types
        $serviceTypes = $servicesModel->findAll();

        $errors = [];
        $old = []; // To repopulate form on error

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Retrieve and sanitize POST data
            $property_id = filter_input(INPUT_POST, 'property_id', FILTER_SANITIZE_NUMBER_INT);
            $service_type_id = filter_input(INPUT_POST, 'service_type', FILTER_SANITIZE_NUMBER_INT); // This is the service_id from the 'services' table
            $service_description = trim(filter_input(INPUT_POST, 'service_description', FILTER_SANITIZE_SPECIAL_CHARS));
            $service_date_input = filter_input(INPUT_POST, 'service_date', FILTER_SANITIZE_SPECIAL_CHARS);
            $service_date = date('Y-m-d', strtotime($service_date_input));

            $status = 'Pending'; // Initial status

            // Get property name from active bookings
            $property_name = 'N/A';
            foreach ($activeBookings as $booking) {
                if ($booking->property_id == $property_id) {
                    $property_name = $booking->property_name;
                    break;
                }
            }

            // Find service details (name, cost) from service types based on the ID
            $service_name = 'N/A'; // This corresponds to 'service_type' column in serviceLog
            $cost_per_hour = null; // Initialize cost
            foreach ($serviceTypes as $service) {
                // Use the ID from the form post ($service_type_id) to find the matching service
                if ($service->service_id == $service_type_id) {
                    $service_name = $service->name; // Get the name for the 'service_type' column
                    $cost_per_hour = $service->cost_per_hour; // Get the cost
                    break;
                }
            }

            // --- Validation ---
            if (!$property_id) $errors['property_id'] = "Please select a property.";
            // Use $service_name for validation check, as it's derived from a valid $service_type_id
            if ($service_name === 'N/A') $errors['service_type'] = "Please select a valid service type.";
            if (empty($service_description)) $errors['service_description'] = "Please provide a description.";
            if (!$service_date_input || $service_date < date('Y-m-d')) $errors['service_date'] = "Please select a valid future date.";
            // Validate cost_per_hour based on model requirements (must be numeric and > 0)
            if ($cost_per_hour === null || !is_numeric($cost_per_hour) || $cost_per_hour <= 0) {
                 $errors['service_type'] = "Selected service type is invalid or missing cost information.";
            }


            if (empty($errors)) {
                // Prepare data strictly according to ServiceLog::$allowedColumns
                // Inside the POST handling block where $data is prepared:
                $data = [
                    'service_type'         => $service_name,
                    'date'                 => $service_date,
                    'property_id'          => $property_id,
                    'property_name'        => $property_name,
                    'cost_per_hour'        => $cost_per_hour,
                    'total_hours'          => 1, // Default to 1 hour
                    'total_cost'           => $cost_per_hour * 1, // Add this line
                    'status'               => 'Pending',
                    'service_description'  => $service_description,
                    'requested_person_id'  => $_SESSION['user']->pid
                ];

                if ($serviceLogModel->insert($data)) {
                    // Success
                    $_SESSION['flash']['msg'] = "Service request submitted successfully!";
                    $_SESSION['flash']['type'] = "success";
                    // Clear old form data on success
                    unset($_SESSION['form_data']);
                } else {
                    // Database insertion failed
                    $errors['database'] = "Failed to submit service request. Please try again.";
                    // Add model errors if available (these would come from the validate method called within insert)
                    if (!empty($serviceLogModel->errors)) {
                        // Use the model's errors directly
                        $errorMessages = array_values($serviceLogModel->errors);
                        $_SESSION['flash']['msg'] = implode('<br>', $errorMessages);
                    } else {
                         $_SESSION['flash']['msg'] = $errors['database']; // Fallback message
                    }
                    $_SESSION['flash']['type'] = "error";
                    // Keep old form data on failure
                    $_SESSION['form_data'] = $old;
                }
            } else {
                // Controller-level validation failed
                $_SESSION['flash']['msg'] = implode('<br>', array_values($errors)); // Use array_values to get just messages
                $_SESSION['flash']['type'] = "error";
                // Keep old form data on failure
                $_SESSION['form_data'] = $old;
            }


            redirect('customer/requestServiceOccupiedOld');
            exit;
        }

        // Prepare data for the view
        $viewData = [
            'user' => $_SESSION['user'],
            'activeBookings' => $activeBookings,
            'serviceTypes' => $serviceTypes,
            // Retrieve errors/success message from flash session
            // Ensure errors passed to view are just the messages
            'errors' => ($_SESSION['flash']['type'] ?? '') === 'error' ? ($_SESSION['flash']['msg'] ? explode('<br>', $_SESSION['flash']['msg']) : []) : [],
            'old' => $_SESSION['form_data'] ?? [], // Get old form data if it exists
            'success_message' => ($_SESSION['flash']['type'] ?? '') === 'success' ? $_SESSION['flash']['msg'] : ''
        ];

        // Clear flash message and old form data from session after retrieving them
        unset($_SESSION['flash']);
        unset($_SESSION['form_data']);


        $this->view('customer/requestServiceOccupied', $viewData);
    }

    public function externalRepairListing()
    {
        $servicesModel = new Services();
        $services = $servicesModel->getAllServices();

        if (!empty($services)) {
            foreach ($services as $key => $service) {
                if (empty($service->service_img)) continue;
                $imagePath = ROOTPATH . 'public/assets/images/repairimages/' . $service->service_img;
                if (!file_exists($imagePath)) {
                    $found = false;
                    $extensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $baseName = pathinfo($service->service_img, PATHINFO_FILENAME);
                    foreach ($extensions as $ext) {
                        $testPath = ROOTPATH . 'public/assets/images/repairimages/' . $baseName . '.' . $ext;
                        if (file_exists($testPath)) {
                            $services[$key]->service_img = $baseName . '.' . $ext;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $services[$key]->service_img = '';
                    }
                }
            }
        }

        $this->view('customer/externalRepairListing', [
            'services' => $services
        ]);
    }
    
    public function requestServiceExternal()
    {
        $servicesModel = new Services();
        $service = null;
        $cost_per_hour = null;
        $service_type = '';

        // Fetch service details if service_id is provided
        if (isset($_GET['service_id'])) {
            $service = $servicesModel->getServiceById($_GET['service_id']);
            if ($service) {
                $cost_per_hour = $service->cost_per_hour;
                $service_type = $service->name;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $externalService = new ExternalService();

            // Handle file upload for property_images
            $images = [];
            $maxFiles = 3;
            $uploadDir = ROOTPATH . 'public/assets/images/external_services/properties/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            if (empty($_FILES['property_images']['name'][0])) {
                $errors['property_images'] = "You must upload at least 1 image.";
            } elseif (count($_FILES['property_images']['name']) > $maxFiles) {
                $errors['property_images'] = "You can upload a maximum of 3 images.";
            } else {
                foreach ($_FILES['property_images']['tmp_name'] as $key => $tmp_name) {
                    $fileName = uniqid() . '_' . basename($_FILES['property_images']['name'][$key]);
                    $targetFile = $uploadDir . $fileName;
                    if (move_uploaded_file($tmp_name, $targetFile)) {
                        // Save only the relative path for DB
                        $images[] = 'external_services/properties/' . $fileName;
                    }
                }
            }

            // Always fetch cost_per_hour from DB using service_id from hidden input
            $service_id = $_POST['service_id'] ?? null;
            $service = $servicesModel->getServiceById($service_id);
            $cost_per_hour = $service ? $service->cost_per_hour : null;
            $service_type = $service ? $service->name : '';

            $data = [
                'service_type'         => $service_type,
                'date'                 => date('Y-m-d'),
                'property_address'     => $_POST['property_address'],
                'property_description' => $_POST['property_description'],
                'service_images'       => $images, 
                'status'               => 'pending',
                'requested_person_id'  => $_SESSION['user']->pid,
                'created_at'           => date('Y-m-d H:i:s'),
                'cost_per_hour'        => $cost_per_hour,
            ];

            if (empty($errors) && $externalService->validate($data)) {
                $insertData = $data;
                $insertData['service_images'] = json_encode($data['service_images'], JSON_UNESCAPED_SLASHES);
                $externalService->insert($insertData);
                $_SESSION['flash']['msg'] = "External service request submitted.";
                $_SESSION['flash']['type'] = "success";
                redirect('dashboard/requestService');
                exit;
            } else {
                $this->view('customer/requestServiceExternal', [
                    'errors' => $errors ?? $externalService->errors,
                    'service_type' => $service_type,
                    'cost_per_hour' => $cost_per_hour,
                    'service_id' => $service_id
                ]);
                return;
            }
        }

        // Show the form with service details pre-filled
        $this->view('customer/requestServiceExternal', [
            'service_type' => $service_type,
            'cost_per_hour' => $cost_per_hour,
            'service_id' => $_GET['service_id'] ?? ''
        ]);
    }


    public function externalMaintenance()
    {
        // Get the current user's ID
        $customerId = $_SESSION['user']->pid;
        
        // Instantiate the ExternalService model
        $externalService = new ExternalService();
        
        // Get external service requests for the current customer
        $serviceLogs = $externalService->where(['requested_person_id' => $customerId]);
        
        // If no service logs found, initialize as empty array
        if (!$serviceLogs) {
            $serviceLogs = [];
        }
    
        // Apply status filtering
        if (!empty($_GET['status_filter'])) {
            $status = $_GET['status_filter'];
            $filteredLogs = [];
            foreach ($serviceLogs as $log) {
                if (strtolower($log->status) == strtolower($status)) {
                    $filteredLogs[] = $log;
                }
            }
            $serviceLogs = $filteredLogs;
        }
    
        // Apply date range filtering
        if (!empty($_GET['date_from']) || !empty($_GET['date_to'])) {
            $dateFrom = !empty($_GET['date_from']) ? strtotime($_GET['date_from']) : null;
            $dateTo = !empty($_GET['date_to']) ? strtotime($_GET['date_to'] . ' 23:59:59') : null;
            
            $filteredLogs = [];
            foreach ($serviceLogs as $log) {
                $logDate = strtotime($log->date);
                
                // Check if the log date is within the specified range
                $includeLog = true;
                if ($dateFrom && $logDate < $dateFrom) $includeLog = false;
                if ($dateTo && $logDate > $dateTo) $includeLog = false;
                
                if ($includeLog) {
                    $filteredLogs[] = $log;
                }
            }
            $serviceLogs = $filteredLogs;
        }
        
        // Apply sorting with extended options
        if (!empty($_GET['sort'])) {
            $sort = $_GET['sort'];
            
            usort($serviceLogs, function($a, $b) use ($sort) {
                switch ($sort) {
                    case 'date_asc':
                        return strtotime($a->date) - strtotime($b->date);
                    case 'date_desc':
                        return strtotime($b->date) - strtotime($a->date);
                    case 'service_type':
                        return strcasecmp($a->service_type ?? '', $b->service_type ?? '');
                    case 'cost_asc':
                        $costA = isset($a->total_cost) ? $a->total_cost : (($a->total_hours ?? 0) * ($a->cost_per_hour ?? 0) + ($a->additional_charges ?? 0));
                        $costB = isset($b->total_cost) ? $b->total_cost : (($b->total_hours ?? 0) * ($b->cost_per_hour ?? 0) + ($b->additional_charges ?? 0));
                        return $costA - $costB;
                    case 'cost_desc':
                        $costA = isset($a->total_cost) ? $a->total_cost : (($a->total_hours ?? 0) * ($a->cost_per_hour ?? 0) + ($a->additional_charges ?? 0));
                        $costB = isset($b->total_cost) ? $b->total_cost : (($b->total_hours ?? 0) * ($b->cost_per_hour ?? 0) + ($b->additional_charges ?? 0));
                        return $costB - $costA;
                    default:
                        return strtotime($b->date) - strtotime($a->date); // Default: newest first
                }
            });
        }
        
        // Calculate total expenses with improved logic
        $totalExpenses = 0;
        $completedServices = 0;
        $pendingServices = 0;
        $ongoingServices = 0;
        
        foreach ($serviceLogs as $log) {
            // First try to use total_cost if available
            if (isset($log->total_cost) && $log->total_cost > 0) {
                $totalExpenses += $log->total_cost;
            } else {
                // Otherwise calculate from hours, rate and additional charges
                $hours = $log->total_hours ?? 0;
                $rate = $log->cost_per_hour ?? 0;
                $additionalCharges = $log->additional_charges ?? 0;
                $totalExpenses += ($hours * $rate) + $additionalCharges;
            }
            
            // Count services by status
            $status = strtolower($log->status ?? '');
            if ($status === 'done' || $status === 'paid') {
                $completedServices++;
            } elseif ($status === 'ongoing') {
                $ongoingServices++;
            } elseif ($status === 'pending') {
                $pendingServices++;
            }
        }
        
        $this->view('customer/externalMaintenance', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'serviceLogs' => $serviceLogs,
            'totalExpenses' => $totalExpenses,
            'completedServices' => $completedServices,
            'pendingServices' => $pendingServices,
            'ongoingServices' => $ongoingServices,
            'totalServices' => count($serviceLogs)
        ]);
    }


    public function payExternalService($serviceId = null)
    {
        if (!$serviceId) {
            $_SESSION['flash']['msg'] = "Invalid service selected for payment.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/externalMaintenance');
        }
        
        // Get the service details
        $externalService = new ExternalService();
        $service = $externalService->first(['id' => $serviceId]);
        
        // Check if service exists and belongs to the current user
        if (!$service || $service->requested_person_id != $_SESSION['user']->pid) {
            $_SESSION['flash']['msg'] = "You don't have permission to pay for this service.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/externalMaintenance');
        }
        
        // Check if service is in the right status (done but not paid)
        if (strtolower($service->status) !== 'done') {
            $_SESSION['flash']['msg'] = "This service is not ready for payment.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/externalMaintenance');
        }
        
        // Calculate all costs
        $usual_cost = ($service->cost_per_hour ?? 0) * ($service->total_hours ?? 0);
        $additional_charges = $service->additional_charges ?? 0;
        $service_cost = $usual_cost + $additional_charges;
        $service_charge = $service_cost * 0.10; // 10% service charge
        $total_amount = $service_cost + $service_charge;
        
        // Pass data to view
        $data = [
            'title' => "Pay for External Service",
            'service' => $service,
            'usual_cost' => $usual_cost,
            'additional_charges' => $additional_charges,
            'service_charge' => $service_charge,
            'total_amount' => $total_amount
        ];
        
        $this->view('customer/payExternalService', $data);
    }


    public function payRegularService($serviceId = null)
    {
        if (!$serviceId) {
            $_SESSION['flash']['msg'] = "Invalid service selected for payment.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/maintenance');
        }
        
        // Get the service details
        $serviceLog = new ServiceLog();
        $service = $serviceLog->first(['service_id' => $serviceId]);
        
        // Check if service exists and belongs to the current user
        if (!$service || $service->tenant_id != $_SESSION['user']->pid) {
            $_SESSION['flash']['msg'] = "You don't have permission to pay for this service.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/maintenance');
        }
        
        // Check if service is in the right status (Done but not paid)
        if ($service->status !== 'Done') {
            $_SESSION['flash']['msg'] = "This service is not ready for payment.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/maintenance');
        }
        
        // Calculate all costs
        $service_cost = $service->cost ?? 0;
        $service_charge = $service_cost * 0.10; // 10% service charge
        $total_amount = $service_cost + $service_charge;
        
        // Pass data to view
        $data = [
            'title' => "Pay for Regular Service",
            'service' => $service,
            'service_cost' => $service_cost,
            'service_charge' => $service_charge,
            'total_amount' => $total_amount
        ];
        
        $this->view('customer/payRegularService', $data);
    }


    public function completeExternalPayment($serviceId = null)
    {
        // Check if it's an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $response = ['success' => false, 'message' => ''];
        
        // Check if service ID is provided
        if (!$serviceId) {
            $response['message'] = "Invalid service selected for payment.";
            if ($isAjax) {
                echo json_encode($response);
                exit;
            } else {
                $_SESSION['flash']['msg'] = $response['message'];
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/externalMaintenance');
            }
        }
        
        // Get the service details
        $externalService = new ExternalService();
        $service = $externalService->first(['id' => $serviceId]);
        
        // Check if service exists and belongs to the current user
        if (!$service || $service->requested_person_id != $_SESSION['user']->pid) {
            $response['message'] = "You don't have permission to pay for this service.";
            if ($isAjax) {
                echo json_encode($response);
                exit;
            } else {
                $_SESSION['flash']['msg'] = $response['message'];
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/externalMaintenance');
            }
        }
        
        // Get payment method from request (form data or JSON)
        if (isset($_POST['payment_method'])) {
            $paymentMethod = $_POST['payment_method'];
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
            $paymentMethod = $data['payment_method'] ?? '';
        }
        
        // Process payment proof upload if bank transfer
        $paymentProofPath = null;
        if ($paymentMethod === 'bank_transfer' && !empty($_FILES['payment_proof']['name'])) {
            $file = $_FILES['payment_proof'];
            
            // Check file type
            $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
            if (!in_array($file['type'], $allowed_types)) {
                $response['message'] = "Invalid file type. Only JPG, PNG and PDF are allowed.";
                echo json_encode($response);
                exit;
            }
            
            // Check file size (max 5MB)
            if ($file['size'] > 5 * 1024 * 1024) {
                $response['message'] = "File size exceeds 5MB limit.";
                echo json_encode($response);
                exit;
            }
            
            // Create specialized and unique filename structure
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $timestamp = date('Ymd_His');
            $customerId = $_SESSION['user']->pid;
            $randomString = bin2hex(random_bytes(8)); // 16 characters of randomness
            
            // Create directory structure
            $yearMonth = date('Y/m');
            $uploadDir = "payment_proofs/{$yearMonth}/";
            $uploadPath = ROOTPATH . '/public/assets/images/' . $uploadDir;
            
            // Create directories if they don't exist
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            // Build the unique filename:
            // Format: INVOICE-NUMBER_SERVICE-ID_USER-ID_TIMESTAMP_RANDOM.EXT
            $invoiceNumber = 'INV-'.date('Ymd').'-'.rand(1000, 9999);
            $filename = "INV{$invoiceNumber}_SRV{$serviceId}_USR{$customerId}_{$timestamp}_{$randomString}.{$ext}";
            $destination = $uploadPath . $filename;
            $relativePath = $uploadDir . $filename;
            
            // Upload file
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $paymentProofPath = $relativePath;
            } else {
                $response['message'] = "Error uploading payment proof.";
                echo json_encode($response);
                exit;
            }
        }
        
        // Calculate total amount
        $usual_cost = ($service->cost_per_hour ?? 0) * ($service->total_hours ?? 0);
        $additional_charges = $service->additional_charges ?? 0;
        $service_cost = $usual_cost + $additional_charges;
        $service_charge = $service_cost * 0.10; // 10% service charge
        $total_amount = $service_cost + $service_charge;
        
        // Create payment record using our enhanced method
        $servicePayment = new ServicePayment();
        $invoice_number = $servicePayment->generateInvoiceNumber();
        $paymentData = [
            'service_id' => $serviceId,
            'amount' => $total_amount,
            'payment_date' => date('Y-m-d H:i:s'),
            'invoice_number' => $invoice_number,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $paymentId = $servicePayment->createPayment($paymentData);
        
        // Update service status to "paid"
        if ($paymentId) {
            $updated = $externalService->update($serviceId, [
                'status' => 'paid',
                'payment_date' => date('Y-m-d H:i:s')
            ]);
            
            if ($updated) {
                $response['success'] = true;
                $response['message'] = "Payment processed successfully.";
                if ($isAjax) {
                    echo json_encode($response);
                    exit;
                } else {
                    $_SESSION['flash']['msg'] = "Payment processed successfully.";
                    $_SESSION['flash']['type'] = "success";
                    redirect('dashboard/externalMaintenance');
                }
            } else {
                $response['message'] = "Payment recorded but service status update failed.";
                if ($isAjax) {
                    echo json_encode($response);
                    exit;
                } else {
                    $_SESSION['flash']['msg'] = $response['message'];
                    $_SESSION['flash']['type'] = "warning";
                    redirect('dashboard/externalMaintenance');
                }
            }
        } else {
            $response['message'] = "Failed to process payment. Please try again.";
            if ($isAjax) {
                echo json_encode($response);
                exit;
            } else {
                $_SESSION['flash']['msg'] = $response['message'];
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/externalMaintenance');
            }
        }
    }


    public function completeRegularPayment($serviceId = null)
    {
        // Check if it's an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $response = ['success' => false, 'message' => ''];
        
        // Check if service ID is provided
        if (!$serviceId) {
            $response['message'] = "Invalid service selected for payment.";
            if ($isAjax) {
                echo json_encode($response);
                exit;
            } else {
                $_SESSION['flash']['msg'] = $response['message'];
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/maintenance');
            }
        }
        
        // Get the service details
        $serviceLog = new ServiceLog();
        $service = $serviceLog->first(['service_id' => $serviceId]);
        
        // Check if service exists and belongs to the current user
        if (!$service || $service->tenant_id != $_SESSION['user']->pid) {
            $response['message'] = "You don't have permission to pay for this service.";
            if ($isAjax) {
                echo json_encode($response);
                exit;
            } else {
                $_SESSION['flash']['msg'] = $response['message'];
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/maintenance');
            }
        }
        
        // Get payment method from request
        if (isset($_POST['payment_method'])) {
            $paymentMethod = $_POST['payment_method'];
        } else {
            $data = json_decode(file_get_contents('php://input'), true);
            $paymentMethod = $data['payment_method'] ?? '';
        }
        
        // // Process payment proof upload if bank transfer (same as external payment)
        // $paymentProofPath = null;
        // if ($paymentMethod === 'bank_transfer' && !empty($_FILES['payment_proof']['name'])) {
        //     // File upload handling code (same as in completeExternalPayment)
        //     // ...
        // }
        
        // Calculate total amount
        $service_cost = $service->cost ?? 0;
        $service_charge = $service_cost * 0.10; // 10% service charge
        $total_amount = $service_cost + $service_charge;
        
        // Create payment record using our enhanced method
        $servicePayment = new ServicePayment();
        $paymentData = [
            'service_id' => $serviceId,
            'amount' => $total_amount,
            'payment_date' => date('Y-m-d H:i:s'),
            'invoice_number' => $servicePayment->generateInvoiceNumber(),
            'payment_method' => $paymentMethod,
            // 'payment_proof' => $paymentProofPath,
            'status' => 'completed',
            'transaction_id' => 'TXN-'.strtoupper(substr(md5(time()), 0, 10)),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $paymentId = $servicePayment->createPayment($paymentData);
        
        // Update service status to "Paid"
        if ($paymentId) {
            $updated = $serviceLog->update($serviceId, [
                'status' => 'Paid',
                'payment_date' => date('Y-m-d H:i:s')
            ], 'service_id');
            
            if ($updated) {
                $response['success'] = true;
                $response['message'] = "Payment processed successfully.";
                if ($isAjax) {
                    echo json_encode($response);
                    exit;
                } else {
                    $_SESSION['flash']['msg'] = "Payment processed successfully.";
                    $_SESSION['flash']['type'] = "success";
                    redirect('dashboard/maintenance');
                }
            } else {
                $response['message'] = "Payment recorded but service status update failed.";
                if ($isAjax) {
                    echo json_encode($response);
                    exit;
                } else {
                    $_SESSION['flash']['msg'] = $response['message'];
                    $_SESSION['flash']['type'] = "warning";
                    redirect('dashboard/maintenance');
                }
            }
        } else {
            $response['message'] = "Failed to process payment. Please try again.";
            if ($isAjax) {
                echo json_encode($response);
                exit;
            } else {
                $_SESSION['flash']['msg'] = $response['message'];
                $_SESSION['flash']['type'] = "error";
                redirect('dashboard/maintenance');
            }
        }
    }

    public function preparePayHerePayment($serviceId = null)
    {
        try {
            // Disable error reporting and display
            error_reporting(0);
            ini_set('display_errors', 0);
            
            // Clear any previous output
            while (ob_get_level()) {
                ob_end_clean();
            }
            
            // Set critical headers - do this before ANY output
            header('Content-Type: application/json');
            header('Cache-Control: no-cache, no-store, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Close session writing to prevent locks
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_write_close();
            }
            
            // Create a log file for debugging
            $logFile = ROOTPATH . '/payhere_fix.log';
            file_put_contents($logFile, date('Y-m-d H:i:s') . ' - Starting payment for service: ' . $serviceId . PHP_EOL, FILE_APPEND);
            
            // Skip regular AJAX checking and other unnecessary validation that might output errors
            
            // Get the JSON data directly
            $json_data = file_get_contents('php://input');
            $data = json_decode($json_data, true) ?: [];
            
            // Get the service details
            $externalService = new ExternalService();
            $service = $externalService->first(['id' => $serviceId]);
            
            if (!$service) {
                echo json_encode(['success' => false, 'message' => 'Service not found']);
                exit;
            }
            
            // Calculate total amount
            $usual_cost = ($service->cost_per_hour ?? 0) * ($service->total_hours ?? 0);
            $additional_charges = $service->additional_charges ?? 0;
            $service_cost = $usual_cost + $additional_charges;
            $service_charge = $service_cost * 0.10; // 10% service charge
            $total_amount = $service_cost + $service_charge;
            
            // Create payment record
            $servicePayment = new ServicePayment();
            $invoice_number = $servicePayment->generateInvoiceNumber();
            $paymentData = [
                'service_id' => $serviceId,
                'amount' => $total_amount,
                'payment_date' => date('Y-m-d H:i:s'),
                'invoice_number' => $invoice_number,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $paymentId = $servicePayment->createPayment($paymentData);
            
            // Get user details
            $user = $_SESSION['user'] ?? null;
            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'User session expired']);
                exit;
            }
            
            $names = explode(' ', trim($user->fname . ' ' . $user->lname));
            $firstName = $names[0] ?? '';
            $lastName = (count($names) > 1) ? end($names) : '';
            
            // Prepare response
            $response = [
                'success' => true,
                'merchant_id' => '1221145', // PayHere merchant ID
                'return_url' => ROOT . "/customer/payhereReturn/{$paymentId}/{$serviceId}/1",
                'cancel_url' => ROOT . "/customer/payhereCancel/{$paymentId}/{$serviceId}/1",
                'notify_url' => ROOT . "/customer/payhereNotify",
                'order_id' => $paymentData['invoice_number'],
                'amount' => number_format($total_amount, 2, '.', ''),
                'currency' => 'LKR',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $data['email'] ?? $user->email,
                'phone' => $data['phone'] ?? $user->phone,
                'address' => $user->address ?? 'Not provided',
                'city' => $user->city ?? 'Not provided',
                'country' => 'Sri Lanka',
                'delivery_address' => $service->property_address ?? 'Not applicable',
                'delivery_city' => 'Not applicable',
                'delivery_country' => 'Sri Lanka',
                'service_id' => $serviceId,
                'user_id' => $user->pid
            ];
            
            // Log the successful response
            file_put_contents($logFile, date('Y-m-d H:i:s') . ' - Success: ' . json_encode($response) . PHP_EOL, FILE_APPEND);
            
            // Output JSON and exit
            echo json_encode($response);
            exit;
            
        } catch (Exception $e) {
            // Log any errors
            $logFile = ROOTPATH . '/payhere_fix_error.log';
            file_put_contents($logFile, date('Y-m-d H:i:s') . ' - Error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            
            // Return error JSON
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
            exit;
        }
    }
     

    public function payhereReturn($paymentId = null, $serviceId = null, $isExternal = 1)
    {
        $isExternal = (int)$isExternal === 1;
        
        if (!$paymentId || !$serviceId) {
            $_SESSION['flash']['msg'] = "Invalid payment information.";
            $_SESSION['flash']['type'] = "error";
            redirect($isExternal ? 'dashboard/externalMaintenance' : 'dashboard/maintenance');
        }
        
        // Update status based on payment ID
        $servicePayment = new ServicePayment();
        $payment = $servicePayment->first(['payment_id' => $paymentId]);
        
        if (!$payment) {
            $_SESSION['flash']['msg'] = "Payment record not found.";
            $_SESSION['flash']['type'] = "error";
            redirect($isExternal ? 'dashboard/externalMaintenance' : 'dashboard/maintenance');
        }
        
        // Check payment status
        if ($payment->status == 'completed') {
            // Payment was successful (already confirmed via notify_url)
            $_SESSION['flash']['msg'] = "Payment completed successfully!";
            $_SESSION['flash']['type'] = "success";
        } else {
            // Payment status might still be pending as notification might come after return
            $_SESSION['flash']['msg'] = "Your payment is being processed. Thank you!";
            $_SESSION['flash']['type'] = "info";
            
            // Optionally update the payment status if PayHere didn't notify yet
            if (isset($_GET['order_id']) && $_GET['order_id'] == $payment->invoice_number) {
                // Update payment record to completed
                $servicePayment->update($paymentId, [
                    'status' => 'completed', 
                    'transaction_id' => $_GET['payment_id'] ?? null,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
                
                // Update service status based on service type
                if ($isExternal) {
                    $externalService = new ExternalService();
                    $externalService->update($serviceId, [
                        'status' => 'paid',
                        'payment_date' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $serviceLog = new ServiceLog();
                    $serviceLog->update($serviceId, [
                        'status' => 'Paid',  // Note capital P in Paid to match ServiceLog validation
                        'payment_date' => date('Y-m-d H:i:s')
                    ], 'service_id');
                }
                
                $_SESSION['flash']['msg'] = "Payment completed successfully!";
                $_SESSION['flash']['type'] = "success";
            }
        }
        
        redirect($isExternal ? 'dashboard/externalMaintenance' : 'dashboard/maintenance');
    }

    /**
     * Handle PayHere cancel callback
     */
    public function payhereCancel($paymentId = null, $serviceId = null, $isExternal = 1)
    {
        $isExternal = (int)$isExternal === 1;
        
        if (!$paymentId || !$serviceId) {
            $_SESSION['flash']['msg'] = "Payment was cancelled.";
            $_SESSION['flash']['type'] = "warning";
            redirect($isExternal ? 'dashboard/externalMaintenance' : 'dashboard/maintenance');
        }
        
        // Update payment status to cancelled
        $servicePayment = new ServicePayment();
        $servicePayment->update($paymentId, [
            'status' => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $_SESSION['flash']['msg'] = "You cancelled the payment process.";
        $_SESSION['flash']['type'] = "warning";
        redirect($isExternal ? 
            'customer/payExternalService/' . $serviceId : 
            'customer/payRegularService/' . $serviceId);
    }

    /**
     * Handle PayHere notification callback (async)
     */
    public function payhereNotify()
    {
        // Create a log file for debugging
        $logFile = ROOTPATH . '/payhere_notify.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . ' - PayHere notification received' . PHP_EOL, FILE_APPEND);
        file_put_contents($logFile, json_encode($_POST) . PHP_EOL, FILE_APPEND);
        
        // Verify the payment notification
        if (!isset($_POST['merchant_id']) || !isset($_POST['order_id']) || !isset($_POST['payment_id']) || !isset($_POST['status_code'])) {
            file_put_contents($logFile, 'Invalid notification data' . PHP_EOL, FILE_APPEND);
            exit;
        }
        
        $merchantId = $_POST['merchant_id'];
        $orderId = $_POST['order_id'];
        $payherePaymentId = $_POST['payment_id'];
        $statusCode = (int)$_POST['status_code'];
        $paymentAmount = $_POST['payhere_amount'] ?? 0;
        $paymentCurrency = $_POST['payhere_currency'] ?? 'LKR';
        
        // Verify merchant ID
        if ($merchantId !== '1221145') {
            file_put_contents($logFile, 'Invalid merchant ID' . PHP_EOL, FILE_APPEND);
            exit;
        }
        
        // Get the payment record
        $servicePayment = new ServicePayment();
        $payment = $servicePayment->first(['invoice_number' => $orderId]);
        
        if (!$payment) {
            file_put_contents($logFile, 'Payment record not found for order: ' . $orderId . PHP_EOL, FILE_APPEND);
            exit;
        }
        
        // Verify payment amount (to prevent fraud)
        if (abs($payment->amount - $paymentAmount) > 0.01) { // Use a small tolerance for floating point comparison
            file_put_contents($logFile, "Amount mismatch: Expected {$payment->amount}, Got {$paymentAmount}" . PHP_EOL, FILE_APPEND);
            // Continue processing but log the discrepancy
        }
        
        // Update payment status based on PayHere status code
        if ($statusCode == 2) { // Payment successful
            // Update payment record
            $servicePayment->update($payment->payment_id, [
                'status' => 'completed',
                'transaction_id' => $payherePaymentId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            // Update service record based on service type
            if ($payment->serviceType === 'external') {
                $externalService = new ExternalService();
                $externalService->update($payment->service_id, [
                    'status' => 'paid',
                    'payment_date' => date('Y-m-d H:i:s')
                ]);
                file_put_contents($logFile, 'External service payment completed for order: ' . $orderId . PHP_EOL, FILE_APPEND);
            } else if ($payment->serviceType === 'regular') {
                $serviceLog = new ServiceLog();
                $serviceLog->update($payment->service_id, [
                    'status' => 'Paid',
                    'payment_date' => date('Y-m-d H:i:s')
                ], 'service_id');
                file_put_contents($logFile, 'Regular service payment completed for order: ' . $orderId . PHP_EOL, FILE_APPEND);
            } else {
                file_put_contents($logFile, 'Unknown service type for payment: ' . $payment->serviceType . PHP_EOL, FILE_APPEND);
            }
        } elseif ($statusCode == 0) { // Payment pending
            $servicePayment->update($payment->payment_id, [
                'status' => 'pending',
                'transaction_id' => $payherePaymentId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            file_put_contents($logFile, 'Payment pending for order: ' . $orderId . PHP_EOL, FILE_APPEND);
        } else { // Payment failed or canceled
            $servicePayment->update($payment->payment_id, [
                'status' => 'failed',
                'transaction_id' => $payherePaymentId,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            file_put_contents($logFile, 'Payment failed for order: ' . $orderId . ' with status code: ' . $statusCode . PHP_EOL, FILE_APPEND);
        }
        
        // Return 200 OK to PayHere
        http_response_code(200);
        exit;
    }

    public function testPayHereAPI()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json');
        
        // Log access to this endpoint
        $logFile = ROOTPATH . '/payhere_test.log';
        file_put_contents($logFile, date('Y-m-d H:i:s') . ' - Test API accessed' . PHP_EOL, FILE_APPEND);
        
        echo json_encode([
            'success' => true,
            'message' => 'API is working correctly',
            'timestamp' => time()
        ]);
        exit;
    }

    public function directJsonAPI()
    {
        // Disable error display and reporting for API responses
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // End any active output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Close session writing to prevent locks
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        
        // Set essential headers
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Log access for debugging
        $log_file = ROOTPATH . '/api_test.log';
        file_put_contents($log_file, date('Y-m-d H:i:s') . ' - Direct JSON API accessed' . PHP_EOL, FILE_APPEND);
        
        // Return clean JSON response
        echo json_encode([
            'success' => true,
            'message' => 'API is working correctly',
            'timestamp' => time()
        ]);
        exit;
    }

    public function directPayHereAPI($serviceId = null) 
    {
        // Disable error display and reporting for API responses
        error_reporting(0);
        ini_set('display_errors', 0);
        
        // End any active output buffering
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        // Close session writing early
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        
        // Set essential headers
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        try {
            // Get JSON data
            $json_data = file_get_contents('php://input');
            $data = json_decode($json_data, true);
            
            // Mock response for testing - replace with actual PayHere integration
            $mockMerchantData = [
                'success' => true,
                'merchant_id' => '1221145', 
                'return_url' => ROOT . "/customer/payhereReturn/123/{$serviceId}/1",
                'cancel_url' => ROOT . "/customer/payhereCancel/123/{$serviceId}/1",
                'notify_url' => ROOT . "/customer/payhereNotify",
                'order_id' => 'INV-'.date('Ymd').'-'.rand(1000, 9999),
                'amount' => '1000.00',
                'currency' => 'LKR',
                'first_name' => 'Test',
                'last_name' => 'User',
                'email' => $data['email'] ?? 'test@example.com',
                'phone' => $data['phone'] ?? '0771234567',
                'address' => 'Test Address',
                'city' => 'Colombo',
                'country' => 'Sri Lanka'
            ];
            
            // Log success
            file_put_contents(ROOTPATH . '/payhere_direct.log', date('Y-m-d H:i:s') . ' - Success: ' . json_encode($mockMerchantData) . PHP_EOL, FILE_APPEND);
            
            echo json_encode($mockMerchantData);
            exit;
            
        } catch (Exception $e) {
            // Log error
            file_put_contents(ROOTPATH . '/payhere_direct_error.log', date('Y-m-d H:i:s') . ' - Error: ' . $e->getMessage() . PHP_EOL, FILE_APPEND);
            
            echo json_encode([
                'success' => false,
                'message' => 'API error: ' . $e->getMessage()
            ]);
            exit;
        }
    }


    public function cancelBooking($bookingId)
    {
        if (!isset($_SESSION['user'])) {
            redirect('login');
            return;
        }
        $BookingOrders = new BookingOrders();
        // Get the booking to verify ownership
        $orders = $BookingOrders->getOrdersByOwner($_SESSION['user']->pid);
        $booking = null;
        foreach ($orders as $order) {
            if ($order->booking_id == $bookingId) {
                $booking = $order;
                break;
            }
        }
        if ($booking) {
            $BookingOrders->updateBookingStatusByOwnerAndDates(
                $booking->property_id,
                $booking->person_id,
                $booking->start_date,
                $booking->end_date,
                'Cancelled'
            );
            $_SESSION['flash']['msg'] = "Booking cancelled successfully.";
            $_SESSION['flash']['type'] = "success";
        } else {
            $_SESSION['flash']['msg'] = "Booking not found or not authorized.";
            $_SESSION['flash']['type'] = "error";
        }
        redirect('dashboard/occupiedProperties');
    }

    public function markAsPaid($booking_id)
    {
        if (!isset($_SESSION['user'])) {
            redirect('login');
            return;
        }
        $BookingOrders = new BookingOrders();
        $order = $BookingOrders->findById($booking_id);
        if ($order && $order->person_id == $_SESSION['user']->pid) {
            $BookingOrders->updatePaymentStatus($booking_id, 'Paid');
            $_SESSION['flash']['msg'] = "Payment successful!";
            $_SESSION['flash']['type'] = "success";
        } else {
            $_SESSION['flash']['msg'] = "Unable to mark as paid.";
            $_SESSION['flash']['type'] = "error";
        }
        redirect('dashboard/occupiedProperties');
    }

    public function requestServiceOccupied() {
        $user_id = $_SESSION['user']->pid ?? 0;
        $bookingModel = new BookingOrders();
        $propertyModel = new PropertyConcat();

        $bookings = $bookingModel->getOrdersByOwner($user_id);
        $propertyIds = [];

        if ($bookings) {
            $today = strtotime('now');
            foreach ($bookings as $booking) {
                $bookingStatus = strtolower($booking->booking_status ?? '');
                $paymentStatus = strtolower($booking->payment_status ?? '');
                $startDate = strtotime($booking->start_date ?? 'now');
                $endDate = strtotime($booking->end_date ?? 'now');

                if (
                    ($bookingStatus === 'confirmed' || $bookingStatus === 'active') &&
                    $paymentStatus === 'paid' &&
                    $today >= $startDate && $today <= $endDate
                ) {
                    $propertyIds[$booking->property_id] = true;
                }
            }
        }

        $properties = [];
        if ($propertyIds) {
            foreach (array_keys($propertyIds) as $propertyId) {
                $property = $propertyModel->first(['property_id' => $propertyId]);
                if ($property) {
                    $properties[] = $property;
                }
            }
        }

        $this->view('customer/requestServiceOccupied', [
            'properties' => $properties
        ]);
    }
    
}
