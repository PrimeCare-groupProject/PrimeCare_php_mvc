<?php

function show($stuff)
{
    echo '<pre>';
    print_r($stuff);
    echo '</pre>';
}

function esc($str)
{
    return htmlspecialchars($str);
}

function redirect($path)
{
    header("location: " . ROOT . "/" . $path);
}

function check_extensions()
{
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

    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $not_installed[] = $ext;
        }
    }

    if (count($not_installed) > 0) {
        show('Required extensions not installed : ' . implode(', ', $not_installed));
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
function get_pagination_vars(): array
{
    $vars = [];
    $vars['page'] = $_GET['page'] ?? 1;
    $vars['page'] = (int) $vars['page'];
    $vars['prev_page'] = $vars['page'] <= 1 ? 1 : $vars['page'] - 1;
    $vars['next_page'] = $vars['page'] + 1;

    return $vars;
}

//returns old values of form fields after refresh
function  old_value(string $key, mixed $default = '', string $mode = 'post'): mixed
{
    $POST = ($mode == 'post') ? $_POST : $_GET;
    if (isset($POST[$key])) {
        return $POST[$key];
    }
    return $default;
} //to use - <input value="old_value('name','defsult value')">

//returns old checks of form fields after refresh
function old_check(string $key, string $value = "", mixed $default = ''): mixed
{
    if (isset($POST[$key])) {
        if ($POST[$key] == $value) {
            return ' checked ';
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && ($default == $value)) {
            return ' checked ';
        }
    }
    return '';
} //to use - <input type="checkbox" old_check('name','checked')>

//returns old selects of form fields after refresh
function old_select(string $key, mixed $value, mixed $default = '',  string $mode = ""): mixed
{
    $POST = ($mode == 'post') ? $_POST : $_GET;
    if (isset($POST[$key])) {
        if ($POST[$key] == $value) {
            return ' selected ';
        }
    } else {
        if ($default == $value) {
            return ' selected ';
        }
    }
    return '';
} //to use - <select><option value="1" old_select('name','1')>1</option></select>

//converting dates into user readable format
function get_date(string $date, string $format = 'jS M, Y'): string
{
    return date($format, strtotime($date));
}

//get part of url needed
function URL($key)
{
    $URL = $_GET['url'] ?? 'home'; // make home default
    $URL = explode('/', trim($URL, '/')); // split based on '/'

    switch ($key) {
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
function get_img($image_url = "", $type = 'user')
{
    // Check if the provided URL is actually a URL
    if (filter_var($image_url, FILTER_VALIDATE_URL)) {
        return $image_url;
    }
    // Otherwise, assume it's a file name and construct the path
    $relPath =  "assets/images/uploads/" . ($type == 'property' ? 'property_image/' : 'profile_pictures/') . $image_url;
    $filePath =  ROOT . DIRECTORY_SEPARATOR . $relPath;
    if (file_exists($relPath)) {
        return $filePath;
    }
    // Return a default image if the file doesn't exist
    if ($type == 'user') {
        return ROOT . "/assets/images/user.png";
    } else if ($type == 'property') {
        return ROOT . "/assets/images/property.png";
    }
    if (empty($image_url)) {
        if ($type == 'user') {
            return ROOT . "/assets/images/user.png";
        } else if ($type == 'property') {
            return ROOT . "/assets/images/property.png";
        }
    }
    // return ROOT . "/assets/images/user.png";
}

// require '../app/libraries/PHPMailer/send.php'; //send email
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'PHPMailer' . DIRECTORY_SEPARATOR . 'send.php';

// This function for show flash messages
// type could be success, error, warning, info
// function flash_message($message, $type = 'info') {
//     $colors = (object) [
//         'bgColor' => '',
//         'textColor' => ''
//     ];
//     $icon = '';

//     // Assign colors and icons based on message type
//     switch ($type) {
//         case 'success':
//             $colors->bgColor = '#d4edda'; // Light green
//             $colors->textColor = '#155724'; // Dark green
//             $icon = 'fas fa-check-circle'; // Check icon for success
//             break;
//         case 'error':
//             $colors->bgColor = '#f8d7da'; // Light red
//             $colors->textColor = '#721c24'; // Dark red
//             $icon = 'fas fa-times-circle'; // Times icon for error
//             break;
//         case 'warning':
//             $colors->bgColor = '#fff3cd'; // Light yellow
//             $colors->textColor = '#856404'; // Dark yellow
//             $icon = 'fas fa-exclamation-circle'; // Exclamation icon for warning
//             break;
//         case 'info':
//         default:
//             $colors->bgColor = '#d1ecf1'; // Light blue
//             $colors->textColor = '#0c5460'; // Dark blue
//             $icon = 'fas fa-info-circle'; // Info icon for informational messages
//             break;
//     }

//     if (isset($message)) {
//     echo '
//         <div class="alert show
//         ' . ($type) . '"
//         style="background: ' . $colors->bgColor . '; color: ' . $colors->textColor . '; border-left: 8px solid ' . $colors->textColor . '; padding: 10px; margin: 10px 0; border-radius: 4px; display: flex; align-items: center;">
//             <span><i class="' . $icon . '" style="margin-right: 10px; color: ' . $colors->textColor . '"></i></span>
//             <span class="msg" style="flex: 1; color: ' . $colors->textColor . '">' . $message . '</span>
//             <div class="close-btn-alert" style="cursor: pointer; margin-left: auto; color: ' . $colors->textColor . ';">
//                 <span><i class="fas fa-times"></i></span>
//             </div>
//         </div>
//         <script>
//             document.addEventListener("DOMContentLoaded", () => {
//                 const alertBox = document.querySelector(".alert");
//                 const closeBtn = document.querySelector(".close-btn-alert");

//                 // Close on button click
//                 closeBtn.addEventListener("click", (event) => {
//                     event.preventDefault(); // Prevent default behavior
//                     alertBox.classList.remove("show");
//                     alertBox.classList.add("hide");
//                 });

//                 // Auto-hide after 5 seconds
//                 setTimeout(() => {
//                     if (alertBox.classList.contains("show")) {
//                         alertBox.classList.remove("show");
//                         alertBox.classList.add("hide");
//                     }
//                 }, 5000);
//             });
//         </script>
//     ';
// }

// }

// flash message function
        // public function index()
        // {
        //     $flash = [
        //         'msg' => "This is the message",
        //         'type' => "success"
        //     ];
        //     $_SESSION['flash'] = $flash;


        //     $this->view('owner/dashboard', [
        //         'user' => $_SESSION['user'],
        //         'errors' => $_SESSION['errors'] ?? [],
        //         'status' => $_SESSION['status'] ?? ''
        //     ]);
        // }
    // Use this notation to show flash messages

function flash_message()
{

    $message = $_SESSION['flash']['msg'];
    $type = $_SESSION['flash']['type'];
    $colors = (object) [
        'bgColor' => '',
        'textColor' => ''
    ];
    $icon = '';

    // Assign colors and icons based on message type
    switch ($type) {
        case 'success':
            $colors->bgColor = '#d4edda'; // Light green
            $colors->textColor = '#155724'; // Dark green
            $icon = 'fas fa-check-circle'; // Check icon for success
            break;
        case 'error':
            $colors->bgColor = '#f8d7da'; // Light red
            $colors->textColor = '#721c24'; // Dark red
            $icon = 'fas fa-times-circle'; // Times icon for error
            break;
        case 'warning':
            $colors->bgColor = '#fff3cd'; // Light yellow
            $colors->textColor = '#856404'; // Dark yellow
            $icon = 'fas fa-exclamation-circle'; // Exclamation icon for warning
            break;
        case 'info':
        default:
            $colors->bgColor = '#d1ecf1'; // Light blue
            $colors->textColor = '#0c5460'; // Dark blue
            $icon = 'fas fa-info-circle'; // Info icon for informational messages
            break;
    }

    if (isset($message)) {
        echo '
        <div class="alert show
        ' . ($type) . '"
        style="background: ' . $colors->bgColor . '; color: ' . $colors->textColor . '; border-left: 8px solid ' . $colors->textColor . '; padding: 10px; margin: 10px 0; border-radius: 4px; display: flex; align-items: center;">
            <span><i class="' . $icon . '" style="margin-right: 10px; color: ' . $colors->textColor . '"></i></span>
            <span class="msg" style="flex: 1; color: ' . $colors->textColor . '">' . $message . '</span>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const alertBox = document.querySelector(".alert");
                const closeBtn = document.querySelector(".close-btn-alert");
            
                // Auto-hide after 5 seconds
                setTimeout(() => {
                    if (alertBox.classList.contains("show")) {
                        alertBox.classList.remove("show");
                        alertBox.classList.add("hide");
                    }
                }, 3000);
            });
        </script>
    ';

        unset($_SESSION['flash']);
    }
}
