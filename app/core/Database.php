<?php
defined('ROOTPATH') or exit('Access denied');


use LDAP\Result;

class Database{

    private static $instance = null;
    private $con;

    private function __construct()
    {
        $string = "mysql:host=".DBHOST.";dbname=".DBNAME."";
        $con = new PDO($string, DBUSER, DBPASS);
        $this->con = $con;
    }

    public static function getInstance(){
        if (self::$instance == null)
        {
        self::$instance = new Database();
        }
    
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->con;
    }
    
    public function query($query, $data = []){
        try {
            $con = $this->getConnection();
            $stm = $con->prepare($query);
            // show($stm);
            // show($data);
            // die();
            $check = $stm->execute($data);
            // var_dump($check);
            // die();
            
            // For INSERT, UPDATE, DELETE queries, return the boolean execution result
            if ((stripos($query, 'select') === false) and (stripos($query, 'SHOW') === false) ) {
                return $check;  // Return true if executed successfully, false otherwise
            }
        
            // For SELECT queries, fetch the result
            if ($check) {
                $result = $stm->fetchAll(PDO::FETCH_OBJ);
                if (is_array($result) && count($result)) {
                    return $result;
                }
            }
            return false;  // No results or query failed
        } catch (PDOException $e) {
            // Handle database exceptions
            show($stm);
            show($data);
            echo "Database error: " . $e->getMessage();
            die();
            return false;
        } catch (Exception $e) {
            // Handle general exceptions
            echo "Error: " . $e->getMessage();
            die();
            return false;
        }
    }
    

    public function get_row($query, $data = []){
        $con = $this->getConnection();
        $stm = $con->prepare($query);

        $check = $stm->execute($data);
        if(is_array(($check))){
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if(is_array($result) && count($result)){
                return $result[0];
            }
        }
    }
}
