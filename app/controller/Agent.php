<?php
defined('ROOTPATH') or exit('Access denied');

class Agent
{
    use controller;

    public function index()
    {
        $this->dashboard();
    }

    /*public function dashboard()
    {
        $serviceLog = new ServiceLog();
        $allServices = $serviceLog->findAll();
        $limitedServices = array_slice($allServices, 0, 4); // Get first 4 services

        $services = new Services();
        $tasks = $services->selecttwotables(
            $serviceLog->table,
            'service_id',
            'services_id',
            []
        );

        $this->view('agent/dashboard', [
            'services' => $limitedServices,
            'tasks' => $tasks,
        ]);
    }*/

    public function dashboard()
    {
        // Fetch total properties managed by the agent
        $propertyModel = new Property();
        $totalProperties = $propertyModel->getTotalCountWhere(['agent_id' => $_SESSION['user']->pid]);

        // Fetch total service providers
        $userModel = new User();
        $totalServiceProviders = $userModel->getTotalCountWhere(['user_lvl' => 2]);

        // Fetch total repair requests
       /* $repairModel = new ProblemReport();
        $totalRepairRequests = $repairModel->getTotalCountWhere(['agent_id' => $_SESSION['user']->pid]);*/

        // Fetch total bookings
        $bookingModel = new BookingOrders();
        $totalBookings = $bookingModel->getTotalCountWhere(['person_id' => $_SESSION['user']->pid]);

        $serviceLog = new ServiceLog();
        $allServices = $serviceLog->findAll();
        $limitedServices = array_slice($allServices, 0, 4); // Get first 4 services
        $newservices = $serviceLog->getTotalCountWhere(['status' => 'pending']);

        $services = new Services();
        $tasks = $services->selecttwotables(
            $serviceLog->table,
            'service_id',
            'services_id',
            []
        );

        // Pass data to the view
        $this->view('agent/dashboard', [
            'totalProperties' => $totalProperties,
            'totalServiceProviders' => $totalServiceProviders,
            'totalBookings' => $totalBookings,
            'services' => $limitedServices,
            'tasks' => $tasks,
            'totalRepairRequests' => $newservices,
        ]);
    }

    public function profile()
    {
        $user = new User();

        // notifications
        if ($_SESSION['user']->AccountStatus == 3) { // reject update
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            // set message
            $_SESSION['flash']['msg'] = "Your account update has been rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == 4) { // Approved update
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Your account has been accepted.";
            $_SESSION['flash']['type'] = "success";
        } elseif ($_SESSION['user']->AccountStatus == -3) { // Reject delete
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Account removal was Rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == -4) { // Approve delete
            // update data
            $updateAcc = $user->update($_SESSION['user']->pid, [
                'AccountStatus' => 1
            ], 'pid');
            // update session
            if ($updateAcc) {
                $_SESSION['user']->AccountStatus = 1;
            }
            $_SESSION['flash']['msg'] = "Account removal was Rejected.";
            $_SESSION['flash']['type'] = "error";
        } elseif ($_SESSION['user']->AccountStatus == 2) { // update pending

            $_SESSION['flash']['msg'] = "Account update request is pending.";
            $_SESSION['flash']['type'] = "warning";
        } elseif ($_SESSION['user']->AccountStatus == -2) { // Delete pending

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

    public function expenses()
    {
        echo "User Expenses Section";
    }

    public function requestedTasks()
    {
        // Get pending services from service log
        $service = new ServiceLog();
        $services = $service->where(['status' => 'Pending']);
    
        // Get available service providers
        $user = new User();
        $service_providers = $user->where(['user_lvl' => 2]);
        
        // Get all services from services table for name matching
        $services_model = new Services();
        $all_services = $services_model->getAllServices();
        
        // Create mapping from service type name to service_id
        $service_type_to_id = [];
        if ($all_services) {
            foreach ($all_services as $service_item) {
                $service_type_to_id[strtolower($service_item->name)] = $service_item->service_id;
            }
        }
        
        // Get approved service provider applications
        $serviceApplication = new ServiceApplication();
        $approved_applications = $serviceApplication->where(['status' => 'Approved']);
        
        // Create mapping from service_id to array of approved provider IDs
        $approved_providers_by_service = [];
        if ($approved_applications) {
            foreach ($approved_applications as $application) {
                $service_id = $application->service_id;
                $provider_id = $application->service_provider_id;
                
                if (!isset($approved_providers_by_service[$service_id])) {
                    $approved_providers_by_service[$service_id] = [];
                }
                
                $approved_providers_by_service[$service_id][] = $provider_id;
            }
        }
        
        // Process services and create filtered provider lists
        $data = [
            'services' => $services,
            'providers_by_service' => [],
            'service_providers' => $service_providers // Keep for backward compatibility
        ];
        
        if ($services) {
            foreach ($services as $service_item) {
                // Process property images
                if (!empty($service_item->property_id)) {
                    $property = new Property();
                    $propertyData = $property->first(['property_id' => $service_item->property_id]);
                    
                    if ($propertyData) {
                        $propertyImage = new PropertyImageModel();
                        $images = $propertyImage->where(['property_id' => $service_item->property_id]);
                        
                        if ($images && is_array($images) && !empty($images) && !empty($images[0]->image_url)) {
                            $service_item->property_image = $images[0]->image_url;
                        } else {
                            $service_item->property_image = 'listing_alt.jpg';
                        }
                    } else {
                        $service_item->property_image = 'listing_alt.jpg';
                    }
                } else {
                    $service_item->property_image = 'listing_alt.jpg';
                }
                
                // Match service_type from serviceLog with service names in services table
                $matched_service_id = null;
                if (!empty($service_item->service_type)) {
                    // Try direct match first
                    $service_type_lower = strtolower($service_item->service_type);
                    if (isset($service_type_to_id[$service_type_lower])) {
                        $matched_service_id = $service_type_to_id[$service_type_lower];
                    } else {
                        // Try partial match
                        foreach ($service_type_to_id as $name => $id) {
                            if (strpos($service_type_lower, $name) !== false || 
                                strpos($name, $service_type_lower) !== false) {
                                $matched_service_id = $id;
                                break;
                            }
                        }
                    }
                }
                
                // Get approved providers for this service type
                $approved_provider_ids = [];
                if ($matched_service_id && isset($approved_providers_by_service[$matched_service_id])) {
                    $approved_provider_ids = $approved_providers_by_service[$matched_service_id];
                }
                
                // Filter providers by approval status and task count
                $filtered_providers = [];
                foreach ($service_providers as $provider) {
                    if (in_array($provider->pid, $approved_provider_ids)) {
                        $provider->image_url = $provider->image_url ?? 'Agent.png';
                        
                        // Count ongoing services for this provider
                        $ongoing_count = $service->where([
                            'service_provider_id' => $provider->pid,
                            'status' => 'Ongoing'
                        ]);
                        
                        // Convert ongoing_count to array if false (no services)
                        $ongoing_count = $ongoing_count === false ? [] : $ongoing_count;
                        
                        // Add provider if they have less than or equal to 4 ongoing services
                        if (count($ongoing_count) <= 4) {
                            $filtered_providers[] = $provider;
                        }
                    }
                }
                
                // Store filtered providers for this service
                $data['providers_by_service'][$service_item->service_id] = $filtered_providers;
            }
        }
    
        // Handle service provider assignment when accept button is pressed
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service_id'])) {
            $service_id = $_POST['service_id'];
            
            // Get the selected service provider from the dropdown
            $provider_id = $_POST['service_provider_select'] ?? null;
    
            if (empty($provider_id)) {
                // New validation: Check if a provider is selected
                $_SESSION['flash'] = [
                    'msg' => "Please select a service provider before accepting the task",
                    'type' => "error"
                ];
            } else {
                // Create a new ServiceLog instance to ensure it's a model with where() method
                $serviceModel = new ServiceLog();
                
                // Check again if provider hasn't exceeded limit
                $ongoing_services = $serviceModel->where([
                    'service_provider_id' => $provider_id,
                    'status' => 'Ongoing'
                ]);
    
                // Convert to empty array if false
                $ongoing_services = $ongoing_services === false ? [] : $ongoing_services;
    
                if (count($ongoing_services) >= 4) {
                    $_SESSION['flash'] = [
                        'msg' => "This service provider has reached their maximum service limit",
                        'type' => "error"
                    ];
                } else {
                    // Update service with assigned provider and change status to Ongoing
                    $result = $serviceModel->update($service_id, [
                        'service_provider_id' => $provider_id,
                        'status' => 'Ongoing'
                    ], 'service_id');
    
                    if ($result) {
                        // Get service details to include in notification
                        $serviceDetails = $serviceModel->first(['service_id' => $service_id]);
                        
                        // Create notification for the service provider
                        $notificationModel = new NotificationModel();
                        $notificationData = [
                            'user_id' => $provider_id,
                            'title' => "New Task Assignment",
                            'message' => "You have been assigned a new " . 
                                        ($serviceDetails->service_type ?? "maintenance") . 
                                        " task for property " . 
                                        ($serviceDetails->property_name ?? "ID: " . $serviceDetails->property_id),
                            'link' => "/php_mvc_backend/public/dashboard/repairRequests",
                            'color' => 'Notification_green', 
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        // Insert notification for service provider
                        $notificationModel->insert($notificationData);
    
                        // Create notification for property owner
                        if (!empty($serviceDetails->property_id)) {
                            // Get property owner ID from property table
                            $propertyModel = new Property();
                            $propertyDetails = $propertyModel->first(['property_id' => $serviceDetails->property_id]);
                            
                            if (!empty($propertyDetails->person_id)) {
                                $notificationModel = new NotificationModel();
                                $ownerNotificationData = [
                                    'user_id' => $propertyDetails->person_id, // Use person_id from property table
                                    'title' => "Service Request Accepted",
                                    'message' => "Your " . ($serviceDetails->service_type ?? "maintenance") . 
                                                " service request has been accepted and assigned to a service provider.",
                                    'link' => "/php_mvc_backend/public/dashboard/maintenance",
                                    'color' => 'Notification_green',
                                    'is_read' => 0,
                                    'created_at' => date('Y-m-d H:i:s')
                                ];
                                
                                // Insert notification for property owner
                                $notificationModel->insert($ownerNotificationData);
                            }
                        }
                        
                        $_SESSION['flash'] = [
                            'msg' => "Service request accepted and assigned successfully",
                            'type' => "success"
                        ];
                    } else {
                        $_SESSION['flash'] = [
                            'msg' => "Failed to update service request",
                            'type' => "error"
                        ];
                    }
                }
            }
            
            redirect('dashboard/requestedTasks');
            exit; // Prevent further execution
        }
    
        // Handle service request deletion when decline button is pressed
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_service_id'])) {
            $service_id = $_POST['delete_service_id'];
    
            // Update the service request status to "Rejected" instead of deleting
            $result = $service->update($service_id, [
                'status' => 'Rejected'
            ], 'service_id');
    
            if ($result) {
                // Get service details to include in notification
                $serviceDetails = $service->first(['service_id' => $service_id]);
                
                // Create notification for property owner about rejection
                if (!empty($serviceDetails->property_id)) {
                    // Get property owner ID from property table
                    $propertyModel = new Property();
                    $propertyDetails = $propertyModel->first(['property_id' => $serviceDetails->property_id]);
                    
                    if (!empty($propertyDetails->person_id)) {
                        $notificationModel = new NotificationModel();
                        $ownerNotificationData = [
                            'user_id' => $propertyDetails->person_id, // Use person_id from property table
                            'title' => "Service Request Declined",
                            'message' => "Your " . ($serviceDetails->service_type ?? "maintenance") . 
                                        " service request has been declined. Please contact support for more information.",
                            'link' => "/php_mvc_backend/public/dashboard/maintenance",
                            'color' => 'Notification_red',
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        // Insert notification for property owner
                        $notificationModel->insert($ownerNotificationData);
                    }
                }
                
                $_SESSION['flash'] = [
                    'msg' => "Service request declined successfully",
                    'type' => "success"
                ];
            } else {
                $_SESSION['flash'] = [
                    'msg' => "Failed to decline service request",
                    'type' => "error"
                ];
            }
    
            redirect('dashboard/requestedTasks');
            exit; 
        }
    
        $this->view('agent/requestedTasks', $data);
    }

    public function tasks($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'newtask':
                $this->newTask($c,$d);
                break;
            case 'delete':
                $service_id = (int)$c;
                $service = new ServiceLog;
                $service->delete($service_id, 'service_id');
                redirect('/dashboard/tasks');
                break;
            case 'edittasks':
                $this->editTasks($c, $d);
                break;
            default:
                $service = new ServiceLog;
                $services = new Services;
                $tasks = $services->selecttwotables(
                    $service->table,
                    'service_id',
                    'services_id',
                );
                $this->view('agent/tasks', ['tasks' => $tasks]);
                break;
        }
    }

    public function newTask($c,$d)
    {
        $property = new agentAssignment;
        $pro = new Property();
        $properties = $property ->selecttwotables($pro->table,
                                                'property_id',
                                                'property_id',);
        $service = new Services();
        $services = $service->findAll();
        $this->view('agent/newtask', ['properties' => $properties,'services' => $services]);
    }

    public function editTasks($c, $d)
    {
        $service_id = $c;
        $task = new ServiceLog;
        $tasks = $task->where(['service_id' => $service_id])[0];
        $this->view('agent/edittasks', ['tasks' => $tasks]);
    }

    public function taskRemoval()
    {

        $this->view('agent/taskremoval');
    }

    public function services($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'editservices':
                $this->editservices($c);
                break;
            case 'delete':
                $service_id = (int)$c;
                $service = new Services;
                $service->delete($service_id, 'service_id');
                redirect('/dashboard/services');
                break;
            case 'addnewservice':
                $this->addnewservice();
                break;
            default:
                $service = new Services;
                $services = $service->findAll();
                $this->view('agent/services', ['services' => $services]);
                break;
        }
    }

