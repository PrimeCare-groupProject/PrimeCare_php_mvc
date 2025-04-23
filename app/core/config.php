 <?php
  defined('ROOTPATH') or exit('Access denied');


  if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('ROOT', 'http://localhost/php_mvc_backend/public');
    // database config remote dev environment
    define('DBNAME', 'primecare_all');
    define('DBHOST', 'mysql-primecare.alwaysdata.net');
    define('DBUSER', 'primecare');
    define('DBPASS', 'Primecare@123');
  } else {
    // database config for online platform
    define('DBNAME', 'my_db');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');
    define('ROOT', 'http://primecare@alwaysdata.net');
  }

  define('APP_NAME', 'PrimeCare');
  define('APP_DESC', 'Protect . Earn . Find');
  define('RENTAL_PRICE', 1000); // rental price for a day
  define('ADVANCE_PERCENTAGE', 10); // advance price for a day

  //  true means show any errors
  define('DEBUG', true);
  define('APPROOT', dirname(dirname(__FILE__))); 

  define('MERCHANT_ID', '1230089'); 
  define('MERCHANT_SECRET', 'MzY1MzU1Njc1MTI4MDcwMzM0OTkyOTg4MDc4MzUxNDc2MjEwOTc3'); 
  // Test VISA card Number: 4916217501611292
  define('PAYHERE_URL', 'https://sandbox.payhere.lk/pay/checkout'); 
  define('Notification_count' , 10);
  define('MANAGER_ID' , 63);
