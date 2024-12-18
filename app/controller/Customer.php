<?php
defined('ROOTPATH') or exit('Access denied');

class Customer
{
    use controller;

    public function index()
    {
        redirect('dashboard/profile');
        // $this->view('profile', [
        //     'user' => $_SESSION['user'],
        //     'errors' => $_SESSION['errors'] ?? [],
        //     'status' => $_SESSION['status'] ?? ''
        // ]);
    }

    public function dashboard()
    {
        $this->view('customer/dashboard', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function propertyUnit($propertyId)
    {
        // $property = new PropertyConcat;
        // $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        // //show($propertyUnit);

        // $this->view('customer/propertyUnit', ['property' => $propertyUnit]);
    }

    public function profile()
    {
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
            } else if (isset($_POST['logout'])) {
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

    private function handleProfileSubmission()
    {
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
            $_SESSION['errors'] = $errors;
            $_SESSION['status'] = $status;

            redirect('dashboard/profile');
            exit;

        }else{
            $user = new User();
            $availableUser = $user->first(['email' => $email]);
            if(isset($availableUser) && $availableUser->pid != $_SESSION['user']->pid){
                $errors[] = "Email already exists.";
                $_SESSION['errors'] = $errors;
                $_SESSION['status'] = $status;

                redirect('dashboard/profile');
                exit;
            }
        }
        if(!$user->validate($_POST)){
            $errors = [$user->errors['fname'] ?? $user->errors['lname'] ?? $user->errors['email'] ?? $user->errors['contact'] ?? []];
            $_SESSION['errors'] = $errors;
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

    private function logout()
    {
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }

    public function occupiedProperties()
    {
        $this->view('customer/occupiedProperties', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function search()
    {
        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'pending']);
        $this->view('customer/search', ['properties' => $properties]);
    }

    public function maintenanceRequests()
    {
        $this->view('customer/maintainanceRequest');
    }

    public function payments()
    {
        $this->view('customer/payments');
    }

    public function reportProblem()
    {
        $this->view('customer/reportProblem', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function leaveProperty($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        $this->view('customer/leaveProperty', ['property' => $propertyUnit]);
    }

    public function bookProperty($propertyId)
    {
        $property = new PropertyConcat;
        $owner = new User();
        $agent = new User();

        $owner = $owner->where(['pid' => $property->where(['property_id' => $propertyId])[0]->person_id])[0];
        //$agent = $agent->where(['pid' => $property->where(['property_id' => $propertyId])[0]->agent_id])[0];
        $agent = $agent->where(['pid' => 62])[0];
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        // show($propertyUnit);
        // show($owner);
        // show($agent);
        $this->view('customer/bookProperty', ['property' => $propertyUnit , 'owner' => $owner, 'agent' => $agent]);
    }

    public function repairListing(){
        $service = new Services;
        $services = $service->findAll();

        $this->view('customer/repairListing' , ['services' => $services]);
    }

    public function updateRole(){
        $services = new Services;
        $services = $services->findAll();
        $this->view('customer/updateRole', ['services' => $services]);
    }

    public function updateToOwner(){
        $user = new User();
        if($user->update($_SESSION['user']->pid, ['user_lvl' => '1'], 'pid')){
            $_SESSION['user']->user_lvl = '1';
            echo "Role updated successfully";
            redirect('dashboard');
            exit;
        }else{
            $_SESSION['errors'] = ['Failed to update role. Please try again.'];
            echo "Failed to update role. Please try again.";
            redirect('dashboard/updateRole');
            exit;
        }
    }

    public function updateToSerPro(){
    }
}
