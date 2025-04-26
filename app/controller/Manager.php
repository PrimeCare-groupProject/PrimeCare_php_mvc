<!-- ManagerDashboard -->
<?php
defined('ROOTPATH') or exit('Access denied');

class Manager
{
    use controller;
    public function index()
    {
        checkSalaryReminder(MANAGER_ID);
        $this->dashboard();
    }

    public function dashboard()
    {
        checkSalaryReminder(MANAGER_ID);
        // Load the required models
        $propertyModel = new Property();
        $userModel = new User();
        $bookingOrders = new BookingOrders();
        $agentAssignmentModel = new agentAssignment();

        // Fetch recent agent assignments
        $recentAssignments = $agentAssignmentModel->findAll();
        // Fetch the required data
        $totalProperties = $propertyModel->getTotalCountWhere();
        $registeredAgents = $userModel->getTotalCountWhere(['user_lvl' => 3]); // Assuming user_lvl = 3 for agents
        $serviceProviders = $userModel->getTotalCountWhere(['user_lvl' => 2]); // Assuming user_lvl = 2 for service providers
        $tenents = $bookingOrders->getTotalCountWhere(['booking_status' => 'Confirmed']); // Assuming user_lvl = 2 for service providers
        // Pass the data to the view
        $this->view('manager/dashboard', [
            'totalProperties' => $totalProperties,
            'registeredAgents' => $registeredAgents,
            'serviceProviders' => $serviceProviders,
            'tenents' => $tenents,
            'recentAssignments' => $recentAssignments
        ]);
    }

    public function generateUsername($fname, $length = 10)
    {
        // Normalize the first name by removing non-alphanumeric characters
        $fname = preg_replace('/[^a-zA-Z0-9]/', '', $fname);
        // Truncate if longer than the desired length
        $username = (strlen($fname) > $length) ? substr($fname, 0, $length) : $fname;

        // Add random characters until reaching the desired length
        while (strlen($username) < $length) {
            // Generate a random character from numbers (0-9) or uppercase letters (A-Z)
            $randomChar = mt_rand(0, 1) ? chr(mt_rand(48, 57)) : chr(mt_rand(65, 90));
            $username .= $randomChar;
        }

        return substr($username, 0, $length); // Ensure exactly $length characters
    }

