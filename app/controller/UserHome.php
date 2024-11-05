<?php

class UserHome
{
    use controller;

    public function index()
    {
        $this->view('userhome');
    }
}
