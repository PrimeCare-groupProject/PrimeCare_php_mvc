ManagerDashboard
<?php

class AgentDashboard {
    use controller;
    public function index() {
        echo "Manager Dashboard";
    }

    public function profile() {
        echo "User Profile Section";
    }

    public function expenses() {
        echo "User Expenses Section";
    }
}
