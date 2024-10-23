<?php
defined('ROOTPATH') or exit('Access denied');

class Login{
    use controller;

    public function index(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
            $user = new User;
    
            // Collect user input
            $arr['email'] = $_POST['email'];
            $arr['password'] = $_POST['password']; 
    
            // Fetch the user from database
            $result = $user->first(['email' => $arr['email']], []);
    
            if($result && isset($result->password)){
                
                // Verify the password using password_verify()
                if(password_verify($arr['password'], $result->password)){
                    unset($result->password); // Remove the password before storing user data in session
                    $_SESSION['user'] = $result; 
                    
                    // Redirect to home or dashboard
                    redirect('home');
                    exit();
                } else {
                    // Password doesn't match
                    $user->errors['auth'] = 'Invalid email or password';
                    $this->view('login', ['user' => $user]);
                }
            } else {
                $user->errors['auth'] = 'Invalid credentials';
                $this->view('login', ['user' => $user]);
            }
        } else {
            $this->view('login');
        }
    }
    

    
    
}

