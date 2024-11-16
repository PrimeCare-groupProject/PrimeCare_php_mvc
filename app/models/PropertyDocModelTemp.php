<?php

class PropertyDocModelTemp
{
    use Model;

    protected $table = 'property_deed_image_temp';
    protected $order_column = "image_url";
    protected $allowedColumns = [
        'image_url',
        'property_id',
    ];

    public $errors = [];
}
