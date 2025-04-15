<?php
defined('ROOTPATH') or exit('Access denied');

class ServiceProvider {
    use controller;
    
    public function index() {
        // Instead of loading the view directly, call dashboard()
        $this->dashboard();
    }

    public function dashboard() {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }

        $serviceLog = new ServiceLog();
        $provider_id = $_SESSION['user']->pid;

        // Get all services for this provider
        $conditions = ['service_provider_id' => $provider_id];
        $allServices = $serviceLog->where($conditions);

        // Ensure $allServices is always an array
        if (!is_array($allServices)) {
            $allServices = [];
        }

        // Initialize analytics data
        $totalProfit = 0;
        $totalHoursWorked = 0;
        $completedWorks = 0;
        $pendingWorks = 0;
        $ongoingWorks = [];
        $monthlyIncome = [];
        $serviceTypeDistribution = [];
        $serviceTypeEarnings = [];
        $weeklyEarnings = [];

        // Current month/year for monthly income
        $currentMonth = date('m');
        $currentYear = date('Y');
        $currentMonthIncome = 0;

        // Process each service
        foreach ($allServices as $service) {
            // Calculate cost
            $serviceCost = $service->cost_per_hour * $service->total_hours;
            
            // Track totalProfit
            $totalProfit += $serviceCost;
            
            // Track totalHours
            $totalHoursWorked += $service->total_hours;

            // Lowercase status for comparison
            $status = strtolower($service->status);

            // Change these comparisons to match the capitalization in your database
            if ($status === 'done' || $status === 'Done') {
                $completedWorks++;
            } elseif ($status === 'pending' || $status === 'Pending') {
                $pendingWorks++;
            } elseif ($status === 'ongoing' || $status === 'Ongoing') {
                $ongoingWorks[] = $service;
            }

            // Track monthly income if it matches current month/year
            $serviceDate = strtotime($service->date);
            $serviceMonth = date('m', $serviceDate);
            $serviceYear = date('Y', $serviceDate);

            if ($serviceYear == $currentYear && $serviceMonth == $currentMonth) {
                $currentMonthIncome += $serviceCost;
            }

            // Example: track weekly earnings by day of this week
            $serviceWeekDay = date('D', $serviceDate);
            if (!isset($weeklyEarnings[$serviceWeekDay])) {
                $weeklyEarnings[$serviceWeekDay] = 0;
            }
            $weeklyEarnings[$serviceWeekDay] += $serviceCost;

            // Example: track service type distribution/earnings
            $type = $service->service_type ?: 'Unknown';
            if (!isset($serviceTypeDistribution[$type])) {
                $serviceTypeDistribution[$type] = 0;
                $serviceTypeEarnings[$type] = 0;
            }
            $serviceTypeDistribution[$type]++;
            $serviceTypeEarnings[$type] += $serviceCost;
        }

        // Sort ongoing works by date descending, then limit to 5
        if ($ongoingWorks) {
            usort($ongoingWorks, function($a, $b) {
                return strtotime($b->date) - strtotime($a->date);
            });
            $ongoingWorks = array_slice($ongoingWorks, 0, 5);
        }

        // Total works
        $totalWorks = $completedWorks + $pendingWorks + count($ongoingWorks);

        // Completion rate
        $completionRate = $totalWorks ? round(($completedWorks / $totalWorks) * 100) : 0;

        // Avg hours per service (if desired)
        $avgHoursPerService = ($totalWorks && $totalHoursWorked) 
            ? round($totalHoursWorked / $totalWorks, 1) 
            : 0;

        // Inside your dashboard() method, after processing all services

        // Get 5 most recently completed tasks
        $recentCompletedTasks = [];
        foreach ($allServices as $service) {
            if (strtolower($service->status) === 'done') {
                $recentCompletedTasks[] = $service;
            }
        }

        // Sort by date descending
        usort($recentCompletedTasks, function($a, $b) {
            return strtotime($b->date) - strtotime($a->date);
        });

        // Limit to 5 tasks
        $recentCompletedTasks = array_slice($recentCompletedTasks, 0, 5);

