<?php
defined('ROOTPATH') or exit('Access denied');

class Agent {
    use controller;
    public function index() {
        echo "Agent Dashboard";
    }

    public function profile() {
        echo "User Profile Section";
    }

    public function expenses() {
        echo "User Expenses Section";
    }
}
