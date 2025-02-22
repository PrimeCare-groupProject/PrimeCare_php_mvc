<?php
defined('ROOTPATH') or exit('Access denied');

class Owner
{
    use controller;

    private function propertyUnitOwner($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);

        $this->view('owner/propertyUnit', ['property' => $propertyUnit]);
    }

    public function index()
    {
        // $flash = [
        //     'msg' => "This is the message",
        //     'type' => "success"
        // ];
        // $_SESSION['flash'] = $flash;


        $this->view('owner/dashboard', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function dashboard()
    {
        $this->view('owner/dashboard', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'],
            'status' => $_SESSION['status']
        ]);
    }

    public function maintenance()
    {
        $this->view('owner/maintenance', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function financeReport()
    {
        $this->view('owner/financeReport', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function tenants()
    {
        $this->view('owner/tenants', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    private function addProperty()
    {
        $this->view('owner/addProperty', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    private function deletePropertyImage($propertyId)
    {
        $propertyImage = new PropertyImageModel;
        // Fetch all images associated with the property
        $images = $propertyImage->where(['property_id' => $propertyId]);
        show($images);
        foreach ($images as $image) {
            $imagePath = ROOTPATH . "public/assets/images/uploads/property_images/" . $image->image_url;
            show($imagePath);
            // Check if the file exists before attempting to delete
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }

    // delete
    private function deletePropertyDocument($propertyId)
    {
        $propertyDoc = new PropertyDocModel;
        // Fetch all documents associated with the property
        $docs = $propertyDoc->where(['property_id' => $propertyId])[0];
        $docPath = ROOTPATH . "public/assets/documents/property_docs/" . $docs->image_url;
        show($docPath);
        if (file_exists($docPath)) {
            unlink($docPath);
        }
    }

    public function delete($propertyId)
    {
        $property = new PropertyModel;

        // Delete the property images
        $this->deletePropertyImage($propertyId);

        // Delete the property documents
        $this->deletePropertyDocument($propertyId);

        // Delete the property
        $property->delete($propertyId, 'property_id');

        $_SESSION['status'] = 'Property deleted successfully!';

        // Redirect to the property listing page
        redirect('dashboard/propertyListing');
    }

    private function updateProperty($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);
        $this->view('owner/updateProperty', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyUnit
        ]);
    }

    public function propertyListing($a = '', $b = '', $c = '', $d = '')
    {
        if ($a == 'addproperty') {
            $this->addProperty($b = '', $c = '', $d = '');
            return;
            // } else if ($a == 'propertyunit') {
            //     $this->propertyUnit($b = '', $c = '', $d = '');
            //     return;
        } else if ($a == "repairlisting") {
            $this->repairListing($b = '', $c = '', $d = '');
            return;
        } else if ($a == "servicerequest") {
            $this->serviceRequest($b = '', $c = '', $d = '');
            return;
        } else if ($a == "updateproperty") {
            $this->updateProperty($propertyId = $b);
            return;
        } else if ($a == "propertyunitowner") {
            $this->propertyUnitOwner($propertyId = $b);
            return;
        } else if ($a == "financialreportunit") {
            $this->financialReportUnit($propertyId = $b);
            return;
        } else if ($a == "reportproblem") {
            $this->reportProblem($propertyId = $b);
            return;
        } else if ($a == "dropProperty") {
            $this->dropProperty($propertyId = $b);
            return;
        } else if ($a == "create") {
            $this->create($propertyId = $b);
            return;
        }

        //if property listing is being called
        $property = new PropertyConcat;
        $properties = $property->where(['person_id' => $_SESSION['user']->pid]);

        $this->view('owner/propertyListing', ['properties' => $properties]);
    }


    public function propertyUnit($propertyId)
    {

        $property = new PropertyModel; // Initialize Property instance



        $this->view('owner/propertyUnit', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            $property
        ]);
    }

    private function repairListing()
    {
        $this->view('owner/repairListing', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function serviceRequest($type = '')
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle form submission here
            $data = [
                'service_type' => $_POST['service_type'],
                'date' => $_POST['date'],
                'property_id' => $_POST['property_id'], // This will now be the actual property ID
                'property_name' => $_POST['property_name'],
                'status' => $_POST['status'],
                'service_description' => $_POST['service_description'],
                'cost_per_hour' => $_POST['cost_per_hour']
            ];

            // Add service request to database
            $service = new ServiceLog();
            if ($service->insert($data)) {
                $_SESSION['status'] = "Service request submitted successfully";
                $_SESSION['success_message'] = "Request sent successfully!";
            } else {
                $_SESSION['errors'][] = "Failed to submit service request";
            }

            redirect('dashboard/serviceRequest');
            return;
        }

        $this->view('owner/serviceRequest', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'success_message' => $_SESSION['success_message'] ?? '',
            'type' => $type
        ]);

        // Clear session messages after displaying
        unset($_SESSION['success_message']);
        unset($_SESSION['errors']);
        unset($_SESSION['status']);
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

    private function reportProblem()
    {
        $this->view('owner/reportProblem', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    private function financialReportUnit()
    {
        $this->view('owner/financialReportUnit', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function showReviews()
    {
        $this->view('owner/showReviews', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function create()
    {
        $property = new PropertyModel;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the form data
            if (!$property->validateProperty($_POST)) {
                $this->view('owner/addProperty', ['property' => $property]);
                return;
            }

            // Prepare property data for insertion
            $arr = [
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'description' => $_POST['description'],
                'address' => $_POST['address'],
                'zipcode' => $_POST['zipcode'],
                'city' => $_POST['city'],
                'state_province' => $_POST['state_province'],
                'country' => $_POST['country'],
                'year_built' => $_POST['year_built'],
                'rent_on_basis' => $_POST['rent_on_basis'] ?? 0,
                'units' => $_POST['units'] ?? 0,
                'size_sqr_ft' => $_POST['size_sqr_ft'],
                'bedrooms' => $_POST['bedrooms'] ?? 0,
                'bathrooms' => $_POST['bathrooms'] ?? 0,
                'floor_plan' => $_POST['floor_plan'],
                'parking' => $_POST['parking'] ?? 'no',
                'furnished' => $_POST['furnished'] ?? 'no',
                'status' => $_POST['status'] ?? 'pending',
                'person_id' => $_SESSION['user']->pid
            ];

            // Insert property data into the database
            $res = $property->insert($arr);

            if ($res) {
                // Get the ID of the last inserted property
                $propertyId = $property->where(['name' => $_POST['name'], 'address' => $_POST['address']])[0]->property_id;

                // Upload images using the generic function
                $imageErrors = upload_image(
                    $_FILES['property_images'],
                    ROOTPATH . 'public/assets/images/uploads/property_images/',
                    new PropertyImageModel(),
                    $propertyId,
                    [
                        'allowed_ext' => ['jpg', 'jpeg', 'png'],
                        'prefix' => 'property',
                        'url_field' => 'image_url',
                        'fk_field' => 'property_id'
                    ]
                );

                // Upload documents using the same function with different config
                $documentErrors = upload_image(
                    $_FILES['property_documents'],
                    ROOTPATH . 'public/assets/documents/uploads/property_documents/',
                    new PropertyDocModel(),
                    $propertyId,
                    [
                        'allowed_ext' => ['pdf', 'docx', 'txt'],
                        'prefix' => 'doc',
                        'url_field' => 'document_path',
                        'fk_field' => 'property_id',
                        'max_size' => 10 * 1024 * 1024 // 10MB
                    ]
                );

                // Check for any upload errors
                if (!empty($imageErrors) || !empty($documentErrors)) {
                    $property->errors['media'] = array_merge($imageErrors, $documentErrors);
                    $_SESSION['flash']['msg'] = "Property added failed!";
                    $_SESSION['flash']['type'] = "error";
                    $this->view('owner/addProperty', ['property' => $property]);
                    return;
                }

                // Redirect on success
                $_SESSION['flash']['msg'] = "Property added successfully!";
                $_SESSION['flash']['type'] = "success";
                redirect('property/propertyListing');
            } else {
                $property->errors['insert'] = 'Failed to add Property. Please try again.';
                $this->view('property/propertyListing', ['property' => $property]);
            }
        } else {
            $this->view('property/propertyListing', ['property' => $property]);
        }
    }

    // drop property
    public function dropProperty($propertyId)
    {
        $property = new PropertyModel;
        if ($property->update($propertyId, ['status' => 'inactive'], 'property_id')) {
            $_SESSION['flash']['msg'] = 'Property dropped successfully!';
            $_SESSION['flash']['type'] = 'success';
        } else {
            $_SESSION['flash']['msg'] = 'Failed to drop property. Please try again.';
            $_SESSION['flash']['type'] = 'error';
        }
        // Redirect to the property listing page
        redirect('property/propertyListing');
    }
}
