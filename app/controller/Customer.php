<?php
defined('ROOTPATH') or exit('Access denied');

    class Customer{
        use controller;

        public function index(){
            $this->view('customer/dashboard');
        }

        public function dashboard(){
            $this->view('customer/dashboard');
        }

        public function profile(){
            $this->view('customer/profile');
        }

        public function occupiedProperties(){
            $this->view('customer/occupiedProperties');
        }

        public function search(){
            $this->view('customer/search');
        }

        public function maintenanceRequests(){
            $this->view('customer/maintenanceRequests');
        }

        public function payments(){
            $this->view('customer/payments');
        }
    }