<?php
defined('ROOTPATH') or exit('Access denied');

class Property
{
    use controller;

    public function create()
    {
        $property = new PropertyModel; // Initialize Property instance

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the form data
            if (!$property->validateProperty($_POST)) {
                // show($user->errors);
                $this->view('owner/addProperty', ['property' => $property]); // Re-render signup view with errors
                return; // Exit if validation fails
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

            // Insert user data into the database
            $res = $property->insert($arr);

            if ($res) {
                // Get the ID of the last inserted property
                $propertyId = $property->where(['name' => $_POST['name'], 'address' => $_POST['address']])[0]->property_id;
                $this->uploadPropertyMedia($propertyId); // Upload
                $property->errors = [];
                redirect('dashboard/propertyListing'); // Use a full URL or a path as necessary
            } else {
                // Handle the error case if insertion fails
                // You can add error handling here if needed
                $property->errors['insert'] = 'Failed to add Property. Please try again.';
                // show($user->errors);
                $this->view('owner/addProperty', ['property' => $property]);
            }
        }
        // Render the signup view if it's a GET request or if there are errors
        $this->view('owner/propertyListing', ['property' => $property]);
    }



    public function uploadPropertyMedia($propertyId)
    {
        $property = new PropertyModel;
        $errors = [];
        $status = '';

        // Directory paths for images and documents
        // $imageDir = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "property_images" . DIRECTORY_SEPARATOR;
        //$documentDir = ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "documents" . DIRECTORY_SEPARATOR . "property_docs" . DIRECTORY_SEPARATOR;
        $imageDir = ROOTPATH . "public/assets/images/uploads/property_images/";
        $documentDir = ROOTPATH . "public/assets/documents/property_docs/";


        // Create directories if they do not exist
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }
        if (!is_dir($documentDir)) {
            mkdir($documentDir, 0755, true);
        }

        // Handle image uploads
        if (isset($_FILES['property_images'])) {
            foreach ($_FILES['property_images']['name'] as $key => $imageName) {
                $imageTmp = $_FILES['property_images']['tmp_name'][$key];
                $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                $validImageExtensions = ['jpg', 'jpeg', 'png'];

                if (in_array($imageFileType, $validImageExtensions)) {
                    $uniqueImageName = uniqid() . "_property_" . $propertyId . "." . $imageFileType;
                    $targetImagePath = $imageDir . $uniqueImageName;

                    if (move_uploaded_file($imageTmp, $targetImagePath)) {
                        // Save image data in database (optional)
                        $propertyImage = new PropertyImageModel;
                        $propertyImage->insert(['image_url' => $uniqueImageName, 'property_id' => $propertyId]);
                        $status .= "Image uploaded successfully: $uniqueImageName<br>";
                    } else {
                        $errors[] = "Error uploading image: $imageName";
                    }
                } else {
                    $errors[] = "Invalid image format for: $imageName";
                }
            }
        }

        // Handle document uploads
        if (isset($_FILES['property_documents'])) {
            $docName = $_FILES['property_documents']['name'];
            $docTmp = $_FILES['property_documents']['tmp_name'];
            $docFileType = strtolower(pathinfo($docName, PATHINFO_EXTENSION));
            $validDocExtensions = ['pdf', 'doc', 'docx'];

            if (in_array($docFileType, $validDocExtensions)) {
                $uniqueDocName = uniqid() . "_doc_" . $propertyId . "." . $docFileType;
                $targetDocPath = $documentDir . $uniqueDocName;

                if (move_uploaded_file($docTmp, $targetDocPath)) {
                    // Save document data in database (optional)
                    $propertyDoc = new PropertyDocModel;
                    $propertyDoc->insert(['image_url' => $uniqueDocName, 'property_id' => $propertyId]);
                    $status .= "Document uploaded successfully: $uniqueDocName<br>";
                } else {
                    $errors[] = "Error uploading document: $docName";
                }
            } else {
                $errors[] = "Invalid document format for: $docName";
            }
        }

        // Store errors and status in session for feedback
        $property->errors['media'] = $errors;
        redirect("addProperty");
    }
}
