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

    private function addAgent(){
        $user = new User();
        $payment_details = new PaymentDetails();

        
        $this->view('manager/addAgent');
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
        $this->view('manager/requestApproval');
    }

    public function financialManagement(){
        $this->view('manager/financialManagement');
    }

    public function assignAgents(){
        $this->view('manager/assignagents');
    }

    public function agentManagement($b = ''){
        if($b == 'addagent'){
            $this->view('manager/addAgent');
            // $this->addAgent();
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
    
}
