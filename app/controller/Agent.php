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
        // If the Account status is 3 show rejected flash or if 4 show accepted flash
        if ($_SESSION['user']->AccountStatus == 3) {
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
        } elseif ($_SESSION['user']->AccountStatus == 4) {
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
            case 'spremove':
                $this->spremove();
                break;
            default:
                $this->view('agent/serviceproviders');
                break;
        }
    }

    public function payments()
    {
        $this->view('agent/payments');
    }

    public function addserviceprovider()
    {
        $this->view('agent/addserviceprovider');
    }

    public function removeserviceprovider($c, $d)
    {
        switch ($d) {
            case 'spremove':
                $this->spremove();
                break;
            default:
                $this->view('agent/removeserviceprovider');
                break;
        }
    }

    public function spremove()
    {
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
                /*echo "<pre>";
        print_r($bookings);
        echo "</pre>";*/
                $this->view('agent/booking',['bookings'=> $bookings]);
                break;
        }
    }

    public function bookingAccept($c)
    {
        $book = new BookingModel;
        $property = new Property;
        $person = new User; 
        $pid = $book->where(['booking_id' => $c],)[0];
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
        $this->view('agent/bookingaccept',['bookings'=> $bookings]);
        
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
        $this->view('agent/showhistory',['bookings'=> $bookings]);
        
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