    private function addAgent()
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
                $this->view('manager/addAgent', [
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
                'user_lvl' => 3,
                'username' => $this->generateUsername($_POST['fname']),
            ];
            // echo "validating user details<br>";

            // Validate the form data
            if (!$user->validate($personalDetails)) {
                // show($user->errors);
                // echo "if2";
                $_SESSION['flash']['msg'] = is_array($user->errors) ? implode("\n", $user->errors) : (string)$user->errors;
                $_SESSION['flash']['type'] = "error";
                $this->view('manager/addAgent', [
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
                $this->view('manager/addAgent', [
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
                $this->view('manager/addAgent', [
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
                            $this->view('manager/addAgent', [
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
                        $this->view('manager/addAgent', [
                            'user' => $resultUser,
                            'errors' => $errors,
                            'message' => ''
                        ]); // Re-render signup view with error
                        unset($errors['payment']); // Clear the error after displaying it
                        return; // Exit if payment details already exist
                    }
                    // Save payment details

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

                    $this->view('manager/addAgent', [
                        'user' => $resultUser,
                        'errors' => $errors
                    ]);

                    return;
                } else {
                    $_SESSION['flash']['msg'] = "Failed to add agent. Please try again.";
                    $_SESSION['flash']['type'] = "error";

                    $this->view('manager/addAgent', [
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

            $this->view('manager/addAgent', [
                'user' => $resultUser,
                'errors' => $errors,
                'message' => ''
            ]);

            unset($errors['auth']); // Clear the error after displaying it
            return;
        }


        $this->view(
            'manager/addAgent',
            [
                'errors' => $errors,
                'message' => ''
            ]
        );
        return;
    }


    public function profile()
    {
        $user = new User();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['delete_account'])) {
                $errors = [];
                $status = '';
                //AccountStatus
                $userId = $_SESSION['user']->pid; // Replace with actual user ID from session

                $_SESSION['flash']['msg'] = "Managers are not allowed to delete Accounts.";
                $_SESSION['flash']['type'] = "error";


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


    public function managementHome($a = '', $b = '', $c = '', $d = '')
    {
        switch ($a) {
            case 'propertymanagement':
                $this->propertyManagement($b, $c, $d);
                break;
            case 'employeemanagement':
                $this->employeeManagement();
                break;
            case 'agentmanagement':
                $this->agentManagement($b, $c, $d);
                break;
            case 'financemanagement':
                $this->financialManagement($c, $d);
                break;
            case 'confirmUpdate':
                $this->confirmUpdate($b, $c, $d);
                break;
            case 'rejectUpdate':
                $this->rejectUpdate($b, $c, $d);
                break;
            case 'employeeListing':
                $this->employeeListing($b, $c, $d);
                break;
            case 'salaryView':
                $this->salaryView($b, $c, $d);
                break;
            case 'payForOne':
                $this->payForOne($b, $c, $d);
                break;
            case 'payAll':
                $this->payAll($b, $c, $d);
                break;
            case 'financeView':
                $this->financeView($b, $c, $d);
                break;
            case 'monthlyReport':
                $this->monthlyReport($c, $d);
                break;
            default:
                $this->view('manager/managementHome');
                break;
        }
    }

    public function financeView()
    {
        $rentalPaymentModel = new RentalPayment();
        $sharePaymentModel = new SharePayment();
        $servicePaymentModel = new ServicePayment();
        $salaryPaymentModel = new SalaryPayment();
        $ledgerModel = new Ledger();

        // Fetch all necessary payment data
        $rentalPayments = $rentalPaymentModel->findAll() ?: [];
        $sharePayments = $sharePaymentModel->findAll() ?: [];
        $servicePayments = $servicePaymentModel->findAll() ?: [];
        $salaryPayments = $salaryPaymentModel->findAll() ?: [];
        $ledger = $ledgerModel->findAll() ?: [];

        // Pre-calculate totals
        $totalRentalPayments = array_sum(array_column($rentalPayments, 'amount'));
        $totalSharePayments = array_sum(array_column($sharePayments, 'amount'));
        $totalServicePayments = array_sum(array_column($servicePayments, 'amount'));
        $totalSalaryPayments = array_sum(array_column($salaryPayments, 'salary_amount'));

        // Calculate total advance and full payments separately
        $advancePayments = $sharePaymentModel->where(['payment_type' => 'advance']);
        $fullPayments = $sharePaymentModel->where(['payment_type' => 'full']);

        $totalAdvancePayments = array_sum(array_column($advancePayments ?: [], 'amount'));
        $totalFullPayments = array_sum(array_column($fullPayments ?: [], 'amount'));


        $this->view('manager/financeTab', [
            'rentalPayments' => $rentalPayments,
            'sharePayments' => $sharePayments,
            'servicePayments' => $servicePayments,
            'salaries' => $salaryPayments,
            'ledger' => $ledger,
            'totalRentalPayments' => $totalRentalPayments,
            'totalSharePayments' => $totalSharePayments,
            'totalServicePayments' => $totalServicePayments,
            'totalSalaryPayments' => $totalSalaryPayments,
            'totalAdvancePayments' => $totalAdvancePayments,
            'totalFullPayments' => $totalFullPayments
        ]);
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

    public function propertyManagement($b = '', $c = '', $d = '')
    {
        switch ($b) {
            case 'assignagents':
                $this->assignAgents($c, $d);
                break;
            case 'requestapproval':
                $this->requestApproval($c, $d);
                break;
            case 'propertyView':
                $this->propertyView($c, $d);
                break;
            case 'confirmAssign':
                $this->confirmAssign($c, $d);
                break;
            case 'propertyDetails':
                $this->propertyDetails($c, $d);
                break;
            case 'deleteView':
                $this->deleteView($c, $d);
                break;
            case 'propertyDeleteConfirmation':
                $this->propertyDeleteConfirmation($c, $d);
                break;
            default:
                $this->view('manager/propertymanagement');
                break;
        }
    }

    public function monthlyReport()
    {
        $rentalPaymentModel = new RentalPayment();
        $sharePaymentModel = new SharePayment();
        $servicePaymentModel = new ServicePayment();
        $salaryPaymentModel = new SalaryPayment();
        $ledgerModel = new Ledger();

        // Get current month and year
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Helper function to filter by created_at date (assuming you have a created_at field)
        $filterCurrentMonth = function ($records) use ($currentMonth, $currentYear) {
            return array_filter($records, function ($record) use ($currentMonth, $currentYear) {
                if (!isset($record->created_at)) return false;
                $timestamp = strtotime($record->created_at);
                return date('m', $timestamp) == $currentMonth && date('Y', $timestamp) == $currentYear;
            });
        };

        // Fetch all necessary payment data and filter by current month
        $rentalPayments = $filterCurrentMonth($rentalPaymentModel->findAll() ?: []);
        $sharePayments = $filterCurrentMonth($sharePaymentModel->findAll() ?: []);
        $servicePayments = $filterCurrentMonth($servicePaymentModel->findAll() ?: []);
        $salaryPayments = $filterCurrentMonth($salaryPaymentModel->findAll() ?: []);
        $ledger = $filterCurrentMonth($ledgerModel->findAll() ?: []);

        // Pre-calculate totals
        $totalRentalPayments = array_sum(array_column($rentalPayments, 'amount'));
        $totalSharePayments = array_sum(array_column($sharePayments, 'amount'));
        $totalServicePayments = array_sum(array_column($servicePayments, 'amount'));
        $totalSalaryPayments = array_sum(array_column($salaryPayments, 'salary_amount'));

        // Calculate total advance and full payments separately
        $advancePayments = array_filter($sharePayments, function ($payment) {
            return $payment->payment_type == 'advance';
        });

        $fullPayments = array_filter($sharePayments, function ($payment) {
            return $payment->payment_type == 'full';
        });

        $totalAdvancePayments = array_sum(array_column($advancePayments, 'amount'));
        $totalFullPayments = array_sum(array_column($fullPayments, 'amount'));

        $this->view('manager/monthlyReport', [
            'rentalPayments' => $rentalPayments,
            'sharePayments' => $sharePayments,
            'servicePayments' => $servicePayments,
            'salaries' => $salaryPayments,
            'ledger' => $ledger,
            'totalRentalPayments' => $totalRentalPayments,
            'totalSharePayments' => $totalSharePayments,
            'totalServicePayments' => $totalServicePayments,
            'totalSalaryPayments' => $totalSalaryPayments,
            'totalAdvancePayments' => $totalAdvancePayments,
            'totalFullPayments' => $totalFullPayments
        ]);
    }


    public function propertyDeleteConfirmation($propertyID)
    {
        $propertyModel = new Property;
        $property = $propertyModel->first(['property_id' => $propertyID]);
        if (!$property) {
            $_SESSION['flash'] = [
                'msg' => "Property not found.",
                'type' => "error"
            ];
            redirect('dashboard/managementhome/propertymanagement/propertyDetails');
        } else {
            if ($property->status == 'Occupied') {
                $_SESSION['flash'] = [
                    'msg' => "Property is currently occupied. Cannot delete.",
                    'type' => "error"
                ];
                redirect('dashboard/managementhome/propertymanagement/propertyDetails');
            } elseif ($property->status == 'Active') {
                if ($property->purpose == 'Rent') {
                    $isDeleted = $propertyModel->update($propertyID, ['status' => 'Inactive'], 'property_id');
                    if ($isDeleted) {
                        $_SESSION['flash'] = [
                            'msg' => "Property marked as inactive successfully.",
                            'type' => "success"
                        ];
                        enqueueNotification('Property marked as inactive', 'Property has been marked as inactive.', '', 'Notification_grey');
                        enqueueNotification('Property Deleted!', 'Your ' . $property->name . ' Property has been marked as inactive.', '', 'Notification_red', $property->person_id);
                        redirect('dashboard/managementhome/propertymanagement/propertyDetails');
                    } else {
                        $_SESSION['flash'] = [
                            'msg' => "Failed to mark property as inactive. Please try again.",
                            'type' => "error"
                        ];
                        redirect('dashboard/managementhome/propertymanagement/propertyDetails');
                    }
                } elseif ($property->end_date < date('Y-m-d')) {
                    $isDeleted = $propertyModel->update($propertyID, ['status' => 'Inactive'], 'property_id');
                    if ($isDeleted) {
                        $_SESSION['flash'] = [
                            'msg' => "Property deleted successfully.",
                            'type' => "success"
                        ];
                        enqueueNotification('Property Deleted!', 'Your ' . $property->name . ' Property has been deleted.', '', 'Notification_red', $property->person_id);
                        enqueueNotification('Property marked as inactive', 'Property has been marked as inactive.', '', 'Notification_grey');
                        redirect('dashboard/managementhome/propertymanagement/propertyDetails');
                    } else {
                        $_SESSION['flash'] = [
                            'msg' => "Failed to delete property. Please try again.",
                            'type' => "error"
                        ];
                        redirect('dashboard/managementhome/propertymanagement/propertyDetails');
                    }
                } else {
                    $_SESSION['flash'] = [
                        'msg' => "Property is currently safeguard under the system. Cannot delete.",
                        'type' => "error"
                    ];
                    redirect('dashboard/managementhome/propertymanagement/propertyDetails');
                }
            }
        }
    }

    public function deleteView($propertyID)
    {
        $property = new PropertyConcat;
        $property = $property->first(['property_id' => $propertyID]);
        $this->view('manager/propertyDeleteConfirmation', ['property' => $property]);
    }

    public function propertyDetails()
    {
        $propertyModel = new PropertyConcat;
        $property = $propertyModel->findAll();
        $this->view('manager/propertyListing', ['property' => $property]);
    }

    public function propertyView($propertyID)
    {
        $property = new PropertyConcat;
        $property = $property->first(['property_id' => $propertyID]);
        $this->view('manager/propertyView', ['property' => $property]);
    }

    public function confirmAssign($propertyID, $agentID)
    {
        $property = new Property;
        $res_property = $property->update($propertyID, ['agent_id' => $agentID], 'property_id');

        $agentAssignment = new agentAssignment;
        $res_agent = $agentAssignment->insert([
            'property_id' => $propertyID,
            'agent_id' => $agentID,
            'property_status' => 'pending',
            'pre_inspection' => 'waiting',
        ]);

        if ($res_property && $res_agent) {
            $_SESSION['flash'] = [
                'msg' => "Property assigned to agent successfully!",
                'type' => "success"
            ];
            enqueueNotification('Property assigned', 'Property has been assigned to you!', ROOT . '/dashboard/property/propertyView/' . $propertyID, 'Notification_green', $agentID);
        } else {
            $_SESSION['flash'] = [
                'msg' => "Failed to assign property to agent. Please try again.",
                'type' => "error"
            ];
        }

        redirect('dashboard/managementhome/propertymanagement/assignagents');
    }

    private function employeeManagement()
    {
        $user = new User;

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "update_user") {
            $user->validate($_POST);
            $errors = $user->errors;

            if (isset($_POST['email'])) {
                $email = $_POST['email'];
                $availableUser = $user->first(['email' => $email]);
                if ($availableUser && $availableUser->pid != $_POST['pid']) {
                    $errors['email'] = "Email already exists. Use another one.";
                }
            }

            if (!empty($errors)) {
                $_SESSION['flash'] = [
                    'msg' => implode("<br>", $errors),
                    'type' => "error"
                ];
            } else {
                // Perform update
                if (isset($_POST['action'])) unset($_POST['action']);
                $updateStatus = $user->update($_POST['pid'], $_POST, 'pid');
                if ($updateStatus) {
                    $_SESSION['flash'] = [
                        'msg' => "User updated successfully!",
                        'type' => "success"
                    ];
                } else {
                    $_SESSION['flash'] = [
                        'msg' => "Failed to update user. Please try again.",
                        'type' => "error"
                    ];
                }
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "delete_user") {
            $pid = $_POST['pid'];
            // show($_POST);
            // die();
            $user->update($pid, ['AccountStatus' => 0], 'pid');
            $_SESSION['flash'] = [
                'msg' => "User deleted successfully!",
                'type' => "success"
            ];
            // $user->update();
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "block_user") {
            $pid = $_POST['pid'];
            // show($_POST);
            // die();
            $updateStatus = $user->update($pid, ['AccountStatus' => -1], 'pid');
            if ($updateStatus) {
                $_SESSION['flash'] = [
                    'msg' => "User Blocked successfully!",
                    'type' => "success"
                ];
            }
        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "unblock_user") {
            $pid = $_POST['pid'];
            // show($_POST);
            // die();
            $updateStatus = $user->update($pid, ['AccountStatus' => 0], 'pid');
            if ($updateStatus) {
                $_SESSION['flash'] = [
                    'msg' => "User Unblocked successfully!",
                    'type' => "success"
                ];
            }
        }

        $user->setLimit(7);

        $searchterm = $_GET['searchterm'] ?? "";
        $limit = $user->getLimit();
        $countWithTerms = $user->getTotalCountWhere([], [], $searchterm);

        $totalPages = ceil($countWithTerms / $limit);
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($currentPage - 1) * $limit; // Corrected offset calculation

        $user->setOffset($offset);
        $userlist = $user->where([], [], $searchterm);

        if (!empty($userlist)) {
            foreach ($userlist as $user) {
                unset($user->password); // Remove password from result
            }
        }

        $pagination = new Pagination($currentPage, $totalPages, 2);
        $paginationLinks = $pagination->generateLinks();

        $this->view('manager/employeeManagement', [
            'paginationLinks' => $paginationLinks,
            'userlist' => $userlist ?? [],
            'tot' => $totalPages
        ]);
    }

    public function requestApproval()
    {
        $property = new PropertyModelTemp;
        $requests = $property->where(['request_status' => 'pending']);
        $this->view('manager/requestApproval', ['requests' => $requests]);
        // $this->view('manager/requestApproval');
    }

    private function financialManagement($c = '', $d = '')
    {
        $this->view('manager/financemanagement');
    }

    public function employeeListing()
    {
        $agentModel = new Agent;
        $agents = $agentModel->where(['AccountStatus' => 1, 'assign_month' => date('Y-m')]);
        $this->view('manager/salary/employeeListing', ['agents' => $agents]);
    }

    public function salaryView($agentID)
    {
        $agentModel = new Agent;
        $agent = $agentModel->first(['pid' => $agentID]);
        $this->view('manager/salary/salaryView', ['agent' => $agent]);
    }

    public function payForOne($agentID)
    {
        $agentModel = new Agent;
        $agent = $agentModel->first(['pid' => $agentID, 'assign_month' => date('Y-m')]);
        if (!$agent) {
            $_SESSION['flash']['msg'] = "Agent not found to proceed with payment.";
            $_SESSION['flash']['type'] = "warning";
            redirect('dashboard/managementhome/employeeListing');
            return;
        }

        $salaryModel = new SalaryPayment;
        $isPaidAlready = $salaryModel->first(['employee_id' => $agentID, 'paid_month' => date('Y-m')]);
        if ($isPaidAlready) {
            $_SESSION['flash']['msg'] = "Agent ID " . $agentID . " has already been paid for this month!";
            $_SESSION['flash']['type'] = "warning";
            redirect('dashboard/managementhome/employeeListing');
            return;
        }

        $salaryAmount = AGENT_BASIC_SALARY + ($agent->property_count * AGENT_INCREMENT);

        $salaryDetails = [
            'employee_id'   => $agent->pid,
            'salary_amount' => $salaryAmount,
            'payment_date'  => date('Y-m-d'),
            'paid_month'    => date('Y-m'),
            'person_id'     => MANAGER_ID
        ];

        $isPaidSuccessfully = $salaryModel->insert($salaryDetails);

        if ($isPaidSuccessfully) {
            $_SESSION['flash']['msg'] = "Salary paid successfully for Agent ID - " . $agentID;
            $_SESSION['flash']['type'] = "success";

            // $ledgerModel = new Ledger;
            // $ledgerModel->insert([
            //     'transaction_type'   => 'salary_payment',
            //     'reference_id' => $agentID,
            //     'reference_type'  => 'employee',
            //     'amount' => $salaryAmount,
            //     'description' => "Salary paid to Agent ID - " . $agentID
            // ]);

            enqueueNotification(
                "Salary Payment Processed",
                "You successfully paid salary to Agent ID {$agentID} for " . date('F Y'),
                '',
                'Notification_green',
                MANAGER_ID
            );
            enqueueNotification(
                "Salary Received",
                "Your salary for " . date('F Y') . " has been credited. Amount: Rs {$salaryAmount}",
                ROOT . 'agent/salary/history',
                'Notification_green',
                $agentID
            );

            redirect('dashboard/managementhome/employeeListing');
            return;
        } else {
            $_SESSION['flash']['msg'] = "Failed to process salary payment for Agent ID - " . $agentID;
            $_SESSION['flash']['type'] = "error";
            redirect('dashboard/managementhome/employeeListing');
            return;
        }
    }


    public function payAll()
    {
        $agentModel = new Agent;
        $salaryModel = new SalaryPayment;

        // Get all agents for the current month who are ACTIVE
        $agents = $agentModel->where(['AccountStatus' => 1, 'assign_month' => date('Y-m')]);

        if (!$agents || !is_array($agents) || count($agents) == 0) {
            $_SESSION['flash']['msg'] = "No agents found to pay for this month.";
            $_SESSION['flash']['type'] = "warning";
            redirect('dashboard/managementhome/employeeListing');
            return;
        }

        $successCount = 0;
        $failureCount = 0;

        foreach ($agents as $agent) {
            // Check if already paid
            $isPaidAlready = $salaryModel->first(['employee_id' => $agent->pid, 'paid_month' => date('Y-m')]);
            if ($isPaidAlready) {
                continue; // skip if already paid
            }

            $salaryAmount = AGENT_BASIC_SALARY + ($agent->property_count * AGENT_INCREMENT);

            $salaryDetails = [
                'employee_id'   => $agent->pid,
                'salary_amount' => $salaryAmount,
                'payment_date'  => date('Y-m-d'),
                'paid_month'    => date('Y-m'),
                'person_id'     => MANAGER_ID
            ];

            $isPaidSuccessfully = $salaryModel->insert($salaryDetails);

            if ($isPaidSuccessfully) {
                $successCount++;

                // $ledgerModel = new Ledger;
                // $ledgerModel->insert([
                //     'transaction_type'   => 'salary_payment',
                //     'reference_id' => $agent->pid,
                //     'reference_type'  => 'employee',
                //     'amount' => $salaryAmount,
                //     'description' => "Salary paid to Agent ID - " . $agent->pid
                // ]);

                // Notify the Agent
                enqueueNotification(
                    "Salary Received",
                    "Your salary for " . date('F Y') . " has been credited. Amount: Rs {$salaryAmount}",
                    ROOT . 'agent/salary/history',
                    'Notification_green',
                    $agent->pid
                );
            } else {
                $failureCount++;
            }
        }

        // Notify Manager about the summary
        enqueueNotification(
            "Salary Payment Summary",
            "Salary payment completed: {$successCount} success, {$failureCount} failed for " . date('F Y') . ".",
            ROOT . 'dashboard/managementhome/employeeListing',
            $failureCount == 0 ? 'Notification_green' : 'Notification_red',
            MANAGER_ID
        );

        // Flash Message
        $_SESSION['flash']['msg'] = "Salary payment completed. Success: {$successCount}, Failed: {$failureCount}.";
        $_SESSION['flash']['type'] = $failureCount == 0 ? "success" : "warning";

        redirect('dashboard/managementhome/employeeListing');
    }


    public function salaryDetails($agentID) {}


    public function assignAgents()
    {
        $property = new Property;
        $properties = $property->where(['status' => 'pending', 'agent_id' => MANAGER_ID]);

        $agents = new User;
        $agents = $agents->where(['user_lvl' => 3, 'AccountStatus' => 1]);

        $this->view('manager/agentsToProperty', ['properties' => $properties, 'agents' => $agents]);
    }

    private function removeAgents()
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
                    'user_lvl' => 3
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
        $countWithTerms = $user->getTotalCountWhere(['AccountStatus' => -2, 'user_lvl' => 3], [], $searchterm);

        $totalPages = ceil($countWithTerms / $limit);
        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($currentPage - 1) * $limit;

        $user->setOffset($offset);
        $users = $user->where(['AccountStatus' => -2, 'user_lvl' => 3], [], $searchterm);

        $pagination = new Pagination($currentPage, $totalPages, 2);
        $paginationLinks = $pagination->generateLinks();

        if (!empty($users)) {
            foreach ($users as $user) {
                unset($user->password); // Remove password from result
            }
        }
        // show($users);
        // die;
        $this->view('manager/removeAgents', [
            'paginationLinks' => $paginationLinks,
            'users' => $users ?? [],
            'tot' => $totalPages
        ]);
    }

    public function agentManagement($b = '')
    {
        if ($b == 'addagent') {
            $this->addAgent();
        } else if ($b == 'removeagents') {
            $this->removeAgents();
        } else if ($b == 'approval') {
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
                redirect('dashboard/managementhome/agentmanagement');
            }
        } else {
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

                $old = $user->findByMultiplePids($pids, ['user_lvl' => 3]);

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

            $this->view('manager/agentManagement', [
                'paginationLinks' => $paginationLinks,
                'new' => $new ?? [],
                'old' => $old ?? [],
                'tot' => $totalPages
            ]);
        }
    }

    public function generateAgentReport()
    {
        // Include the library
        require_once __DIR__ . '/../library/HtmlToPdf.php';

        // Define HTML and CSS
        $html = "
        <h1>Agent Report</h1>
        <p>This is a sample report for agents.</p>
        <b>Generated on: " . date('Y-m-d H:i:s') . "</b>
        ";
        $css = "
        h1 { color: blue; font-size: 24px; }
        p { font-size: 14px; }
        b { font-weight: bold; }
        ";

        // Generate PDF
        $pdfGenerator = new HtmlToPdf($html, $css, 'output/agent_report.pdf');
        $pdfGenerator->generatePdf();

        // Redirect or display success message
        echo "Agent report generated successfully!";
    }

    public function contacts()
    {
        $randomMessages = new RandomMessage;
        $randomPerson = new RandomPerson;

        // Fetch all messages with status 1
        $messages = $randomMessages->where(['status' => 1], []);
        $groupedMessages = [];

        if (!empty($messages)) {
            foreach ($messages as $message) {
                // Fetch the person details for the message
                $personDetails = $randomPerson->first(['pid' => $message->pid]);
                $personDetails->pid = $message->pid;

                // Initialize the grouped messages for this person if not already done
                if (!isset($groupedMessages[$message->pid])) {
                    $groupedMessages[$message->pid] = (object) [
                        'personDetails' => $personDetails,
                        'messages' => '', // Use a string variable to store all messages
                        'count' => 0 // Initialize count variable
                    ];
                }
                if (substr_count($message->message, "\n") > 0) {
                    $groupedMessages[$message->pid]->count += substr_count($message->message, "\n");
                }
                // Append the message content to the string variable
                $groupedMessages[$message->pid]->messages .= $message->message . "\n";
                $groupedMessages[$message->pid]->count++; // Increment the count
            }
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['replyMessage'])) {
            $replyMessage = trim($_POST['emailMessage']);
            // show($_POST);
            if (empty($replyMessage) && $_POST['complete'] == 0) {
                $_SESSION['flash']['msg'] = "Reply Message should not be empty";
                $_SESSION['flash']['type'] = "error";
            } else {
                if (isset($_POST['email'])) {
                    $email = trim($_POST['email']);
                    $name = trim($_POST['name']) ?? 'User';


                    // $status = sendMail(
                    //     $email ,
                    //     'Primecare Respond', "
                    //     <div style=\"font-family: Arial, sans-serif; color: #333; padding: 20px;\">
                    //         <h1 style=\"color: #4CAF50;\">Reply to Your Query</h1>
                    //         <p>Hello, {$name}</p>
                    //         <p>Thank you for reaching out to us.</p>
                    //         <p>{$replyMessage}</p>
                    //         <p>If you have any further questions, please feel free to reply to this email.</p>
                    //         <br>
                    //         <p>Best regards,<br>PrimeCare Support Team</p>
                    //     </div>
                    // ");
                    // if(!$status['error']){
                    //     $_SESSION['flash']['msg'] = "Reply sent successfully!";
                    //     $_SESSION['flash']['type'] = "success";
                    // } else {
                    //     $_SESSION['flash']['msg'] = "Failed to send reply. Please try again.";
                    //     $_SESSION['flash']['type'] = "error";
                    // }
                }
                if (isset($_POST['complete']) && $_POST['complete'] == 1) {
                    // show($_POST);
                    // die;
                    $pid = $_POST['pid'];
                    $randomMessages->update($pid, ['status' => 0], 'pid');
                    $_SESSION['flash']['msg'] = "Message marked as complete.";
                    $_SESSION['flash']['type'] = "success";
                    redirect('dashboard/contacts');
                }
            }
        }

        // Pass the grouped messages to the view
        $this->view('manager/contacts', ['messages' => $groupedMessages]);
    }

    private function logout()
    {
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }

    public function comparePropertyUpdate($propertyID)
    {
        $property = new PropertyConcat;
        $propertyUpdate = new PropertyConcatTemp;
        $property = $property->first(['property_id' => $propertyID]);
        $propertyUpdate = $propertyUpdate->first(['property_id' => $propertyID]);
        $this->view('manager/comparePropertyUpdate', ['property' => $property, 'propertyUpdate' => $propertyUpdate]);
    }

    public function comparePropertyUpdateAccept($propertyID)
    {
        $property = new PropertyConcatTemp;
        $propertyUpdate = new PropertyConcat;
    }
}
