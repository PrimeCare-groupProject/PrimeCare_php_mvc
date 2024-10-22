 <?php
defined('ROOTPATH') or exit('Access denied');

 
 if($_SERVER['SERVER_NAME'] == 'localhost'){
   // database config local build environment
   define('DBNAME', 'my_db');
   define('DBHOST', 'localhost');
   define('DBUSER', 'root');
   define('DBPASS', '');
   define('ROOT', 'http://localhost/php_mvc_backend/public');
 }else{
   // database config for online platform
   define('DBNAME', 'my_db');
   define('DBHOST', 'localhost');
   define('DBUSER', 'root');
   define('DBPASS', '');
   define('ROOT', 'http://www.yourwebsite.com');
 }
    
 define('APP_NAME', 'PrimeCare');
 define('APP_DESC', 'Protect . Earn . Find');

//  true means show any errors
 define('DEBUG', true);
 