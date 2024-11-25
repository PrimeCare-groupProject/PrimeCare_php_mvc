<?php
defined('ROOTPATH') or exit('Access denied');

class ServiceProvider {
    use controller;
    
    public function index() {
        $this->view('serviceprovider/dashboard');
    }

    public function dashboard() {
        $this->view('serviceprovider/dashboard');
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

    public function addLogs(){
        $this->view('serviceprovider/addLogs');
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
    
        // Construct the query conditions
        $conditions = [
            'service_provider_id' => $provider_id
        ];
    
        // Add status filter if provided
        if (isset($_GET['status']) && $_GET['status'] !== 'all') {
            $conditions['status'] = $_GET['status'];
        }
    
        // Get total count for pagination
        $allServices = $serviceLog->where($conditions); // Fetch all matching records
        $total_records = count($allServices); // Count the array
        $total_pages = ceil($total_records / $items_per_page);
    
        // Paginate the records
        $services = array_slice($allServices, $offset, $items_per_page); // Paginate manually
    
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
            'selected_status' => $_GET['status'] ?? 'all'
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
