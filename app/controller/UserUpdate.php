<?php
defined('ROOTPATH') or exit('Access denied');

class UserUpdate{
    use controller;

    public function index(){
        $this->view('userUpdate');
    }
}

