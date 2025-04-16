<?php require 'customerHeader.view.php' ?>

<div class="user_view-menu-bar">
    <h2>Book Property : </h2>
    <h2 style="color: var(--green-color);"><?= $property->name ?></h2>
</div>

<div class="RP__container">
    <div class="RP_side-containers">
        <img src="<?= ROOT ?>/assets/images/bookProperty.jpg" alt="Report about property">
    </div>

    <div class="RP_side-containers">
        <div class="BP__admin-contacts">
            <div class="BP_container_of_content">
                <div class="BP__image_container">
                    <img src="<?= get_img($agent->image_url) ?>" alt="" class="BP__image_container_img">
                </div>
                <div class="BP_details_section" style="align-items: flex-end;">
                    <div class="BP__data-label">Agent</div>
                    <div class="BP__data-value"><?= $agent->fname ?> <?= $agent->lname ?></div>
                    <div class="BP__data">
                        <img src="<?= ROOT ?>/assets/images/telephone.png" alt="call" class="BP__data_img">
                        <div class="BP__data-value"><?= $agent->contact ?></div>
                    </div>
                </div>
            </div>

            <div class="BP_container_of_content">
                <div class="BP_details_section" style="align-items: flex-start;">
                    <div class="BP__data-label">Owner</div>
                    <div class="BP__data-value"><?= $owner->fname ?> <?= $owner->lname ?></div>
                    <div class="BP__data">
                        <img src="<?= ROOT ?>/assets/images/telephone.png" alt="call" class="BP__data_img">
                        <div class="BP__data-value"><?= $owner->contact ?></div>
                    </div>
                </div>
                <div class="BP__image_container">
                    <img src="<?= get_img($owner->image_url) ?>" alt="" class="BP__image_container_img">
                </div>
            </div>
        </div>
    </div>
</div>
        <form method="POST" action="<?= ROOT ?>/Booking/update" enctype="multipart/form-data">
       <!-- <?php foreach ($images as $image): ?>-->
            <!--<?php if ($booking->property_id == $image->property_id): ?>-->
                <div class="owner-addProp-container">
                    <div class="owner-addProp-form-left">

                        <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $image->image_url ?>" alt="Back" class="bookingimg">

                        <input type="hidden" name="booking_id" value="<?= $booking->booking_id ?>">

                        <input type="hidden" name="accepteddate" value="<?php echo date('Y-m-d'); ?>">

                        <label class="input-label">Property Name</label>
                        <input type="text" name="propertyname" value="<?= $booking->name ?>" class="input-field" readonly>

                        <label class="input-label">Province</label>
                        <input type="text" name="province" value="<?= $booking->state_province?>" class="input-field" readonly>

                        <label class="input-label">Country</label>
                        <input type="text" name="country" value="<?= $booking->country?>" class="input-field" readonly>

                        <label class="input-label">City</label>
                        <input type="text" name="city" value="<?= $booking->city?>" class="input-field" readonly>

                        <label class="input-label">Address</label>
                        <input type="text" name="address" value="<?= $booking->address?>" class="input-field" readonly>

                    </div>
                    <div class="owner-addProp-form-right">

                        <label class="input-label">Customer Name</label>
                        <input type="text" name="customername" value="<?= $booking->fname?> <?= $booking->lname?>" class="input-field" readonly>

                        <label class="input-label">Customer ID</label>
                        <input type="text" name="customerId" value="<?= $booking->pid?>" class="input-field" readonly>

                        <label class="input-label">Email</label>
                        <input type="email" name="email" value="<?= $booking->email?>" class="input-field" readonly>

                        <label class="input-label">Mobile Number</label>
                        <input type="email" name="mobilenumber" value="<?= $booking->contact?>" class="input-field" readonly>

                        <label class="input-label">Booked Date</label>
                        <input type="email" name="bookedDate" value="<?= $booking->booked_date?>" class="input-field" readonly>

                        <label class="input-label">Lease Starting Date</label>
                        <input type="email" name="startingDate" value="<?= $booking->start_date?>" class="input-field" readonly>

                        <label class="input-label">Renting Period (Months)</label>
                        <input type="email" name="renting_period" value="<?= $booking->renting_period?>" class="input-field" readonly>

                        <label class="input-label">Rental (LKR)</label>
                        <input type="email" name="rental" value="<?= $booking->price?>" class="input-field" readonly>

                        <label class="input-label">Payment Status</label>
                        <input type="email" name="Paymentstatus" value="<?= $booking->payment_status?>" class="input-field" readonly>

                        <div class="buttons-to-right">
                            <button type="submit" name="action" value="accept" class="primary-btn accept-btn1" id="acceptButton">Accept</button>
                            <button type="submit" name="action" value="reject" class="secondary-btn reject-btn1" id="rejectButton">Reject</button>
                        </div>
                    </div>
                </div>
            <!--<?php endif; ?>-->
        <!--<?php endforeach; ?>-->
        </form>


<script src="<?= ROOT ?>/assets/js/customer/booking.js"></script>

<?php require 'customerFooter.view.php' ?>
