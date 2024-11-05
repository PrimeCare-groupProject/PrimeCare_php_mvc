<?php

class OwnerDashboard {
    use controller;
    public function index() {
        echo "Owner Dashboard";
    }

    public function profile() {
        echo "User Profile Section";
    }

    public function expenses() {
        echo "User Expenses Section";
    }
}
