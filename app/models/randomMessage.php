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

    
}
