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
        
        <form action="" method="post">
            <div>
                <label for="start_date">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="input-field" required>
            </div>
            <div>
                <label for="renting_period">Renting Period</label>
                <input type="text" name="renting_period" id="renting_period" class="input-field" required>
            </div>
            <div class="to-flex-end">
                <button type="submit" class="primary-btn" style="align-self: flex-end;">Confirm Booking</button>
            </div>
        </form>
    </div>

</div>
</div>
<script src="<?= ROOT ?>/assets/js/customer/booking.js"></script>

<?php require 'customerFooter.view.php' ?>