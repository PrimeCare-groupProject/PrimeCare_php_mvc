<?php
defined('ROOTPATH') or exit('Access denied');

class Login {
    use controller;

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User;

            // Validate and sanitize user input
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($email) || empty($password)) {
                $_SESSION['flash']['msg'] = 'Email and password are required.';
                $_SESSION['flash']['type'] = 'error';
                // $user->errors['auth'] = 'Email and password are required.';
                $this->view('login', ['user' => $user]);
                return;
            }

            // Fetch the user from the database
            $result = $user->first(['email' => $email], []);

            if ($result) {
                // Verify the password
                if (password_verify($password, $result->password)) {
                    unset($result->password); // Remove the password before storing in session

                    // Handle account status
                    if ($result->AccountStatus == 0) {
                        // Update account status to active
                        $updated = $user->update($result->pid, [
                            'AccountStatus' => 1
                        ], 'pid');

                        if ($updated) {
                            $result->AccountStatus = 1;

                            // Set welcome message
                            $_SESSION['flash'] = [
                                'msg' => "Welcome back to Primcare!",
                                'type' => "welcome"
                            ];
                        }
                    } elseif ($result->AccountStatus == -1) {
                        // Account is blocked
                        $_SESSION['flash'] = [
                            'msg' => "User Blocked! Please contact the administrator.",
                            'type' => "error"
                        ];
                        redirect('login');
                        return;
                    } elseif ($result->AccountStatus == 2) {
                        // Account is blocked
                        $_SESSION['flash'] = [
                            'msg' => "Your details are being processed still. Hang in there.",
                            'type' => "welcome"
                        ];
                    } elseif ($result->AccountStatus == 3) {
                        // Account is blocked
                        $_SESSION['flash'] = [
                            'msg' => "Your details have been rejected. Try again with valid values.",
                            'type' => "error"
                        ];
                    } elseif ($result->AccountStatus == 4) {
                        // Account is blocked
                        $_SESSION['flash'] = [
                            'msg' => "Your details have been Updated.",
                            'type' => "success"
                        ];
                    }

                    // Store user data in session
                    $_SESSION['user'] = $result;

                    // Set welcome message
                    $_SESSION['flash'] = [
                        'msg' => "Welcome to Primcare!",
                        'type' => "welcome"
                    ];

                    // Redirect to home page
                    $nxtUrl = $_SESSION['redirect_url'] ?? 'home';
                    unset($_SESSION['redirect_url']); 
                    redirect($nxtUrl);
                    // redirect('home');
                    return;
                } else {
                    // Password doesn't match
                    $_SESSION['flash'] = [
                        'msg' => "Invalid email or password.",
                        'type' => "error"
                    ];
                    // $user->errors['auth'] = 'Invalid email or password.';
                }
            } else {
                // User not found
                $_SESSION['flash'] = [
                    'msg' => "Invalid credentials.",
                    'type' => "error"
                ];
                // $user->errors['auth'] = 'Invalid credentials.';
            }

            // Display login page with errors
            $this->view('login', ['user' => $user]);
        } else {
            // Display login page
            if (isset($_SESSION['success'])) {
                $this->view('login', ['success' => $_SESSION['success']]);
                unset($_SESSION['success']); // Clear the success message
            } else {
                $this->view('login');
            }
        }
    }
}