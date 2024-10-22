
<?php
defined('ROOTPATH') or exit('Access denied');

class _404{
    use controller;
    public function index($a = '', $b = '', $c = ''){
        echo "404 page not found";
        
        $this->view('404');
    }
}

