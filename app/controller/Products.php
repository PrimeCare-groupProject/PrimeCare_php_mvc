<?php
defined('ROOTPATH') or exit('Access denied');

class Products{
    use controller;
    public function index(){
        $booking = new PropertyBookings;
        // Example usage of PropertyBookings methods
        $propertyId = 22;
        $personId = 84;

        $this->view('test');
        // // Create a monthly booking
        // $monthlyResult = $booking->createMonthlyBooking($propertyId, $personId, '2023-11-01', '2023-11-30', 2000.00);
        // if (is_array($monthlyResult)) {
        //     echo "Errors: ";
        //     print_r($monthlyResult);
        // } else {
        //     echo "Monthly booking created successfully!";
        // }

        // Create a commercial booking
        // $commercialResult = $booking->createCommercialBooking($propertyId, $personId, '2023-10-01', '2023-10-31', 5000.00);
        // if (is_array($commercialResult)) {
        //     echo "Errors: ";
        //     print_r($commercialResult);
        // } else {
        //     echo "Commercial booking created successfully!<br>";
        // }

        // Check property availability
        // $availability = $booking->checkPropertyAvailability($propertyId, '2023-10-01', '2023-10-05');
        // if ($availability) {
        //     echo "Property is available for the selected dates.<br>";
        // } else {
        //     echo "Property is not available for the selected dates.<br>";

        // }

        // Get all bookings for a property
        // $activeBookings = $booking->getAllBookingsForProperty($propertyId);
        // if (is_array($activeBookings)) {
        //     echo "Errors: ";
        //     show($activeBookings);
        // } else {
        //     echo "Active bookings: ";
        //     print_r($activeBookings);
        // }
        
        // cancel booking
        // $cancelResult = $booking->cancelBooking(11,84);
        // if (is_array($cancelResult)) {
        //     echo "Errors: ";
        //     print_r($cancelResult);
        // } else {
        //     echo "Booking cancelled successfully!<br>";
        //     var_dump($cancelResult);
        // }

         // Get active bookings for a property
        // $activeBookings = $booking->getActiveBookingsForProperty($propertyId);
        // if (is_array($activeBookings)) {
        //     echo "Errors: ";
        //     show($activeBookings);
        // } else {
        //     echo "Active bookings: ";
        //     print_r($activeBookings);
        // }

        // Archive a completed booking
        // $bookingId = 10; // Example booking ID
        // $archiveResult = $booking->checkoutBooking($bookingId);
        // if (($archiveResult)) {
        //     echo "Booking with ID $bookingId archived successfully!<br>";
        // } else {
        //     echo "Booking with ID $bookingId failed successfully!<br>";
        // }

        // Terminate a lease early
        // $bookingId = 10; // Example booking ID
        // $reason = 'Tenant requested early termination due to relocation';
        // $terminationResult = $booking->terminateLeaseEarly($bookingId, $reason);
        // if ($terminationResult) {
        //     echo "Errors: ";
        //     print_r($terminationResult);
        // } else {
        //     echo "Lease terminated early successfully!<br>";
        // }

        // Get booking history for a person
        // $personBookingHistory = $booking->getPersonBookingHistory($personId);
        // if (is_array($personBookingHistory)) {
        //     echo "Errors: ";
        //     show($personBookingHistory);
        // } else {
        //     echo "Booking history for person ID $personId: ";
        //     print_r($personBookingHistory);
        // }
    }
}

