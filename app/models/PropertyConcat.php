<?php
// Property concated class
class PropertyConcat
{
    use Model;

    protected $table = 'propertyTable_with_images';
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
        'size_sqr_ft',
        'number_of_floors',
        'floor_plan',

        'units',
        'bedrooms',
        'bathrooms',
        'kitchen',
        'living_room',

        'furnished',
        'furniture_description',

        'parking',
        'parking_slots',
        'type_of_parking',

        'utilities_included',
        'additional_utilities',
        'additional_amenities',
        'security_features',

        'purpose',
        'rental_period',
        'rental_price',

        'owner_name',
        'owner_email',
        'owner_phone',
        'additional_contact',

        'special_instructions',
        'legal_details',

        'status',
        'person_id',
        'agent_id',
        'property_images',
        'property_deed_images',
    ];

    public $errors = [];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }
}
