<?php
defined('ROOTPATH') or exit('Access denied');

    class Owner{
        use controller;

        public function index(){
            $this->view('owner/dashboard',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function dashboard(){
            $this->view('owner/dashboard',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function maintenance(){
            $this->view('owner/maintenance',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function financeReport(){
            $this->view('owner/financeReport',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function tenants(){
            $this->view('owner/tenants',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function addProperty(){
            $this->view('owner/addProperty',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function propertyListing($a = '', $b = '', $c = '', $d = ''){
            if($a == 'addproperty'){
                $this->addProperty($b = '', $c = '', $d = '');
                return;
            }else if($a == 'propertyunit'){
                $this->propertyUnit($b = '', $c = '', $d = '');
                return;
            }
            $this->view('owner/propertyListing',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function propertyUnit(){
            $this->view('owner/propertyUnit',[
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);
        }

        public function profile(){
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                // $targetDir = ".." ."/.." . "/public" . "/assets" . "/images" . "/uploads" . "/profile_pictures" . "/";
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

            $this->view('owner/profile', [
                'user' => $_SESSION['user'],
                'errors' => $_SESSION['errors'] ?? [],
                'status' => $_SESSION['status'] ?? ''
            ]);

            // Clear session data after rendering the view
            unset($_SESSION['errors']);
            unset($_SESSION['status']);
        }

    }

    