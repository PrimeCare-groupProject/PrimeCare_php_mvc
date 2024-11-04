<?php
defined('ROOTPATH') or exit('Access denied');

    class Agent{
        use controller;

        public function index(){
            $this->view('agent/dashboard');
        }

        public function dashboard(){
            $this->view('agent/dashboard');
        }

        public function profile(){
            $this->view('agent/profile');
        }

        public function preInspection(){
            $this->view('agent/preInspection');
        }

        public function requistedTask(){
            $this->view('agent/requestedTasks');
        }

        public function taskManagemnt(){
            $this->view('agent/taskManagement');
        }

        public function manageBookings(){
            $this->view('agent/manageBookings');
        }

        public function problems(){
            $this->view('agent/problems');
        }

        public function payments(){
            $this->view('agent/payments');
        }
    }