        // Add to data array
        $data['recentCompletedTasks'] = $recentCompletedTasks;

        // Prepare final data
        $data = [
            'totalProfit' => $totalProfit,
            'totalHoursWorked' => $totalHoursWorked,
            'avgHoursPerService' => $avgHoursPerService,
            'completedWorks' => $completedWorks,
            'pendingWorks' => $pendingWorks,
            'ongoingWorks' => $ongoingWorks,
            'totalWorks' => $totalWorks,
            'completionRate' => $completionRate,
            'currentMonthIncome' => $currentMonthIncome,
            'weeklyEarnings' => $weeklyEarnings,
            'serviceTypeDistribution' => $serviceTypeDistribution,
            'serviceTypeEarnings' => $serviceTypeEarnings,
            'recentCompletedTasks' => $recentCompletedTasks,
        ];

        // Load the dashboard view
        $this->view('serviceprovider/dashboard', $data);
    }

    public function profile(){
        $user = new User();
        
        // notifications
        if ($_SESSION['user']->AccountStatus == 3) {// reject update
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if($updateAcc){
                $_SESSION['user']->AccountStatus = 1;
            }
            // set message
            $_SESSION['flash']['msg'] = "Your account update has been rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == 4) {// Approved update
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if($updateAcc){
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Your account has been accepted.";
            $_SESSION['flash']['type'] = "success";
        } elseif ($_SESSION['user']->AccountStatus == -3) {// Reject delete
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if($updateAcc){
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Account removal was Rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == -4) {// Approve delete
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if($updateAcc){
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Account removal was Rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == 2) {// update pending
            
            $_SESSION['flash']['msg'] = "Account update request is pending.";
            $_SESSION['flash']['type'] = "warning";
        }elseif ($_SESSION['user']->AccountStatus == -2) {// Delete pending
            
            $_SESSION['flash']['msg'] = "Account removal request is pending.";
            $_SESSION['flash']['type'] = "warning";
        } // 0 for deleted in home page
        
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
            } else if (isset($_POST['logout'])) {
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

    private function handleProfileSubmission()
    {
        $errors = [];
        $status = '';
        $targetFile = null;

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
        $user = new UserChangeDetails();

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
            // Check if the file is an image
            $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($profilePicture['type'], $validMimeTypes)) {
                $errors[] = "Invalid file type. Please upload an image file.";
            }

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
            if (!isset($_SESSION['user']) || !property_exists($_SESSION['user'], 'pid')) {
                $errors[] = "User session is not valid.";
            }
            $userId = $_SESSION['user']->pid;
            $user = new UserChangeDetails();
            // show($user->findAll());

            // Check if a record already exists for the user
            $isUserEdited = $user->first(['pid' => $userId]);
            // var_dump($isUserEdited);
            // Dynamically build the data array with only available fields
            $data = [];
            $data['pid'] = $userId;

            if (!empty($firstName)) $data['fname'] = $firstName;
            if (!empty($lastName)) $data['lname'] = $lastName;
            if (!empty($email)) $data['email'] = $email;
            if (!empty($contactNumber)) $data['contact'] = $contactNumber;
            if (!empty($targetFile)) $data['image_url'] = $targetFile;
            // die();
            // If no data is provided, skip the operation
            if (empty($data)) {
                $errors[] = "No data provided to update.<br>";
            } else {
                if (!$isUserEdited) {
                    // Include the pid when inserting a new record
                    $updated = $user->insert($data);
                } else {
                    // Update the existing record using pid as the condition
                    $dataToUpdate = [];
                    foreach ($data as $key => $value) {
                        if (isset($isUserEdited->$key) && strcmp((string)$isUserEdited->$key, (string)$value) !== 0) {
                            $dataToUpdate[$key] = $value;
                        }
                    }
                    if (!empty($dataToUpdate)) {
                        $updated = $user->update($userId, $dataToUpdate, 'pid');
                        echo "Done updateing: ";
                    } else {
                        $updated = true; // No changes needed, consider it successful
                    }
                }
            }

            if ($updated) {
                $normalUser = new User();
                $normalUser->update($userId, [
                    'AccountStatus' => 2
                ], 'pid');
                // Update the user session data
                $_SESSION['user']->AccountStatus = 2;
                $status = "Profile update request sent successfully! Please wait for approval.";
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

    public function addLogs() {
        // Ensure user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
    
        $serviceLog = new ServiceLog();
    
        // Handle POST request for form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $uploaded_images = [];
    
            // Validate required POST data
            $service_id = $_POST['service_id'] ?? null;
            $total_hours = $_POST['total_hours'] ?? null;
            $provider_description = $_POST['description'] ?? null;
    
            // Basic validation
            if (empty($service_id) || empty($total_hours) || empty($provider_description)) {
                $_SESSION['error'] = 'All fields are required.';
                redirect("serviceprovider/addLogs?service_id=$service_id");
                return;
            }
    
            // Handle image uploads
            if (isset($_FILES['property_image'])) {
                $imageDir = ROOTPATH . "public/assets/images/uploads/service_logs/";
                
                // Create directory if it doesn't exist
                if (!is_dir($imageDir)) {
                    mkdir($imageDir, 0755, true);
                }
    
                // Process each uploaded image
                foreach ($_FILES['property_image']['name'] as $key => $imageName) {
                    if ($_FILES['property_image']['error'][$key] === 0) {
                        $imageTmp = $_FILES['property_image']['tmp_name'][$key];
                        $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                        
                        // Validate image type
                        if (in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                            $uniqueImageName = uniqid() . "_service_" . $service_id . "." . $imageFileType;
                            
                            if (move_uploaded_file($imageTmp, $imageDir . $uniqueImageName)) {
                                $uploaded_images[] = $uniqueImageName;
                            } else {
                                $errors[] = "Failed to upload image: $imageName";
                            }
                        } else {
                            $errors[] = "Invalid file type for: $imageName. Only JPG, JPEG, and PNG are allowed.";
                        }
                    }
                }
            }
    
            // Check for upload errors
            if (!empty($errors)) {
                $_SESSION['error'] = implode("<br>", $errors);
                redirect("serviceprovider/addLogs?service_id=$service_id");
                return;
            }
    
            // Get existing service log
            $existing_log = $serviceLog->first(['service_id' => $service_id]);
            if (!$existing_log) {
                $_SESSION['error'] = 'Service log not found';
                redirect("serviceprovider/addLogs?service_id=$service_id");
                return;
            }
    
            // Prepare update data
            $update_data = [
                'total_hours' => $total_hours,
                'status' => 'Done',
                'service_provider_description' => $provider_description
            ];
    
            // Add images if any were uploaded
            if (!empty($uploaded_images)) {
                $update_data['service_images'] = json_encode($uploaded_images);
            }
    
            // Update the service log
            $update_result = $serviceLog->update($service_id, $update_data, 'service_id');
    
            if ($update_result) {
                $_SESSION['success'] = 'Service log updated successfully.';
                redirect('serviceprovider/repairRequests');
                return;
            } else {
                $_SESSION['error'] = 'Failed to update service log. Please try again.';
                error_log('Service Log Update Failed - Data: ' . print_r($update_data, true));
                redirect("serviceprovider/addLogs?service_id=$service_id");
                return;
            }
        }
    
        // Handle GET request - Display the form
        $service_id = $_GET['service_id'] ?? null;
        if (!$service_id) {
            redirect('serviceprovider/repairRequests');
            return;
        }
    
        // Get service details
        $current_service = $serviceLog->first(['service_id' => $service_id]);
        if (!$current_service) {
            $_SESSION['error'] = 'Service not found';
            redirect('serviceprovider/repairRequests');
            return;
        }
    
        // Prepare view data
        $view_data = [
            'service_id' => $service_id,
            'property_id' => $current_service->property_id ?? null,
            'property_name' => $current_service->property_name ?? null,
            'service_type' => $current_service->service_type ?? null,
            'status' => $current_service->status ?? null,
            'earnings' => $current_service->cost_per_hour * ($current_service->total_hours ?? 0),
            'current_service' => $current_service
        ];
    
        // Load view with data
        $this->view('serviceprovider/addLogs', $view_data);
    }

    public function earnings() {
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
    
        $serviceLog = new ServiceLog();
        $provider_id = $_SESSION['user']->pid;
    
        // Fetch earnings from the serviceLog table
        $conditions = ['service_provider_id' => $provider_id];
        $earnings = $serviceLog->where($conditions);
    
        // Prepare chart data
        $chartData = [];
        foreach ($earnings as $service) {
            $chartData[] = [
                'name' => $service->service_type ?? 'Unknown', // Default if name is missing
                'totalEarnings' => $service->cost_per_hour * $service->total_hours
            ];
        }
    
        // Pass data to the view
        $this->view('serviceprovider/earnings', ['chartData' => $chartData]);
    }    
    
    public function serviceSummery(){
        $service_id = $_GET['service_id'] ?? null;
        if (!$service_id) {
            redirect('serviceprovider/repairRequests');
            return;
        }

        // Get service details
        $serviceLog = new ServiceLog();
        $service_details = $serviceLog->first(['service_id' => $service_id]);

        if (!$service_details) {
            $_SESSION['error'] = 'Service not found';
            redirect('serviceprovider/repairRequests');
            return;
        }

        // Prepare view data
        $view_data = [
            'service_id' => $service_id,
            'property_id' => $service_details->property_id ?? null,
            'property_name' => $service_details->property_name ?? null,
            'service_type' => $service_details->service_type ?? null,
            'status' => $service_details->status ?? null,
            'earnings' => $service_details->cost_per_hour * ($service_details->total_hours ?? 0),
            'service_images' => json_decode($service_details->service_images ?? '[]'),
            'service_provider_description' => $service_details->service_provider_description ?? '',
        ];

        // Load view with data
        $this->view('serviceprovider/serviceSummery', $view_data);
    }


    public function repairListing(){
        $this->view('serviceprovider/repairListing');
    }

    public function repairRequests() {
    // Check if user is logged in
    if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
        redirect('login');
        return;
    }

    $serviceLog = new ServiceLog();
    $provider_id = $_SESSION['user']->pid;

    // Get current page for pagination
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $items_per_page = 10;
    $offset = ($page - 1) * $items_per_page;

    // Get status filter
    $selected_status = isset($_GET['status']) ? $_GET['status'] : 'all';

    // Construct the query conditions
    $conditions = [
        'service_provider_id' => $provider_id
    ];

    // Add status filter if provided
    if ($selected_status !== 'all') {
        $conditions['status'] = $selected_status;
    }

    // Get total count for pagination
    $allServices = $serviceLog->where($conditions); // Fetch all matching records
    
    // Fix: Ensure $allServices is always an array
    if (!is_array($allServices)) {
        $allServices = []; // Convert to empty array if false or other non-array value
    }
    
    $total_records = count($allServices);
    $total_pages = ceil($total_records / $items_per_page);

    // Paginate the records
    $services = array_slice($allServices, $offset, $items_per_page);

    // Calculate time left for pending services
    foreach ($services as &$service) {
        $service->earnings = $service->cost_per_hour * $service->total_hours;

        if ($service->status === 'Ongoing') {
            // Calculate hours left (assuming 48-hour SLA from service date)
            $service_date = new DateTime($service->date);
            $current_date = new DateTime();
            $time_diff = $service_date->diff($current_date);
            $hours_passed = ($time_diff->days * 24) + $time_diff->h;
            $days_left = floor($hours_passed / 24);
            $hours_left = $hours_passed % 24;
            $service->time_left = $hours_left > 0 ? $days_left . 'd ' . $hours_left . 'hr' : ($days_left > 0 ? $days_left . 'd' : 'Overdue');
        } else {
            $service->time_left = '-';
        }
    }

    // Prepare data for the view
    $data = [
        'services' => $services,
        'current_page' => $page,
        'total_pages' => $total_pages,
        'selected_status' => $selected_status
    ];

    // Load the view with data
    $this->view('serviceprovider/repairRequests', $data);
}
    

    public function repairs(){
        $this->view('serviceprovider/repairs');
    }

    private function logout(){
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }
}
