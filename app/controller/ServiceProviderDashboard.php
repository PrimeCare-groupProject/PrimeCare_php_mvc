<?php

class ServiceProviderDashboard {
    use controller;
    
    public function index() {
        $this->view('serviceprovider/dashboard');
    }

    public function profile() {
        $this->view('serviceprovider/profile');
    }

    public function addLogs(){
        $this->view('serviceprovider/addLogs');
    }

    public function repairListing(){
        $this->view('serviceprovider/repairListing');
    }

    public function repairRequests(){
        $this->view('serviceprovider/repairRequests');
    }

    public function repairs(){
        $this->view('serviceprovider/repairs');
    }
}
