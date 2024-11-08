<?php
defined('ROOTPATH') or exit('Access denied');

class Agent{
    use controller;

    public function index(){
        $this->view('agent/dashboard');
    }

    public function dashboard(){
        $this->view('agent/dashboard');
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

    public function expenses() {
        echo "User Expenses Section";
    }
    
    public function preInspection(){
        $this->view('agent/preInspection');
    }

    public function requestedTasks(){
        $service = new Service();
        $services = $service->where(['status' => 'Pending']);

        // Get available service providers (user_lvl = 2) with their image URLs
        $user = new User();
        $service_providers = $user->where(['user_lvl' => 2]);

        // Filter out service providers who already have 4 or more ongoing services
        $filtered_providers = [];
        foreach($service_providers as $provider) {
            $provider->image_url = $provider->image_url ?? 'Agent.png';
            
            // Count ongoing services for this provider
            $ongoing_count = $service->where([
                'service_provider_id' => $provider->pid,
                'status' => 'Ongoing'
            ]);
            
            // Convert ongoing_count to array if false (no services)
            $ongoing_count = $ongoing_count === false ? [] : $ongoing_count;
            
            // Add provider if they have less than 4 ongoing services
            if(count($ongoing_count) < 4) {
                $filtered_providers[] = $provider;
            }
        }

        $data = [
            'services' => $services,
            'service_providers' => $filtered_providers
        ];

        // Handle service provider assignment when accept button is pressed
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service_id'])) {
            $service_id = $_POST['service_id'];
            
            // Get the selected service provider from the dropdown
            $provider_id = $_POST['service_provider_select'];
            
            if($provider_id) {
                // Check again if provider hasn't exceeded limit
                $ongoing_services = $service->where([
                    'service_provider_id' => $provider_id,
                    'status' => 'Ongoing'
                ]);
                
                // Convert to empty array if false
                $ongoing_services = $ongoing_services === false ? [] : $ongoing_services;

                if(count($ongoing_services) >= 4) {
                    $_SESSION['error'] = "This service provider has reached their maximum service limit";
                } else {
                    // Update service with assigned provider and change status to Ongoing
                    $result = $service->update($service_id, [
                        'service_provider_id' => $provider_id,
                        'status' => 'Ongoing'
                    ], 'service_id');

                    if($result) {
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
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_service_id'])) {
            $service_id = $_POST['delete_service_id'];
            
            // Delete the service request
            $result = $service->delete($service_id, 'service_id');

            if($result) {
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

    public function taskManagemnt(){
        $this->view('agent/taskManagement');
    }

    public function manageBookings(){
        $this->view('agent/manageBookings');
    }

    public function problems(){
        $this->view('agent/problems');
    }

    public function payments(){
        $this->view('agent/payments');
    }

}
