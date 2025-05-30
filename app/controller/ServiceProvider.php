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
        $externalService = new ExternalService(); 
        $provider_id = $_SESSION['user']->pid;
    
        // Get all regular services for this provider
        $conditions = ['service_provider_id' => $provider_id];
        $allServices = $serviceLog->where($conditions);
    
        // Get all external services for this provider
        $externalConditions = ['service_provider_id' => $provider_id];
        $allExternalServices = $externalService->where($externalConditions);
    
        // Ensure both are arrays
        if (!is_array($allServices)) {
            $allServices = [];
        }
        
        if (!is_array($allExternalServices)) {
            $allExternalServices = [];
        }
    
        // Initialize analytics data
        $totalProfit = 0;
        $totalHoursWorked = 0;
        $completedWorks = 0;
        $pendingWorks = 0;
        $worksToDo = 0;
        $ongoingWorks = [];
        $monthlyIncome = [];
        $serviceTypeDistribution = [];
        $serviceTypeEarnings = [];
        $weeklyEarnings = [
            'Mon' => 0, 'Tue' => 0, 'Wed' => 0, 
            'Thu' => 0, 'Fri' => 0, 'Sat' => 0, 'Sun' => 0
        ];
    
        // Current month/year for monthly income
        $currentMonth = date('m');
        $currentYear = date('Y');
        $currentMonthIncome = 0;
    
        // Process regular services
        foreach ($allServices as $service) {
            // Calculate cost using total_cost field
            $serviceCost = $service->total_cost ?? 0;
            
            // Track totalProfit
            if(isset($service->total_cost)) {
                $totalProfit += $service->total_cost;
            }
            
            // Track totalHours
            if(isset($service->total_hours)) {
                $totalHoursWorked += $service->total_hours;
            }
    
            // Lowercase status for consistent comparison
            $status = strtolower($service->status ?? '');
    
            // Track status counts
            if ($status === 'done') {
                $completedWorks++;
            } elseif ($status === 'pending') {
                $pendingWorks++;
            } elseif ($status === 'ongoing') {
                $ongoingWorks[] = $service;
                $worksToDo++;
            }
    
            // Track monthly incme
            if(isset($service->date)) {
                $serviceDate = strtotime($service->date);
                $serviceMonth = date('m', $serviceDate);
                $serviceYear = date('Y', $serviceDate);
    
                if ($serviceYear == $currentYear && $serviceMonth == $currentMonth) {
                    $currentMonthIncome += $serviceCost;
                }
    
                // Track weekly earnings
                $serviceWeekDay = date('D', $serviceDate);
                if (!isset($weeklyEarnings[$serviceWeekDay])) {
                    $weeklyEarnings[$serviceWeekDay] = 0;
                }
                $weeklyEarnings[$serviceWeekDay] += $serviceCost;
            }
    
            // Track service type distribution/earnings
            $type = $service->service_type ?: 'Unknown';
            if (!isset($serviceTypeDistribution[$type])) {
                $serviceTypeDistribution[$type] = 0;
                $serviceTypeEarnings[$type] = 0;
            }
            $serviceTypeDistribution[$type]++;
            $serviceTypeEarnings[$type] += $serviceCost;
        }
    
        // Process external services
        foreach ($allExternalServices as $service) {
            // Calculate cost using total_cost field
            $serviceCost = $service->total_cost ?? 0;
            
            // Track totalProfit
            if(isset($service->total_cost)) {
                $totalProfit += $service->total_cost;
            }
            
            // Track totalHours
            if(isset($service->total_hours)) {
                $totalHoursWorked += $service->total_hours;
            }
    
            // Status comparison - external services use lowercase status values
            $status = strtolower($service->status ?? '');
    
            // Track status counts
            if ($status === 'done') {
                $completedWorks++;
            } elseif ($status === 'pending') {
                $pendingWorks++;
            } elseif ($status === 'ongoing') {
                // Create a standardized object for display
                $standardizedService = (object)[
                    'service_id' => $service->id,
                    'service_type' => $service->service_type,
                    'property_name' => $service->property_address,
                    'date' => $service->date,
                    'total_hours' => $service->total_hours,
                    'cost_per_hour' => $service->cost_per_hour,
                    'service_description' => $service->property_description,
                    'is_external' => true // Flag to identify external services
                ];
                
                $ongoingWorks[] = $standardizedService;
                $worksToDo++;
            }
    
            // Track monthly income
            if(isset($service->date)) {
                $serviceDate = strtotime($service->date);
                $serviceMonth = date('m', $serviceDate);
                $serviceYear = date('Y', $serviceDate);
    
                if ($serviceYear == $currentYear && $serviceMonth == $currentMonth) {
                    $currentMonthIncome += $serviceCost;
                }
    
                // Track weekly earnings
                $serviceWeekDay = date('D', $serviceDate);
                if (isset($weeklyEarnings[$serviceWeekDay])) {
                    $weeklyEarnings[$serviceWeekDay] += $serviceCost;
                }
            }
    
            // Track service type distribution/earnings
            $type = $service->service_type ?: 'External';
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
                return strtotime($b->date ?? '') - strtotime($a->date ?? '');
            });
            $ongoingWorks = array_slice($ongoingWorks, 0, 5);
            
            // Add time_info and earnings to each ongoing work
            foreach ($ongoingWorks as &$work) {
                // Calculate time info
                if (isset($work->date)) {
                    $service_date = new DateTime($work->date);
                    $current_date = new DateTime();
                    $time_diff = $service_date->diff($current_date);
                    $hours_passed = ($time_diff->days * 24) + $time_diff->h;
                    $work->time_info = $hours_passed . ' hr' . ($hours_passed != 1 ? 's' : '') . ' ago';
                } else {
                    $work->time_info = 'N/A';
                }
                
                // Calculate earnings
                $work->earnings = isset($work->cost_per_hour) && isset($work->total_hours) ? 
                    $work->cost_per_hour * $work->total_hours : 0;
            }
        }
    
        // Total works
        $totalWorks = $completedWorks + $pendingWorks + count($ongoingWorks);
    
        // Completion rate
        $completionRate = $totalWorks ? round(($completedWorks / $totalWorks) * 100) : 0;
    
        // Avg hours per service 
        $avgHoursPerService = ($totalWorks && $totalHoursWorked) 
            ? round($totalHoursWorked / $totalWorks, 1) 
            : 0;
    
        // Get 5 most recently completed tasks (combining both regular and external)
        $recentCompletedTasks = [];
        
        // Add completed regular services
        foreach ($allServices as $service) {
            if (strtolower($service->status ?? '') === 'done') {
                $recentCompletedTasks[] = $service;
            }
        }
        
        // Add completed external services (converting to regular service format)
        foreach ($allExternalServices as $external) {
            if (strtolower($external->status ?? '') === 'done') {
                // Create a standardized object for display
                $completedTask = (object)[
                    'service_id' => $external->id,
                    'service_type' => $external->service_type,
                    'property_name' => $external->property_address, 
                    'date' => $external->date,
                    'cost_per_hour' => $external->cost_per_hour, 
                    'total_hours' => $external->total_hours,
                    'is_external' => true
                ];
                
                $recentCompletedTasks[] = $completedTask;
            }
        }
    
        // Sort by date descending
        usort($recentCompletedTasks, function($a, $b) {
            return strtotime($b->date ?? '') - strtotime($a->date ?? '');
        });
    
        // Limit to 5 tasks
        $recentCompletedTasks = array_slice($recentCompletedTasks, 0, 5);
    
        // Prepare final data
        $data = [
            'totalProfit' => $totalProfit,
            'totalHoursWorked' => $totalHoursWorked,
            'avgHoursPerService' => $avgHoursPerService,
            'completedWorks' => $completedWorks,
            'pendingWorks' => $pendingWorks,
            'ongoingWorks' => $ongoingWorks,
            'worksToDo' => $worksToDo,
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

    public function profile()
    {
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
        } elseif ($_SESSION['user']->AccountStatus == -2) {// Delete pending
            
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
                    'AccountStatus' => -2 //pending deletion
                ], 'pid');

                if ($updated) {
                    $_SESSION['user']->AccountStatus = -2; // Assuming 0 indicates a deleted account

                    $_SESSION['flash']['msg'] = "Deletion Request sent.Please wait for appoval.";
                    $_SESSION['flash']['type'] = "success";

                } else {
                    $_SESSION['flash']['msg'] = "Failed to request deletion of account. Please try again.";
                    $_SESSION['flash']['type'] = "error";
                    // $errors[] = "Failed to delete account. Please try again.";
                }

                // Store errors in session and redirect back
                $_SESSION['errors'] = $errors;
                redirect('dashboard/profile');
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
            $additional_charges = floatval($_POST['additional_charges'] ?? 0);
            $additional_charges_reason = $_POST['additional_charges_reason'] ?? '';
            $images_to_remove = !empty($_POST['images_to_remove']) ? explode(',', $_POST['images_to_remove']) : [];
            
            // Check if this is just a save operation or a complete operation
            $is_save_only = isset($_POST['save_operation']) && $_POST['save_operation'] == '1';
    
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
    
            // Calculate the usual cost (hourly rate × hours worked)
            $usual_cost = (float)$existing_log->cost_per_hour * (float)$total_hours;
    
            // Prepare update data
            $update_data = [
                'total_hours' => $total_hours,
                'service_provider_description' => $provider_description,
                'usual_cost' => $usual_cost  
            ];
            
            // Only change status to Done if completing the service, not just saving
            if (!$is_save_only) {
                $update_data['status'] = 'Done';
            }
    
            // Handle additional charges
            if ($additional_charges > 0) {
                $update_data['additional_charges'] = $additional_charges;
                $update_data['additional_charges_reason'] = $additional_charges_reason;
            } else {
                // Ensure additional charges is set to zero when not provided
                $update_data['additional_charges'] = 0;
                $update_data['additional_charges_reason'] = '';
            }
    
            // Calculate and set total_cost
            $update_data['total_cost'] = $usual_cost + (float)$additional_charges;
    
            // Process existing images and handle removals
            $existing_service_images = [];
            if (!empty($existing_log->service_images)) {
                $decoded_images = json_decode($existing_log->service_images, true);
                if (is_array($decoded_images)) {
                    $existing_service_images = $decoded_images;
                }
            }
    
            // Remove images if any were marked for deletion
            if (!empty($images_to_remove)) {
                $existing_service_images = array_filter($existing_service_images, function($img) use ($images_to_remove) {
                    return !in_array($img, $images_to_remove);
                });
                
                // Physically delete the files from storage
                foreach ($images_to_remove as $image_name) {
                    $image_path = ROOTPATH . "public/assets/images/uploads/service_logs/" . $image_name;
                    if (file_exists($image_path)) {
                        unlink($image_path);
                    }
                }
            }
    
            // Add new uploaded images
            if (!empty($uploaded_images)) {
                $existing_service_images = array_merge($existing_service_images, $uploaded_images);
            }
    
            // Update the service_images field with combined array
            $update_data['service_images'] = json_encode(array_values($existing_service_images) ?: []);
    
            // Update the service log
            $update_result = $serviceLog->update($service_id, $update_data, 'service_id');
    
            if ($update_result) {
                // Only send notification if completing the service (not just saving :)
                if (!$is_save_only) {
                    // Fetch the property details to get owner information
                    if (!empty($existing_log->property_id)) {
                        $property = new Property();
                        $propertyDetails = $property->first(['property_id' => $existing_log->property_id]);
                        
                        if ($propertyDetails && !empty($propertyDetails->person_id)) {
                            // Calculate total cost including additional charges
                            $totalCost = ($existing_log->cost_per_hour * $total_hours) + $additional_charges;
                            
                            // Create notification message with additional charges if applicable
                            $notificationMessage = "The " . ($existing_log->service_type ?? "maintenance") . 
                                                " service for " . ($propertyDetails->name ?? "your property") . 
                                                " has been completed.";
                            
                            // Add additional charges information to notification if applicable
                            if ($additional_charges > 0) {
                                $notificationMessage .= " Additional charges of LKR" . number_format($additional_charges, 2) . 
                                                    " were applied for " . $additional_charges_reason . ".";
                            }
                            
                            // Create notification for property owner
                            $notificationModel = new NotificationModel();
                            $ownerNotificationData = [
                                'user_id' => $propertyDetails->person_id,
                                'title' => "Service Completed",
                                'message' => $notificationMessage,
                                'color' => 'Notification_green',
                                'is_read' => 0,
                                'link' => ROOT . '/dashboard/maintenance',
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                            
                            // Insert notification for property owner
                            $notificationModel->insert($ownerNotificationData);
                        }
                    }
                }
                
                // Different success messages and redirects based on operation
                if ($is_save_only) {
                    $_SESSION['success'] = 'Service log changes saved successfully.';
                    redirect("serviceprovider/addLogs?service_id=$service_id"); // Stay on the edit page
                } else {
                    $_SESSION['success'] = 'Service log completed successfully.';
                    redirect('serviceprovider/serviceSummery?service_id=' . $service_id); // Go to summary page
                }
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
    
        // Fetch property address
        $propertyModel = new Property();
        $property_details = $propertyModel->first(['property_id' => $current_service->property_id]);
    
        // Fetch property image using PropertyImageModel
        $property_image = 'listing_alt.jpg'; // default
        if ($property_details) {
            require_once '../app/models/PropertyImageModel.php'; // adjust path if needed
            $propertyImageModel = new PropertyImageModel();
            $images = $propertyImageModel->where(['property_id' => $current_service->property_id]);
            if ($images && is_array($images) && !empty($images) && !empty($images[0]->image_url)) {
                $property_image = $images[0]->image_url;
            }
        }
    
        // Fetch requester info
        $userModel = new User();
        $requested_person = $userModel->first(['pid' => $current_service->requested_person_id]);
    
        // Get existing service images for removal option
        $existing_images = [];
        if (!empty($current_service->service_images)) {
            $decoded_images = json_decode($current_service->service_images, true);
            if (is_array($decoded_images)) {
                $existing_images = $decoded_images;
            }
        }
    
        // Prepare view data
        $view_data = [
            'service_id' => $service_id,
            'property_id' => $current_service->property_id ?? null,
            'property_name' => $current_service->property_name ?? null,
            'service_type' => $current_service->service_type ?? null,
            'status' => $current_service->status ?? null,
            'hourly_rate' => $current_service->cost_per_hour ?? 2500,
            'earnings' => $current_service->cost_per_hour * ($current_service->total_hours ?? 0),
            'current_service' => $current_service,
            'property_address' => $property_details->address ?? '',
            'service_description' => $current_service->service_description ?? '',
            'requester_name' => trim(($requested_person->fname ?? '') . ' ' . ($requested_person->lname ?? '')),
            'requester_email' => $requested_person->email ?? '',
            'requester_contact' => $requested_person->contact ?? '',
            'property_image' => $property_image,
            'existing_images' => $existing_images,
            'total_hours' => $current_service->total_hours ?? null,
            'provider_description' => $current_service->service_provider_description ?? '',
            'additional_charges' => $current_service->additional_charges ?? 0,
            'additional_charges_reason' => $current_service->additional_charges_reason ?? ''
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
        $externalService = new ExternalService(); 
        $provider_id = $_SESSION['user']->pid;
    
        // Fetch completed services with earnings details from ServiceLog
        $conditions = [
            'service_provider_id' => $provider_id,
            'status' => 'Done'  // Only get completed services
        ];
        $completedServices = $serviceLog->where($conditions);
    
        // Ensure $completedServices is always an array
        if (!is_array($completedServices)) {
            $completedServices = [];
        }
    
        // Fetch completed external services
        $externalConditions = [
            'service_provider_id' => $provider_id,
            'status' => 'done'  
        ];
        $completedExternalServices = $externalService->where($externalConditions);
    
        // Ensure $completedExternalServices is always an array
        if (!is_array($completedExternalServices)) {
            $completedExternalServices = [];
        }
    
        // Prepare chart data for regular services
        $chartData = [];
        foreach ($completedServices as $service) {
            // Calculate earnings
            $totalEarnings = $service->total_cost;
            
            // Add to chart data
            $chartData[] = [
                'name' => $service->service_type ?? 'Unknown',
                'totalEarnings' => $totalEarnings,
                'property_name' => $service->property_name ?? 'Unknown Property',
                'date' => $service->date,
                'total_hours' => $service->total_hours,
                'service_images' => $service->service_images ?? null,
                'service_provider_description' => $service->service_provider_description ?? null,
                'service_type' => 'regular'  // Mark as regular service for UI differentiation
            ];
        }
    
        // Add external services to the chart data
        foreach ($completedExternalServices as $service) {
            // Calculate earnings with the same formula
            $totalEarnings = $service->total_cost; 
            
            // Process images properly
            $serviceImages = null;
            if (!empty($service->service_completion_images)) {
                $images = json_decode($service->service_completion_images, true);
                // Fix image paths if needed
                if (is_array($images)) {
                    // Ensure paths are properly formatted
                    $serviceImages = array_map(function($img) {
                        // If the path already has "uploads/" at the beginning, use as is
                        if (strpos($img, 'uploads/') === 0) {
                            return $img;
                        }
                        // If the path contains uploads but not at beginning, extract the filename
                        else if (strpos($img, 'uploads/') !== false) {
                            return substr($img, strpos($img, 'uploads/'));
                        }
                        // Otherwise assume it's just a filename and prepend the path
                        else {
                            return 'uploads/external_services/' . $img;
                        }
                    }, $images);
                }
            }
            
            // Add to chart data with fixed image paths
            $chartData[] = [
                'name' => $service->service_type ?? 'External Service',
                'totalEarnings' => $totalEarnings,
                'property_name' => $service->property_address ?? 'External Location',
                'date' => $service->date,
                'total_hours' => $service->total_hours,
                'service_images' => $serviceImages, // Fixed service images
                'service_provider_description' => $service->service_provider_description ?? null,
                'service_type' => 'external'  // Mark as external service for UI differentiation
            ];
        }
    
        // Sort all services by date (newest first)
        usort($chartData, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
    
        // Pass data to the view
        $this->view('serviceprovider/earnings', ['chartData' => $chartData]);
    }
    
    public function serviceSummery() {
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

        // NEW FEATURE: Get requester/customer info
        $userModel = new User();
        $customer = null;
        if (!empty($service_details->requested_person_id)) {
            $customer = $userModel->first(['pid' => $service_details->requested_person_id]);
        }

        // NEW FEATURE: Process service images with proper error handling
        $service_images = [];
        if (!empty($service_details->service_images)) {
            $decoded_images = json_decode($service_details->service_images, true);
            if (is_array($decoded_images)) {
                $service_images = $decoded_images;
            }
        }

        // NEW FEATURE: Get property details
        $propertyModel = new Property();
        $property_details = null;
        if (!empty($service_details->property_id)) {
            $property_details = $propertyModel->first(['property_id' => $service_details->property_id]);
        }

        // NEW FEATURE: Format service provider name
        $service_provider_name = $_SESSION['user']->fname . ' ' . $_SESSION['user']->lname;
        if (!empty($service_details->service_provider_id) && $service_details->service_provider_id != $_SESSION['user']->pid) {
            $service_provider = $userModel->first(['pid' => $service_details->service_provider_id]);
            if ($service_provider) {
                $service_provider_name = $service_provider->fname . ' ' . $service_provider->lname;
            }
        }

        // Prepare view data with all required fields for the enhanced UI
        $view_data = [
            'service_id' => $service_id,
            'property_id' => $service_details->property_id ?? null,
            'property_name' => $service_details->property_name ?? null,
            'service_type' => $service_details->service_type ?? null,
            'status' => $service_details->status ?? 'Unknown',
            'cost_per_hour' => $service_details->cost_per_hour ?? 0,
            'total_hours' => $service_details->total_hours ?? 0,
            'usual_cost' => $service_details->usual_cost ?? 0,
            'additional_charges' => $service_details->additional_charges ?? 0,
            'additional_charges_reason' => $service_details->additional_charges_reason ?? null,
            'total_cost' => $service_details->total_cost ?? 0,
            'date' => $service_details->date ?? null,
            'service_images' => $service_images,
            'service_description' => $service_details->service_description ?? '',
            'service_provider_description' => $service_details->service_provider_description ?? '',
            'service_provider_name' => $service_provider_name,
            'requester_name' => $customer ? ($customer->fname . ' ' . $customer->lname) : 'Unknown',
            'requester_contact' => $customer ? $customer->contact : 'Not available',
            'property_address' => $property_details ? $property_details->address : 'Address not available'
        ];

        // Load view with data
        $this->view('serviceprovider/serviceSummery', $view_data);
    }

    public function repairListing() {
    // Check if user is logged in
    if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
        redirect('login');
        return;
    }
    
    // Fetch all services from the database
    $services = new Services();
    $allServices = $services->getAllServices();
    
    $this->view('serviceprovider/repairListing', [
        'services' => $allServices
    ]);
}

public function serviceOverview() {
    // Check if user is logged in
    if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
        redirect('login');
        return;
    }
    
    $service_id = $_GET['service_id'] ?? null;
    $service_name = $_GET['service_name'] ?? null;
    
    if (!$service_id && !$service_name) {
        redirect('serviceprovider/repairListing');
        return;
    }
    
    // Get service details
    $services = new Services();
    $service = null;
    
    if ($service_id) {
        $service = $services->getServiceById($service_id);
    } else if ($service_name) {
        $service = $services->first(['name' => $service_name]);
    }
    
    if (!$service) {
        $_SESSION['error'] = 'Service not found';
        redirect('serviceprovider/repairListing');
        return;
    }
    
    // Get provider's previous tasks for this service type
    $serviceLog = new ServiceLog();
    $provider_id = $_SESSION['user']->pid;
    
    $previous_tasks = $serviceLog->where([
        'service_provider_id' => $provider_id,
        'service_type' => $service->name,
        'status' => 'Done'
    ]);
    
    // Ensure $previous_tasks is always an array
    if (!is_array($previous_tasks)) {
        $previous_tasks = [];
    }
    
    // Sorting(newest first)
    usort($previous_tasks, function($a, $b) {
        return strtotime($b->date) - strtotime($a->date);
    });
    
    // Prepare view data
    $data = [
        'service' => $service,
        'previous_tasks' => $previous_tasks
    ];
    
    $this->view('serviceprovider/serviceOverview', $data);
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
        $service->earnings = $service->total_cost;

        if ($service->status === 'Ongoing') {
            // Calculate hours left (assuming 48-hour SLA from service date)
            $service_date = new DateTime($service->date);
            $current_date = new DateTime();
            $time_diff = $service_date->diff($current_date);
            $hours_passed = ($time_diff->days * 24) + $time_diff->h;
            $days_left = floor($hours_passed / 24);
            $hours_left = $hours_passed % 24;
            $service->time_left = $hours_left > 0 ? $days_left . 'd ' : ($days_left > 0 ? $days_left . 'd' : 'Overdue');
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

    public function externalServices() {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
    
        $externalService = new ExternalService();
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
    
        // Get all matching records for counting
        $allServices = $externalService->where($conditions);
        
        // Fix: Ensure $allServices is always an array
        if (!is_array($allServices)) {
            $allServices = []; // Convert to empty array if false or non-array
        }
        
        $total_records = count($allServices);
        $total_pages = ceil($total_records / $items_per_page);
    
        // Paginate the records
        $services = array_slice($allServices, $offset, $items_per_page);
    
        // Calculate earnings and time left for services
        foreach ($services as &$service) {
            // Calculate earnings based on cost_per_hour and total_hours
            $service->earnings = $service->cost_per_hour * $service->total_hours;
    
            if ($service->status === 'ongoing') {
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
            
            // Decode service_images from JSON if exists
            if (!empty($service->service_images)) {
                $service->service_images_array = json_decode($service->service_images, true);
            } else {
                $service->service_images_array = [];
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
        $this->view('serviceProvider/externalServices', $data);
    }

    public function updateExternalService() {
        // Ensure user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
    
        $externalService = new ExternalService();
    
        // Handle POST request for form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            $uploaded_images = [];
    
            // Validate required POST data
            $service_id = $_POST['id'] ?? null;
            $total_hours = $_POST['total_hours'] ?? null;
            $provider_description = $_POST['service_provider_description'] ?? null;
            $additional_charges = floatval($_POST['additional_charges'] ?? 0);
            $additional_charges_reason = $_POST['additional_charges_reason'] ?? '';
            $images_to_remove = !empty($_POST['images_to_remove']) ? explode(',', $_POST['images_to_remove']) : [];
    
            // Basic validation
            if (empty($service_id) || empty($total_hours) || empty($provider_description)) {
                $_SESSION['error'] = 'All fields are required.';
                redirect("serviceprovider/updateExternalService?id=$service_id");
                return;
            }
    
            // Get existing service
            $existing_service = $externalService->first(['id' => $service_id]);
            if (!$existing_service) {
                $_SESSION['error'] = 'Service not found';
                redirect("serviceprovider/updateExternalService?id=$service_id");
                return;
            }
    
            // Handle image uploads for service completion
            if (isset($_FILES['service_image'])) {
                $imageDir = ROOTPATH . "public/assets/images/uploads/external_services/";
                
                // Create directory if it doesn't exist
                if (!is_dir($imageDir)) {
                    mkdir($imageDir, 0755, true);
                }
    
                // Process each uploaded image
                foreach ($_FILES['service_image']['name'] as $key => $imageName) {
                    if ($_FILES['service_image']['error'][$key] === 0) {
                        $imageTmp = $_FILES['service_image']['tmp_name'][$key];
                        $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                        
                        // Validate image type
                        if (in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                            $uniqueImageName = uniqid() . "_completion_" . $service_id . "." . $imageFileType;
                            
                            if (move_uploaded_file($imageTmp, $imageDir . $uniqueImageName)) {
                                $uploaded_images[] = "uploads/external_services/" . $uniqueImageName;
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
                redirect("serviceprovider/updateExternalService?id=$service_id");
                return;
            }
    
            // Calculate the usual cost (hourly rate × hours worked)
            $usual_cost = (float)$existing_service->cost_per_hour * (float)$total_hours;
    
            // Prepare update data
            $update_data = [
                'total_hours' => $total_hours,
                'service_provider_description' => $provider_description,
                'usual_cost' => $usual_cost
            ];
    
            // Handle mark as complete
            if (isset($_POST['mark_complete']) && $_POST['mark_complete'] == '1') {
                $update_data['status'] = 'done';
            }
    
            // Handle additional charges
            if ($additional_charges > 0) {
                $update_data['additional_charges'] = $additional_charges;
                $update_data['additional_charges_reason'] = $additional_charges_reason;
            } else {
                // Ensure additional charges is set to zero when not provided
                $update_data['additional_charges'] = 0;
                $update_data['additional_charges_reason'] = '';
            }
    
            // Calculate and set total_cost
            $update_data['total_cost'] = $usual_cost + (float)$additional_charges;
    
            // Process service completion images (additions and removals)
            $existing_completion_images = [];
            if (!empty($existing_service->service_completion_images)) {
                $decoded_images = json_decode($existing_service->service_completion_images, true);
                if (is_array($decoded_images)) {
                    $existing_completion_images = $decoded_images;
                }
            }
            
            // Remove images if any were marked for deletion
            if (!empty($images_to_remove)) {
                $existing_completion_images = array_filter($existing_completion_images, function($img) use ($images_to_remove) {
                    return !in_array($img, $images_to_remove);
                });
                
                // Physically delete the files 
                foreach ($images_to_remove as $image_path) {
                    $full_path = ROOTPATH . "public/assets/images/" . $image_path;
                    if (file_exists($full_path)) {
                        unlink($full_path);
                    }
                }
            }
            
            // Add new images if any were uploaded
            if (!empty($uploaded_images)) {
                $existing_completion_images = array_merge($existing_completion_images, $uploaded_images);
            }
            
            // Update the service_completion_images field - safely encode as JSON
            $update_data['service_completion_images'] = json_encode(array_values($existing_completion_images) ?: []);
    
            // Update the external service
            $update_result = $externalService->update($service_id, $update_data);
    
            if ($update_result) {
                // Create notification for customer if service was marked as complete
                if (isset($update_data['status']) && $update_data['status'] === 'done') {
                    if (!empty($existing_service->requested_person_id)) {
                        // Calculate total cost including additional charges
                        $totalCost = ($existing_service->cost_per_hour * $total_hours) + $additional_charges;
                        
                        // Create notification message with additional charges if applicable
                        $notificationMessage = "Your " . ($existing_service->service_type ?? "external service") . 
                                            " request for " . ($existing_service->property_address ?? "your property") . 
                                            " has been completed.";
                        
                        // Add additional charges information to notification if applicable
                        if ($additional_charges > 0) {
                            $notificationMessage .= " Additional charges of LKR" . number_format($additional_charges, 2) . 
                                                " were applied for " . $additional_charges_reason . ".";
                        }
                        
                        // Create notification for customer
                        $notificationModel = new NotificationModel();
                        $customerNotificationData = [
                            'user_id' => $existing_service->requested_person_id,
                            'title' => "External Service Completed",
                            'message' => $notificationMessage,
                            'color' => 'Notification_green',
                            'is_read' => 0,
                            'link' => ROOT . '/dashboard/requestService',
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        // Insert notification for customer
                        $notificationModel->insert($customerNotificationData);
                    }
                }
                
                if (isset($update_data['status']) && $update_data['status'] === 'done') {
                    $_SESSION['success'] = 'Service marked as complete successfully.';
                    redirect('serviceprovider/externalServiceSummary?id=' . $service_id);
                    return;
                } else {
                    $_SESSION['success'] = 'Service updated successfully.';
                    redirect('serviceprovider/externalServices');
                    return;
                }
            } else {
                $_SESSION['error'] = 'Failed to update service. Please try again.';
                error_log('External Service Update Failed - Data: ' . print_r($update_data, true));
                redirect("serviceprovider/updateExternalService?id=$service_id");
                return;
            }
        }
        
        // Handle GET request - Display the form
        $service_id = $_GET['id'] ?? null;
        if (!$service_id) {
            redirect('serviceprovider/externalServices');
            return;
        }
    
        // Get service details
        $service = $externalService->first(['id' => $service_id]);
        if (!$service) {
            $_SESSION['error'] = 'Service not found';
            redirect('serviceprovider/externalServices');
            return;
        }
    
        // Verify service provider is assigned to this service
        if ($service->service_provider_id != $_SESSION['user']->pid) {
            $_SESSION['error'] = 'You are not assigned to this service';
            redirect('serviceprovider/externalServices');
            return;
        }
    
        // Get property images
        $propertyImages = [];
        if (!empty($service->service_images)) {
            $decoded = json_decode($service->service_images, true);
            if (is_array($decoded)) {
                $propertyImages = $decoded;
            }
        }
    
        // Get service completion images
        $serviceCompletionImages = [];
        if (!empty($service->service_completion_images)) {
            $decoded = json_decode($service->service_completion_images, true);
            if (is_array($decoded)) {
                $serviceCompletionImages = $decoded;
            }
        }
    
        // Get customer info
        $userModel = new User();
        $customer = null;
        if (!empty($service->requested_person_id)) {
            $customer = $userModel->first(['pid' => $service->requested_person_id]);
        }
    
        // Prepare view data
        $view_data = [
            'service' => $service,
            'customer' => $customer,
            'propertyImages' => $propertyImages,
            'serviceCompletionImages' => $serviceCompletionImages
        ];
    
        // Load view with data
        $this->view('serviceProvider/updateExternalService', $view_data);
    }

    public function externalServiceSummary() {
        // Get service ID from URL
        $service_id = $_GET['id'] ?? null;
        if (!$service_id) {
            redirect('serviceprovider/externalServices');
            return;
        }
    
        // Get service details
        $externalService = new ExternalService();
        $service = $externalService->first(['id' => $service_id]);
    
        if (!$service) {
            $_SESSION['error'] = 'Service not found';
            redirect('serviceprovider/externalServices');
            return;
        }
    
        // Verify service provider is assigned to this service
        if ($service->service_provider_id != $_SESSION['user']->pid) {
            $_SESSION['error'] = 'You are not assigned to this service';
            redirect('serviceprovider/externalServices');
            return;
        }
    
        // Get customer info
        $userModel = new User();
        $customer = null;
        if (!empty($service->requested_person_id)) {
            $customer = $userModel->first(['pid' => $service->requested_person_id]);
        }
    
        // Get property images
        $propertyImages = [];
        if (!empty($service->service_images)) {
            $propertyImages = json_decode($service->service_images, true);
        }
    
        // Prepare view data
        $view_data = [
            'service' => $service,
            'customer' => $customer,
            'propertyImages' => $propertyImages
        ];
    
        // Load view with data
        $this->view('serviceProvider/externalServiceSummary', $view_data);
    }

    private function logout(){
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }


    // Display application form for a service

    public function applyForService($service_id = null) {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
        
        if (!$service_id) {
            $_SESSION['flash']['msg'] = "No service selected";
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/repairListing');
            return;
        }
        
        // Get service details
        $services = new Services();
        $service = $services->getServiceById($service_id);
        
        if (!$service) {
            $_SESSION['flash']['msg'] = "Service not found";
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/repairListing');
            return;
        }
        
        // Check if already applied
        $serviceApplication = new ServiceApplication();
        $existingApplication = $serviceApplication->first([
            'service_id' => $service_id,
            'service_provider_id' => $_SESSION['user']->pid
        ]);
        
        if ($existingApplication) {
            // Redirect to check status page if already applied
            redirect('serviceprovider/checkApplicationStatus/' . $service_id);
            return;
        }
        
        $this->view('serviceProvider/serviceApplication', [
            'service' => $service
        ]);
    }

    // Process service application

    public function submitServiceApplication() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('serviceprovider/repairListing');
            return;
        }
        
        $service_id = $_POST['service_id'] ?? null;
        
        if (!$service_id) {
            $_SESSION['flash']['msg'] = "Service ID is required";
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/repairListing');
            return;
        }
        
        // Enhanced proof document validation
        if (!isset($_FILES['proof_document']) || 
            $_FILES['proof_document']['error'] === UPLOAD_ERR_NO_FILE || 
            $_FILES['proof_document']['size'] == 0) {
            
            $_SESSION['flash']['msg'] = "Proof document is required. Please upload a valid file.";
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/applyForService/' . $service_id);
            return;
        }
        
        // Handle file upload
        $uploadError = null;
        $filePath = null;
        
        if ($_FILES['proof_document']['error'] === 0) {
            $uploadDir = 'uploads/service_applications/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = uniqid('proof_') . '_' . basename($_FILES['proof_document']['name']);
            $filePath = $uploadDir . $fileName;
            
            $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            
            // Validate file type
            if (!in_array($fileType, ['pdf', 'jpg', 'jpeg', 'png'])) {
                $uploadError = "Only PDF, JPG, JPEG, and PNG files are allowed.";
            } else if ($_FILES['proof_document']['size'] > 5000000) { // 5MB limit
                $uploadError = "File size cannot exceed 5MB.";
            } else if (!move_uploaded_file($_FILES['proof_document']['tmp_name'], $filePath)) {
                $uploadError = "Failed to upload file.";
            }
        } else {
            $uploadError = "Error uploading file. Please try again.";
        }
        
        if ($uploadError) {
            $_SESSION['flash']['msg'] = $uploadError;
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/applyForService/' . $service_id);
            return;
        }
        
        // Save application
        $serviceApplication = new ServiceApplication();
        $data = [
            'service_id' => $service_id,
            'service_provider_id' => $_SESSION['user']->pid,
            'proof' => $filePath,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($serviceApplication->validate($data) && $serviceApplication->insert($data)) {
            $_SESSION['flash']['msg'] = "Your application has been submitted successfully.";
            $_SESSION['flash']['type'] = "success";
            redirect('serviceprovider/checkApplicationStatus/' . $service_id);
        } else {
            $_SESSION['flash']['msg'] = "Failed to submit application. Please try again.";
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/applyForService/' . $service_id);
        }
    }

    // Check application status

    public function checkApplicationStatus($service_id = null) {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
        
        if (!$service_id) {
            $_SESSION['flash']['msg'] = "No service selected";
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/repairListing');
            return;
        }
        
        // Get service details
        $services = new Services();
        $service = $services->getServiceById($service_id);
        
        if (!$service) {
            $_SESSION['flash']['msg'] = "Service not found";
            $_SESSION['flash']['type'] = "error";
            redirect('serviceprovider/repairListing');
            return;
        }
        
        // Get application details
        $serviceApplication = new ServiceApplication();
        $application = $serviceApplication->first([
            'service_id' => $service_id,
            'service_provider_id' => $_SESSION['user']->pid
        ]);
        
        if (!$application) {
            // No application found, redirect to apply page
            redirect('serviceprovider/applyForService/' . $service_id);
            return;
        }
        
        $this->view('serviceProvider/applicationStatus', [
            'service' => $service,
            'application' => $application
        ]);
    }
}
