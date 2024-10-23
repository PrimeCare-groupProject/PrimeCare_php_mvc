<?php
defined('ROOTPATH') or exit('Access denied');

    class Owner{
        use controller;

        public function index(){
            $this->view('owner/dashboard');
        }

        public function dashboard(){
            $this->view('owner/dashboard');
        }

        public function maintenance(){
            $this->view('owner/maintenance');
        }

        public function financeReport(){
            $this->view('owner/financeReport');
        }

        public function tenants(){
            $this->view('owner/tenants');
        }

        public function addProperty(){
            $this->view('owner/addProperty');
        }

        public function propertyListing($a = '', $b = '', $c = '', $d = ''){
            if($a == 'addproperty'){
                $this->addProperty($b = '', $c = '', $d = '');
                return;
            }else if($a == 'propertyunit'){
                $this->propertyUnit($b = '', $c = '', $d = '');
                return;
            }
            $this->view('owner/propertyListing');
        }

        public function propertyUnit(){
            $this->view('owner/propertyUnit');
        }

        public function profile(){
           
            // Check if form is submitted via POST
            // if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //     // Get form data
            //     $firstName = $_POST['first-name'] ?? null;
            //     $lastName = $_POST['last-name'] ?? null;
            //     $email = $_POST['email'] ?? null;
            //     $contactNumber = $_POST['contact-number'] ?? null;
    
            //     // Check if profile picture is uploaded
            //     if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            //         // Handle the file upload
            //         $profilePicture = $_FILES['profile_picture'];
    
            //         // Define the target directory and file name
            //         $targetDir = 'uploads/profile_pictures/';
            //         $targetFile = $targetDir . basename($profilePicture['name']);
            //         $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
            //         // Validate file type (allowing only image files)
            //         $validExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            //         if (in_array($imageFileType, $validExtensions)) {
            //             // Move the uploaded file to the target directory
            //             if (move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
            //                 echo "Profile picture uploaded successfully!";
            //             } else {
            //                 echo "Error uploading profile picture.";
            //             }
            //         } else {
            //             echo "Invalid file type. Please upload an image file.";
            //         }
            //     }
    
            //     // Update user profile in the database
            //     // (Assume you have a User model to interact with the database)
            //     $userId = 1; // Replace with the actual user ID from the session or context
            //     $user = new User(); // Assume a User model exists
            //     $user->update($userId, [
            //         'first_name' => $firstName,
            //         'last_name' => $lastName,
            //         'email' => $email,
            //         'contact_number' => $contactNumber,
            //         'profile_picture' => $targetFile // Path to the uploaded profile picture
            //     ]);
    
            //     // Redirect or provide feedback
            //     header('Location: /profile');
            //     exit;
            // }else{
                $this->view('owner/profile');
            // }
        }
    }

    