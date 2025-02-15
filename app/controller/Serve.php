<?php
defined('ROOTPATH') or exit('Access denied');

class Serve{
    use controller;

// Services crud
    public function create() {
        $service = new Services;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $arr = [
                    'name' => $_POST['name'],
                    'cost_per_hour' => $_POST['cost_per_hour'],
                    'description' => $_POST['description']
            ];

            // Check if files are uploaded
            if (!empty($_FILES['service_images']['name'][0])) {
                $uploadDir = ROOTPATH . "public/assets/images/uploads/services_images/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Ensure directory exists
                }

                $uploadedFiles = [];
                foreach ($_FILES['service_images']['name'] as $key => $name) {
                    $tempName = $_FILES['service_images']['tmp_name'][$key];
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $newName = uniqid('service_', true) . '.' . $extension; // Unique filename
                    $destination = $uploadDir . $newName;

                    if (move_uploaded_file($tempName, $destination)) {
                        $uploadedFiles[] = "assets/images/uploads/services_images/" . $newName;
                    }
                }

                // Save the file paths to the database (as JSON for multiple files)
                if (!empty($uploadedFiles)) {
                    $arr['service_img'] = implode(',', $uploadedFiles);
                }
            }
            // Insert repair data into the database
            $res = $service->insert($arr);
            
            if ($res) {
                // Set flash message in session
                $_SESSION['flash_message'] = 'Service created successfully!';

            } else {
                // Handle failure (e.g., insert failed)
                $_SESSION['flash_message'] = 'Failed to create service. Please try again.';
            }
            // Ensure the form page is loaded after submission
            $this->view('agent/addnewservice');
        }
    }

    public function read() {
        $service = new Services;
        $services = $service->findAll();
        $this->view('agent/services', ['services' => $services]);
    }

    public function update() {
        $service = new Services;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $arr = [
                    'service_id' => $_POST['service_id'],
                    'name' => $_POST['name'],
                    'cost_per_hour' => $_POST['cost_per_hour'],
                    'description' => $_POST['description']
            ];


            /*$existingService = $service->where(['service_id' => $_POST['service_id']]);
            $oldImage = ROOTPATH . $existingService[0]->service_img;
            if (file_exists($oldImage)) {
                echo "Bimsara";
                unlink($oldImage); // Delete the old image
            }*/
            $oldImage = "C:/xampp/htdocs/php_mvc_backend/public/" . $_POST['service_img'];
            
            // Check if files are uploaded
            if (!empty($_FILES['service_images']['name'])) {
                $uploadDir = ROOTPATH . "public/assets/images/uploads/services_images/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Ensure directory exists
                }

                $uploadedFiles = [];
                    $name = $_FILES['service_images']['name'];
                    $tempName = $_FILES['service_images']['tmp_name'];
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $newName = uniqid('service_', true) . '.' . $extension; // Unique filename
                    $destination = $uploadDir . $newName;

                    if (move_uploaded_file($tempName, $destination)) {
                        $uploadedFiles[] = "assets/images/uploads/services_images/" . $newName;
                    }            

                // Save the file paths to the database (as JSON for multiple files)
                if (!empty($uploadedFiles)) {
                    $arr['service_img'] = implode(',', $uploadedFiles);
                }
            }

            if (file_exists($oldImage)) {
                unlink($oldImage); // Delete the old image
            }

            // Update repair data into the database
            $res = $service->update($_POST['service_id'], $arr, 'service_id');

            if ($res) {
                // Set flash message in session
                $_SESSION['flash_message'] = 'Service created successfully!';

            } else {
                // Handle failure (e.g., insert failed)
                $_SESSION['flash_message'] = 'Failed to create service. Please try again.';
            }
            $service2 = $service->where(['service_id' => $_POST['service_id']])[0];
            $this->view('agent/editservices', ['service1' => $service2]);
            
        }
    }
    
    public function preInspect() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $property_id = $_POST['property_id'];

            $preinspect =  new PropertyModel;
            $preinspection = $preinspect->where(['property_id' => $property_id])[0];
            
            $user_id = $preinspection->person_id;
            $userdata =  new User;
            $user = $userdata->where(['pid' => $user_id])[0];

            $this->view('agent/preinspectionupdate', ['preinspect' => $preinspection,'user' => $user]);
        }
    }

    public function preInspectUpdate(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $preinspect =  new PropertyModel;
            
            $res = $preinspect->update($_POST['property_id'], ['status' => "active"], 'property_id');
            redirect('/dashboard/preInspection');
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["action"])) {
                if ($_POST["action"] == "accept") {
                    // Call Accept Controller
                    $preinspect =  new PropertyModel;
            
                    $res = $preinspect->update($_POST['property_id'], ['status' => "active"], 'property_id');
                    redirect('/dashboard/preInspection');
                    exit;
                } elseif ($_POST["action"] == "reject") {
                    $preinspect =  new PropertyModel;
            
                    $res = $preinspect->update($_POST['property_id'], ['status' => "inactive"], 'property_id');
                    redirect('/dashboard/preInspection');
                    exit;
                }
            }
        }
    }


    // service unit retrieve for customer
    public function serviceUnit($service_id) {
        $service = new Services;
        $service = $service->where(['service_id' => $service_id])[0];
        $this->view('customer/repairUnit', ['service' => $service]);
    }

}


