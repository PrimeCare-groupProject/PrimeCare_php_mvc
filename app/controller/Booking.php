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
        $booking = new BookingModel();
        $booking->insert($data);
        redirect('booking/index');
    }

    public function update(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["action"])) {
                if ($_POST["action"] == "accept") {
                    // Call Accept Controller
                    $book =  new BookingModel;
                    $res = $book->update($_POST['booking_id'], ['accept_status' => 'accepted','accepted_date' => $_POST['accepteddate']], 'booking_id');
                     if ($res) {
                        // Set flash message in session
                        $_SESSION['flash_message'] = 'Booking Accepted successfully!';
        
                    } else {
                        // Handle failure (e.g., insert failed)
                        $_SESSION['flash_message'] = 'Failed to update booking. Please try again.';
                    }
                    $property = new Property;
                    $person = new User; 
                    $pid = $book->where(['booking_id' => $_POST['booking_id']],)[0];
                    $bookings = $book->selecthreetables($property->table,
                                                        'property_id', 
                                                        'property_id', 
                                                        $person->table,
                                                        'customer_id', 
                                                        'pid',
                                                        'booking_id',
                                                        $_POST['booking_id'],
                                                        'AND',
                                                        'customer_id',
                                                        $pid->customer_id
                                                        );
                    $this->view('agent/bookingaccept',['bookings'=> $bookings]);
                    exit;
                } elseif ($_POST["action"] == "reject") {
                    // Call Accept Controller
                    $book =  new BookingModel;
                    $res = $book->update($_POST['booking_id'], ['accept_status' => 'rejected'], 'booking_id');
                     if ($res) {
                        // Set flash message in session
                        $_SESSION['flash_message'] = 'Booking Rejected successfully!';
        
                    } else {
                        // Handle failure (e.g., insert failed)
                        $_SESSION['flash_message'] = 'Failed to reject booking. Please try again.';
                    }
                    $property = new Property;
                    $person = new User; 
                    $pid = $book->where(['booking_id' => $_POST['booking_id']],)[0];
                    $bookings = $book->selecthreetables($property->table,
                                                        'property_id', 
                                                        'property_id', 
                                                        $person->table,
                                                        'customer_id', 
                                                        'pid',
                                                        'booking_id',
                                                        $_POST['booking_id'],
                                                        'AND',
                                                        'customer_id',
                                                        $pid->customer_id
                                                        );
                    $this->view('agent/bookingaccept',['bookings'=> $bookings]);
                    exit;
                }
            }
        }
    }

}
