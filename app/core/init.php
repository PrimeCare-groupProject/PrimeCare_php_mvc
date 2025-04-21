<?php
defined('ROOTPATH') or exit('Access denied');

#load all other controllers in the folder
#all php files in core will definitly load so all must be called
// spl_autoload_register(function($classname){
//     require $filename = "../app/models/".ucfirst($classname).".php";	
// });#if no class is found, find it and load it

spl_autoload_register(function($classname) {
    $paths = [
        "../app/models/" . ucfirst($classname) . ".php"
        //"../app/controller/" . ucfirst($classname) . ".php",
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});


require 'config.php';
require 'functions.php';
require 'Database.php'; #capitals becuz classes
require 'Model.php';
require 'Controller.php';
require 'App.php';
require 'Pagination.php';
// require './../libraries/PHPMailer/send.php';
