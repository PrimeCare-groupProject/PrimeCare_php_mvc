<?php
// Property class
class PropertyImageModel
{
    use Model;

    protected $table = 'property_image';
    protected $order_column = "image_url";
    protected $allowedColumns = [
        'image_url',
        'property_id',
    ];

    public $errors = [];
}
