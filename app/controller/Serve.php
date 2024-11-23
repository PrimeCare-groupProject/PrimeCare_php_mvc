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
                    $arr['service_images'] = implode(',', $uploadedFiles);
                }
            }
            echo '<pre>';
    print_r($arr);
    echo '</pre>';
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
            $this->view('agent/addnewrepair');
        }
    }

    public function read() {
        $service = new Services;
        $services = $service->findAll();
        $this->view('agent/repairings', ['services' => $services]);
    }

    public function update() {
        $service = new Services;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $service_id = $_POST['service_id'];
                    $name = $_POST['name'];
                    $cost_per_hour = $_POST['cost_per_hour'];
                    $description = $_POST['description'];
            // update repair data in database
            $result = $service->update($service_id, [
                'name' => $name,
                'cost_per_hour' => $cost_per_hour,
                'description' => $description,
            ], 'service_id');

            if ($result) {
                // Set flash message in session
                $_SESSION['flash_message'] = 'Service Updated Successfully!';
                
            } else {
                // Handle failure (e.g., insert failed)
                $_SESSION['flash_message'] = 'Failed to update service. Please try again.';
            }
            redirect('dashboard/repairings/editrepairing/'.$service_id);
        }
    }

    public function delete() {

    }

}