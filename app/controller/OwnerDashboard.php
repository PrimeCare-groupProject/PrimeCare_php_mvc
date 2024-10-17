<?php
    class OwnerDashboard{
        use controller;

        public function index(){
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

        public function propertyListing(){
            $this->view('owner/propertyListing');
        }

        public function propertyUnit(){
            $this->view('owner/propertyUnit');
        }

        public function profile(){
            $this->view('owner/profile');
        }
    }