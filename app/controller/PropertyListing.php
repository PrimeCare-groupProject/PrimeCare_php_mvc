<?php
defined('ROOTPATH') or exit('Access denied');

class propertyListing{
    use controller;

    public function showListing(){
        $this->view('propertyListing');
    }
}