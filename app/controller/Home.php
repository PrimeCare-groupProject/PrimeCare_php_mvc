<?php
defined('ROOTPATH') or exit('Access denied');
//remove direct access    // use controller; <- this is for namespaces
class Home{
    
    use controller;
    public function index(){
        $property = new PropertyConcat;
        $services = new Services;

        $services = $services->findAll();
        $properties = $property->where(['status' => 'pending']);

        $this->view('hometest' , ['properties' => $properties , 'services' => $services]);
    }

    public function serviceListing(){
        $this->view('serviceListing');
    }

}

