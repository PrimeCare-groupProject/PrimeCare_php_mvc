<?php
defined('ROOTPATH') or exit('Access denied');

class Review
{
    use controller;

    public function review()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $propertyId = $_POST['property_id'];
            $name = trim($_POST['reviewer_name']);
            $rating = floatval($_POST['rating']);
            $review = $_POST['review'];

            $data = [
                'property_id' => $propertyId,
                'customer_name' => $name,
                'description' => $review,
                'rating' => $rating
            ];

            $review = new ReviewModel;
            $review->insert($data);

            $property = new PropertyConcat;
            $propertyUnit = $property->where(['property_id' => $propertyId])[0];
        //show($propertyUnit);

            $reviews = $review->findAll();
            $this->view('customer/propertyUnit', ['property' => $propertyUnit, 'reviews' => $reviews]);

        }   
    }

    

}