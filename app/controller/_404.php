
<?php
defined('ROOTPATH') or exit('Access denied');

class _404{
    use controller;
    public function index($a = '', $b = '', $c = '', $d = ''){
        echo $a . "<br>";
        echo $b . "<br>";
        echo $c . "<br>";
        echo $d . "<br>";
        echo "404 page not found";
        
        $this->view('404');
    }
}

