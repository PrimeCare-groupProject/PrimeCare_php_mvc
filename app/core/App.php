<?php
class App{

    private $controller = 'home';
    private $method = 'index';

    private function splitURL(){
        $URL = $_GET['url'] ?? 'home';#make home default
        $URL = explode('/', trim($URL, '/'));#split based on /
        return $URL;
    }
    
    public function loadController(){
        $URL = $this->splitURL();
        if (ucfirst($URL[0]) === 'Dashboard') {
            if (isset($_SESSION['user']) && isset($_SESSION['user']->user_lvl)) {
            // Assign the controller based on user role
            $lvl = $_SESSION['user']->user_lvl;
            // show($lvl); // Debug purposes

            switch ($lvl) {
                case 1:
                    $this->controller = 'OwnerDashboard'; // regular user dashboard
                    // echo "case1";
                    break;
                case 2:
                    $this->controller = 'ServiceProviderDashboard'; // manager dashboard
                    break;
                case 3:
                    $this->controller = 'AgentDashboard'; // admin dashboard
                    break;
                case 4:
                    $this->controller = 'ManagerDashboard'; // manager dashboard
                    break;
                default:
                    $this->controller = '_404'; // load 404 page for unknown roles
                    break;
            }
            } else {
                $this->controller = 'Login'; // redirect to login if not logged in
            }
        }else{
            $this->controller = ucfirst($URL[0]) ?? 'home'; //make home default
        }

        $filename = "../app/controller/" . $this->controller . ".php";
        // show($filename);
        
        #selects controller
        if (file_exists($filename)) {
            require $filename;
            unset($URL[0]);#remove used parts
        } else {
            require "../app/controller/_404.php";# if no page found load 404 page
            $this->controller = '_404';
        }

        $controller = new $this->controller;

        #selects method
        if(!empty($URL[1])){
            if(method_exists($controller, $URL[1])){
                // echo "method function";
                $this->method = $URL[1];
                unset($URL[1]);#remove uses parts
            }else{
                // echo "index function";
            }
        }
        // show($URL);
        try {
            call_user_func_array([$controller, $this->method], $URL);
        } catch (Exception $e) {
            // Log the error (optionally) or handle it
            error_log($e->getMessage());
            // Optionally load a 500 error page
            require "../app/controller/_500.php";
        }
        

    }
    // $show(loadController());
}