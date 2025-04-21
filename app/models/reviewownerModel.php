<?php
// Property class
class ReviewownerModel {
    use Model;

    protected $table = 'reviewowner';
    protected $order_column = "review_id";
    protected $allowedColumns = [
        'name',
        'description',
        'rating'
    ];

    public $errors = [];
    public $success = [];
 
}
 
