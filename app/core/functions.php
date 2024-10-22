<?php

function show($stuff){
    echo '<pre>';
    print_r($stuff);
    echo '</pre>';
}

function esc($str){
    return htmlspecialchars($str);
}

function redirect($path){
    header("location: ".ROOT."/".$path);
}

function check_extensions(){
    $required_extensions = [
        // 'gd',
        'mysqli',
        'pdo_mysql',
        'pdo_sqlite',
        'curl',
        'fileinfo',
        // 'intl',
        'exif',
        'mbstring',
    ];

    $not_installed = [];

    foreach($required_extensions as $ext){
        if(!extension_loaded($ext)){
            $not_installed[] = $ext;
        }
    }

    if(count($not_installed) > 0){
        show('Required extensions not installed : '.implode(', ', $not_installed));
        die;
    }
}
check_extensions();

//load image if exists
// function get_image(mixed $file = '' ,string $type = 'post'): string
// {
//     $file = $file ?? '';
//     if(file_exists($file)){
//         return ROOT . "/" . $file;
//     }
//     return ROOT."/assets/images/no-image.png";
// }

//returns pagination links
function get_pagination_vars():array{
    $vars = [];
    $vars['page'] = $_GET['page'] ?? 1;
    $vars['page'] = (int) $vars['page'];
    $vars['prev_page'] = $vars['page'] <= 1 ? 1 : $vars['page'] - 1;
    $vars['next_page'] = $vars['page'] + 1;

    return $vars;
}

//returns old values of form fields after refresh
function  old_value(string $key, mixed $default = '',string $mode = 'post'):mixed{
    $POST = ($mode == 'post') ? $_POST : $_GET;
    if(isset($POST[$key])){
        return $POST[$key];
    }
    return $default;
}//to use - <input value="old_value('name','defsult value')">

//returns old checks of form fields after refresh
function old_check(string $key, string $value = "", mixed $default = ''):mixed{
    if(isset($POST[$key])){
        if($POST[$key] == $value){
            return ' checked ';
        }
    }else{
        if($_SERVER['REQUEST_METHOD'] == 'GET' && ($default == $value)){
            return ' checked ';
        }
    }
    return '';
} //to use - <input type="checkbox" old_check('name','checked')>

//returns old selects of form fields after refresh
function old_select(string $key, mixed $value, mixed $default = '',  string $mode = ""):mixed{
    $POST = ($mode == 'post') ? $_POST : $_GET;
    if(isset($POST[$key])){
        if($POST[$key] == $value){
            return ' selected ';
        }
    }else{
        if($default == $value){
            return ' selected ';
        }
    }
    return '';
} //to use - <select><option value="1" old_select('name','1')>1</option></select>

//converting dates into user readable format
function get_date(string $date, string $format = 'jS M, Y'):string{
    return date($format, strtotime($date));
}

//get part of url needed
function URL($key){
    $URL = $_GET['url'] ?? 'home'; // make home default
    $URL = explode('/', trim($URL, '/')); // split based on '/'
    
    switch($key){
        case 'controller':
        case 'page':
        case 0:
            return $URL[0] ?? null;

        case 'method':
        case 'section':
        case 1:
            return $URL[1] ?? null;

        case 'params':
        case 'action':
        case 2:
            return $URL[2] ?? null;
        
        case 3:
            return $URL[3] ?? null;    
        default:
            return array_slice($URL, 4);
    }
}

