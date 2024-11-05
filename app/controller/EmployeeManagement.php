<?php

class EmployeeManagement{
    use controller;

    public function index(){
        $this->view('manager/employeeManagement');
    }
}

