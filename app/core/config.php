 <?php
defined('ROOTPATH') or exit('Access denied');

 
 if($_SERVER['SERVER_NAME'] == 'localhost'){
   define('ROOT', 'http://localhost/php_mvc_backend/public');
   // database config local dev environment
    // define('DBNAME', 'my_db');
    // define('DBHOST', 'localhost');
    // define('DBUSER', 'root');
    // define('DBPASS', '');
  // database config remote dev environment
   define('DBNAME', 'primecare_all');
   define('DBHOST', 'mysql-primecare.alwaysdata.net');
   define('DBUSER', 'primecare');
   define('DBPASS', 'Primecare@123');
 }else{
   // database config for online platform
   define('DBNAME', 'my_db');
   define('DBHOST', 'localhost');
   define('DBUSER', 'root');
   define('DBPASS', '');
   define('ROOT', 'http://primecare@alwaysdata.net');
 }
    
 define('APP_NAME', 'PrimeCare');
 define('APP_DESC', 'Protect . Earn . Find');

//  true means show any errors
 define('DEBUG', true);
 