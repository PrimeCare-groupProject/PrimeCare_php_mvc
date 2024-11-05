<?php

class Signup {
    use controller;

    public function generateUsername($fname, $length = 10) {
        // Normalize the first name by removing non-alphanumeric characters
        $fname = preg_replace('/[^a-zA-Z0-9]/', '', $fname);
        // Truncate if longer than the desired length
        $username = (strlen($fname) > $length) ? substr($fname, 0, $length) : $fname;

        // Add random characters until reaching the desired length
        while (strlen($username) < $length) {
            // Generate a random character from numbers (0-9) or uppercase letters (A-Z)
            $randomChar = mt_rand(0, 1) ? chr(mt_rand(48, 57)) : chr(mt_rand(65, 90));
            $username .= $randomChar;
        }
        
        return substr($username, 0, $length); // Ensure exactly $length characters
    }

    public function index() {
        $user = new User; // Initialize User instance

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check for existing email
            // show($_POST);
            $arr['email'] = $_POST['email'];
            $result = $user->first($arr, []);
            if ($result && isset($result->email)) {
                $user->errors['email'] = 'Email already exists';
                // show($user->errors);
                // echo "if1";
                $this->view('signup'); // Re-render signup view with error
                return; // Exit if email exists
            }

            // Validate the form data
            if (!$user->validate($_POST)) {
                // show($user->errors);
                // echo "if2";
                $this->view('signup'); // Re-render signup view with errors
                return; // Exit if validation fails
            }

            // Prepare user data for insertion
            $arr = [
                'fname' => $_POST['fname'],
                'lname' => $_POST['lname'],
                'email' => $_POST['email'],
                'contact' => $_POST['contact'],
                'password' => $_POST['password'],
                'user_lvl' => 1,
                'username' => $this->generateUsername($_POST['fname']), // Generate username
            ];
            // show($arr);

            // Insert user data into the database
            $res = $user->insert($arr);
            
            if ($res) {
                // Redirect to home if the insertion is successful
                unset($arr->password);
                $_SESSION['user'] = $arr; // Store user data in session
                redirect('home');// Use a full URL or a path as necessary
                exit; // Good practice to call exit after header
            } else {
                // Handle the error case if insertion fails
                // You can add error handling here if needed
                $user->errors['insert'] = 'Failed to create account. Please try again.';
                // show($user->errors);
            }
        }

        // Render the signup view if it's a GET request or if there are errors
        $this->view('signup');
    }
}
