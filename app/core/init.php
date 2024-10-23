<?php
defined('ROOTPATH') or exit('Access denied');

#load all other controllers in the folder
#all php files in core will definitly load so all must be called
spl_autoload_register(function($classname){
    require $filename = "../app/models/".ucfirst($classname).".php";	
});#if no class is found, find it and load it
require 'config.php';
require 'functions.php';
require 'Database.php'; #capitals becuz classes
require 'Model.php';
require 'Controller.php';
require 'App.php';