<?php
defined('ROOTPATH') or exit('Access denied');

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


    private function hashPw($password) {
        // Use the bcrypt algorithm (default in password_hash) to hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        return $hashedPassword;
    }
    

    public function index() {
        $user = new User; // Initialize User instance
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Check for existing email
            // show($_POST);
            $emailExists = $user->first(['email' => $_POST['email']], []);
            $nicExists = $user->first(['nic' => $_POST['nic']], []);

            if ($emailExists || $nicExists) {
                $_SESSION['flash'] = [
                    'msg' => "Email or NIC already exists.",
                    'type' => "error"
                ];
                $this->view('signup', ['user' => $user]); // Re-render signup view with error
                return; // Exit if email or NIC exists
            }

            // Validate the form data
            if (!$user->validate($_POST)) {
                
                // Convert errors array to string and show the first error
                $errorMsg = implode(", ", $user->errors);
                $_SESSION['flash'] = [
                    'msg' => !empty($errorMsg) ? reset($user->errors) : "Validation failed.",
                    'type' => "error"
                ];
                // echo "if2";
                $this->view('signup',['user' => $user]); // Re-render signup view with errors
                return; // Exit if validation fails
            }

            // Prepare user data for insertion
            $arr = [
                'fname' => $_POST['fname'],
                'lname' => $_POST['lname'],
                'email' => $_POST['email'],
                'contact' => $_POST['contact'],
                'password' => $this->hashPw($_POST['password']),
                'user_lvl' => 0,
                'nic' => $_POST['nic'],
                'username' => $this->generateUsername($_POST['fname']), // Generate username
            ];
            // show($arr);

            // Insert user data into the database
            $res = $user->insert($arr);

                
            if ($res) {
                // Redirect to home if the insertion is successful
                $updatedUser = $user->first(['email' => $arr['email']], []);
                unset($updatedUser->password); // Remove password from the user object
                $_SESSION['user'] = $updatedUser; // Store user data in session
                
                $_SESSION['flash'] = [
                    'msg' => "Welcome to Primcare!",
                    'type' => "welcome"
                ];

                $nxtUrl = $_SESSION['redirect_url'] ?? 'home';
                unset($_SESSION['redirect_url']); 
                redirect($nxtUrl);

                exit; // Good practice to call exit after header
            } else {
                // Handle the error case if insertion fails
                // You can add error handling here if needed
                $_SESSION['flash'] = [
                    'msg' => "Failed to create account. Please try again.",
                    'type' => "error"
                ];
                // $user->errors['insert'] = 'Failed to create account. Please try again.';
                // show($user->errors);
                $this->view('signup',['user' => $user]);
            }
        }

        // Render the signup view if it's a GET request or if there are errors
        $this->view('signup',['user' => $user]);
    }
}
