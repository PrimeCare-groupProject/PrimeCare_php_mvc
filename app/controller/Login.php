<?php

class Login{
    use controller;

    public function index(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
        
            $user = new User;
            $arr['email'] = $_POST['email'];
            $arr['password'] = $_POST['password'];
            $result = $user->first($arr,[]);
            show($result);
            if($result && isset($result->password) && $result->password == $arr['password']){
                // Remove the password field from the result object
                unset($result->password);
                $_SESSION['user'] = $result;//// Store the user data in the session
                // show("worked");
                header('Location: home');
            }else{
                $this->view('login');
            }
        }else{
            $this->view('login');
        }
    }

    // public function hashPw($password) {
    //     // Use the bcrypt algorithm (default in password_hash) to hash the password
    //     $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    //     return $hashedPassword;
    // }
    
}

