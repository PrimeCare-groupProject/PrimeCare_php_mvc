<?php

class Home{
    
    use controller;
    public function index(){
        
        $this->view('home');
    }

}

