<?php
defined('ROOTPATH') or exit('Access denied');

class Service{
    use controller;

// Services crud
    public function create() {

    }

    public function read() {
        $service = new Services;
        $services = $service->findAll();
        $this->view('agent/repairings', ['services' => $services]);
    }

    public function update() {

    }

    public function delete() {

    }

}