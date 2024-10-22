<?php
defined('ROOTPATH') or exit('Access denied');

class Products{
    use controller;
    public function index(){
        echo "this is the products controller";
    }
}

