<?php
defined('ROOTPATH') or exit('Access denied');
// include_once SENDMAIL_PATH ;

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
                    // show($_SESSION['current_user']);

                    $status = sendMail($_SESSION['current_user']->email ,'Primecare Password Reset Code', "
                        <div style='font-family: Arial, sans-serif; color: #333; padding: 20px;'>
                            <h1 style='color: #4CAF50;'>Password Reset Request</h1>
                            <p>Hello,</p>
                            <p>We received a request to reset your password. Please use the following code to reset your password:</p>
                            <h3 style='color: #4CAF50;'>$reset_code</h3>
                            <p>If you did not request this, please ignore this email.</p>
                            <br>
                            <p>Best regards,<br>PrimeCare Support Team</p>
                        </div>
                    ");	

                    
                    if ($status['error']) {
                        $_SESSION['flash']['msg'] = 'An error occurred while sending the reset code. Please try again.';
                        $_SESSION['flash']['type'] = 'error';
                        // $user->errors['auth'] = 'An error occurred while sending the reset code. Please try again.';
                        $this->view('resetPassword', ['user' => $user]);
                    }else{
                        $_SESSION['flash']['msg'] = 'The reset code have been sent to your mail. Please check your email.';
                        $_SESSION['flash']['type'] = 'success';
                        unset($user->errors['auth']);
                        $this->view('resetPassword', ['user' => $user, 'confirmed' => true]);
                    }
                } else {
                    $_SESSION['flash']['msg'] = 'No user found with that email. Try again';
                    $_SESSION['flash']['type'] = 'error';
                    // $user->errors['auth'] = 'No user found with that email. Try again';
                    $this->view('resetPassword', ['user' => $user]);
                }

            } elseif (isset($_POST['code_submission'])) {
                // Handle reset code verification
                $reset_code_input = $_POST['reset_code'];
                // show($_SESSION['current_user']);
                if (isset($_SESSION['current_user']) && $_SESSION['current_user']->reset_code === $reset_code_input) {
                    $this->view('resetPassword', ['user' => $user, 'confirmed' => true, 'code_verified' => true]);
                } else {
                    $_SESSION['flash']['msg'] = 'Invalid reset code. Please try again.';
                    $_SESSION['flash']['type'] = 'error';
                    // $user->errors['auth'] = 'Invalid reset code. Please try again.';
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
                    
                    $_SESSION['flash']['type'] = 'success';
                    
                    // $_SESSION['success'] = ' Password reset successful. Login with your new password.';
                    if (isset($_SESSION['user'])) {
                        $_SESSION['flash']['msg'] = 'Password reset successful.';
                        redirect('dashboard/profile');
                    } else {
                        $_SESSION['flash']['msg'] = 'Password reset successful. Login with your new password.';
                        redirect('login');
                    }
                    exit();
                } else {
                    $_SESSION['flash']['msg'] = 'Passwords do not match or insufficient lenght. Enter matching passwords.';
                    $_SESSION['flash']['type'] = 'error';
                    // $user->errors['auth'] = 'Passwords do not match or insufficient lenght. Enter matching passwords.';
                    $this->view('resetPassword', ['user' => $user, 'confirmed' => true, 'code_verified' => true]);
                }
            }
        } else {
            $this->view('resetPassword');
        }
    }
}
