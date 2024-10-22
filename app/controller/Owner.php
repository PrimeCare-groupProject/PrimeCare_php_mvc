<?php
defined('ROOTPATH') or exit('Access denied');

    class Owner{
        use controller;

        public function index(){
            $this->view('owner/dashboard');
        }

        public function dashboard(){
            $this->view('owner/dashboard');
        }

        public function maintenance(){
            $this->view('owner/maintenance');
        }

        public function financeReport(){
            $this->view('owner/financeReport');
        }

        public function tenants(){
            $this->view('owner/tenants');
        }

        public function addProperty(){
            $this->view('owner/addProperty');
        }

        public function propertyListing($a = '', $b = '', $c = '', $d = ''){
            if($a == 'addproperty'){
                $this->addProperty($b = '', $c = '', $d = '');
                return;
            }else if($a == 'propertyunit'){
                $this->propertyUnit($b = '', $c = '', $d = '');
                return;
            }
            $this->view('owner/propertyListing');
        }

        public function propertyUnit(){
            $this->view('owner/propertyUnit');
        }

        public function profile(){
            $this->view('owner/profile');
        }
    }