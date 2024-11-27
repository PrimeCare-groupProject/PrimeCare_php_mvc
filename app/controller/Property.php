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
                $_SESSION['status'] = 'Property added successfully.';
                //$this->view('property/propertyListing', ['property' => $property]);
                redirect('property/propertyListing');
            } else {
                $property->errors['insert'] = 'Failed to add Property. Please try again.';
                $this->view('property/propertyListing', ['property' => $property]);
            }
        } else {
            $this->view('property/propertyListing', ['property' => $property]);
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
        $property->delete($propertyId, 'property_id');

        $_SESSION['status'] = 'Property deleted successfully!';

        // Redirect to the property listing page
        redirect('property/propertyListing');
    }

    // drop property
    public function dropProperty($propertyId)
    {
        $property = new PropertyModel;
        if ($property->update($propertyId, ['status' => 'inactive'], 'property_id')) {
            $_SESSION['status'] = 'Property dropped successfully!';
        } else {
            $_SESSION['status'] = 'Failed to drop property. Please try again.';
        }
        // Redirect to the property listing page
        redirect('property/propertyListing');
    }

    // update
    public function update($propertyId)
    {
        $property = new PropertyModel;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the form data
            if (!$property->validateProperty($_POST)) {
                $this->view('owner/editProperty', ['property' => $property]);
                return;
            }

            // Prepare property data for update
            $arr = [
                'property_id' => $propertyId,
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

            // Update property data in the database
            $res = $property->update($propertyId, $arr, 'property_id');

            if ($res) {
                $mediaErrors = [];

                if (!empty($_FILES['property_images']['name'][0])) {
                    $this->deletePropertyImage($propertyId); // Remove old images
                    $imageErrors = $this->uploadPropertyImages($propertyId);
                    $mediaErrors = array_merge($mediaErrors, $imageErrors);
                }

                if (!empty($_FILES['property_documents']['name'][0])) {
                    $this->deletePropertyDocument($propertyId); // Remove old documents
                    $documentErrors = $this->uploadPropertyDocuments($propertyId);
                    $mediaErrors = array_merge($mediaErrors, $documentErrors);
                }

                // Handle errors
                if (!empty($mediaErrors)) {
                    $property->errors['media'] = $mediaErrors;
                    $this->view('owner/updateProperty', ['property' => $property]);
                    return;
                }

                $_SESSION['status'] = 'Property updated successfully!';
                redirect('property/propertyUnitOwner/' . $propertyId);
                //$this->view('owner/propertyUnit', ['property' => $property]);
            } else {
                $property->errors['update'] = 'Failed to update Property. Please try again.';
                $this->view('owner/updateProperty', ['property' => $property]);
            }
        } else {
            $property = $property->where(['property_id' => $propertyId])[0];
            $this->view('owner/updateProperty', ['property' => $property]);
        }
    }

    public function updatePropertyTemp($propertyId)
    {
        $property = new PropertyModelTemp;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $arr = [
                'property_id' => $propertyId,
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

            // Update property data in the database
            $res = $property->update($propertyId, $arr, 'property_id');

            if ($res) {
                $mediaErrors = [];

                if (!empty($_FILES['property_images']['name'][0])) {
                    $this->deletePropertyImage($propertyId); // Remove old images
                    $imageErrors = $this->uploadPropertyImages($propertyId);
                    $mediaErrors = array_merge($mediaErrors, $imageErrors);
                }

                if (!empty($_FILES['property_documents']['name'][0])) {
                    $this->deletePropertyDocument($propertyId); // Remove old documents
                    $documentErrors = $this->uploadPropertyDocuments($propertyId);
                    $mediaErrors = array_merge($mediaErrors, $documentErrors);
                }

                // Handle errors
                if (!empty($mediaErrors)) {
                    $property->errors['media'] = $mediaErrors;
                    $this->view('owner/updateProperty', ['property' => $property]);
                    return;
                }

                $_SESSION['status'] = 'Property updated successfully!';
                redirect('property/propertyUnitOwner/' . $propertyId);
                //$this->view('owner/propertyUnit', ['property' => $property]);
            } else {
                $property->errors['update'] = 'Failed to update Property. Please try again.';
                $this->view('owner/updateProperty', ['property' => $property]);
            }
        } else {
            $property = $property->where(['property_id' => $propertyId])[0];
            $this->view('owner/updateProperty', ['property' => $property]);
        }
    }

    // update temp
    public function updateRequest($propertyId){
        $property = $this->getProperty($propertyId);

        $propertyTemp = new PropertyModelTemp;
        $propertyTest = new propertyModel;
        $agent_id = $propertyTest->first(['property_id' => $propertyId])['agent_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the form data
            if (!$propertyTest->validateProperty($_POST)) {
                $this->view('owner/propertyUnit/'.$propertyId, ['property' => $property]);
                return;
            }

            // Prepare property data for insertion
            $arr = [
                'property_id' => $propertyId,
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
                'person_id' => $_SESSION['user']->pid,
                'agent_id' => $agent_id,
                'request_type' => 'update'
            ];

            // Insert property data into the database
            $res = $propertyTemp->insert($arr);

            if ($res) {
                // Upload images and documents, handling errors separately
                $imageErrors = $this->uploadPropertyImagesTemp($propertyId);
                $documentErrors = $this->uploadPropertyDocumentsTemp($propertyId);

                // Check for any upload errors
                if (!empty($imageErrors) || !empty($documentErrors)) {
                    $propertyTemp->errors['media'] = array_merge($imageErrors, $documentErrors);
                    $this->view('owner/addProperty', ['property' => $property]);
                    return;
                }

                // Redirect on success
                $_SESSION['status'] = 'Property Update request sent successfully.';
                redirect('property/propertyListing');
            } else {
                $propertyTemp->errors['insert'] = 'Failed to update Property. Please try again.';
                $this->view('property/propertyListing', ['property' => $property]);
            }
        } else {
            $this->view('property/propertyListing', ['property' => $property]);
        }

        
        
    }

    public function updateTemp($propertyId)
    {
        $property = new PropertyModelTemp;
        $propertyInstance = new PropertyModel;
        $agent_id = $propertyInstance->first(['property_id' => $propertyId])->agent_id;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the form data
            if (!$property->validateProperty($_POST)) {
                redirect('property/propertyListing');
                return;
            }

            // Prepare property data for update
            $arr = [
                'property_id' => $propertyId,
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
                'person_id' => $_SESSION['user']->pid,
                'agent_id' => $agent_id,
                'request_type' => 'update'
            ];

            // check if there is any column that has been changed
            

            // Update property data in the database
            $res = $property->insert($arr);

            if ($res) {
                // Upload images and documents, handling errors separately
                $imageErrors = $this->uploadPropertyImagesTemp($propertyId);
                $documentErrors = $this->uploadPropertyDocumentsTemp($propertyId);

                // Check for any upload errors
                if (!empty($imageErrors) || !empty($documentErrors)) {
                    $property->errors['media'] = array_merge($imageErrors, $documentErrors);
                    redirect('property/propertyUnitOwner/'.$propertyId);
                    return;
                }

                // Redirect on success to send update request
                redirect('property/propertyUnitOwner/'.$propertyId);
            } else {
                $property->errors['update'] = 'Failed to update Property. Please try again.';
                $this->view('owner/updateProperty', ['property' => $property]);
            }
        } else {
            $property = $property->where(['property_id' => $propertyId])[0];
            $this->view('owner/updateProperty', ['property' => $property]);
        }
    }

    // retrieve for the all the users
    public function propertyLisingToAllUsers()
    {
        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'pending']);

        $this->view('customer/search', ['properties' => $properties]);
    }

    public function propertyUnit($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);

        $this->view('customer/propertyUnit', ['property' => $propertyUnit]);
    }

    // retrieve for the owner
    public function propertyListing()
    {
        $property = new PropertyConcat;
        $properties = $property->where(['person_id' => $_SESSION['user']->pid], ['status' => 'inactive']);

        $this->view('owner/propertyListing', ['properties' => $properties]);
    }

    public function propertyUnitOwner($propertyId)
    {
        $property = new PropertyConcat;
        $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);

        $this->view('owner/propertyUnit', ['property' => $propertyUnit]);
    }

    public function getProperty($propertyId)
    {
        $property = new PropertyModel;
        $property = $property->where(['property_id' => $propertyId]);
        return $property;
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

    public function uploadPropertyImagesTemp($propertyId)
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
                        $propertyImage = new PropertyImageModelTemp;
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

    // retrieve 
    public function getPropertyImages($propertyId)
    {
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

    public function uploadPropertyDocumentsTemp($propertyId)
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
                    $propertyDoc = new PropertyDocModelTemp;
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
        $propertyDoc = new PropertyDocModel;
        // Fetch all documents associated with the property
        $docs = $propertyDoc->where(['property_id' => $propertyId])[0];
        $docPath = ROOTPATH . "public/assets/documents/property_docs/" . $docs->image_url;
        show($docPath);
        if (file_exists($docPath)) {
            unlink($docPath);
        }
    }

    //retrieve 
    public function getPropertyDocuments($propertyId)
    {
        $propertyDoc = new PropertyDocModel;
        $propertyDocs = $propertyDoc->where(['property_id' => $propertyId]);
        return $propertyDocs;
    }
}