    public function editservices($c)
    {
        $service_id = $c;
        $service = new Services;
        $service1 = $service->where(['service_id' => $service_id])[0];
        $this->view('agent/editservices', ['service1' => $service1]);
    }

    public function addnewservice()
    {
        $this->view('agent/addnewservice');
    }

    public function preInspection($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'preinspectionupdate':
                $this->view('agent/preinspectionupdate');
                break;
            case 'inspectiondetails':
                $this->inspectiondetails($property_id = $c);
                return;
            case 'showReport':
                $this->showReport($property_id = $c);
                return;
            case 'submitReport':
                $this->submitReport($property_id = $c);
                return;
            case 'viewProperty':
                $this->viewProperty($property_id = $c);
                return;
            case 'submitPreInspection':
                $this->submitPreInspection($property_id = $c);
                return;
            default:
                $preinspection = new PropertyConcat;
                $inspection = $preinspection->where(['status' => 'Pending', 'agent_id' => $_SESSION['user']->pid]);
                $this->view('agent/preInspection', ['preinspection' => $inspection]);
                break;
        }
    }

    public function submitPreInspection($propertyID)
    {
        $preInspection = new PreInspection;
        $propertyModel = new Property;
        $property = $propertyModel->where(['property_id' => $propertyID])[0] ?? null;

        //show($property);

        if ($property) {
            $data = [
                'agent_id' => $_SESSION['user']->pid,
                'property_id' => $propertyID,
                'provided_details' => $_POST['provided_details'],
                'title_deed' => $_POST['title_deed'],
                'utility_bills' => $_POST['utility_bills'],
                'owner_id_copy' => $_POST['owner_id_copy'],
                'lease_agreement' => $_POST['lease_agreement'],
                'property_condition' => $_POST['property_condition'],
                'Maintenance_issues' => $_POST['Maintenance_issues'],
                'owner_present' => $_POST['owner_present'],
                'notes' => $_POST['notes'],
                'recommendation' => $_POST['recommendation']
            ];
            $res = $preInspection->insert($data);

            if (isset($_FILES['property_document']) && $_FILES['property_document']['error'] == 0) {
                $uploadDir = 'uploads/preinspections/';
                $fileName = uniqid() . '_' . basename($_FILES['property_document']['name']);
                $uploadFile = $uploadDir . $fileName;
            
                // Make sure directory exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
            
                if (move_uploaded_file($_FILES['property_document']['tmp_name'], $uploadFile)) {
                    $data['document_path'] = $uploadFile;
                } else {
                    $_SESSION['flash'] = [
                        'msg' => "Document upload failed.",
                        'type' => "error"
                    ];
                    redirect('dashboard/preInspection');
                    return;
                }
            }
            
            // show($data);
            // echo $preInspection->isValidForRegister($data);
            if ($res) {
                if ($property->agent_id == $_SESSION['user']->pid && $preInspection->isValidForRegister($data)) {
                    $isActive = $propertyModel->update($propertyID, ['status' => 'Active'], 'property_id');
                    $message_to_owner = $preInspection->messages['recommendation'] ?? null;
                    if(!$message_to_owner){
                        $message_to_owner = 'And No recommendation provided.';
                    }
                    if ($isActive) {
                        $_SESSION['flash'] = [
                            'msg' => "Pre-Inspection submitted successfully!",
                            'type' => "success"
                        ];
                        enqueueNotification('Pre-Inspection submitted', 'Open to Rent your Property ID : ' . $propertyID . $message_to_owner , ROOT . '/dashboard/propertylisting/propertyunitowner/' . $propertyID, 'Notification_green', $property->person_id);
                        enqueueNotification('Pre-Inspection submitted', 'Pre-Inspection has been submitted on Property ID : ' . $propertyID, ROOT . '/dashboard/property/propertyView/' . $propertyID, 'Notification_green');
                    } else {
                        $_SESSION['flash'] = [
                            'msg' => "Failed to update property status. Please try again.",
                            'type' => "error"
                        ];
                        enqueueNotification('Failed to update property status', 'Failed to update property status on Property ID : ' . $propertyID, '', 'Notification_red');
                    }
                } else{
                    $message = $preInspection->getValidationMessages();
                    $message_to_owner = $preInspection->messages['recommendation'] ?? null;
                    $_SESSION['flash'] = [
                        'msg' => $message,
                        'type' => "error"
                    ];
                    enqueueNotification('Pre-Inspection validation failed', 'Pre-Inspection validation failed on Property ID : ' . $propertyID . '. ' . $message_to_owner , '', 'Notification_red' , $property->person_id);
                    enqueueNotification('Pre-Inspection validation failed', $message , '', 'Notification_orange' , $property->person_id);
                }
            } else {
                $_SESSION['flash'] = [
                    'msg' => "Failed to submit Pre-Inspection. Please try again.",
                    'type' => "error"
                ];
                enqueueNotification('Failed to submit Pre-Inspection', 'Failed to submit Pre-Inspection on Property ID : ' . $propertyID, '', 'Notification_red');
            }
        } else {
            $_SESSION['flash'] = [
                'msg' => "Property not found.",
                'type' => "error"
            ];
            enqueueNotification('Property not found', 'Property not found on Property ID : ' . $propertyID, '', 'Notification_red');
        }
        redirect('dashboard/preInspection');
    }

    public function property($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'changeRequests':
                $this->ChangeRequests($c);
                break;
            case 'propertyView':
                $this->propertyView($c);
                break;
            case 'showMyProperties':
                $this->showMyProperties();
                break;
            case 'removalRequests':
                $this->removalRequests($c);
                break;
            case 'comparePropertyUpdate':
                $this->comparePropertyUpdate($c);
                break;
            case 'confirmUpdate':
                $this->confirmUpdate($c);
                break;
            case 'rejectUpdate':
                $this->rejectUpdate($c);
                break;
            case 'confirmDeletion':
                $this->confirmDeletion($c);
                break;
            case 'rejectDeletion':
                $this->rejectDeletion($c);
                break;
            default:
                $this->view('agent/property/propertyHome');
                break;
        }
    }

    public function confirmDeletion($propertyID)
    {
        $property = new DeleteRequests;
        $isSuccess = $property->update($propertyID, ['request_status' => 'Approved'], 'property_id');
        if (!$isSuccess) {
            $_SESSION['flash'] = [
                'msg' => "Failed to approve property request. Please try again.",
                'type' => "error"
            ];
            enqueueNotification('Failed to Approve removal request', 'Property Removal request has been failed to Approve on Property ID : ' . $propertyID, '', 'Notification_grey');
            redirect('dashboard/property/removalRequests');
            return;
        }

        $originalRow = new Property;
        $originalRow = $originalRow->update($propertyID, ['status' => 'Inactive'], 'property_id');

        if (!$originalRow) {
            $_SESSION['flash'] = [
                'msg' => "Failed to approve property request. Please try again.",
                'type' => "error"
            ];
            enqueueNotification('Failed to Approve removal request', 'Property Removal request has been failed to Approve on Property ID : ' . $propertyID, '', 'Notification_grey');
            redirect('dashboard/property/removalRequests');
            return;
        }

        $OriginalProperty = new Property;
        $OriginalProperty = $OriginalProperty->first(['property_id' => $propertyID]);
        $ownerID = $OriginalProperty->person_id;
        $propertyName = $OriginalProperty->name;
        $_SESSION['flash'] = [
            'msg' => "Property Removal request approved successfully!",
            'type' => "success"
        ];
        $property->delete($propertyID, 'property_id');
        enqueueNotification('Property Removel request approved', 'Property Removal request has been approved on ' . $propertyID . ' ' . $propertyName, '', 'Notification_green', $ownerID);
        enqueueNotification('Property Removel request approved', 'Property Removal request has been approved on Property ID : ' . $propertyID, '', 'Notification_grey');
        redirect('dashboard/property/removalRequests');
    }

    public function rejectDeletion($property_id)
    {
        $property = new DeleteRequests;
        $isSuccess = $property->update($property_id, ['request_status' => 'Decline'], 'property_id');
        if (!$isSuccess) {
            $_SESSION['flash'] = [
                'msg' => "Failed to decline property request. Please try again.",
                'type' => "error"
            ];
            enqueueNotification('Property request rejected', 'Property Removal request has been rejected on Property ID : ' . $property_id, '', 'Notification_grey');
            redirect('dashboard/property/removalRequests');
            return;
        }
        $originalRow = new Property;
        $ownerID = $originalRow->first(['property_id' => $property_id])->person_id;
        $propertyName = $originalRow->first(['property_id' => $property_id])->name;
        $_SESSION['flash'] = [
            'msg' => "Property request rejected successfully!",
            'type' => "warning"
        ];
        $property->delete($property_id, 'property_id');
        enqueueNotification('Property request rejected', 'Property Removal request has been rejected on ' . $property_id . ' ' . $propertyName, ROOT . '/dashboard/propertylisting/propertyunitowner/' . $property_id, 'Notification_red', $ownerID);
        enqueueNotification('Property request rejected', 'Property Removal request has been rejected on Property ID : ' . $property_id, '', 'Notification_grey');
        redirect('dashboard/property/removalRequests');
    }


    public function confirmUpdate($propertyID)
    {
        $updatedProperty = new PropertyModelTemp;
        $currentProperty = new Property;

        $property = $updatedProperty->first(['property_id' => $propertyID]);
        if ($property->request_status == 'pending') {
            $data = [
                'name' => $property->name,
                'type' => $property->type,
                'description' => $property->description,
                'address' => $property->address,
                'zipcode' => $property->zipcode,
                'city' => $property->city,
                'state_province' => $property->state_province,
                'country' => $property->country,
                'year_built' => $property->year_built,
                'size_sqr_ft' => $property->size_sqr_ft,
                'number_of_floors' => $property->number_of_floors,
                'floor_plan' => $property->floor_plan,
                'units' => $property->units,
                'bedrooms' => $property->bedrooms,
                'bathrooms' => $property->bathrooms,
                'kitchen' => $property->kitchen,
                'living_room' => $property->living_room,
                'furnished' => $property->furnished,
                'furniture_description' => $property->furniture_description,
                'parking' => $property->parking,
                'parking_slots' => $property->parking_slots,
                'type_of_parking' => $property->type_of_parking,
                'utilities_included' => $property->utilities_included,
                'additional_utilities' => $property->additional_utilities,
                'additional_amenities' => $property->additional_amenities,
                'security_features' => $property->security_features,
                'purpose' => $property->purpose,
                'rental_period' => $property->rental_period,
                'rental_price' => $property->rental_price,
                'owner_name' => $property->owner_name,
                'owner_email' => $property->owner_email,
                'owner_phone' => $property->owner_phone,
                'additional_contact' => $property->additional_contact,
                'special_instructions' => $property->special_instructions,
                'legal_details' => $property->legal_details,
                'status' => $property->status,
                'person_id' => $property->person_id,
                'agent_id' => $property->agent_id,
                'duration' => $property->duration,
                'start_date' => $property->start_date,
                'end_date' => $property->end_date
            ];

            $res = $currentProperty->update($propertyID, $data, 'property_id');
            if ($res) {
                $updatedProperty->update($propertyID, ['request_status' => 'accept'], 'property_id');
                $ownerID = $updatedProperty->first(['property_id' => $propertyID])->person_id;
                $propertyName = $updatedProperty->first(['property_id' => $propertyID])->name;
                $_SESSION['flash'] = [
                    'msg' => "Property request approved successfully!",
                    'type' => "success"
                ];
                enqueueNotification('Property request approved', 'Property Update request has been approved on ' . $propertyID . ' ' . $propertyName, '', 'Notification_green', $ownerID);
                enqueueNotification('Property request approved', 'Property Update request has been approved on Property ID : ' . $propertyID, '', 'Notification_grey');
                $updatedProperty->delete($propertyID, 'property_id');
                redirect('dashboard/managementhome/propertymanagement/requestapproval');
            } else {
                $_SESSION['flash'] = [
                    'msg' => "Failed to approve property request. Please try again.",
                    'type' => "error"
                ];
                redirect('dashboard/managementhome/propertymanagement/requestapproval');
            }
        } else {
            $_SESSION['flash'] = [
                'msg' => "Property request already approved.",
                'type' => "warning"
            ];
            redirect('dashboard/managementhome/propertymanagement/requestapproval');
        }
    }

    public function rejectUpdate($propertyID)
    {
        $updatedProperty = new PropertyModelTemp;
        $res = $updatedProperty->update($propertyID, ['request_status' => 'decline'], 'property_id');
        if ($res) {
            $ownerID = $updatedProperty->first(['property_id' => $propertyID])->person_id;
            $propertyName = $updatedProperty->first(['property_id' => $propertyID])->name;
            $_SESSION['flash'] = [
                'msg' => "Property request rejected successfully!",
                'type' => "warning"
            ];
            enqueueNotification('Property request rejected', 'Property Update request has been rejected on ' . $propertyID . ' ' . $propertyName, '', 'Notification_red', $ownerID);
            enqueueNotification('Property request rejected', 'Property Update request has been rejected on Property ID : ' . $propertyID, '', 'Notification_grey');

            $updatedProperty->delete($propertyID, 'property_id');
            redirect('dashboard/managementhome/propertymanagement/requestapproval');
        }
    }


    public function comparePropertyUpdate($propertyID)
    {
        $property = new PropertyConcat;
        $propertyUpdate = new PropertyConcatTemp;
        $property = $property->first(['property_id' => $propertyID]);
        $propertyUpdate = $propertyUpdate->first(['property_id' => $propertyID]);
        $this->view('agent/property/comparePropertyUpdate', ['property' => $property, 'propertyUpdate' => $propertyUpdate]);
    }

    public function removalRequests()
    {
        $propertyToDelete = new DeleteRequests;
        $property = $propertyToDelete->where(['agent_id' => $_SESSION['user']->pid]);
        $this->view('agent/property/removalRequests', ['properties' => $property]);
    }

    public function propertyView($property_id)
    {
        $property = new PropertyConcat;
        $property = $property->where(['property_id' => $property_id])[0];
        $this->view('agent/property/propertyView', ['property' => $property]);
    }

    public function showMyProperties()
    {
        $property = new PropertyConcat;
        $properties = $property->where(['agent_id' => $_SESSION['user']->pid] , ['status' => 'Inactive']);
        $this->view('agent/property/showMyProperties', ['properties' => $properties]);
    }

    public function ChangeRequests($property_id)
    {
        $property = new PropertyModelTemp;
        $requests = $property->where(['request_status' => 'pending', 'agent_id' => $_SESSION['user']->pid]);
        $this->view('agent/property/changeRequests', ['requests' => $requests]);
    }

    public function viewProperty($property_id)
    {
        $property = new PropertyConcat;
        $property = $property->where(['property_id' => $property_id])[0];
        $this->view('agent/viewProperty', ['property' => $property]);
    }

    public function submitReport($property_id)
    {
        $property = new Property;
        $property = $property->where(['property_id' => $property_id])[0];
        $this->view('agent/submitReport', ['property' => $property]);
    }

    public function showReport($property_id)
    {
        $property = new Property;
        $agent = new User;
        $property = $property->where(['property_id' => $property_id])[0];
        $agent = $agent->where(['pid' => $_SESSION['user']->pid])[0];

        $this->view('agent/showReport', [
            'property' => $property,
            'agent' => $agent
        ]);
    }

    public function inspectiondetails($property_id)
    {
        $property = new PropertyConcat;
        $property = $property->where(['property_id' => $property_id, 'agent_id' => $_SESSION['user']->pid])[0];
        // show($property);
        $this->view('agent/inspectiondetails', ['property' => $property]);
    }

    private function propertyUnit($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);

        $this->view('agent/propertyUnit', ['property' => $propertyUnit ]);
    }

    public function ContactSupport($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'propertyunit':
                $this->propertyUnit($c);
                break;
            default:

                $property = new Property;
                $problemReportView = new ProblemReportView();
                $problemReport = new ProblemReport();
                $problemReportImage = new problemReportImage;
                
                // Handle status update POST
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_id'], $_POST['new_status'])) {
                    $report_id = (int)$_POST['report_id'];
                    $new_status = $_POST['new_status'];
                    // Only allow valid statuses
                    $allowed = ['pending', 'in_progress', 'resolved'];
                    if (in_array($new_status, $allowed)) {
                        $problemReport->update($report_id, ['status' => $new_status], 'report_id');
                        $_SESSION['flash']['msg'] = "Status updated!";
                        $_SESSION['flash']['type'] = "success";
                    } else {
                        $_SESSION['flash']['msg'] = "Invalid status.";
                        $_SESSION['flash']['type'] = "error";
                    }
                    redirect('dashboard/contactsupport');
                    return;
                }

                $searchTerm = $_GET['search'] ?? '';
                $reports = [];

                if (!empty($searchTerm)) {
                    // Use search method if a search term is given
                    $results = $problemReport->search($searchTerm, $_SESSION['user']->pid);
                    // Parse/format the search results for the view
                    if (!empty($results)) {
                        foreach ($results as $report) {
                            // Optionally fetch property name if needed
                            $propertyDetails = $property->where(['property_id' => $report->property_id])[0] ?? null;
                            $propertyName = $propertyDetails ? $propertyDetails->name : 'Unknown Property';
                            
                            // Fetch images for this report using ProblemReportImage
                            $images = [];
                            $imageRows = $problemReportImage->getImagesByReport($report->report_id);
                            if ($imageRows && is_array($imageRows)) {
                                foreach ($imageRows as $imgRow) {
                                    if (!empty($imgRow->image_url)) {
                                        $images[] = $imgRow->image_url;
                                    }
                                }
                            }

                            $reports[] = [
                                'report_id' => $report->report_id,
                                'problem_description' => $report->problem_description,
                                'urgency_level' => $report->urgency_level,
                                'urgency_label' => $report->urgency_label ?? '',
                                'property_id' => $report->property_id,
                                'status' => $report->status,
                                'submitted_at' => $report->submitted_at,
                                'images' => $images, // You may fetch images if needed
                                'property_name' => $propertyName,
                            ];
                        }
                    }
                } else {
                    // Default: collect all reports for agent's properties
                    $propertyIds = $property->getPropertyIdsByAgent($_SESSION['user']->pid);
                    $allReports = [];
                    if (!empty($propertyIds)) {
                        foreach ($propertyIds as $propertyId) {
                            $reportsForProperty = $problemReportView->getReportsWithImages($propertyId);
                            $propertyDetails = $property->where(['property_id' => $propertyId])[0] ?? null;
                            $propertyName = $propertyDetails ? $propertyDetails->name : 'Unknown Property';
                            if (!empty($reportsForProperty)) {
                                foreach ($reportsForProperty as $report) {
                                    $report['property_name'] = $propertyName;
                                    $report['property_id'] = $propertyId;
                                    $allReports[] = $report;
                                }
                            }
                        }
                    }
                    $reports = $allReports;
                }

                $this->view('agent/contactSupport', [
                    'user' => $_SESSION['user'],
                    'errors' => $_SESSION['errors'] ?? [],
                    'status' => $_SESSION['status'] ?? '',
                    'reports' => $reports,
                ]);
                unset($_SESSION['errors']);
                unset($_SESSION['status']);
                break;
        }
    }


    public function inventory($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'newinventory':
                $this->newinventory();
                break;
            case 'editinventory':
                $this->editinventory($c);
                break;
            default:
            $invent = new InventoryModel;
            $inventories = $invent->findAll();
            $this->view('agent/inventory',['inventories' => $inventories]); 
                break;
        }
    }


    public function newinventory()
    {
        $property = new agentAssignment;
        $pro = new Property();
        $properties = $property ->selecttwotables($pro->table,
                                                'property_id',
                                                'property_id',);
        $this->view('agent/newinventory', ['properties' => $properties]);
    }

    public function editinventory($c)
    {
        $invent = new InventoryModel;
        $inventory = $invent->where(['inventory_id' => $c])[0];
        $this->view('agent/editinventory', ['inventory' => $inventory]);
    }

    public function manageBookings()
    {
        $this->view('agent/manageBookings');
    }

    public function problems()
    {
        $this->view('agent/problems');
    }

    public function manageProviders($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'serviceproviders':
                $this->serviceProviders($c, $d);
                break;
            case 'payments':
                $this->payments();
                break;
            case 'propertyowners':
                $this->propertyOwners($c, $d);
                break;
            case 'managetenents':
                $this->tenents($c, $d);
                break;
            default:
                $this->view('agent/manageProviders');
                break;
        }
    }

    public function tenents( $c = '', $d = '')
    {
        switch ($c) {
            case 'edittenents':
                $this->edittenents($c,$d);
                break;

            case 'addtenents':
                $this->view('agent/addtenents');
                break;
            default:
                $book = new BookingModel;
                $property = new Property;
                $properties = $property->findAll();
                $bookings = $book->findAll();
                $person1 = new User;
                $persons = $person1->findAll();
                /*echo "<pre>";
                print_r($images);
                echo "</pre>";*/
                $this->view('agent/tenents',['bookings'=> $bookings,'persons' => $persons, 'properties' => $properties]);
                break;
        }
    }

    public function edittenents( $c = '', $d = '')
    {
                
        $book = new BookingModel;
        $property = new Property;
        $person = new User; 
        $pid = $book->where(['booking_id' => $d],)[0];
        $image1 = new PropertyImageModel;
        $images = $image1->findAll(); 
        $bookings = $book->selecthreetables($property->table,
                                            'property_id', 
                                            'property_id', 
                                            $person->table,
                                            'customer_id', 
                                            'pid',
                                            'booking_id',
                                            $d,
                                            'AND',
                                            'customer_id',
                                            $pid->customer_id
                                            );
        //$this->view('agent/showhistory',['bookings'=> $bookings,'images' => $images]);
                /*echo "<pre>";
                print_r($images);
                echo "</pre>";*/
                $this->view('agent/edittenents',['bookings'=> $bookings ,'images' => $images]);
    }

    public function serviceProviders($c, $d)
    {
        switch ($c) {
            case 'addserviceprovider':
                $this->addServiceProvider();
                break;
            case 'removeproviders':
                $this->removeproviders();
                break;
            case 'approval':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changes'])) {
                    foreach ($_POST['changes'] as $change) {
                        $pid = $change['pid'] ?? null;
                        $action = $change['action'] ?? null;

                        if ($pid && $action) {
                            $userDetail = new UserChangeDetails;
                            $user = new User;
                            if ($action === 'approve') {
                                // Handle approval logic
                                $newDetails = $userDetail->first(['pid' => $pid]);
                                // show($newDetails);
                                if ($newDetails) {
                                    $oldUserDetails = $user->first(['pid' => $pid]);
                                    // Update database
                                    $updateStatus = $user->update($pid, [
                                        'fname' => $newDetails->fname,
                                        'lname' => $newDetails->lname,
                                        'email' => $newDetails->email,
                                        'contact' => $newDetails->contact,
                                        'image_url' => $newDetails->image_url,
                                        'AccountStatus' => 4
                                    ], 'pid');

                                    $result = $userDetail->delete($pid, 'pid');
                                    if ($updateStatus && $result) {
                                        $_SESSION['flash']['msg'] = "Changes approved successfully!";
                                        $_SESSION['flash']['type'] = "success";

                                        // Delete the old image in the user table
                                        if ($oldUserDetails && !empty($oldUserDetails->image_url)) {
                                            $profilePicturePath = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR . $oldUserDetails->image_url;
                                            if (file_exists($profilePicturePath)) {
                                                unlink($profilePicturePath);
                                            }
                                        }
                                    } else {
                                        $_SESSION['flash']['msg'] = "Failed to approve changes. Please try again.";
                                        $_SESSION['flash']['type'] = "error";
                                    }
                                }
                            } elseif ($action === 'reject') {
                                // Handle rejection logic
                                $newDetails = $userDetail->first(['pid' => $pid]);
                                $result = $userDetail->delete($pid, 'pid');
                                $user = $user->update($pid, ['AccountStatus' => 3], 'pid'); // Rejected

                                if ($result) {
                                    $_SESSION['flash']['msg'] = "Changes rejected successfully!";
                                    $_SESSION['flash']['type'] = "success";

                                    // Delete the image in the user details table
                                    if ($newDetails && !empty($newDetails->image_url)) {
                                        $profilePicturePath = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR . $newDetails->image_url;
                                        if (file_exists($profilePicturePath)) {
                                            unlink($profilePicturePath);
                                        }
                                    }
                                } else {
                                    $_SESSION['flash']['msg'] = "Failed to reject changes. Please try again.";
                                    $_SESSION['flash']['type'] = "error";
                                }
                            }
                        }
                    }
                    $this->view('agent/serviceproviders');
                }
                break;
            default:
                $user = new User;
                $newUser = new UserChangeDetails;

                $newUser->setLimit(7);

                $searchterm = $_GET['searchterm'] ?? "";
                $limit = $newUser->getLimit();
                $countWithTerms = $newUser->getTotalCountWhere([], [], $searchterm);

                $totalPages = ceil($countWithTerms / $limit);
                $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $offset = ($currentPage - 1) * $limit;

                $newUser->setOffset($offset);
                $new = $newUser->where([], [], $searchterm);

                $pids = [];
                $old = [];

                if (!empty($new)) {
                    $pids = array_map(function ($user) {
                        return $user->pid;
                    }, $new);

                    $old = $user->findByMultiplePids($pids, ['user_lvl' => 2]);

                    if (!empty($old)) {
                        $old = array_reduce($old, function ($carry, $oldUser) use ($pids) {
                            $carry[array_search($oldUser->pid, $pids)] = $oldUser;
                            return $carry;
                        }, []);
                        ksort($old);

                        foreach ($old as $oldUser) {
                            unset($oldUser->password);
                            unset($oldUser->nic);
                            unset($oldUser->user_lvl);
                            unset($oldUser->username);
                            unset($oldUser->created_date);
                            unset($oldUser->reset_code);
                            unset($oldUser->AccountStatus);
                        }
                    }
                }

                $pagination = new Pagination($currentPage, $totalPages, 2);
                $paginationLinks = $pagination->generateLinks();

                $this->view('agent/serviceproviders', [
                    'paginationLinks' => $paginationLinks,
                    'new' => $new ?? [],
                    'old' => $old ?? [],
                    'tot' => $totalPages
                ]);
                break;
        }
    }

    private function removeOwners($c, $d)
    {
        $user = new User();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pid'], $_POST['action'])) {
            $pid = intval($_POST['pid']);
            $action = $_POST['action'];

            if ($action === 'approve') {
                // Set active status to 0 and user level to 0
                $updateStatus = $user->update($pid, [
                    'AccountStatus' => -4,
                    'user_lvl' => 0
                ], 'pid');

                if ($updateStatus) {
                    $_SESSION['flash']['msg'] = "User removal successful!";
                    $_SESSION['flash']['type'] = "success";
                } else {
                    $_SESSION['flash']['msg'] = "Failed to approve user removal. Please try again.";
                    $_SESSION['flash']['type'] = "error";
                }
            } elseif ($action === 'reject') {
                // Set active status to 3
                $updateStatus = $user->update($pid, [
                    'AccountStatus' => -3,
                    'user_lvl' => 1
                ], 'pid');

                if ($updateStatus) {
                    $_SESSION['flash']['msg'] = "User removal rejected successfully!";
                    $_SESSION['flash']['type'] = "success";
                } else {
                    $_SESSION['flash']['msg'] = "Failed to reject user removal. Please try again.";
                    $_SESSION['flash']['type'] = "error";
                }
            }
        }

        $user->setLimit(7);

        $searchterm = $_GET['searchterm'] ?? "";
        $limit = $user->getLimit();
        $countWithTerms = $user->getTotalCountWhere(['AccountStatus' => -2, 'user_lvl' => 1], [], $searchterm);

        $totalPages = ceil($countWithTerms / $limit);
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($currentPage - 1) * $limit;

        $user->setOffset($offset);
        $users = $user->where(['AccountStatus' => -2, 'user_lvl' => 1], [], $searchterm);

        $pagination = new Pagination($currentPage, $totalPages, 2);
        $paginationLinks = $pagination->generateLinks();

        if (!empty($users)) {
            foreach ($users as $user) {
                unset($user->password); // Remove password from result
            }
        }
        // show($users);
        // die;
        $this->view('agent/removeowners', [
            'paginationLinks' => $paginationLinks,
            'users' => $users ?? [],
            'tot' => $totalPages
        ]);

        $this->view('agent/removeowners');
    }

    public function propertyOwners($c, $d)
    {
        switch ($c) {
            case 'removeowners':
                $this->removeOwners($c, $d);
                break;
            case 'approval':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changes'])) {
                    foreach ($_POST['changes'] as $change) {
                        $pid = $change['pid'] ?? null;
                        $action = $change['action'] ?? null;

                        if ($pid && $action) {
                            $userDetail = new UserChangeDetails;
                            $user = new User;
                            if ($action === 'approve') {
                                // Handle approval logic
                                $newDetails = $userDetail->first(['pid' => $pid]);
                                if ($newDetails) {
                                    $oldUserDetails = $user->first(['pid' => $pid]);
                                    // Update database
                                    $updateStatus = $user->update($pid, [
                                        'fname' => $newDetails->fname,
                                        'lname' => $newDetails->lname,
                                        'email' => $newDetails->email,
                                        'contact' => $newDetails->contact,
                                        'image_url' => $newDetails->image_url,
                                        'AccountStatus' => 4
                                    ], 'pid');

                                    $result = $userDetail->delete($pid, 'pid');
                                    if ($updateStatus && $result) {
                                        $_SESSION['flash']['msg'] = "Changes approved successfully!";
                                        $_SESSION['flash']['type'] = "success";

                                        // Delete the old image in the user table
                                        if ($oldUserDetails && !empty($oldUserDetails->image_url)) {
                                            $profilePicturePath = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR . $oldUserDetails->image_url;
                                            if (file_exists($profilePicturePath)) {
                                                unlink($profilePicturePath);
                                            }
                                        }
                                    } else {
                                        $_SESSION['flash']['msg'] = "Failed to approve changes. Please try again.";
                                        $_SESSION['flash']['type'] = "error";
                                    }
                                }
                            } elseif ($action === 'reject') {
                                // Handle rejection logic
                                $newDetails = $userDetail->first(['pid' => $pid]);
                                $result = $userDetail->delete($pid, 'pid');
                                $user = $user->update($pid, ['AccountStatus' => 3], 'pid'); // Rejected

                                if ($result) {
                                    $_SESSION['flash']['msg'] = "Changes rejected successfully!";
                                    $_SESSION['flash']['type'] = "success";

                                    // Delete the image in the user details table
                                    if ($newDetails && !empty($newDetails->image_url)) {
                                        $profilePicturePath = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR . $newDetails->image_url;
                                        if (file_exists($profilePicturePath)) {
                                            unlink($profilePicturePath);
                                        }
                                    }
                                } else {
                                    $_SESSION['flash']['msg'] = "Failed to reject changes. Please try again.";
                                    $_SESSION['flash']['type'] = "error";
                                }
                            }
                        }
                    }
                    $this->view('agent/propertyowners');
                }
                break;
            default:
                $user = new User;
                $newUser = new UserChangeDetails;

                $newUser->setLimit(7);

                $searchterm = $_GET['searchterm'] ?? "";
                $limit = $newUser->getLimit();
                $countWithTerms = $newUser->getTotalCountWhere([], [], $searchterm);

                $totalPages = ceil($countWithTerms / $limit);
                $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
                $offset = ($currentPage - 1) * $limit;

                $newUser->setOffset($offset);
                $new = $newUser->where([], [], $searchterm);

                $pids = [];
                $old = [];

                if (!empty($new)) {
                    $pids = array_map(function ($user) {
                        return $user->pid;
                    }, $new);

                    $old = $user->findByMultiplePids($pids, ['user_lvl' => 1]);

                    if (!empty($old)) {
                        $old = array_reduce($old, function ($carry, $oldUser) use ($pids) {
                            $carry[array_search($oldUser->pid, $pids)] = $oldUser;
                            return $carry;
                        }, []);
                        ksort($old);

                        foreach ($old as $oldUser) {
                            unset($oldUser->password);
                            unset($oldUser->nic);
                            unset($oldUser->user_lvl);
                            unset($oldUser->username);
                            unset($oldUser->created_date);
                            unset($oldUser->reset_code);
                            unset($oldUser->AccountStatus);
                        }
                    }
                }

                $pagination = new Pagination($currentPage, $totalPages, 2);
                $paginationLinks = $pagination->generateLinks();

                $this->view('agent/propertyowners', [
                    'paginationLinks' => $paginationLinks,
                    'new' => $new ?? [],
                    'old' => $old ?? [],
                    'tot' => $totalPages
                ]);
                break;
        }
    }

    public function payments()
    {
        $this->view('agent/payments');
    }

    private function addserviceprovider()
    {
        $user = new User();
        $payment_details = new PaymentDetails();
        $location = new UserLocation();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Validate and sanitize personal details
            $email = filter_var($_POST['email'] ?? null, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $_SESSION['flash']['msg'] = "Invalid email format.";
                $_SESSION['flash']['type'] = "error";
                // $errors['email'] = "Invalid email format.";
            }

            $contact = esc($_POST['contact'] ?? null);
            $fname = esc($_POST['fname'] ?? null);
            $lname = esc($_POST['lname'] ?? null);
            $nic = esc($_POST['nic'] ?? null);

            // echo "checking if user exist <br>";
            $resultUser = $user->first(['email' => $email], []);
            $nicUser = $user->first(['nic' => $nic], []);

            if (($resultUser && !empty($resultUser->email)) || $nicUser && !empty($nicUser->email)) {
                //update user class errors
                $_SESSION['flash']['msg'] = "Email or NIC already exists.";
                $_SESSION['flash']['type'] = "error";
                // $errors['email'] = 'Email or NIC already exists';
                $this->view('agent/addserviceprovider', [
                    'user' => $resultUser,
                    'errors' => $errors,
                    'message' => ''
                ]); // Re-render signup view with error

                unset($errors['email']); // Clear the error after displaying it
                return; // Exit if email exists
            }
            //generatepassword
            $password = bin2hex(random_bytes(4)); // Generates an 8-character password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $personalDetails = [
                'nic' => $nic,
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'contact' => $contact,
                'password' => $password, // Hash the password before saving
                'confirmPassword' => $password,
                'user_lvl' => 2,
                // 'username' => $this->generateUsername($_POST['fname']),
            ];
            // echo "validating user details<br>";

            // Validate the form data
            if (!$user->validate($personalDetails)) {
                // show($user->errors);
                // echo "if2";
                $_SESSION['flash']['msg'] = is_array($user->errors) ? implode("\n", $user->errors) : (string)$user->errors;
                $_SESSION['flash']['type'] = "error";
                $this->view('agent/addserviceprovider', [
                    'user' => $resultUser,
                    // 'errors' => $user->errors, 
                    'message' => ''
                ]); // Re-render signup view with errors

                unset($user->errors); // Clear the error after displaying it
                return; // Exit if validation fails
            }
            // echo "user details validated:<br>";

            unset($personalDetails['confirmPassword']);


            // Validate and sanitize location details
            if (!$location->validateLocation($_POST)) {
                $_SESSION['flash']['msg'] = is_array($location->errors) ? implode("\n", $location->errors) : (string)$location->errors;
                $_SESSION['flash']['type'] = "error";
                $this->view('agent/addserviceprovider', [
                    'user' => $resultUser,
                    'errors' => $errors,
                    'message' => ''
                ]); // Re-render signup view with errors

                unset($location->errors); // Clear the error after displaying it
                return; // Exit if validation fails
            }


            $personalDetails['password'] = $hashedPassword; //set hashed password
            // echo "inserting user details<br>";
            // show($personalDetails);

            $userStatus = $user->insert($personalDetails);

            if (!$userStatus) {
                // echo "user details insertion failed<br>"; 
                $_SESSION['flash']['msg'] = "Failed to add agent. Please try again.";
                $_SESSION['flash']['type'] = "error";
                // $errors['auth'] = "Failed to add agent. Please try again.";
                $this->view('agent/addserviceprovider', [
                    'user' => $resultUser,
                    'errors' => $errors,
                    'message' => ''
                ]);

                unset($errors['auth']); // Clear the error after displaying it
                return;
            } else {
                // echo "user details inserted<br>"; 

            }

            // Validate and sanitize bank details
            $cardName = esc($_POST['cardName'] ?? null);
            $accountNo = esc($_POST['accountNo'] ?? null);
            $branch = esc($_POST['branch'] ?? null);
            $bankName = esc($_POST['bankName'] ?? null);

            //location data
            $province = esc($_POST['province'] ?? null);
            $district = esc($_POST['district'] ?? null);
            $city = esc($_POST['city'] ?? null);
            $address = esc($_POST['address'] ?? null);

            // echo "Inside if bank details<br>";
            if (empty($user->errors) && $userStatus) {
                $userDetails = $user->where(['email' => $email]);
                $userId = $userDetails[0]->pid;
                // var_dump($userId);
                if ($userStatus) {

                    if ($userId) {
                        $locationStatus = $location->insert([
                            'pid' => $userId,
                            'province' => $province,
                            'district' => $district,
                            'city' => $city,
                            'address' => $address
                        ]);

                        if (!$locationStatus) {
                            $_SESSION['flash']['msg'] = "Failed to save location details. Please try again.";
                            $_SESSION['flash']['type'] = "error";
                            $user->delete($userId, 'pid'); // Rollback user creation
                            $this->view('agent/addserviceprovider', [
                                'errors' => $errors,
                                'message' => ''
                            ]);
                            return;
                        }
                    }

                    //check if payment details already exist
                    $paymentDetails = $payment_details->first(['pid' => $userId, 'account_no' => $accountNo]);
                    // echo "checking if payment details exist<br>";
                    if ($paymentDetails) {
                        $user->delete($personalDetails['pid'], 'pid');
                        $_SESSION['flash']['msg'] = "Payment details already exist for this account number.";
                        $_SESSION['flash']['type'] = "error";
                        // $errors['payment'] = "Payment details already exist for this account number.";
                        $this->view('agent/addserviceprovider', [
                            'user' => $resultUser,
                            'errors' => $errors,
                            'message' => ''
                        ]); // Re-render signup view with error
                        unset($errors['payment']); // Clear the error after displaying it
                        return; // Exit if payment details already exist
                    }
                    // Save payment details
                    // echo "inserting payment details<br>";
                    // var_dump([
                    //     'card_name' => $cardName,
                    //     'account_no' => $accountNo,
                    //     'bank' => $bankName,
                    //     'branch' => $branch,
                    //     'pid' => $userId,
                    // ]);

                    $paymentDetailStatus = $payment_details->insert([
                        'card_name' => $cardName,
                        'account_no' => $accountNo,
                        'bank' => $bankName,
                        'branch' => $branch,
                        'pid' => $userId,
                    ]);

                    // show($paymentDetailStatus);
                    // echo "payment details inserted<br>";

                    if ($paymentDetailStatus) {
                        // echo "payment done, now sending mail<br>";
                        $status = sendMail(
                            $email,
                            'Primecare Agent Registration',
                            "
                            <div style=\"font-family: Arial, sans-serif; color: #333; padding: 20px;\">
                                <h1 style=\"color: #4CAF50;\">Agent Registration</h1>
                                <p>Hello, $fname $lname</p>
                                <p>Your account has been created successfully. Your temporary password is:</p>
                                <h3 style=\"color: #4CAF50;\">$password</h3>
                                <p>If you did not request this, please ignore this email.</p>
                                <br>
                                <p>Best regards,<br>PrimeCare Support Team</p>
                            </div>
                        "
                        );
                        if (!$status['error']) {
                            $message = "Agent added successfully!. Password has been sent to email";
                            $_SESSION['flash']['msg'] = $message;
                            $_SESSION['flash']['type'] = "success";
                            // echo "mail sent<br>";
                        } else {
                            $message = "Agent added successfully!. Failed to send email. Contact Agent at {$contact}";
                            $_SESSION['flash']['msg'] = $message;
                            $_SESSION['flash']['type'] = "success";
                            // echo "mail could not be sent<br>";
                        }
                    } else {
                        $message = "Failed to add Agent Payement Details. Please try again.";
                        $_SESSION['flash']['msg'] = $message;
                        $_SESSION['flash']['type'] = "error";
                        $user->delete($personalDetails['pid'], 'pid');
                        // echo "payment details insertion failed<br>";
                    }

                    $this->view('agent/addserviceprovider', [
                        'user' => $resultUser,
                        'errors' => $errors
                    ]);

                    return;
                } else {
                    $_SESSION['flash']['msg'] = "Failed to add agent. Please try again.";
                    $_SESSION['flash']['type'] = "error";

                    $this->view('agent/addserviceprovider', [
                        'user' => $resultUser,
                        'errors' => $errors,
                        'message' => ''
                    ]);

                    unset($errors['auth']); // Clear the error after displaying it
                    return;
                }
            }
            $_SESSION['flash']['msg'] = "Failed to add agent. Please try again.";
            $_SESSION['flash']['type'] = "error";
            $user->delete($personalDetails['pid'], 'pid');

            $this->view('agent/addserviceprovider', [
                'user' => $resultUser,
                'errors' => $errors,
                'message' => ''
            ]);

            unset($errors['auth']); // Clear the error after displaying it
            return;
        }


        $this->view(
            'agent/addserviceprovider',
            [
                'errors' => $errors,
                'message' => ''
            ]
        );
        return;
    }

    public function removeproviders()
    {
        $user = new User();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pid'], $_POST['action'])) {
            $pid = intval($_POST['pid']);
            $action = $_POST['action'];

            if ($action === 'approve') {
                // Set active status to 0 and user level to 0
                $updateStatus = $user->update($pid, [
                    'AccountStatus' => -4,
                    'user_lvl' => 0
                ], 'pid');

                if ($updateStatus) {
                    $_SESSION['flash']['msg'] = "User removal successful!";
                    $_SESSION['flash']['type'] = "success";
                } else {
                    $_SESSION['flash']['msg'] = "Failed to approve user removal. Please try again.";
                    $_SESSION['flash']['type'] = "error";
                }
            } elseif ($action === 'reject') {
                // Set active status to 3
                $updateStatus = $user->update($pid, [
                    'AccountStatus' => -3,
                    'user_lvl' => 2
                ], 'pid');

                if ($updateStatus) {
                    $_SESSION['flash']['msg'] = "User removal rejected successfully!";
                    $_SESSION['flash']['type'] = "success";
                } else {
                    $_SESSION['flash']['msg'] = "Failed to reject user removal. Please try again.";
                    $_SESSION['flash']['type'] = "error";
                }
            }
        }

        $user->setLimit(7);

        $searchterm = $_GET['searchterm'] ?? "";
        $limit = $user->getLimit();
        $countWithTerms = $user->getTotalCountWhere(['AccountStatus' => -2, 'user_lvl' => 2], [], $searchterm);

        $totalPages = ceil($countWithTerms / $limit);
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($currentPage - 1) * $limit;

        $user->setOffset($offset);
        $users = $user->where(['AccountStatus' => -2, 'user_lvl' => 2], [], $searchterm);

        $pagination = new Pagination($currentPage, $totalPages, 2);
        $paginationLinks = $pagination->generateLinks();

        if (!empty($users)) {
            foreach ($users as $user) {
                unset($user->password); // Remove password from result
            }
        }
        // show($users);
        // die;
        $this->view('agent/spremove', [
            'paginationLinks' => $paginationLinks,
            'users' => $users ?? [],
            'tot' => $totalPages
        ]);

        $this->view('agent/spremove');
    }


    public function bookings($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'bookingremoval':
                $this->bookingRemoval($c);
                break;
            case 'bookingaccept':
                $this->bookingAccept($c);
                break;
            case 'history':
                $this->bookinghistory($c, $d);
                break;
            default:
                $BookingOrders = new BookingOrders();
                $property = new PropertyConcat();
                // Get all properties managed by this agent
                $properties = $property->where(['agent_id' => $_SESSION['user']->pid]);
                $properties = is_array($properties) ? $properties : [];
                $propertyIds = array_map(function ($property) {
                    return $property->property_id;
                }, $properties);
                // Fetch all pending bookings for all properties
                $pendingOrders = [];
                foreach ($propertyIds as $propertyId) {
                    $pending = $BookingOrders->getBookingsByPropertyId($propertyId);
                    if (!empty($pending)) {
                        foreach ($pending as $order) {
                            // Optionally, attach property images/details here if needed
                            $propertyData = $property->where(['property_id' => $order->property_id]);
                            if (!empty($propertyData)) {
                                $order->property_images = $propertyData[0]->property_images ?? null;
                            }
                            $pendingOrders[] = $order;
                        }
                    }
                }

                $this->view('agent/booking', [
                    'orders' => $pendingOrders
                ]);
                break;
        }
    }

    public function bookingAccept($c)
    {
        $book = new BookingModel;
        $property = new Property;
        $person = new User;
        $pid = $book->where(['booking_id' => $c],)[0];
        $image1 = new PropertyImageModel;
        $images = $image1->findAll();
        $bookings = $book->selecthreetables(
            $property->table,
            'property_id',
            'property_id',
            $person->table,
            'customer_id',
            'pid',
            'booking_id',
            $c,
            'AND',
            'customer_id',
            $pid->customer_id
        );
        $this->view('agent/bookingaccept', ['bookings' => $bookings, 'images' => $images]);
    }

    public function bookinghistory($c = '', $d = '')
    {
        switch ($c) {
            case 'showhistory':
                $this->showhistory($d);
                break;
            default:
                $BookingOrders = new BookingOrders();
                $PropertyConcat = new PropertyConcat();
                $User = new User();
                
                // Get all properties managed by this agent
                $properties = $PropertyConcat->where(['agent_id' => $_SESSION['user']->pid]);
                $properties = is_array($properties) ? $properties : [];
                $propertyIds = array_map(function ($property) {
                    return $property->property_id;
                }, $properties);
                
                $allBookings = [];
                if (!empty($propertyIds)) {
                    // Get all bookings for these properties with status accepted or rejected
                    foreach ($propertyIds as $propertyId) {
                        $bookings = $BookingOrders->where(['property_id' => $propertyId]);
                        
                        if (!empty($bookings)) {
                            foreach ($bookings as $booking) {
                                // Only include accepted or rejected bookings
                                if (in_array(strtolower($booking->booking_status), ['confirmed', 'cancelled', 'completed', 'rejected'])) {
                                    // Get property details
                                    $propertyData = $PropertyConcat->where(['property_id' => $booking->property_id]);
                                    $propertyName = !empty($propertyData) ? $propertyData[0]->name : '-';

                                    // Get customer details
                                    $customer = $User->where(['pid' => $booking->person_id]);
                                    $customerName = !empty($customer) ? ($customer[0]->fname . ' ' . $customer[0]->lname) : '-';

                                    // Format rental period to include units
                                    $rentalPeriod = strtolower($booking->rental_period ?? '');
                                    $duration = $booking->duration ?? '';
                                    
                                    // Combine period type with duration to show units properly
                                    $formattedRentalPeriod = '';
                                    if (!empty($duration) && !empty($rentalPeriod)) {
                                        if ($rentalPeriod == 'monthly' || $rentalPeriod == 'monlthy') {
                                            $formattedRentalPeriod = $duration . ' months';
                                        } elseif ($rentalPeriod == 'yearly' || $rentalPeriod == 'annually') {
                                            $formattedRentalPeriod = $duration . ' years';
                                        } elseif ($rentalPeriod == 'weekly') {
                                            $formattedRentalPeriod = $duration . ' weeks';
                                        } elseif ($rentalPeriod == 'daily') {
                                            $formattedRentalPeriod = $duration . ' days';
                                        } else {
                                            $formattedRentalPeriod = $duration . ' ' . $rentalPeriod;
                                        }
                                    } else {
                                        $formattedRentalPeriod = $rentalPeriod;
                                    }

                                    // Add to array for view
                                    $allBookings[] = (object)[
                                        'booking_id' => $booking->booking_id,
                                        'name' => $propertyName,
                                        'fname' => !empty($customer) ? $customer[0]->fname : '',
                                        'lname' => !empty($customer) ? $customer[0]->lname : '',
                                        'renting_period' => $formattedRentalPeriod,
                                        'payment_status' => $booking->payment_status,
                                        'accept_status' => ucfirst($booking->booking_status),
                                    ];
                                }
                            }
                        }
                    }
                }

                $this->view('agent/bookinghistory', ['bookings' => $allBookings]);
                break;
        }
    }

    public function showhistory($d)
    {
        $book = new BookingModel;
        $property = new Property;
        $person = new User;
        $pid = $book->where(['booking_id' => $d],)[0];
        $image1 = new PropertyImageModel;
        $images = $image1->findAll();
        $bookings = $book->selecthreetables(
            $property->table,
            'property_id',
            'property_id',
            $person->table,
            'customer_id',
            'pid',
            'booking_id',
            $d,
            'AND',
            'customer_id',
            $pid->customer_id
        );
        $this->view('agent/showhistory', ['bookings' => $bookings, 'images' => $images]);
    }

    public function confirmBooking($bookingId) {
        $BookingOrders = new BookingOrders();
        $booking = $BookingOrders->findById($bookingId);
        if ($booking && strtolower($booking->booking_status) === 'pending') {
            $BookingOrders->updateBookingStatusByOwnerAndDates(
                $booking->property_id,
                $booking->person_id,
                $booking->start_date,
                $booking->end_date,
                'Confirmed'
            );
            
            // Update agent_id to current session user
            $BookingOrders->update($bookingId, [
                'agent_id' => $_SESSION['user']->pid
            ]);
            
            $_SESSION['flash']['msg'] = "Booking confirmed.";
            $_SESSION['flash']['type'] = "success";
        }
        redirect('dashboard/bookings');
    }

    public function cancelBooking($bookingId) {
        $BookingOrders = new BookingOrders();
        $booking = $BookingOrders->findById($bookingId);
        if (!$booking) {
            $_SESSION['flash']['msg'] = "Booking not found.";
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/bookings/bookingremoval');
            return;
        }
        if ($booking && in_array(strtolower($booking->booking_status), ['pending', 'confirmed', 'cancel requested'])) {
            $BookingOrders->updateBookingStatusByOwnerAndDates(
                $booking->property_id,
                $booking->person_id,
                $booking->start_date,
                $booking->end_date,
                'Cancelled'
            );
            // Update agent_id to current session user only if user a agent
            if ($_SESSION['user']->user_lvl == 3) {
                $BookingOrders->update($bookingId, [
                    'agent_id' => $_SESSION['user']->pid,
                ],'booking_id');
            }
            $_SESSION['flash']['msg'] = "Booking cancelled.";
            $_SESSION['flash']['type'] = "success";
        }
        redirect('dashboard/bookings/bookingremoval');
    }

    public function continueBooking($bookingId) {
        $BookingOrders = new BookingOrders();
        $booking = $BookingOrders->findById($bookingId);
        if ($booking && strtolower($booking->booking_status) === 'cancel requested') {
            $BookingOrders->updateBookingStatusByOwnerAndDates(
                $booking->property_id,
                $booking->person_id,
                $booking->start_date,
                $booking->end_date,
                'Confirmed'
            );
            $_SESSION['flash']['msg'] = "Booking continued.";
            $_SESSION['flash']['type'] = "success";
        }
        redirect('dashboard/bookings');
    }

    private function logout()
    {
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }

    public function bookingRemoval(){
        $BookingOrders = new BookingOrders();
        $property = new PropertyConcat();

        // Get all properties managed by this agent
        $properties = $property->where(['agent_id' => $_SESSION['user']->pid]);
        $properties = is_array($properties) ? $properties : [];
        $propertyIds = array_map(function ($property) {
            return $property->property_id;
        }, $properties);
        // Fetch all pending bookings for all properties
        $pendingOrders = [];
        foreach ($propertyIds as $propertyId) {
            $pending = $BookingOrders->getBookingsByPropertyId($propertyId, 'Cancel Requested');
            if (!empty($pending)) {
                foreach ($pending as $order) {
                    // Optionally, attach property images/details here if needed
                    $propertyData = $property->where(['property_id' => $order->property_id]);
                    if (!empty($propertyData)) {
                        $order->property_images = $propertyData[0]->property_images ?? null;
                    }
                    $pendingOrders[] = $order;
                }
            }
        }

        $this->view('agent/bookingRemoval', [
            'orders' => $pendingOrders
        ]);
        
    }

    // public function reportGen($property_id)
    // {
    //     $property = new PropertyConcat;
    //     $property = $property->where(['property_id' => $property_id, 'agent_id' => $_SESSION['user']->pid])[0];

    //     $agent = new User;
    //     $agent = $agent->where(['pid' => $_SESSION['user']->pid])[0];
    //     // require_once APPROOT . '/libraries/fpdf/fpdf.php';
    //     // $pdf = new FPDF();
    //     // $pdf->AddPage();
    //     // $pdf->SetFont('Arial', 'B', 16);
    //     // $pdf->Cell(40, 10, 'Hello, this is a report!');
    //     // $pdf->Output();
    //     // $this->report('preInsp', [
    //     //     'user' => $_SESSION['user'],
    //     //     'property' => $property,
    //     //     'agent' => $agent
    //     // ]);

    //     $reportHTML = $this->generatePreInspectionReport([
    //         'property' => $property,
    //         'agent' => $agent
    //     ]);

    //     echo $reportHTML; // Or send as response, export to PDF, etc.

    // }

    public function externalServiceRequests()
    {
        $externalService = new ExternalService();
        $services = $externalService->where(['status' => 'pending']);

        // Get available service providers (user_lvl = 2) with their image URLs
        $user = new User();
        $service_providers = $user->where(['user_lvl' => 2]);

        // Filter out service providers who already have 4 or more ongoing services
        $filtered_providers = [];
        foreach ($service_providers as $provider) {
            $provider->image_url = $provider->image_url ?? 'Agent.png';

            // Count ongoing services for this provider
            $ongoing_count = $externalService->where([
                'service_provider_id' => $provider->pid,
                'status' => 'ongoing'
            ]);

            // Convert ongoing_count to array if false (no services)
            $ongoing_count = $ongoing_count === false ? [] : $ongoing_count;

            // Add provider if they have less than 4 ongoing services
            if (count($ongoing_count) < 4) {
                $filtered_providers[] = $provider;
            }
        }

        // Process property images for each service (decode JSON)
        if ($services) {
            foreach ($services as $service) {
                // Get property images from service_images JSON field
                if (!empty($service->service_images)) {
                    $images = json_decode($service->service_images, true);
                    
                    // Assign the first image to the service object if available
                    if (is_array($images) && !empty($images)) {
                        // Store the actual image URL from the JSON array
                        $service->property_image = $images[0];
                    } else {
                        // Set default image if no property images found
                        $service->property_image = 'listing_alt.jpg';
                    }
                } else {
                    $service->property_image = 'listing_alt.jpg';
                }
            }
        }

        $data = [
            'services' => $services,
            'service_providers' => $filtered_providers
        ];

        // Handle service provider assignment when accept button is pressed
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service_id'])) {
            $service_id = $_POST['service_id'];

            // Get the selected service provider from the dropdown
            $provider_id = $_POST['service_provider_select'];

            if ($provider_id) {
                // Create a new ExternalService instance to ensure it's a model with where() method
                $serviceModel = new ExternalService();
                
                // Check again if provider hasn't exceeded limit
                $ongoing_services = $serviceModel->where([
                    'service_provider_id' => $provider_id,
                    'status' => 'ongoing'
                ]);

                // Convert to empty array if false
                $ongoing_services = $ongoing_services === false ? [] : $ongoing_services;

                if (count($ongoing_services) >= 4) {
                    $_SESSION['error'] = "This service provider has reached their maximum service limit";
                } else {
                    // Update service with assigned provider and change status to ongoing
                    $result = $serviceModel->update($service_id, [
                        'service_provider_id' => $provider_id,
                        'status' => 'ongoing'
                    ]);

                    if ($result) {
                        // Get service details to include in notification
                        $serviceDetails = $serviceModel->first(['id' => $service_id]);
                        
                        // Create notification for the service provider
                        $notificationModel = new NotificationModel();
                        $notificationData = [
                            'user_id' => $provider_id,
                            'title' => "New External Service Assignment",
                            'message' => "You have been assigned a new " . 
                                        ($serviceDetails->service_type ?? "external service") . 
                                        " task at " . 
                                        ($serviceDetails->property_address ?? "location not specified"),
                            'link' => "/php_mvc_backend/public/dashboard/externalServices",
                            'color' => 'Notification_green', 
                            'is_read' => 0,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        // Insert notification for service provider
                        $notificationModel->insert($notificationData);

                        // Create notification for customer who requested the service
                        if (!empty($serviceDetails->requested_person_id)) {
                            $notificationModel = new NotificationModel();
                            $customerNotificationData = [
                                'user_id' => $serviceDetails->requested_person_id,
                                'title' => "External Service Request Accepted",
                                'message' => "Your " . ($serviceDetails->service_type ?? "service") . 
                                            " request has been accepted and assigned to a service provider.",
                                'link' => "/php_mvc_backend/public/dashboard/requestService",
                                'color' => 'Notification_green',
                                'is_read' => 0,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                            
                            // Insert notification for the customer
                            $notificationModel->insert($customerNotificationData);
                        }
                        
                        $_SESSION['flash']['msg'] = "External service request accepted and assigned successfully";
                        $_SESSION['flash']['type'] = "success";
                        redirect('dashboard/externalServiceRequests');
                        exit; // Add exit here to prevent further execution
                    } else {
                        $_SESSION['flash']['msg'] = "Failed to update service request";
                        $_SESSION['flash']['type'] = "error";
                    }
                }
            } else {
                $_SESSION['flash']['msg'] = "Please select a service provider";
                $_SESSION['flash']['type'] = "error";
            }

            redirect('dashboard/externalServiceRequests');
            exit; // Add exit here to prevent further execution
        }

        // Handle service request deletion when decline button is pressed
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_service_id'])) {
            $service_id = $_POST['delete_service_id'];

            // Update the service request status to "rejected" instead of deleting
            $result = $externalService->update($service_id, [
                'status' => 'rejected'
            ]);

            if ($result) {
                // Get service details to include in notification
                $serviceDetails = $externalService->first(['id' => $service_id]);
                
                // Create notification for customer about rejection
                if (!empty($serviceDetails->requested_person_id)) {
                    $notificationModel = new NotificationModel();
                    $customerNotificationData = [
                        'user_id' => $serviceDetails->requested_person_id,
                        'title' => "External Service Request Declined",
                        'message' => "Your " . ($serviceDetails->service_type ?? "service") . 
                                    " request has been declined. Please contact support for more information.",
                        'link' => "/php_mvc_backend/public/dashboard/requestService",
                        'color' => 'Notification_red',
                        'is_read' => 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Insert notification for the customer
                    $notificationModel->insert($customerNotificationData);
                }
                $_SESSION['flash']['msg'] = "External service request declined successfully";
                $_SESSION['flash']['type'] = "success";
                redirect('dashboard/externalServiceRequests');
                exit; // Add exit here to prevent further execution
            } else {
                $_SESSION['flash']['msg'] = "Failed to decline external service request";
                $_SESSION['flash']['type'] = "error";
            }

            redirect('dashboard/externalServiceRequests');
            exit; // Add exit here to prevent further execution
        }

        $this->view('agent/externalServiceRequests', $data);
    }

    public function serviceRequests()
    {
        // Get pending counts for different service types
        $serviceLog = new ServiceLog();
        $externalService = new ExternalService();
        
        // Count pending maintenance tasks
        $pendingTasks = $serviceLog->where(['status' => 'Pending']);
        $pendingTasksCount = is_array($pendingTasks) ? count($pendingTasks) : 0;
        
        // Count completed (Done) tasks
        $completedTasks = $serviceLog->where(['status' => 'Done']);
        $completedTasksCount = is_array($completedTasks) ? count($completedTasks) : 0;

        // Count paid tasks
        $paidTasks = $serviceLog->where(['status' => 'Paid']);
        $paidTasksCount = is_array($paidTasks) ? count($paidTasks) : 0;

        // Total of Done and Paid tasks
        $tasksCount = $completedTasksCount + $paidTasksCount;
        
        // Count pending external service requests
        $externalRequests = $externalService->where(['status' => 'pending']);
        $externalRequestsCount = is_array($externalRequests) ? count($externalRequests) : 0;
        
        $this->view('agent/serviceRequests', [
            'user' => $_SESSION['user'],
            'pendingTasksCount' => $pendingTasksCount,
            'tasksCount' => $tasksCount,
            'externalRequestsCount' => $externalRequestsCount
        ]);
    }

    // Service Management - Main page with navigation cards

    public function serviceManagement() {
        $this->view('agent/serviceManagement');
    }

    // Service Listing - Page to view and manage available services

    public function serviceListing() {
        $services = new Services();
        $allServices = $services->findAll();
        
        $this->view('agent/serviceListing', [
            'services' => $allServices
        ]);
    }

    // Service Applications - Page to view and manage service provider applications
    
    public function serviceApplications() {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
        
        // Get current page for pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $items_per_page = 9;
        $offset = ($page - 1) * $items_per_page;
        
        // Get status filter
        $selected_status = isset($_GET['status']) ? $_GET['status'] : 'all';
        
        // Create instances of the required models
        $serviceApplication = new ServiceApplication();
        $services = new Services();
        $user = new User();
        
        // Get applications with proper filtering
        if ($selected_status !== 'all') {
            $applications = $serviceApplication->where(['status' => $selected_status]);
        } else {
            $applications = $serviceApplication->findAll();
        }
        
        // Count total records for pagination
        $total_records = is_array($applications) ? count($applications) : 0;
        $total_pages = ceil($total_records / $items_per_page);
        
        // Prepare the enhanced applications array with additional details
        $enhancedApplications = [];
        
        if (is_array($applications) && !empty($applications)) {
            // Only process the required page of results
            $pagedApplications = array_slice($applications, $offset, $items_per_page);
            
            foreach ($pagedApplications as $application) {
                // Get service details
                $service = $services->first(['service_id' => $application->service_id]);
                
                // Get provider details
                $provider = $user->first(['pid' => $application->service_provider_id]);
                
                if ($service && $provider) {
                    // Create an enhanced application object with all required details
                    $enhancedApp = clone $application;
                    $enhancedApp->service_name = $service->name;
                    $enhancedApp->cost_per_hour = $service->cost_per_hour;
                    $enhancedApp->provider_name = $provider->fname . ' ' . $provider->lname;
                    $enhancedApp->provider_contact = $provider->contact;
                    
                    $enhancedApplications[] = $enhancedApp;
                }
            }
        }
        
        // Load the view with data
        $this->view('agent/serviceApplications', [
            'applications' => $enhancedApplications,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'selected_status' => $selected_status
        ]);
    }

    // View application details

    public function viewApplicationDetails($service_id = null, $provider_id = null) {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
        
        if (!$service_id || !$provider_id) {
            $_SESSION['flash']['msg'] = "Invalid application details";
            $_SESSION['flash']['type'] = "error";
            redirect('agent/serviceApplications');
            return;
        }
        
        // Get application details
        $serviceApplication = new ServiceApplication();
        $application = $serviceApplication->first([
            'service_id' => $service_id,
            'service_provider_id' => $provider_id
        ]);
        
        if (!$application) {
            $_SESSION['flash']['msg'] = "Application not found";
            $_SESSION['flash']['type'] = "error";
            redirect('agent/serviceApplications');
            return;
        }
        
        // Get service details
        $services = new Services();
        $service = $services->getServiceById($service_id);
        
        if (!$service) {
            $_SESSION['flash']['msg'] = "Service not found";
            $_SESSION['flash']['type'] = "error";
            redirect('agent/serviceApplications');
            return;
        }
        
        // Get provider details
        $userModel = new User();
        $provider = $userModel->first(['pid' => $provider_id]);
        
        if (!$provider) {
            $_SESSION['flash']['msg'] = "Service provider not found";
            $_SESSION['flash']['type'] = "error";
            redirect('agent/serviceApplications');
            return;
        }
        
        // Load the view with data
        $this->view('agent/viewApplicationDetails', [
            'application' => $application,
            'service' => $service,
            'provider' => $provider
        ]);
    }

    // Update application status

    public function updateApplicationStatus($service_id = null, $provider_id = null, $status = null) {
        // Check if user is logged in
        if (!isset($_SESSION['user']) || empty($_SESSION['user']->pid)) {
            redirect('login');
            return;
        }
        
        if (!$service_id || !$provider_id || !$status) {
            $_SESSION['flash']['msg'] = "Invalid action parameters";
            $_SESSION['flash']['type'] = "error";
            redirect('agent/serviceApplications');
            return;
        }
        
        // Validate status
        if (!in_array($status, ['Approved', 'Rejected', 'Pending'])) {
            $_SESSION['flash']['msg'] = "Invalid status value";
            $_SESSION['flash']['type'] = "error";
            redirect('agent/serviceApplications');
            return;
        }
        
        // Get application
        $serviceApplication = new ServiceApplication();
        $application = $serviceApplication->first([
            'service_id' => $service_id,
            'service_provider_id' => $provider_id
        ]);
        
        if (!$application) {
            $_SESSION['flash']['msg'] = "Application not found";
            $_SESSION['flash']['type'] = "error";
            redirect('agent/serviceApplications');
            return;
        }
    
        // Instead of updating directly, delete and reinsert with new status

        $data = [
            'service_id' => $service_id,
            'service_provider_id' => $provider_id,
            'proof' => $application->proof,
            'status' => $status,
            'created_at' => $application->created_at,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Delete the old record
        $serviceApplication->delete($service_id, 'service_id', [
            'service_provider_id' => $provider_id
        ]);
        
        // Insert the new record with updated status
        $result = $serviceApplication->insert($data);
        
        // Create notification for service provider
        $notificationModel = new NotificationModel();
        $notificationData = [
            'user_id' => $provider_id,
            'title' => "Service Application " . $status,
            'message' => "Your application to provide services has been " . strtolower($status) . ".",
            'color' => $status === 'Approved' ? 'Notification_green' : ($status === 'Rejected' ? 'Notification_red' : 'Notification_blue'),
            'is_read' => 0,
            'link' => ROOT . '/serviceprovider/checkApplicationStatus/' . $service_id,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $notificationModel->insert($notificationData);
        
        $_SESSION['flash']['msg'] = "Application status updated successfully to " . $status;
        $_SESSION['flash']['type'] = "success";
        
        redirect('dashboard/viewApplicationDetails/' . $service_id . '/' . $provider_id);
    }
}
