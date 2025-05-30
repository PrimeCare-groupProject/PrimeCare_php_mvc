<?php

use function PHPSTORM_META\type;

defined('ROOTPATH') or exit('Access denied');

trait Model{//similar to a class but can be inherited by other classes
    public $instance;

    public function __construct() {
        $this->instance = Database::getInstance();
    }
    // public $table = 'users'; //table name which can inherit
    // public $order_column = "id";
    
    protected $limit        = 100;
    protected $offset       = 0;
    protected $order_type   = "desc";
    public    $errors       = [];

    public function setOffset($new){
        return $this->offset = $new;
    }
    public function setLimit($limit) {
        $this->limit = $limit;
    }

    public function getOffset() {
        return $this->offset;
    }

    public function setOrderType($order_type) {
        $this->order_type = $order_type;
    }

    public function getOrderType() {
        return $this->order_type;
    }

    public function setErrors($errors) {
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getLimit (){
        return $this->limit;
    }
    // Helper method to dynamically fetch column names of the table
    private function getTableColumns() {
        $query = "SHOW COLUMNS FROM $this->table";
        $result = $this->instance->query($query);
        if ($result) {
            return array_map(function ($column) {
                return $column->Field;
            }, $result);

        }
    
        return [];
    }

    public function findAll(){//search rows depending on the data passed
        $query = "
            select * 
            from $this->table 
            order by $this->order_column $this->order_type 
            limit $this->limit 
            offset $this->offset
            ";
         //show($query);
        return $this->instance->query($query);
    }
    
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $result = $this->instance->query($query);
        if ($result) {
            return $result[0]->total;
        }
        return 0;
    }

