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


function upload_image(
    array $uploaded_files,
    string $target_dir,
    object $model,
    int $foreign_key_id,
    array $options = []
): array {
    $errors = [];
    $defaults = [
        'allowed_ext' => ['jpg', 'jpeg', 'png'],
        'prefix' => 'file',
        'url_field' => 'image_url',
        'fk_field' => 'property_id',
        'max_size' => 5242880, // 5MB in bytes
        'overwrite' => false
    ];
    $config = array_merge($defaults, $options);

    // Create directory if it doesn't exist
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Check if files were uploaded
    if (empty($uploaded_files['name'][0])) {
        $errors[] = 'No files selected for upload';
        return $errors;
    }

    foreach ($uploaded_files['name'] as $index => $filename) {
        $tmp_name = $uploaded_files['tmp_name'][$index];
        $error = $uploaded_files['error'][$index];
        $size = $uploaded_files['size'][$index];

        // Skip empty fields
        if ($error === UPLOAD_ERR_NO_FILE) continue;

        // Handle upload errors
        if ($error !== UPLOAD_ERR_OK) {
            $errors[] = "Upload error (#$error) for: " . esc($filename);
            continue;
        }

        // Validate file size
        if ($size > $config['max_size']) {
            $errors[] = "File too large: " . esc($filename) . " (" . format_bytes($size) . ")";
            continue;
        }

        // Get file extension
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        // Validate extension
        if (!in_array($file_ext, $config['allowed_ext'])) {
            $errors[] = "Invalid file type for: " . esc($filename);
            continue;
        }

        // Generate unique filename
        $new_filename = $config['prefix'] . '_' . uniqid() . '_' . $foreign_key_id . '.' . $file_ext;
        $full_path = rtrim($target_dir, '/') . '/' . $new_filename;

        // Check if file exists
        if (!$config['overwrite'] && file_exists($full_path)) {
            $errors[] = "File already exists: " . esc($filename);
            continue;
        }

        // Move uploaded file
        if (move_uploaded_file($tmp_name, $full_path)) {
            // Save to database
            $model->insert([
                $config['url_field'] => $new_filename,
                $config['fk_field'] => $foreign_key_id
            ]);
        } else {
            $errors[] = "Failed to save: " . esc($filename);
        }
    }

    return $errors;
}

// Helper function to format bytes
function format_bytes($bytes, $precision = 2): string
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= (1 << (10 * $pow));
    return round($bytes, $precision) . ' ' . $units[$pow];
}
