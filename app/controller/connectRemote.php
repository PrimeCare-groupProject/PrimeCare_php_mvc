<?php
$servername = "mysql-primecare.alwaysdata.net";
$username = "primecare";
$password = "Primecare@123";
$dbname = "primecare_db";

echo "Hello World!";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>