    //with searchterm also included
    public function getTotalCountWhere($data = [], $data_not = [], $searchTerm = "") {
        // Initialize the query and parameters
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $parameters = [];
    
        // Start building the WHERE clause
        $whereClauses = [];
    
        // Add positive conditions to the WHERE clause
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $whereClauses[] = "$key = :$key";
                $parameters[$key] = $value; // Bind condition parameters
            }
        }
    
        // Add negative conditions to the WHERE clause
        if (!empty($data_not)) {
            foreach ($data_not as $key => $value) {
                $whereClauses[] = "$key != :not_$key";
                $parameters["not_$key"] = $value; // Bind negative condition parameters
            }
        }
    
        // Add search term conditions to the WHERE clause
        if (!empty($searchTerm)) {
            // Dynamically fetch column names for the table
            $columns = $this->getTableColumns();
            $searchClauses = [];
    
            foreach ($columns as $column) {
                $searchClauses[] = "$column LIKE :searchTerm";
            }
    
            if (!empty($searchClauses)) {
                // Combine search clauses with OR
                $whereClauses[] = "(" . implode(" OR ", $searchClauses) . ")";
            }
    
            // Bind search term with wildcards
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
        }
    
        // Combine all WHERE clauses with AND
        if (!empty($whereClauses)) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }
    
        // Execute the query
        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        $result = $this->instance->query($query, $parameters);
        if ($result) {
            return $result[0]->total;
        }
    
        return 0;
    }
    
    //with searchterm also included
    public function where($data , $data_not = [], $searchTerm = "") {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        
        // Initialize the query
        $query = "SELECT * FROM $this->table ";
        $parameters = [];
        if(!empty($keys) || !empty($keys_not) || strlen($searchTerm) > 0){
            $query .= "WHERE ";
        }
        
        // Add conditions for the data array (exact match)
        if (!empty($keys)) {
            foreach ($keys as $key) {
                $query .= "$key = :$key AND ";
                $parameters[$key] = $data[$key];  // Bind the parameter value
            }
        }
        
        // Add conditions for the data_not array (not equal)
        if (!empty($keys_not)) {
            foreach ($keys_not as $key) {
                $query .= "$key != :$key AND ";
                $parameters[$key] = $data_not[$key];  // Bind the parameter value
            }
        }
        
        // If a search term is provided, add LIKE conditions for each column
        if (!empty($searchTerm)) {
            $searchQuery = $this->searchWithTerm($searchTerm); // Use the searchWithTerm function
            if ($searchQuery) {
                $query .= $searchQuery . " AND "; // Append the search condition
                $parameters['searchTerm'] = '%' . $searchTerm . '%'; // Bind the search term with wildcards
            }
        }
        // Remove the last ' AND ' from the query
        $query = rtrim($query, " AND ");
    
        // Add order, limit, and offset
        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        
        // Execute the query and return the result
        return $this->instance->query($query, $parameters);
    }

    
    private function searchWithTerm($searchTerm) {
        if (empty($searchTerm)) {
            return '';
        }
    
        // Dynamically fetch column names for the table
        $columns = $this->getTableColumns(); // Assuming this function fetches table columns
        
        // Make sure there are columns to search
        if (empty($columns)) {
            return ''; // No columns to search
        }

        $searchQuery = "(";
        foreach ($columns as $column) {
            $searchQuery .= "$column LIKE :searchTerm OR ";
        }
        $searchQuery = rtrim($searchQuery, " OR "); // Remove the last ' OR '
        $searchQuery .= ")";
    
        // Return the search query portion
        return $searchQuery;
    }
    
    public function first($data, $data_not = []){//search row depending on the data passed
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "
            select * from $this->table 
            where ";

        foreach($keys as $key){
            $query .= $key.' = :'.$key." && ";	
        }

        foreach($keys_not as $key){
            $query .= $key.' != :'.$key." && ";	
        }

        $query = trim($query, " && "); #remove the last ' && ' from the query
        $query .= " limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);
        // show($query);
        $result = $this->instance->query($query, $data);
        if($result){
            return $result[0];
        }
        return false;
    }
    
    public function insert($data){
        $keys = array_keys($data);
        $query = "
            insert into $this->table (".implode(", ", $keys).") 
            values  (:".implode(', :', $keys).") "; //impolde is used to concat list to string
        // echo $query;
        // remove unwanted data from the data array
        if(!empty($this->allowedColumns)){
            foreach($keys as $key){
                if(!in_array($key, $this->allowedColumns)){
                    unset($data[$key]); #remove key from the data
                }
            }
        }
        $results = $this->instance->query($query, $data);
        // var_dump($results);
        return $results ? true : false ;

    }

    public function update($id, $data, $id_column = 'id'){    
        $keys = array_keys($data);
        $query = "update $this->table set ";
    
        foreach($keys as $key){
            $query .= "$key = :$key, ";	
        }
    
        $query = rtrim($query, ", "); // Remove the last comma from the query
        $query .= " where $id_column = :$id_column";
        
        // Bind the ID to the data array
        $data[$id_column] = $id;        
        $result = $this->instance->query($query, $data);        
        // Return true on success, false on failure
        return $result !== false;
    }

    public function delete($id, $id_column = 'id'){
        $data[$id_column] = $id;
        $query = "
            delete from $this->table 
            where $id_column = :$id_column";
        // show($query);
        $result = $this->instance->query($query, $data);
        
        return $result !== false;
    }
    
    // Add this to your BaseModel or one of your specific models
    public function selecttwotables($otherModel, $thisColumn, $otherColumn,$filterColumn1 = null, $filterValue1 = null,$filterColumn2 = null, $filterValue2 = null) {
        $query = "SELECT t1.*, t2.* 
                FROM {$this->table} t1 
                JOIN {$otherModel} t2 
                ON t1.{$thisColumn} = t2.{$otherColumn}";
        
        if ($filterValue1 !== null || $filterColumn1) {
            $query .= " WHERE t1.{$filterColumn1} = {$filterValue1}";
        }
        if ($filterValue2 !== null || $filterColumn2) {
            $query .= " AND t1.{$filterColumn2} = {$filterValue2}";
        }
        //show($query);
        return $this->instance->query($query);
    }

    public function selecthreetables($otherModel1, $thisColumn, $otherColumn1,$otherModel2,$othercolumn2,$otherColumn3,$filterColumn1 = null,$filterValue1 = null,$connection1 = null,$filterColumn2 = null, $filterValue2 = null,$connection2 = null,$filterColumn3 = null, $filterValue3 = null) {
        $query = "SELECT t1.*, t2.*, t3.*
                FROM {$this->table} t1 
                JOIN {$otherModel1} t2 
                ON t1.{$thisColumn} = t2.{$otherColumn1}
                JOIN {$otherModel2} t3 
                ON t1.{$othercolumn2} = t3.{$otherColumn3}";
        
        if ($filterValue1 !== null || $filterColumn1) {
            $query .= " WHERE t1.{$filterColumn1} = {$filterValue1}";
        }
        if ($filterValue2 !== null || $filterColumn2) {
            $query .= " {$connection1} t1.{$filterColumn2} = {$filterValue2}";
        }
        if ($filterValue3 !== null || $filterColumn3) {
            $query .= " {$connection2} t3.{$filterColumn3} = {$filterValue3}";
        }
        return $this->instance->query($query);
    }

    public function setReadNotification($user_id) {
        $query = "UPDATE $this->table SET is_read = 1 WHERE user_id = :user_id";
        $parameters = ['user_id' => $user_id]; // properly structured array
        return $this->instance->query($query, $parameters);
    }
    
}