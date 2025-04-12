<?php
// Property class
class PropertyDocModel
{
    use Model;

    protected $table = 'property_deed_image';
    protected $order_column = "image_url";
    protected $allowedColumns = [
        'image_url',
        'property_id',
    ];

    public $errors = [];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
