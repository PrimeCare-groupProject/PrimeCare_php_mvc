<?php
// Property class
class BookingModel
{
    use Model;

    protected $table = 'booking';
    protected $order_column = "booking_id";
    protected $allowedColumns = [
        'property_id',
        'tenant_id',
        'agent_id',
        'booked_date',
        'start_date',
        'renting_period',
        'price',
        'customer_id',
        'status',
        'accept_status',
        'accepted_date'
    ];

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        return null;
    }

    public $errors = [];

}
