<?php
class App
{
    private $controller = 'home';
    private $method = 'index';

    private function splitURL()
    {
        $URL = $_GET['url'] ?? 'home'; // make home default
        $URL = explode('/', trim($URL, '/')); // split based on '/'
        return $URL;
    }

    public function loadController()
    {
        $URL = $this->splitURL();
        $requestedController = ucfirst($URL[0]);

        // Prevent direct access to specific role-based controllers
        $restrictedControllers = ['OwnerDashboard', 'ServiceProviderDashboard', 'AgentDashboard', 'ManagerDashboard'];

        // Redirect direct access to restricted controllers
        if (in_array($requestedController, $restrictedControllers)) {
            redirect("dashboard");
            exit(); // Prevent further execution
        }

        // Check if user is trying to access the dashboard
        if ($requestedController === 'Dashboard' || stripos($URL[0], 'dashboard') !== false) {
            if (isset($_SESSION['user']) && isset($_SESSION['user']->user_lvl)) {
                // Assign the appropriate dashboard based on user role
                $lvl = $_SESSION['user']->user_lvl;

                switch ($lvl) {
                    case 1:
                        $this->controller = 'OwnerDashboard'; // regular user dashboard
                        break;
                    case 2:
                        $this->controller = 'ServiceProviderDashboard'; // service provider dashboard
                        break;
                    case 3:
                        $this->controller = 'AgentDashboard'; // agent dashboard
                        break;
                    case 4:
                        $this->controller = 'ManagerDashboard'; // manager dashboard
                        break;
                    default:
                        $this->controller = '_404'; // load 404 page for unknown roles
                        break;
                }
            } else {
                // If the user is not logged in, redirect them to the login page
                $this->controller = 'Login';
            }
        } else {
            // If not dashboard, use the provided controller or default to 'home'
            $this->controller = $requestedController ?? 'home'; // make home default
        }

        $filename = "../app/controller/" . $this->controller . ".php";

        // Load the selected controller if it exists
        if (file_exists($filename)) {
            require $filename;
            unset($URL[0]); // remove used part of URL
        } else {
            // Load the 404 page if controller is not found
            require "../app/controller/_404.php";
            $this->controller = '_404';
        }

        // Instantiate the controller
        $controller = new $this->controller;

        // Check if method exists
        if (!empty($URL[1])) {
            if (method_exists($controller, $URL[1])) {
                $this->method = $URL[1];
                unset($URL[1]); // remove used parts of URL
            }
        }

        // Safely call the controller method and handle errors
        try {
            call_user_func_array([$controller, $this->method], $URL);
        } catch (Exception $e) {
            // Log error or handle it (optional)
            error_log($e->getMessage());
            // Optionally display an error message or redirect
            require "../app/controller/_404.php";
        }
    }
}
