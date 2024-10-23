<?php

session_start();
$minPHPVersion = '8.0';
if (phpversion() < $minPHPVersion) {
    die("You need PHP version $minPHPVersion or higher to run this program");
}

define('ROOTPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);//define the root path

require '../app/core/init.php';

DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new App();
$app->loadController();