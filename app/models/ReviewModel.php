<?php
// Property class
class ReviewModel {
    use Model;

    protected $table = 'reviews';
    protected $order_column = "review_id";
    protected $allowedColumns = [
        'customer_name',
        'description',
        'property_id',
        'rating'
    ];

    public $errors = [];
    public $success = [];

    
}