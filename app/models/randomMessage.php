<?php

class RandomMessage {
    use Model;

    protected $table = 'randomMessage';
    protected $order_column = "pid";
    protected $allowedColumns = [
        'message_id',
        'message',
        'status', //1 - not read , 0 - read
        'pid'
    ];

    public $errors = [];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
    
}
