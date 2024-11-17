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