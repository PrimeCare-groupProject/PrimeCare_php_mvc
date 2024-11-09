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

/** 
*how to use get_img() 
*<img src="<?php echo get_img('672688e478161__user0@gmail.com.jpg', 'user'); ?>" alt="Profile Picture">
*or <img src="<?= get_img('672688e478161__user0@gmail.com.jpg', 'user'); ?>" alt="Profile Picture">
*/
function get_img($image_url, $type = 'user') {
    // Check if the provided URL is actually a URL
    if (filter_var($image_url, FILTER_VALIDATE_URL)) {
        return $image_url;
    }
    // Otherwise, assume it's a file name and construct the path
    $relPath =  "assets/images/uploads/" . ($type == 'property' ? 'property/' : 'profile_pictures/') . $image_url;
    $filePath =  ROOT .DIRECTORY_SEPARATOR. $relPath;
    if (file_exists($relPath)) {
        return $filePath;
    }
    // Return a default image if the file doesn't exist
    if($type == 'user'){
        return ROOT . "/assets/images/user.png";
    }else if ($type == 'property'){
        return ROOT . "/assets/images/property.png";
    }	
    // return ROOT . "/assets/images/user.png";
}

// require '../app/libraries/PHPMailer/send.php'; //send email
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'send.php';
