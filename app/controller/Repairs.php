<?php
defined('ROOTPATH') or exit('Access denied');

class Repairs
{
    use controller;

    public function index()
    {
        $this->view('ServiceProvider/repairs');
    }
}
