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

        $agent = new User;
        $agentDetails = $agent->where(['pid' => $propertyUnit->agent_id])[0];

        $this->view('owner/propertyUnitShowing', ['property' => $propertyUnit, 'agent' => $agentDetails]);
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
        $this->view('owner/editPropertyEnh', [
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
        } else if ($a == "update") {
            $this->update($propertyId = $b);
            return;
        } else if ($a == "deleteView") {
            $this->deleteView($propertyId = $b);
            return;
        } else if ($a == "deleteRequest") {
            $this->deleteRequest($propertyId = $b);
            return;
        } else if ($a == "review") {
            $this->reviewUnit($propertyId = $b);
            return;
        }

        //if property listing is being called
        $property = new PropertyConcat;
        $properties = $property->where(['person_id' => $_SESSION['user']->pid], ['status' => 'Inactive']);

        $this->view('owner/propertyListing', ['properties' => $properties]);
    }

    public function reviewUnit($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];

        $this->view('owner/singleReview', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyUnit
        ]);
    }

    public function deleteView($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];

        $this->view('owner/deleteProperty', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'property' => $propertyUnit
        ]);
    }


    // public function propertyUnit($propertyId)
    // {
    //     $property = new PropertyConcat;
    //     $propertyUnit = $property->where(['property_id' => $propertyId])[0];

    //     $this->view('owner/propertyUnitShowing', [
    //         'user' => $_SESSION['user'],
    //         'errors' => $_SESSION['errors'] ?? [],
    //         'status' => $_SESSION['status'] ?? '',
    //         $property
    //     ]);
    // }

    private function repairListing()
    {
        // Instantiate the Services model and fetch all services
        $servicesModel = new Services();
        $services = $servicesModel->getAllServices();
        
        // Check and fix image paths for each service
        if (!empty($services)) {
            foreach ($services as $key => $service) {
                // If service_img is empty, skip
                if (empty($service->service_img)) {
                    continue;
                }
                
                // Check if the image exists in the specified location
                $imagePath = ROOTPATH . 'public/assets/images/repairimages/' . $service->service_img;
                if (!file_exists($imagePath)) {
                    // Try to find the image with common image extensions
                    $found = false;
                    $extensions = ['jpg', 'jpeg', 'png', 'gif'];
                    $baseName = pathinfo($service->service_img, PATHINFO_FILENAME);
                    
                    foreach ($extensions as $ext) {
                        $testPath = ROOTPATH . 'public/assets/images/repairimages/' . $baseName . '.' . $ext;
                        if (file_exists($testPath)) {
                            $services[$key]->service_img = $baseName . '.' . $ext;
                            $found = true;
                            break;
                        }
                    }
                    
                    // If still not found, set to empty to use placeholder
                    if (!$found) {
                        $services[$key]->service_img = '';
                    }
                }
            }
        }
        
        $this->view('owner/repairListing', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? '',
            'services' => $services  // Pass the services data to the view
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
        } else {
            $user = new User();
            $availableUser = $user->first(['email' => $email]);
            if (isset($availableUser) && $availableUser->pid != $_SESSION['user']->pid) {
                $errors[] = "Email already exists.";
                $_SESSION['errors'] = $errors;
                $_SESSION['status'] = $status;

                redirect('dashboard/profile');
                exit;
            }
        }
        if (!$user->validate($_POST)) {
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

    // public function create()
    // {
    //     $property = new PropertyModel;

    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         // Validate the form data
    //         if (!$property->validateProperty($_POST)) {
    //             $this->view('owner/addProperty', ['property' => $property]);
    //             return;
    //         }

    //         // Prepare property data for insertion
    //         $arr = [
    //             'name' => $_POST['name'],
    //             'type' => $_POST['type'],
    //             'description' => $_POST['description'],
    //             'address' => $_POST['address'],
    //             'zipcode' => $_POST['zipcode'],
    //             'city' => $_POST['city'],
    //             'state_province' => $_POST['state_province'],
    //             'country' => $_POST['country'],
    //             'year_built' => $_POST['year_built'],
    //             'rent_on_basis' => $_POST['rent_on_basis'] ?? 0,
    //             'units' => $_POST['units'] ?? 0,
    //             'size_sqr_ft' => $_POST['size_sqr_ft'],
    //             'bedrooms' => $_POST['bedrooms'] ?? 0,
    //             'bathrooms' => $_POST['bathrooms'] ?? 0,
    //             'floor_plan' => $_POST['floor_plan'],
    //             'parking' => $_POST['parking'] ?? 'no',
    //             'furnished' => $_POST['furnished'] ?? 'no',
    //             'status' => $_POST['status'] ?? 'pending',
    //             'person_id' => $_SESSION['user']->pid
    //         ];

    //         // Insert property data into the database
    //         $res = $property->insert($arr);

    //         if ($res) {
    //             // Get the ID of the last inserted property
    //             $propertyId = $property->where(['name' => $_POST['name'], 'address' => $_POST['address']])[0]->property_id;

    //             // Upload images using the generic function
    //             $imageErrors = upload_image(
    //                 $_FILES['property_images'],
    //                 ROOTPATH . 'public/assets/images/uploads/property_images/',
    //                 new PropertyImageModel(),
    //                 $propertyId,
    //                 [
    //                     'allowed_ext' => ['jpg', 'jpeg', 'png'],
    //                     'prefix' => 'property',
    //                     'url_field' => 'image_url',
    //                     'fk_field' => 'property_id'
    //                 ]
    //             );

    //             // Upload documents using the same function with different config
    //             $documentErrors = upload_image(
    //                 $_FILES['property_documents'],
    //                 ROOTPATH . 'public/assets/documents/uploads/property_documents/',
    //                 new PropertyDocModel(),
    //                 $propertyId,
    //                 [
    //                     'allowed_ext' => ['pdf', 'docx', 'txt'],
    //                     'prefix' => 'doc',
    //                     'url_field' => 'document_path',
    //                     'fk_field' => 'property_id',
    //                     'max_size' => 10 * 1024 * 1024 // 10MB
    //                 ]
    //             );

    //             // Check for any upload errors
    //             if (!empty($imageErrors) || !empty($documentErrors)) {
    //                 $property->errors['media'] = array_merge($imageErrors, $documentErrors);
    //                 $_SESSION['flash']['msg'] = "Property added failed!";
    //                 $_SESSION['flash']['type'] = "error";
    //                 $this->view('owner/addProperty', ['property' => $property]);
    //                 return;
    //             }

    //             // Redirect on success
    //             $_SESSION['flash']['msg'] = "Property added successfully!";
    //             $_SESSION['flash']['type'] = "success";
    //             redirect('property/propertyListing');
    //         } else {
    //             $property->errors['insert'] = 'Failed to add Property. Please try again.';
    //             $this->view('property/propertyListing', ['property' => $property]);
    //         }
    //     } else {
    //         $this->view('property/propertyListing', ['property' => $property]);
    //     }
    // }

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



    // dont touch this..final create function for properties
    public function create()
    {
        $property = new Property;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the form data
            if (!$property->validateProperty($_POST)) {
                $this->view('owner/addProperty', ['property' => $property]);
                return;
            }

            // Set default values
            $purpose = $_POST['purpose'] ?? 'Rent';
            $rental_period = $_POST['rental_period'] ?? 'Daily';
            $start_date = $_POST['start_date'] ?? date('Y-m-d');
            $end_date = $_POST['end_date'] ?? date('Y-m-d', strtotime('+7 days', strtotime($start_date)));

            // Calculate the rental price based on the purpose
            if ($purpose == 'Rent') {
                $rental_price = $_POST['rental_price'] ?? 0; // Assuming the rental price is directly posted for "Rent"
            } else {
                // Calculate duration in days for Safeguard/Vacation Rental purposes
                $start_timestamp = strtotime($start_date);
                $end_timestamp = strtotime($end_date);

                if ($start_timestamp && $end_timestamp) {
                    // Ensure end date is greater than start date
                    $duration_in_days = ($end_timestamp - $start_timestamp) / (60 * 60 * 24); // Duration in days

                    // If the end date is before the start date, set an error or fallback value
                    if ($duration_in_days <= 0) {
                        $duration_in_days = 1; // Minimum duration of 1 day
                    }

                    // Calculate rental price based on duration (using the constant RENTAL_PRICE)
                    $rental_price = $duration_in_days * RENTAL_PRICE;
                } else {
                    $rental_price = 0; // Default to 0 if invalid dates
                }
            }

            // Prepare property data for insertion
            $arr = [
                'name' => $_POST['name'],
                'type' => $_POST['type'] ?? 'House',
                'description' => $_POST['description'] ?? '',

                'address' => $_POST['address'] ?? '',
                'zipcode' => $_POST['zipcode'] ?? '',
                'city' => $_POST['city'] ?? '',
                'state_province' => $_POST['state_province'] ?? '',
                'country' => $_POST['country'] ?? 'Sri Lanka',

                'year_built' => $_POST['year_built'] ?? 2025,
                'size_sqr_ft' => $_POST['size_sqr_ft'] ?? 0,
                'number_of_floors' => $_POST['number_of_floors'] ?? 0,
                'floor_plan' => $_POST['floor_plan'] ?? 'no',

                'units' => $_POST['units'] ?? 0,
                'bedrooms' => $_POST['bedrooms'] ?? 0,
                'bathrooms' => $_POST['bathrooms'] ?? 0,
                'kitchen' => $_POST['kitchen'] ?? 'no',
                'living_room' => $_POST['living_room'] ?? 'no',

                'furnished' => $_POST['furnished'] ?? 'no',
                'furniture_description' => $_POST['furniture_description'] ?? '',

                'parking' => $_POST['parking'] ?? 'no',
                'parking_slots' => $_POST['parking_slots'] ?? 0,
                'type_of_parking' => $_POST['type_of_parking'] ?? 'none',

                'utilities_included' => isset($_POST['utilities_included']) && is_array($_POST['utilities_included']) ? implode(',', $_POST['utilities_included']) : ($_POST['utilities_included'] ?? ''),
                'additional_utilities' => isset($_POST['additional_utilities']) && is_array($_POST['additional_utilities']) ? implode(',', $_POST['additional_utilities']) : ($_POST['additional_utilities'] ?? ''),
                'additional_amenities' => isset($_POST['additional_amenities']) && is_array($_POST['additional_amenities']) ? implode(',', $_POST['additional_amenities']) : ($_POST['additional_amenities'] ?? ''),
                'security_features' => isset($_POST['security_features']) && is_array($_POST['security_features']) ? implode(',', $_POST['security_features']) : ($_POST['security_features'] ?? ''),

                'purpose' => $purpose,
                'rental_period' => $rental_period,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'rental_price' => $rental_price,

                'owner_name' => $_POST['owner_name'],
                'owner_email' => $_POST['owner_email'],
                'owner_phone' => $_POST['owner_phone'] ?? '',
                'additional_contact' => $_POST['additional_contact'],

                'special_instructions' => isset($_POST['special_instructions']) && is_array($_POST['special_instructions']) ? implode(',', $_POST['special_instructions']) : ($_POST['special_instructions'] ?? ''),
                'legal_details' => isset($_POST['legal_details']) && is_array($_POST['legal_details']) ? implode(',', $_POST['legal_details']) : ($_POST['legal_details'] ?? ''),

                'status' => 'pending',
                'person_id' => $_SESSION['user']->pid,
                'agent_id' => 110,
                'duration' => $_POST['duration'] ?? 1
            ];

            // Insert property data into the database
            //$res = $property->insert($arr);

            // Debugging: Check if the data is prepared correctly
            //error_log("Prepared Data: " . print_r($arr, true));

            $res = $property->insert($arr);

            // if (!$res) {
            //     error_log("Insert function failed!");
            // }

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
                        'url_field' => 'image_url',
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

    public function updatePending($data, $propertyId)
    {
        $property = new Property;
        $res = $property->update($propertyId, $data, 'property_id');
        if ($res) {
            $_SESSION['flash']['msg'] = "Property updated successfully!";
            $_SESSION['flash']['type'] = "success";
        } else {
            $_SESSION['flash']['msg'] = "Failed to update property. Please try again.";
            $_SESSION['flash']['type'] = "error";
        }
        // Redirect to the property listing page
        redirect('property/propertyListing');
    }

    public function update($propertyId)
    {
        $property = new PropertyModelTemp;
        $beforeDetails = new Property;
        $beforeDetails = $beforeDetails->where(['property_id' => $propertyId])[0];

        // Helper to safely implode or fallback
        $safeImplode = function ($value) {
            if (is_array($value)) {
                return implode(',', $value);
            } elseif (is_string($value)) {
                return $value; // Assume already comma-separated
            }
            return '';
        };

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$property->validateProperty($_POST)) {
                $_SESSION['flash']['msg'] = "Update Validation failed!";
                $_SESSION['flash']['type'] = "error";
                $this->view('owner/editPropertyEnh', ['property' => $beforeDetails]);
                return;
            }

            $purpose = $_POST['purpose'] ?? $beforeDetails->purpose;
            $rental_period = $_POST['rental_period'] ?? $beforeDetails->rental_period;
            $start_date = $_POST['start_date'] ?? $beforeDetails->start_date;
            $end_date = $_POST['end_date'] ?? $beforeDetails->end_date;

            if ($purpose == 'Rent') {
                $rental_price = $_POST['rental_price'] ?? $beforeDetails->rental_price;
            } else {
                $start_timestamp = strtotime($start_date);
                $end_timestamp = strtotime($end_date);
                $duration_in_days = ($start_timestamp && $end_timestamp) ? max(1, ($end_timestamp - $start_timestamp) / (60 * 60 * 24)) : 1;
                $rental_price = $duration_in_days * RENTAL_PRICE;
            }

            $arr = [
                'property_id' => $propertyId,
                'name' => $_POST['name'] ?? $beforeDetails->name,
                'type' => $_POST['type'] ?? $beforeDetails->type,
                'description' => $_POST['description'] ?? $beforeDetails->description,
                'address' => $_POST['address'] ?? $beforeDetails->address,
                'zipcode' => $_POST['zipcode'] ?? $beforeDetails->zipcode,
                'city' => $_POST['city'] ?? $beforeDetails->city,
                'state_province' => $_POST['state_province'] ?? $beforeDetails->state_province,
                'country' => $_POST['country'] ?? $beforeDetails->country,

                'year_built' => $_POST['year_built'] ?? $beforeDetails->year_built,
                'size_sqr_ft' => $_POST['size_sqr_ft'] ?? $beforeDetails->size_sqr_ft,
                'number_of_floors' => $_POST['number_of_floors'] ?? $beforeDetails->number_of_floors,
                'floor_plan' => $_POST['floor_plan'] ?? $beforeDetails->floor_plan,

                'units' => $_POST['units'] ?? $beforeDetails->units,
                'bedrooms' => $_POST['bedrooms'] ?? $beforeDetails->bedrooms,
                'bathrooms' => $_POST['bathrooms'] ?? $beforeDetails->bathrooms,
                'kitchen' => $_POST['kitchen'] ?? $beforeDetails->kitchen,
                'living_room' => $_POST['living_room'] ?? $beforeDetails->living_room,

                'furnished' => $_POST['furnished'] ?? $beforeDetails->furnished,
                'furniture_description' => $_POST['furniture_description'] ?? $beforeDetails->furniture_description,

                'parking' => $_POST['parking'] ?? $beforeDetails->parking,
                'parking_slots' => $_POST['parking_slots'] ?? $beforeDetails->parking_slots,
                'type_of_parking' => $_POST['type_of_parking'] ?? $beforeDetails->type_of_parking,

                'utilities_included' => $safeImplode($_POST['utilities_included'] ?? $beforeDetails->utilities_included),
                'additional_utilities' => $safeImplode($_POST['additional_utilities'] ?? $beforeDetails->additional_utilities),
                'additional_amenities' => $safeImplode($_POST['additional_amenities'] ?? $beforeDetails->additional_amenities),
                'security_features' => $safeImplode($_POST['security_features'] ?? $beforeDetails->security_features),

                'purpose' => $purpose,
                'rental_period' => $rental_period,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'rental_price' => $rental_price,

                'owner_name' => $_POST['owner_name'] ?? $beforeDetails->owner_name,
                'owner_email' => $_POST['owner_email'] ?? $beforeDetails->owner_email,
                'owner_phone' => $_POST['owner_phone'] ?? $beforeDetails->owner_phone,
                'additional_contact' => $_POST['additional_contact'] ?? $beforeDetails->additional_contact,

                'special_instructions' => $safeImplode($_POST['special_instructions'] ?? $beforeDetails->special_instructions),
                'legal_details' => $safeImplode($_POST['legal_details'] ?? $beforeDetails->legal_details),

                'status' => $beforeDetails->status,
                'person_id' => $beforeDetails->person_id,
                'agent_id' => $beforeDetails->agent_id,
                'duration' => ($purpose == 'Rent') ? ($_POST['duration'] ?? $beforeDetails->duration) : 0
            ];

            // ✅ Check if status is pending and directly update real table
            if ($beforeDetails->status === 'Pending') {
                $this->updatePending($arr, $propertyId); // function inside same controller
                return;
            }

            // Continue temp table handling for non-pending properties
            $arr['request_status'] = 'pending'; // Only needed in temp table

            $beforeDetailsAsArray = (array)$beforeDetails;
            $detect_change = $property->compareWithPrevios($arr, $beforeDetailsAsArray);

            if ($detect_change) {
                $existingProperty = $property->where(['property_id' => $propertyId]);
                echo "checkpoint 1";
                if (!empty($existingProperty)) {
                    $property->delete($propertyId, 'property_id');
                }
                echo "checkpoint 2";

                $res = $property->insert($arr);

                echo "checkpoint 3";

                if ($res) {
                    if (isset($_FILES['property_images']) && $_FILES['property_images']['error'] === 0 && $_FILES['property_images']['size'] > 0) {
                        $imageErrors = upload_image(
                            $_FILES['property_images'],
                            ROOTPATH . 'public/assets/images/uploads/property_images/',
                            new PropertyImageModelTemp(),
                            $propertyId,
                            [
                                'allowed_ext' => ['jpg', 'jpeg', 'png'],
                                'prefix' => 'property',
                                'url_field' => 'image_url',
                                'fk_field' => 'property_id'
                            ]
                        );

                        if (!empty($imageErrors)) {
                            $property->errors['media'] = $imageErrors;
                            $_SESSION['flash']['msg'] = "Property update failed! Error with image upload.";
                            $_SESSION['flash']['type'] = "error";
                            $this->view('owner/editPropertyEnh', ['property' => $beforeDetails]);
                            return;
                        }
                    }

                    $_SESSION['flash']['msg'] = "Property Update Request Sent!";
                    $_SESSION['flash']['type'] = "success";
                    redirect('property/propertyListing');
                } else {
                    $_SESSION['flash']['msg'] = "Property update failed!";
                    $_SESSION['flash']['type'] = "error";
                    $this->view('owner/editPropertyEnh', ['property' => $beforeDetails]);
                }
            } else {
                $_SESSION['flash']['msg'] = "There is No change!";
                $_SESSION['flash']['type'] = "info";
                redirect('dashboard/propertyListing');
            }
        }
    }








    public function deleteRequest($propertyId)
    {
        $property = new Property;
        $update = $property->update($propertyId, ['status' => 'inactive'], 'property_id');
        if ($update) {
            $_SESSION['flash']['msg'] = "Property deleted successfully!";
            $_SESSION['flash']['type'] = "success";
        } else {
            $_SESSION['flash']['msg'] = "Failed to delete property. Please try again.";
            $_SESSION['flash']['type'] = "error";
        }
        // Redirect to the property listing page
        redirect('property/propertyListing');
    }
}
