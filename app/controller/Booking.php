<?php
defined('ROOTPATH') or exit('Access denied');

class Booking
{
    use controller;

    public function create(){
        $data = [
            'property_id' => $_POST['property_id'],
            'tenant_id' => $_POST['tenant_id'],
            'agent_id' => $_POST['agent_id'],
            'booked_date' => $_POST['booked_date'],
            'renting_period' => $_POST['renting_period']
        ];
        $booking = new Booking();
        $booking->insert($data);
        redirect('booking/index');
    }


}
