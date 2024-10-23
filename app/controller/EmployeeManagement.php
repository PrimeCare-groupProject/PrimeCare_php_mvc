<?php
defined('ROOTPATH') or exit('Access denied');

class EmployeeManagement{
    use controller;

    public function index(){
        $this->view('manager/employeeManagement');
    }
}

