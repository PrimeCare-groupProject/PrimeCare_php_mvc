<?php
defined('ROOTPATH') or exit('Access denied');
//remove direct access
class Home{
    
    use controller;
    public function index(){
        
        $this->view('homeTest');
    }

}

