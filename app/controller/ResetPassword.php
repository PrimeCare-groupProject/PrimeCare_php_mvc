<?php
defined('ROOTPATH') or exit('Access denied');

class ResetPassword {
    use controller;

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = new User;

            if (isset($_POST['email_submission'])) {
                // Handle email submission for reset code
                $arr['email'] = $_POST['email'];
                $result = $user->first($arr);

                if ($result) {
                    unset($result->password); // Remove password for security
                    
                    // Generate and save reset code
                    $reset_code = bin2hex(random_bytes(4));
                    $user->update($arr['email'], ['reset_code' => $reset_code], 'email');
                    
                    $result->reset_code = $reset_code;
                    $_SESSION['current_user'] = $result; // Store user data in session
                    show($_SESSION['current_user']);
                    /*
                    $to = $arr['email'];
                    $subject = 'Password Reset Code';
                    $message = "Your password reset code is: $reset_code";
                    $headers = 'From: PrimeCare';
                    mail($to, $subject, $message, $headers);
                    */

                    unset($user->errors['auth']);
                    $this->view('resetPassword', ['user' => $user, 'confirmed' => true]);
                } else {
                    $user->errors['auth'] = 'No user found with that email. Try again';
                    $this->view('resetPassword', ['user' => $user]);
                }

            } elseif (isset($_POST['code_submission'])) {
                // Handle reset code verification
                $reset_code_input = $_POST['reset_code'];
                show($_SESSION['current_user']);
                if (isset($_SESSION['current_user']) && $_SESSION['current_user']->reset_code === $reset_code_input) {
                    $this->view('resetPassword', ['user' => $user, 'confirmed' => true, 'code_verified' => true]);
                } else {
                    $user->errors['auth'] = 'Invalid reset code. Please try again.';
                    $this->view('resetPassword', ['user' => $user, 'confirmed' => true]);
                }

            } elseif (isset($_POST['password'], $_POST['confirm_password'])) {
                // Handle password reset
                $arr['email'] = $_SESSION['current_user']->email ?? null;
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];

                if ($password === $confirmPassword && strlen($password)>4 && $arr['email']) {
                    // Hash the password and update in the database

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $user->update($arr['email'], ['password' => $hashed_password, 'reset_code' => null], 'email');

                    // Clear session data and redirect to login page
                    unset($_SESSION['current_user']);
                    $_SESSION['success'] = ' Password reset successful. Login with your new password.';
                    redirect('login');
                    exit();
                } else {
                    $user->errors['auth'] = 'Passwords do not match or insufficient lenght. Enter matching passwords.';
                    $this->view('resetPassword', ['user' => $user, 'confirmed' => true, 'code_verified' => true]);
                }
            }
        } else {
            $this->view('resetPassword');
        }
    }
}
