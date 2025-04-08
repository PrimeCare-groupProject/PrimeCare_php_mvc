<!-- ManagerDashboard -->
<?php
defined('ROOTPATH') or exit('Access denied');

class Manager {
    use controller;
    public function index() {
        $this->view('manager/dashboard');
    }

    public function dashboard() {
        $this->view('manager/dashboard');
    }

    public function generateUsername($fname, $length = 10) {
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

    private function addAgent() {
        $user = new User();
        $payment_details = new PaymentDetails();
        $errors = [];
        // show($_POST);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errors = [];

            // Validate and sanitize personal details
            $email = filter_var($_POST['email'] ?? null, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $user->errors['email'] = "Invalid email format.";
            }
            // echo "checking if user exist <br>";
            $resultUser = $user->first(['email' => $email], []);
            if (($resultUser && !empty($resultUser->email))) {
                $errors['email'] = 'Email already exists';
                //update user class errors
                $user->errors['email'] = 'Email already exists';
                $this->view('manager/addAgent',[
                    'user' => $resultUser, 
                    'errors' => $user->errors, 
                    'message' => ''] ); // Re-render signup view with error
                return; // Exit if email exists
            }
            
            $contact = esc($_POST['contact'] ?? null);
            $fname = esc($_POST['fname'] ?? null);
            $lname = esc($_POST['lname'] ?? null);

            //generatepassword
            $password = bin2hex(random_bytes(4)); // Generates an 8-character password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $personalDetails = [
                'fname' => $fname,
                'lname' => $lname,
                'email' => $email,
                'contact' => $contact,
                'password' => $password, // Hash the password before saving
                'confirmPassword' =>$password,
                'user_lvl' => 3,
                'username' => $this->generateUsername($_POST['fname']),
            ];
            // echo "validating user details<br>";

            // Validate the form data
            if (!$user->validate($personalDetails)) {
                // show($user->errors);
                // echo "if2";
                $this->view('manager/addAgent',[
                    'user' => $resultUser, 
                    'errors' => $user->errors, 
                    'message' => '']); // Re-render signup view with errors
                return; // Exit if validation fails
            }
            // echo "user details validated:<br>";
            // show($user->errors);

            unset($personalDetails['confirmPassword']);
            $personalDetails['password'] = $hashedPassword;//set hashed password
            // echo "inserting user details<br>";
            $userStatus = $user->insert($personalDetails);

            if (!$userStatus) {
                // echo "user details insertion failed<br>"; 
                $errors['auth'] = "Failed to add agent. Please try again.";
                $this->view('manager/addAgent', [
                    'user' => $resultUser, 
                    'errors' => $user->errors, 
                    'message' => '']);
                return;
            }else{
                // echo "user details inserted<br>"; 
            }

            // Validate and sanitize bank details
            $cardName = esc($_POST['cardName'] ?? null);
            $accountNo = esc($_POST['accountNo'] ?? null);
            $branch = esc($_POST['branch'] ?? null);
            $bankName = esc($_POST['bankName'] ?? null);

            if (empty($user->errors) && $userStatus) {

                $userDetails = $user->where(['email' => $email]);
                $userId = $userDetails[0]->pid;
                // var_dump($userId);
                if ($userStatus) {
                     
                    $personalDetails['branch'] = 1;
                    $personalDetails['bank'] = 1;
                    // Save payment details
                    // echo "inserting payment details<br>";
                    $paymentDetailStatus = $payment_details->insert([
                        'card_name' => $cardName,
                        'account_no' => $accountNo,
                        'bank' => $bankName,
                        'branch' => $branch,
                        'pid' => $userId,
                    ]);
                    // var_dump($paymentDetailStatus);
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
                        }else{
                            $message = "Agent added successfully!. Failed to send email. Contact Agent at {$contact}";
                        }
                    } else {
                        $message = "Failed to add Agent Payement Details. Please try again.";
                    }

                    $this->view('manager/addAgent', [
                        'user' => $resultUser, 
                        'errors' => $user->errors, 
                        'message' => $message]);
                    return;
                } else {
                    $errors['auth'] = "Failed to add agent. Please try again.";
                    $this->view('manager/addAgent', [
                        'user' => $resultUser, 
                        'errors' => $user->errors, 
                        'message' => '']);
                    return;
                }
            }
            $errors['auth'] = "Failed to add agent. Please try again.";
            $this->view('manager/addAgent', [
                'user' => $resultUser, 
                'errors' => $user->errors, 
                'message' => '']);
            return;
        }
        

        $this->view('manager/addAgent');
        return;
    }
    

    public function profile(){
        $user = new User();
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
    

    public function managementHome($a = '', $b = '', $c = '', $d = ''){
        // echo $a . "<br>";
        // echo $b . "<br>";
        // echo $c . "<br>";
        switch($a){
            case 'propertymanagement':
                $this->propertyManagement($b,$c,$d);
                break;
            case 'employeemanagement':
                $this->employeeManagement();
                break;
            case 'agentmanagement':
                $this->agentManagement($b,$c,$d);
                break;
            case 'financemanagement':
                $this->financialManagement();
                break;
            default:
                $this->view('manager/managementHome');
                break;
        }
    }

    public function propertyManagement($b = '', $c = '', $d = ''){
        switch($b){
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
            default:
                $this->view('manager/propertymanagement');
                break;
        }
    }

    public function propertyView($propertyID){
        $property = new PropertyConcat;
        $property = $property->first(['property_id' => $propertyID]);
        $this->view('manager/propertyView' , ['property' => $property]);
    }

    public function confirmAssign($propertyID , $agentID){
        $property = new Property;
        $res_property = $property->update($propertyID, ['agent_id' => $agentID], 'property_id');

        $agentAssignment = new agentAssignment;
        $res_agent = $agentAssignment->insert([
            'property_id' => $propertyID,
            'agent_id' => $agentID,
            'property_status' => 'pending',
            'pre_inspection' => 'waiting',
        ]);

        if($res_property && $res_agent){
            $_SESSION['flash'] = [
                'msg' => "Property assigned to agent successfully!",
                'type' => "success"
            ];
        }else{
            $_SESSION['flash'] = [
                'msg' => "Failed to assign property to agent. Please try again.",
                'type' => "error"
            ];
        }

        $this->assignAgents();
    }

    private function employeeManagement(){
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

        }else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "delete_user") {
            $pid = $_POST['pid'];
            // show($_POST);
            // die();
            $user->update($pid, ['AccountStatus' => 0], 'pid');
            $_SESSION['flash'] = [
                'msg' => "User deleted successfully!",
                'type' => "success"
            ];
            // $user->update();
        }else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "block_user") {
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
        }else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['action']) && $_POST['action'] == "unblock_user") {
            $pid = $_POST['pid'];
            // show($_POST);
            // die();
            $updateStatus = $user->update($pid, ['AccountStatus' =>0], 'pid');
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


    public function requestApproval(){
        $property = new PropertyModelTemp;
        $requests = $property->where(['request_status' => 'pending']);
        $this->view('manager/requestApproval', ['requests' => $requests]);
        // $this->view('manager/requestApproval');
    }

    private function financialManagement(){
        $this->view('manager/financemanagement');
    }

    public function assignAgents(){
        $property = new Property;
        $properties = $property->where(['status' => 'pending' , 'agent_id' => 110]);

        $agents = new User;
        $agents = $agents->where(['user_lvl' => 3, 'AccountStatus' => 1]);

        $this->view('manager/agentsToProperty' , ['properties' => $properties , 'agents' => $agents]);
    }

    public function agentManagement($b = ''){
        if($b == 'addagent'){
            // $this->view('manager/addAgent');
            $this->addAgent();
        }else{
            $this->view('manager/agentManagement');
        }
    }

    public function contacts() {
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
                if(isset($_POST['email'])){
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
                if(isset($_POST['complete']) && $_POST['complete'] == 1){
                    // show($_POST);
                    // die;
                    $pid = $_POST['pid'];
                    $randomMessages->update($pid, ['status' => 0], 'pid');
                    $_SESSION['flash']['msg'] = "Message marked as complete.";
                    $_SESSION['flash']['type'] = "success";
                    redirect('manager/contacts');
                }
            }
        }
    
        // Pass the grouped messages to the view
        $this->view('manager/contacts', ['messages' => $groupedMessages]);
    }
    
    private function logout(){
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }

    public function comparePropertyUpdate($propertyID){
        $property = new PropertyConcat;
        $propertyUpdate = new PropertyConcatTemp;
        $property = $property->first(['property_id' => $propertyID]);
        $propertyUpdate = $propertyUpdate->first(['property_id' => $propertyID]);
        $this->view('manager/comparePropertyUpdate' , ['property' => $property , 'propertyUpdate' => $propertyUpdate]);
    }

    public function comparePropertyUpdateAccept($propertyID){
        $property = new PropertyConcatTemp;
        $propertyUpdate = new PropertyConcat;
       
    }
    
}
