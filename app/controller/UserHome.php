<?php
defined('ROOTPATH') or exit('Access denied');


class UserHome
{
    use controller;

    public function index()
    {
        $this->view('userhome');
    }
}
