<?php
defined('ROOTPATH') or exit('Access denied');

class Property {
    use Model;

    protected $table = 'properties';  // The table this model is associated with
    protected $order_column = 'created_at';  // Example order column
    protected $allowedColumns = ['name', 'description', 'price', 'location'];  // Define allowed columns for insertion
}
