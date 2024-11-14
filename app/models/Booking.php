<?php
// Property class
class Booking
{
    use Model;

    protected $table = 'booking';
    protected $order_column = "booking_id";
    protected $allowedColumns = [
        'property_id',
        'tenant_id',
        'agent_id',
        'booked_date',
        'renting_period'
    ];

    public $errors = [];
}
