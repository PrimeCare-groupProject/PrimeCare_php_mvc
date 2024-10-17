ManagerDashboard
<?php

class ManagerDashboard {
    use controller;
    public function index() {
        $this->view('manager/dashboard');
    }

    public function profile() {
        $this->view('manager/profile');
    }

    public function managementHome(){
        $this->view('manager/managementHome');
    }

    public function propertyManagement(){
        $this->view('manager/propertyManagement');
    }

    public function requestApproval(){
        $this->view('manager/requestApproval');
    }

    public function employeeManagement(){
        $this->view('manager/employeeManagement');
    }

    public function assignAgent(){
        $this->view('manager/assignAgent');
    }

    public function agentManagement(){
        $this->view('manager/agentManagement');
    }
}
