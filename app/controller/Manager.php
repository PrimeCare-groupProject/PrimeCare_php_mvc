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
                'user_lvl' => 2,
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
                $userId = $_SESSION['user']->pid; // Replace with actual user ID from session
    
                // Delete the user from the database
                $user = new User();
                $deleted = $user->delete($userId, 'pid'); // Implement a delete method in your User model
    
                if ($deleted) {
                    // Delete the user's profile picture if it exists
                    $profilePicturePath = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR . $_SESSION['user']->image_url;
                    if (!empty($_SESSION['user']->image_url) && file_exists($profilePicturePath)) {
                        unlink($profilePicturePath);
                    }
    
                    // Clear the user session data
                    session_unset();
                    session_destroy();
    
                    // Redirect to the home page or login page
                    redirect('home');
                    exit;
                } else {
                    $errors[] = "Failed to delete account. Please try again.";
                }
    
                // Store errors in session and redirect back
                $_SESSION['errors'] = $errors;
                redirect('dashboard/profile');
                exit;
            }else if(isset($_POST['logout'])){
                $this->logout();
            }
        $this->handleProfileSubmission();
        return;
        }

        $this->view('profile', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);

        // Clear session data after rendering the view
        unset($_SESSION['errors']);
        unset($_SESSION['status']);
    }

    private function handleProfileSubmission() {
        $errors = [];
        $status = '';

        // Get form data and sanitize inputs
        $firstName = esc($_POST['fname'] ?? null);
        $lastName = esc($_POST['lname'] ?? null);
        $email = filter_var($_POST['email'] ?? null, FILTER_VALIDATE_EMAIL);
        $contactNumber = esc($_POST['contact'] ?? null);

        // Validate email
        if (!$email) {
        $errors[] = "Invalid email format.";
        }

        // Check if profile picture is uploaded
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $profilePicture = $_FILES['profile_picture'];

        // Define the target directory and create it if it doesn't exist
        $targetDir = ".." .DIRECTORY_SEPARATOR. "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "profile_pictures" . DIRECTORY_SEPARATOR;
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        // Define the target file name using uniqid()
        $imageFileType = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
        $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $validExtensions)) {
            $targetFile = uniqid() . "__" .  $email. '.' . $imageFileType;

            // Move the uploaded file
            if (move_uploaded_file($profilePicture['tmp_name'], $targetDir.$targetFile)) {
            $status = "Profile picture uploaded successfully!".$targetFile;
            } else {
            $errors[] = "Error uploading profile picture. Try changing the file name." . $profilePicture['tmp_name'] . $targetFile;
            }
        } else {
            $errors[] = "Invalid file type. Please upload an image file.";
        }
        }

        // Update user profile in the database
        if (empty($errors) && $_SESSION['user']->pid) {
        $userId = $_SESSION['user']->pid; // Replace with actual user ID from session or context
        $user = new User();
        $updated = $user->update($userId, [
            'fname' => $firstName,
            'lname' => $lastName,
            'email' => $email,
            'contact' => $contactNumber,
            'image_url' => $targetFile ?? null
        ], 'pid');
        
        if ($updated) {
            // Delete old profile picture if a new one is uploaded
            if (isset($targetFile) && !empty($_SESSION['user']->image_url)) {
            $oldPicPath = $targetDir . $_SESSION['user']->image_url;
            if (file_exists($oldPicPath)) {
                unlink($oldPicPath);
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
        $_SESSION['errors'] = $errors;
        $_SESSION['status'] = $status;

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
            case 'financialmanagement':
                $this->financialManagement();
                break;
            default:
                $this->view('manager/managementHome');
                break;
        }
    }

    public function propertyManagement($b = '', $c = '', $d = ''){
        // echo $b . "<br>";
        // echo $c . "<br>";
        // echo $d . "<br>";
        // show(URL(3));
        switch($b){
            case 'assignagents':
                $this->assignAgents($c, $d);
                break;
            case 'requestapproval':
                $this->requestApproval($c, $d);
                break;
            default:
                $this->view('manager/propertymanagement');
                break;
        }
    }

    private function employeeManagement(){
        $user = new User;
        $user->setLimit(7); //set limit before anything

        $searchterm = isset($_GET['searchterm']) ? $_GET['searchterm'] : "";
        $limit = $user->getLimit();
        $countWithTerms = $user->getTotalCountWhere([], [], $searchterm);

        $totalPages =  ceil( $countWithTerms / $limit ) ; 

        $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($limit - 1) * ($currentPage-1 ); #generate offset
        $user->setOffset($offset); //set offset after limit

        $userlist = $user->where([], [], $searchterm);//get the details
        
        if (isset($userlist) && is_array($userlist) && count($userlist) > 0) {
            foreach($userlist as $user){//filter out pasword
                unset($user->password);
            }
        }
        // Instantiate the Pagination class with the current page, total pages, and range
        $pagination = new Pagination($currentPage, $totalPages, 2); 
        $paginationLinks = $pagination->generateLinks();    // Generate pagination links
        // Pass pagination links to the view
        $this->view('manager/employeeManagement',['paginationLinks' => $paginationLinks, 'userlist' => $userlist ? $userlist : [], 'tot' => $totalPages]);
    }

    public function requestApproval(){
        $property = new PropertyModelTemp;
        $requests = $property->where(['request_type' => 'update']);
        $this->view('manager/requestApproval', ['requests' => $requests]);
        // $this->view('manager/requestApproval');
    }

    public function financialManagement(){
        $this->view('manager/financeManagement');
    }

    public function assignAgents(){
        $property = new PropertyModel;
        $properties = $property->where(['status' => 'pending']);

        $this->view('manager/assignagents' , ['properties' => $properties]);
    }

    public function agentManagement($b = ''){
        if($b == 'addagent'){
            // $this->view('manager/addAgent');
            $this->addAgent();
        }else{
            $this->view('manager/agentManagement');
        }
    }

    public function contacts(){
        $this->view('manager/contacts');
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
    
}
