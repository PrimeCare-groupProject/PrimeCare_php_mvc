<?php
defined('ROOTPATH') or exit('Access denied');


class Agent
{
    use controller;

    public function index()
    {
        $this->view('agent/dashboard');
    }

    public function dashboard()
    {
        $this->view('agent/dashboard');
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

    public function expenses()
    {
        echo "User Expenses Section";
    }

    public function requestedTasks()
    {
        $service = new ServiceLog();
        $services = $service->where(['status' => 'Pending']);

        // Get available service providers (user_lvl = 2) with their image URLs
        $user = new User();
        $service_providers = $user->where(['user_lvl' => 2]);

        // Filter out service providers who already have 4 or more ongoing services
        $filtered_providers = [];
        foreach ($service_providers as $provider) {
            $provider->image_url = $provider->image_url ?? 'Agent.png';

            // Count ongoing services for this provider
            $ongoing_count = $service->where([
                'service_provider_id' => $provider->pid,
                'status' => 'Ongoing'
            ]);

            // Convert ongoing_count to array if false (no services)
            $ongoing_count = $ongoing_count === false ? [] : $ongoing_count;

            // Add provider if they have less than 4 ongoing services
            if (count($ongoing_count) < 4) {
                $filtered_providers[] = $provider;
            }
        }

        // Fetch property images for each service
        if ($services) {
            foreach ($services as $service) {
                if (!empty($service->property_id)) {
                    // Get property details
                    $property = new Property();
                    $propertyData = $property->first(['property_id' => $service->property_id]);
                    
                    if ($propertyData) {
                        // Get property images from PropertyImageModel
                        $propertyImage = new PropertyImageModel();
                        $images = $propertyImage->where(['property_id' => $service->property_id]);
                        
                        // Assign the first image to the service object if available
                        if ($images && is_array($images) && !empty($images) && !empty($images[0]->image_url)) {
                            // Store the actual image URL from the property_image table
                            $service->property_image = $images[0]->image_url;
                        } else {
                            // Set default image if no property images found
                            $service->property_image = 'listing_alt.jpg';
                        }
                    } else {
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
                // Check again if provider hasn't exceeded limit
                $ongoing_services = $service->where([
                    'service_provider_id' => $provider_id,
                    'status' => 'Ongoing'
                ]);

                // Convert to empty array if false
                $ongoing_services = $ongoing_services === false ? [] : $ongoing_services;

                if (count($ongoing_services) >= 4) {
                    $_SESSION['error'] = "This service provider has reached their maximum service limit";
                } else {
                    // Update service with assigned provider and change status to Ongoing
                    $result = $service->update($service_id, [
                        'service_provider_id' => $provider_id,
                        'status' => 'Ongoing'
                    ], 'service_id');

                    if ($result) {
                        // Get service details to include in notification
                        $serviceDetails = $service->first(['service_id' => $service_id]);
                 
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
                        
                        $_SESSION['success'] = "Service request accepted and assigned successfully";
                        redirect('dashboard/requestedTasks');
                        exit; // Add exit here to prevent further execution
                    } else {
                        $_SESSION['error'] = "Failed to update service request";
                    }
                }
            } else {
                $_SESSION['error'] = "Please select a service provider";
            }

            redirect('dashboard/requestedTasks');
            exit; // Add exit here to prevent further execution
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
                $_SESSION['success'] = "Service request declined successfully";
                redirect('dashboard/requestedTasks');
                exit; // Add exit here to prevent further execution
            } else {
                $_SESSION['error'] = "Failed to decline service request";
            }

            redirect('dashboard/requestedTasks');
            exit; // Add exit here to prevent further execution
        }

        $this->view('agent/requestedTasks', $data);
    }

    public function tasks($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'newtask':
                $this->newTask();
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
                $tasks = $services->selecttwotables($service->table,
                                                   'service_id', 
                                                   'services_id',
                                                   );
                $this->view('agent/tasks', ['tasks' => $tasks]);
                break;
        }
    }

    public function newTask()
    {
        $this->view('agent/newtask');
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

    public function services($b = '', $c = '', $d = ''){
        switch($b){
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
            default :   
                $preinspection = new PropertyConcat;
                $inspection = $preinspection->where(['status' => 'pending', 'agent_id' => $_SESSION['user']->pid]);
                $this->view('agent/preInspection', ['preinspection' => $inspection]);
                break;
        }
    }

    public function showReport($property_id){
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
        $this->view('agent/newinventory');
    }

    public function editinventory($c){
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
            default:
                $this->view('agent/manageProviders');
                break;
        }
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
                                if($newDetails) {
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
                    $pids = array_map(function($user) {
                        return $user->pid;
                    }, $new);

                    $old = $user->findByMultiplePids($pids, ['user_lvl' => 2]);

                    if(!empty($old)) {
                        $old = array_reduce($old, function($carry, $oldUser) use ($pids) {
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
    
    private function removeOwners($c, $d){
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
                    $pids = array_map(function($user) {
                        return $user->pid;
                    }, $new);

                    $old = $user->findByMultiplePids($pids, ['user_lvl' => 1]);

                    if (!empty($old)) {
                        $old = array_reduce($old, function($carry, $oldUser) use ($pids) {
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

    private function addserviceprovider() {
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
            $nicUser = $user->first(['nic' => $nic ], []);

            if (($resultUser && !empty($resultUser->email)) || $nicUser && !empty($nicUser->email)) {
                //update user class errors
                $_SESSION['flash']['msg'] = "Email or NIC already exists.";
                $_SESSION['flash']['type'] = "error";
                // $errors['email'] = 'Email or NIC already exists';
                $this->view('agent/addserviceprovider',[
                    'user' => $resultUser, 
                    'errors' => $errors, 
                    'message' => ''] ); // Re-render signup view with error
                
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
                'confirmPassword' =>$password,
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
                $this->view('agent/addserviceprovider',[
                    'user' => $resultUser, 
                    // 'errors' => $user->errors, 
                    'message' => '']); // Re-render signup view with errors

                unset($user->errors); // Clear the error after displaying it
                return; // Exit if validation fails
            }
            // echo "user details validated:<br>";

            unset($personalDetails['confirmPassword']);
            

            // Validate and sanitize location details
            if (!$location->validateLocation($_POST)) {
                $_SESSION['flash']['msg'] = is_array($location->errors) ? implode("\n", $location->errors) : (string)$location->errors;
                $_SESSION['flash']['type'] = "error";
                $this->view('agent/addserviceprovider',[
                    'user' => $resultUser, 
                    'errors' => $errors, 
                    'message' => '']); // Re-render signup view with errors

                unset($location->errors); // Clear the error after displaying it
                return; // Exit if validation fails
            }
            

            $personalDetails['password'] = $hashedPassword;//set hashed password
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
                    'message' => '']);

                unset($errors['auth']); // Clear the error after displaying it
                return;
            }else{
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
                        $this->view('agent/addserviceprovider',[
                            'user' => $resultUser, 
                            'errors' => $errors, 
                            'message' => '']); // Re-render signup view with error
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

                    if($paymentDetailStatus){
                        // echo "payment done, now sending mail<br>";
                        $status = sendMail(
                            $email ,
                            'Primecare Agent Registration', "
                            <div style=\"font-family: Arial, sans-serif; color: #333; padding: 20px;\">
                                <h1 style=\"color: #4CAF50;\">Agent Registration</h1>
                                <p>Hello, $fname $lname</p>
                                <p>Your account has been created successfully. Your temporary password is:</p>
                                <h3 style=\"color: #4CAF50;\">$password</h3>
                                <p>If you did not request this, please ignore this email.</p>
                                <br>
                                <p>Best regards,<br>PrimeCare Support Team</p>
                            </div>
                        ");
                        if(!$status['error']){
                            $message = "Agent added successfully!. Password has been sent to email";
                            $_SESSION['flash']['msg'] = $message;
                            $_SESSION['flash']['type'] = "success";
                            // echo "mail sent<br>";
                        }else{
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
                        'message' => '']);

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
                'message' => '']);

            unset($errors['auth']); // Clear the error after displaying it
            return;
        }
        

        $this->view('agent/addserviceprovider',[
            'errors' => $errors, 
            'message' => '']
        );
        return;
    }

    public function removeproviders(){   
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
            case 'bookingaccept':
                $this->bookingAccept($c);
                break;
            case 'history':
                $this->bookinghistory($c,$d);
                break;
            default:
                $book = new BookingModel;
                $property = new Property;
                $bookings = $book->selecttwotables($property->table,
                                                   'property_id', 
                                                   'property_id',
                                                   'accept_status',
                                                   '\'pending\'');
                $image1 = new PropertyImageModel;
                $images = $image1->findAll(); 
                /*echo "<pre>";
                print_r($images);
                echo "</pre>";*/
                $this->view('agent/booking',['bookings'=> $bookings,'images' => $images]);
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
        $bookings = $book->selecthreetables($property->table,
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
        $this->view('agent/bookingaccept',['bookings'=> $bookings ,'images' => $images]);
    }

    public function bookinghistory($c,$d)
    {
        switch ($c) {
            case 'showhistory':
                $this->showhistory($d);
                break;
            default:
            $book = new BookingModel;
            /*echo "<pre>";
        print_r($book);
        echo "</pre>";*/
            $property = new Property;
            $person = new User; 
            $bookings = $book->selecthreetables($property->table,
                                                'property_id', 
                                                'property_id', 
                                                $person->table,
                                                'customer_id', 
                                                'pid',
                                                'accept_status',
                                                '\'accepted\'',
                                                'OR',
                                                'accept_status',
                                                '\'rejected\''
                                                );
            $this->view('agent/bookinghistory',['bookings'=> $bookings]);
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
        $this->view('agent/showhistory',['bookings'=> $bookings,'images' => $images]);
    }

    private function logout()
    {
        session_unset();
        session_destroy();
        redirect('home');
        exit;
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

}
