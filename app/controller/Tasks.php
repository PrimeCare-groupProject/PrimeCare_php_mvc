<?php
defined('ROOTPATH') or exit('Access denied');

class Tasks {
    use Controller;

    public function create() {
        $task = new ServiceLog(); // Assuming you have a Task model
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $arr = [
                'service_type' => $_POST['service_type'],
                'date' => $_POST['date'],
                'property_id' => $_POST['property_id'],
                'property_name' => $_POST['property_name'],
                'cost_per_hour' => $_POST['cost_per_hour'],
                'total_hours' => $_POST['total_hours'],
                'service_provider_id' => $_POST['service_provider_id'],
            ];

            // Check if files are uploaded
            if (!empty($_FILES['tasks_images']['name'][0])) {
               $uploadDir = ROOTPATH . "public/assets/images/uploads/service_images/";
               if (!is_dir($uploadDir)) {
                   mkdir($uploadDir, 0777, true); // Ensure directory exists
               }

               $uploadedFiles = [];
               foreach ($_FILES['service_images']['name'] as $key => $name) {
                   $tempName = $_FILES['service_images']['tmp_name'][$key];
                   $extension = pathinfo($name, PATHINFO_EXTENSION);
                   $newName = uniqid('tasks_', true) . '.' . $extension; // Unique filename
                   $destination = $uploadDir . $newName;

                   if (move_uploaded_file($tempName, $destination)) {
                       $uploadedFiles[] = "assets/images/uploads/service_images/" . $newName;
                   }
               }

               // Save the file paths to the database (as JSON for multiple files)
               if (!empty($uploadedFiles)) {
                   $arr['service_img'] = implode(',', $uploadedFiles);
               }
           }

            // Insert task data into the database
            $res = $task->insert($arr);
            
            if ($res) {
                $_SESSION['flash_message'] = 'Task created successfully!';
                redirect('dashboard/tasks/newtask'); // Redirect to tasks list after creation
            } else {
                $_SESSION['flash_message'] = 'Failed to create task. Please try again.';
                $this->view('agent/addnewtask'); // Stay on the form page if failed
            }
        } else {
            // If not POST request, just show the form
            $this->view('agent/newtask');
        }
    }
}