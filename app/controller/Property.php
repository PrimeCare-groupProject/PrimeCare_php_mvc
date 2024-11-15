<?php
defined('ROOTPATH') or exit('Access denied');

class Property
{
    use controller;

    // property ----------------------------------------------------------------------------------------------------------------------------
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

                // Upload images and documents, handling errors separately
                $imageErrors = $this->uploadPropertyImages($propertyId);
                $documentErrors = $this->uploadPropertyDocuments($propertyId);

                // Check for any upload errors
                if (!empty($imageErrors) || !empty($documentErrors)) {
                    $property->errors['media'] = array_merge($imageErrors, $documentErrors);
                    $this->view('owner/addProperty', ['property' => $property]);
                    return;
                }

                // Redirect on success
                redirect('dashboard/propertyListing');
            } else {
                $property->errors['insert'] = 'Failed to add Property. Please try again.';
                $this->view('owner/addProperty', ['property' => $property]);
            }
        } else {
            $this->view('owner/propertyListing', ['property' => $property]);
        }
    }

    // delete
    public function delete($propertyId)
    {
        $property = new PropertyModel;

        // Delete the property images
        $this->deletePropertyImage($propertyId);

        // Delete the property documents
        $this->deletePropertyDocument($propertyId);

        // Delete the property
        $property->delete($propertyId);

        // Redirect to the property listing page
        redirect('dashboard/propertyListing');
    }

    // retrieve for the all the users
    public function propertyLisingToAllUsers(){
        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'approved']);

        $this->view('customer/search', ['properties' => $properties]);
    }


    // property images --------------------------------------------------------------------------------------------------------------------
    // create
    public function uploadPropertyImages($propertyId)
    {
        $property = new PropertyModel;
        $errors = [];
        $imageDir = ROOTPATH . "public/assets/images/uploads/property_images/";

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        if (isset($_FILES['property_images'])) {
            foreach ($_FILES['property_images']['name'] as $key => $imageName) {
                $imageTmp = $_FILES['property_images']['tmp_name'][$key];
                $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                $validImageExtensions = ['jpg', 'jpeg', 'png'];

                if (in_array($imageFileType, $validImageExtensions)) {
                    $uniqueImageName = uniqid() . "_property_" . $propertyId . "." . $imageFileType;
                    $targetImagePath = $imageDir . $uniqueImageName;

                    if (move_uploaded_file($imageTmp, $targetImagePath)) {
                        $propertyImage = new PropertyImageModel;
                        $propertyImage->insert(['image_url' => $uniqueImageName, 'property_id' => $propertyId]);
                    } else {
                        $errors[] = "Error uploading image: $imageName";
                    }
                } else {
                    $errors[] = "Invalid image format for: $imageName";
                }
            }
        }
        return $errors;
    }

    // delete
    public function deletePropertyImage($propertyId)
    {
        $propertyImage = new PropertyImageModel();
        // Fetch all images associated with the property
        $images = $propertyImage->where($propertyId);
    
        foreach ($images as $image) {
            $imagePath = ROOTPATH . "public/assets/images/uploads/property_images/" . $image['image_url'];
    
            // Check if the file exists before attempting to delete
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
    }
    
    // retrieve 
    public function getPropertyImages($propertyId){
        $propertyImage = new PropertyImageModel;
        $propertyImages = $propertyImage->where(['property_id' => $propertyId]);
        return $propertyImages;
    }

    // property documents -----------------------------------------------------------------------------------------------------------------
    // create
    public function uploadPropertyDocuments($propertyId)
    {
        $property = new PropertyModel;
        $errors = [];
        $documentDir = ROOTPATH . "public/assets/documents/property_docs/";

        if (!is_dir($documentDir)) {
            mkdir($documentDir, 0755, true);
        }

        if (isset($_FILES['property_documents'])) {
            $docName = $_FILES['property_documents']['name'];
            $docTmp = $_FILES['property_documents']['tmp_name'];
            $docFileType = strtolower(pathinfo($docName, PATHINFO_EXTENSION));
            $validDocExtensions = ['pdf', 'doc', 'docx'];

            if (in_array($docFileType, $validDocExtensions)) {
                $uniqueDocName = uniqid() . "_doc_" . $propertyId . "." . $docFileType;
                $targetDocPath = $documentDir . $uniqueDocName;

                if (move_uploaded_file($docTmp, $targetDocPath)) {
                    $propertyDoc = new PropertyDocModel;
                    $propertyDoc->insert(['image_url' => $uniqueDocName, 'property_id' => $propertyId]);
                } else {
                    $errors[] = "Error uploading document: $docName";
                }
            } else {
                $errors[] = "Invalid document format for: $docName";
            }
        }
        return $errors;
    }

    // delete
    public function deletePropertyDocument($propertyId)
    {
        $propertyDoc = new PropertyDocModel();
        // Fetch all documents associated with the property
        $docs = $propertyDoc->where(['property_id' => $propertyId]);
        $docPath = ROOTPATH . "public/assets/documents/property_docs/" . $docs[0]['image_url'];
        if(file_exists($docPath)) {
            unlink($docPath);
        }
    }

    // retrieve 
    // public function getPropertyDocuments($propertyId){
    //     $propertyDoc = new PropertyDocModel;
    //     $propertyDocs = $propertyDoc->where(['property_id' => $propertyId]);
    //     return $propertyDocs;
    // }
}
