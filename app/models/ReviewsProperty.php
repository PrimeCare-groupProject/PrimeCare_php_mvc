<?php

class ReviewsProperty
{
    use Model;

    protected $table = 'reviewsProperty';
    protected $order_column = "review_id";
    protected $allowedColumns = [
        'review_id',
        'person_id',
        'property_id',
        'message',
        'rating',
        'created_at'
    ];

    public $errors = [];

    public function getReviews($property_id)
    {
        $reviews = $this->where(['property_id' => $property_id]);
        if (!$reviews) {
            return false;
        }
        return $reviews;
    }

    public function calcRatings($property_id)
    {
        $reviews = $this->getReviews($property_id);
        if (!$reviews) {
            return false;
        }
        $totalRating = 0;
        $totalReviews = count($reviews);
        foreach ($reviews as $review) {
            $totalRating += $review->rating;
        }
        return $totalReviews > 0 ? $totalRating / $totalReviews : 0;
    }

}
