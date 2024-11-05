<?php

class AddLogs {
    use Controller;

    // Method to handle 'done' status, showing summary view
    public function summeryView() {
        // Logic for summary view
        $this->view('ServiceProvider/summery'); // Adjust the path to your view file
    }

    // Method to handle 'pending' status, showing add logs view
    public function addLogsView() {
        // Logic for add logs view
        $this->view('ServiceProvider/addLogs'); // Adjust the path to your view file
    }
}
?>
