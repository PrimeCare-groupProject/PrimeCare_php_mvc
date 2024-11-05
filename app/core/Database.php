<?php

use LDAP\Result;

trait Database{

    private function connect(){
        $string = "mysql:hostname=".DBHOST.";dbname=".DBNAME."";
        $con = new PDO($string, DBUSER, DBPASS);
        return $con;
    }
    
    public function query($query, $data = []) {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $check = $stm->execute($data);
        
        // For INSERT, UPDATE, DELETE queries, return the boolean execution result
        if (stripos($query, 'select') === false) {
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
    }
    

    public function get_row($query, $data = []){
        $con = $this->connect();
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
