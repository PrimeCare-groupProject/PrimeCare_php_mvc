<?php
defined('ROOTPATH') or exit('Access denied');

class Customer
{
    use controller;

    public function index()
    {
        $this->view('customer/dashboard', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function dashboard()
    {
        $this->view('customer/dashboard', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function propertyUnit()
    {
        $this->view('customer/propertyUnit', [
            'user' => $_SESSION['user'],
            'errors' => $_SESSION['errors'] ?? [],
            'status' => $_SESSION['status'] ?? ''
        ]);
    }

    public function profile()
    {
        $this->view('customer/profile');
    }

    public function occupiedProperties()
    {
        $this->view('customer/occupiedProperties');
    }

    public function search()
    {
        $this->view('customer/search');
    }

    public function maintenanceRequests()
    {
        $this->view('customer/maintainanceRequest');
    }

    public function payments()
    {
        $this->view('customer/payments');
    }
}
