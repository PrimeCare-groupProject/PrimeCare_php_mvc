<?php
defined('ROOTPATH') or exit('Access denied');

trait Model{//similar to a class but can be inherited by other classes
    use Database;

    // public $table = 'users'; //table name which can inherit
    // public $order_column = "id";
    
    protected $limit        = 10;
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

    public function findAll(){//search rows depending on the data passed
        $query = "
            select * 
            from $this->table 
            order by $this->order_column $this->order_type 
            limit $this->limit 
            offset $this->offset
            ";
        // show($query);
        return $this->query($query);
    }
    
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $result = $this->query($query);
        if ($result) {
            return $result[0]->total;
        }
        return 0;
    }

    public function getTotalCountWhere($conditions = [], $searchTerm = "") {
        // Initialize the query
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $parameters = [];
    
        // If search term is provided
        if (!empty($searchTerm)) {
            // Add WHERE clause for search term across all columns
            $query .= " WHERE (";
            
            // Dynamically fetch column names for the table
            $columns = $this->getTableColumns();
            
            foreach ($columns as $column) {
                $query .= "$column LIKE :searchTerm OR ";
            }
    
            $query = rtrim($query, " OR "); // Remove the last ' OR '
            $query .= ")";
            
            // Bind search term with wildcards
            $parameters['searchTerm'] = '%' . $searchTerm . '%';
    
            // Add conditions with AND if any
            if (!empty($conditions)) {
                $query .= " AND ";
                foreach ($conditions as $key => $value) {
                    $query .= "$key = :$key AND ";
                    $parameters[$key] = $value;
                }
                $query = rtrim($query, " AND "); // Remove the last ' AND '
            }
        } else {
            // If no search term, process only conditions
            if (!empty($conditions)) {
                $query .= " WHERE ";
                foreach ($conditions as $key => $value) {
                    $query .= "$key = :$key AND ";
                    $parameters[$key] = $value;
                }
                $query = rtrim($query, " AND "); // Remove the last ' AND '
            }
        }
    
        // Execute the query
        $result = $this->query($query, $parameters);
    
        if ($result) {
            return $result[0]->total;
        }
    
        return 0;
    }
    
    // Helper method to dynamically fetch column names of the table
    private function getTableColumns() {
        $query = "SHOW COLUMNS FROM $this->table";
        $result = $this->query($query);
    
        if ($result) {
            return array_map(function ($column) {
                return $column->Field;
            }, $result);
        }
    
        return [];
    }
    

    public function where($data, $data_not = []){//search rows depending on the data passed
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "
            select * from $this->table 
            where 
            ";

        foreach($keys as $key){
            $query .= $key.' = :'.$key." && ";	
        }

        foreach($keys_not as $key){
            $query .= $key.' != :'.$key." && ";	
        }

        $query = trim($query, " && "); #remove the last ' && ' from the query
        $query .= "
            order by $this->order_column $this->order_type 
            limit $this->limit 
            offset $this->offset";
        $data = array_merge($data, $data_not);
        // show($query);
        return $this->query($query, $data);
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
        $result = $this->query($query, $data);
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
        $results = $this->query($query, $data);
        show($results);
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
        $result = $this->query($query, $data);        
        // Return true on success, false on failure
        return $result !== false;
    }

    public function delete($id, $id_column = 'id'){
        $data[$id_column] = $id;
        $query = "
            delete from $this->table 
            where $id_column = :$id_column";
        show($query);
        $result = $this->query($query, $data);
        
        return $result !== false;
    }
}