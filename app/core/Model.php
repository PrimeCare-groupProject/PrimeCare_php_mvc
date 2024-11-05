<?php
trait Model{//similar to a class but can be inherited by other classes
    use Database;

    // protected $table = 'users'; //table name which can inherit
    // protected $order_column = "id";
    
    protected $limit        = 10;
    protected $offset       = 0;
    protected $order_type   = "desc";
    public    $errors       = [];

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
    
    public function first($data, $data_not){//search row depending on the data passed
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
        $this->query($query, $data);
        
        return false;
    }
}