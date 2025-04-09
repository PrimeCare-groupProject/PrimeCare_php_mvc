<?php
defined('ROOTPATH') or exit('Access denied');


trait controller
{

    public function view($name, $data = [])
    {
        if (!empty($data)) {
            extract($data); //will make available to files below
        }
        $filename = "../app/views/" . $name . ".view.php";
        if (file_exists($filename)) {
            require $filename;
        } else {
            //$filename = "../app/views/user_view/".$name.".view.php";
            $filename = '../app/views/owner/' . $name . '.view.php';
            if (file_exists($filename)) {
                require $filename;
            } else {
                $filename = '../app/views/manager/' . $name . '.view.php';
                if (file_exists($filename)) {
                    require $filename;
                } else {
                    require "../app/views/404.view.php"; // if no page found, load 404 page
                }
            }
        }
    }

    public function report($name , $data = []){
        if(!empty($data)) {
            extract($data); //will make available to files below
        }
        $filename = "../app/reports/" . $name . ".report.php";
        if(file_exists($filename)){
            require $filename;
        }else{
            return;
        }
    }

    private function logout()
    {
        session_unset();
        session_destroy();
        redirect('home');
        exit;
    }
    
    public function switchUser() {
        if (isset($_SESSION['customerView'])) {
            $_SESSION['customerView'] = !$_SESSION['customerView'];
        } else {
            $_SESSION['customerView'] = true;
        }
        redirect('dashboard');
    }
}
