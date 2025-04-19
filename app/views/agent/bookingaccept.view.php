Isira Shehan, [4/13/2025 3:07 PM]
<?php require_once 'agentHeader.view.php'; $booking = $bookings[0];?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/bookings'>
        <button class="back-btn"><img src="<?= ROOT ?>/assets/images/backButton.png" alt="Back" class="navigate-icons"></button>
    </a>
    <h2>Bookings</h2>
</div>

<form method="POST" action="<?= ROOT ?>/Booking/update" enctype="multipart/form-data">
<?php foreach ($images as $image): ?>
    <?php if ($booking->property_id == $image->property_id): ?>
        <div class="booking-details-container">
            <div class="booking-details-column">
                <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image->image_url ?>" alt="Property Image" class="booking-image">

                <input type="hidden" name="booking_id" value="<?= $booking->booking_id ?>">
                <input type="hidden" name="accepteddate" value="<?php echo date('Y-m-d'); ?>">

                <label class="form-label">Property Name</label>
                <input type="text" name="propertyname" value="<?= $booking->name ?>" class="form-input" readonly>

                <label class="form-label">Province</label>
                <input type="text" name="province" value="<?= $booking->state_province?>" class="form-input" readonly>

                <label class="form-label">Country</label>
                <input type="text" name="country" value="<?= $booking->country?>" class="form-input" readonly>

                <label class="form-label">City</label>
                <input type="text" name="city" value="<?= $booking->city?>" class="form-input" readonly>

                <label class="form-label">Address</label>
                <input type="text" name="address" value="<?= $booking->address?>" class="form-input" readonly>
            </div>
            <div class="customer-details-column">
                <label class="form-label">Customer Name</label>
                <input type="text" name="customername" value="<?= $booking->fname?> <?= $booking->lname?>" class="form-input" readonly>

                <label class="form-label">Customer ID</label>
                <input type="text" name="customerId" value="<?= $booking->pid?>" class="form-input" readonly>

                <label class="form-label">Email</label>
                <input type="email" name="email" value="<?= $booking->email?>" class="form-input" readonly>

                <label class="form-label">Mobile Number</label>
                <input type="email" name="mobilenumber" value="<?= $booking->contact?>" class="form-input" readonly>

                <label class="form-label">Booked Date</label>
                <input type="email" name="bookedDate" value="<?= $booking->booked_date?>" class="form-input" readonly>

                <label class="form-label">Lease Starting Date</label>
                <input type="email" name="startingDate" value="<?= $booking->start_date?>" class="form-input" readonly>

                <label class="form-label">Renting Period (Months)</label>
                <input type="email" name="renting_period" value="<?= $booking->renting_period?>" class="form-input" readonly>

                <label class="form-label">Rental (LKR)</label>
                <input type="email" name="rental" value="<?= $booking->price?>" class="form-input" readonly>

                <label class="form-label">Payment Status</label>
                <input type="email" name="Paymentstatus" value="<?= $booking->payment_status?>" class="form-input" readonly>

                <div class="action-buttons">
                    <button type="submit" name="action" value="accept" class="btn-accept">Accept</button>
                    <button type="submit" name="action" value="reject" class="btn-reject">Reject</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
</form>


<?php require_once 'agentFooter.view.php'; ?>