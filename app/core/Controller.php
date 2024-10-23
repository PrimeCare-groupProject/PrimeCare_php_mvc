<?php
defined('ROOTPATH') or exit('Access denied');


trait controller
{

    public function view($name, $data = [])
    {
        if (!empty($data)) {
            extract($data); //will make available to files below
        }
        $filename = "../app/views/" . $name . ".view.php";
        if (file_exists($filename)) {
            require $filename;
        } else {
            //$filename = "../app/views/user_view/".$name.".view.php";
            $filename = '../app/views/owner/' . $name . '.view.php';
            if (file_exists($filename)) {
                require $filename;
            } else {
                $filename = '../app/views/manager/' . $name . '.view.php';
                if (file_exists($filename)) {
                    require $filename;
                } else {
                    require "../app/views/404.view.php"; // if no page found, load 404 page
                }
            }
        }
    }
}
