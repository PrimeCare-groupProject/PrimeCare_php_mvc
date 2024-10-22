<!-- ManagerDashboard -->
<?php
defined('ROOTPATH') or exit('Access denied');

class Manager {
    use controller;
    public function index() {
        $this->view('manager/dashboard');
    }

    public function dashboard() {
        $this->view('manager/dashboard');
    }

    public function profile() {
        $this->view('manager/profile');
    }

    public function managementHome($a = '', $b = '', $c = '', $d = ''){
        // echo $a . "<br>";
        // echo $b . "<br>";
        // echo $c . "<br>";
        switch($a){
            case 'propertymanagement':
                $this->propertyManagement($b,$c,$d);
                break;
            case 'employeemanagement':
                $this->employeeManagement();
                break;
            case 'agentmanagement':
                $this->agentManagement();
                break;
            case 'financialmanagement':
                $this->financialManagement();
                break;
            default:
                $this->view('manager/managementHome');
                break;
        }
    }

    public function propertyManagement($b = '', $c = '', $d = ''){
        // echo $b . "<br>";
        // echo $c . "<br>";
        // echo $d . "<br>";
        // show(URL(3));
        switch($b){
            case 'assignagents':
                $this->assignAgents($c, $d);
                break;
            case 'requestapproval':
                $this->requestApproval($c, $d);
                break;
            default:
                $this->view('manager/propertymanagement');
                break;
        }
    }

    public function employeeManagement(){
        $this->view('manager/employeeManagement');
    }

    public function requestApproval(){
        $this->view('manager/requestApproval');
    }

    public function financialManagement(){
        $this->view('manager/financialManagement');
    }

    public function assignAgents(){
        $this->view('manager/assignagents');
    }

    public function agentManagement(){
        $this->view('manager/agentManagement');
    }

    
}
