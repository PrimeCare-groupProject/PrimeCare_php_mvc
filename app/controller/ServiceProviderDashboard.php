<?php

class ServiceProviderDashboard {
    use controller;
    
    public function index() {
        echo "Service Provider Dashboard";
    }

    public function profile() {
        echo "User Profile Section";
    }

    public function expenses() {
        echo "User Expenses Section";
    }
}
