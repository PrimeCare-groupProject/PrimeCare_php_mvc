<?php
defined('ROOTPATH') or exit('Access denied');
//remove direct access    // use controller; <- this is for namespaces
class Home{
    
    use controller;
    public function index(){
        
        $this->view('hometest');
    }

}

