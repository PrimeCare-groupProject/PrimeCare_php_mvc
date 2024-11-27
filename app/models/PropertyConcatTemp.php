<?php
// Property concated class
class PropertyConcatTemp
{
    use Model;

    protected $table = 'property_with_images_temp';
    protected $order_column = "property_id";
    protected $allowedColumns = [
        'property_id',
        'name',
        'type',
        'description',
        'address',
        'zipcode',
        'city',
        'state_province',
        'country',
        'year_built',
        'rent_on_basis',
        'units',
        'size_sqr_ft',
        'bedrooms',
        'bathrooms',
        'floor_plan',
        'parking',
        'furnished',
        'status',
        'person_id',
        'agent_id',
        'property_images',
        'property_deed_images',
    ];

    public $errors = [];
}
