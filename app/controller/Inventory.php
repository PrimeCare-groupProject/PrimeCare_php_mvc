<?php
defined('ROOTPATH') or exit('Access denied');

class Inventory{
    use controller;

// Services crud
    public function create() {
        $inventory = new InventoryModel;
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $arr = [
                    'inventory_name' => $_POST['inventory_name'],
                    'date' => $_POST['date'],
                    'unit_price' => $_POST['unit_price'],
                    'quantity' => $_POST['quantity'],
                    'description' => $_POST['description'],
                    'seller_name' => $_POST['seller_name'],
                    'seller_address' => $_POST['seller_address'],
                    'property_id' => $_POST['property_id'],
                    'property_name' => $_POST['property_name'],
                    'inventory_type' => $_POST['inventory_type']
            ];

            // Check if files are uploaded
            if (!empty($_FILES['inventory_image']['name'][0])) {
                $uploadDir = ROOTPATH . "public/assets/images/uploads/inventory_image/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true); // Ensure directory exists
                }

                $uploadedFiles = [];
                foreach ($_FILES['inventory_image']['name'] as $key => $name) {
                    $tempName = $_FILES['inventory_image']['tmp_name'][$key];
                    $extension = pathinfo($name, PATHINFO_EXTENSION);
                    $newName = uniqid('inventory_', true) . '.' . $extension; // Unique filename
                    $destination = $uploadDir . $newName;

                    if (move_uploaded_file($tempName, $destination)) {
                        $uploadedFiles[] = "assets/images/uploads/inventory_image/" . $newName;
                    }
                }

                // Save the file paths to the database (as JSON for multiple files)
                if (!empty($uploadedFiles)) {
                    $arr['img_url'] = implode(',', $uploadedFiles);
                }
            }
            // Insert repair data into the database
            $res = $inventory->insert($arr);
            
            if ($res) {
                // Set flash message in session
                $_SESSION['flash_message'] = 'Inventory Added successfully!';

            } else {
                // Handle failure (e.g., insert failed)
                $_SESSION['flash_message'] = 'Failed to add Inventory. Please try again.';
            }
            // Ensure the form page is loaded after submission
            $this->view('agent/newinventory');
        }
    }

    

}


