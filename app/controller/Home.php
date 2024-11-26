<?php
defined('ROOTPATH') or exit('Access denied');
//remove direct access    // use controller; <- this is for namespaces
class Home{
    
    use controller;
    public function index(){
        $property = new PropertyConcat;
        $properties = $property->where(['status' => 'pending']);

        $this->view('hometest' , ['properties' => $properties]);
    }

    public function serviceListing(){
        $this->view('serviceListing');
    }

}

