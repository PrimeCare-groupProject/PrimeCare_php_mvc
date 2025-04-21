<?php require_once 'ownerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2> my properties</h2>
    <div class="gap"></div>
    <div class="PL__filter_container">
        <div class="search-container">
            <select id="propertyStatusFilter" class="search-input">
                <option value="">Status</option>
                <option value="pending">Pending</option>
                <option value="active">Active</option>
                <!-- <option value="inactive">Inactive</option> -->
                <option value="under Maintenance">Under Maintenance</option>
                <option value="Occupied">Occupied</option>
            </select>
        </div>
    </div>
    <div class="gap"></div>
    <div class="PL__filter_container">
        <div class="search-container">
            <select name="propertyPurposeFilter" id="propertyPurposeFilter" class="search-input">
                <option value="">Purpose</option>
                <option value="Rent">Rent</option>
                <option value="Safeguard">Safeguard</option>
            </select>
        </div>
    </div>
    <div class="flex-bar">
        <div class="tooltip-container">
            <a href='<?= ROOT ?>/dashboard/propertyListing/addproperty'><button class="add-btn"><img src="<?= ROOT ?>/assets/images/plus.png" alt="Add" class="navigate-icons"></button></a>
            <span class="tooltip-text">Add new property</span>
        </div>
    </div>
</div>


<!-- <div class="errors" style="display: <?= !empty($status) ? 'block' : 'none'; ?>; background-color: #b5f9a2;">
    <?php if (!empty($status)): ?>
        <p><?= $status;  ?></p>
    <?php endif; ?>
    <?php $_SESSION['status'] = '' ?>
</div>
 -->



<div class="listing-the-property">
    <!-- Property Listings -->
    <div class="property-listing-grid">
        <?php if (!empty($properties)): ?>
            <?php foreach ($properties as $property): ?>
                <div class="property-card" data-status="<?= strtolower($property->status) ?>" data-purpose="<?= strtolower($property->purpose) ?>">

                    <?php
                    $images = explode(',', $property->property_images);
                    $firstImage = !empty($images) ? $images[0] : 'default.png'; // Fallback to a default image if none exist
                    ?>

                    <div class="property-image" style="position: relative;">
                        <div style="position: absolute; top: 10px; right: 10px; z-index: 0; padding: 5px 10px; background-color: rgba(255,255,255,0.8); color: var(--black-color); font-size: 16px; border-radius: 0 15px 0 15px; font-weight: bold; font-family: Outfit, sans-serif;">
                            <?php
                                if($property->purpose == 'Rent') {
                                    echo '<span class="rent-tag">For Rent</span>';
                                } elseif ($property->purpose == 'Safeguard') {
                                    echo '<span class="safeguard-tag">Safeguard</span>';
                                }
                            ?>
                        </div>
                        <a href="<?= ROOT ?>/dashboard/propertyListing/propertyunitowner/<?= $property->property_id ?>"><img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $firstImage ?>" alt="Property Image"></a>
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <h3><?= $property->name ?></h3>
                                <!-- <div class="property-info">
                                    <span><img src="<?= ROOT ?>/assets/images/building-plan.png" class="property-info-img" /><?= $property->units ?> Unit</span>
                                    <span><img src="<?= ROOT ?>/assets/images/double-bed.png" class="property-info-img" /><?= $property->bedrooms ?> Rooms</span>
                                </div> -->
                            </div>
                            <div style="margin-top: 10px;">
                                <div class="property-status">
                                    <?php
                                    $color = 'orange';
                                    switch ($property->status):
                                        case 'Pending':
                                            $color = 'orange';
                                            break;
                                        case 'Under Maintenance':
                                            $color = 'blue';
                                            break;
                                    endswitch;

                                    if ($property->status == 'Active' && $property->purpose == 'Rent') {
                                        $color = 'green';
                                        $showMessage = 'Open for Rent';
                                    } elseif ($property->status == 'Active' && $property->purpose != 'Rent') {
                                        $color = 'green';
                                        $showMessage = 'Safety Confirmed';
                                    } elseif ($property->status == 'Occupied') {
                                        $color = 'blue-solid';
                                        $showMessage = 'Occupied';
                                    } else {
                                        $showMessage = $property->status;
                                    }

                                    ?>
                                    <span class="border-button <?= $color ?>"><?= $property->status ?></span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="property-description"><img src="<?= ROOT ?>/assets/images/location.png" class="property-info-img" /><?= $property->address ?></p>
                        </div>
                        <div>
                            <p class="property-description">
                                <?= $property->description ?>
                            </p>
                        </div>
                        <!-- <div class="property-actions">
                            <a href="#" class="change-status">change Pending</a>
                            <div>
                                <a href="<?= ROOT ?>/dashboard/propertylisting/updateproperty/<?= $property->property_id ?>" class="delete-btn"><img src="<?= ROOT ?>/assets/images/edit.png" class="property-info-img" /></a>
                                <a href="<?= ROOT ?>/dashboard/propertylisting/dropProperty/<?= $property->property_id ?>" class="edit-btn"><img src="<?= ROOT ?>/assets/images/delete.png" class="property-info-img" /></a>
                            </div>
                        </div> -->
                    </div>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p>No properties found.</p>
        <?php endif; ?>
    </div>

</div>

<script>
    const listings = document.querySelectorAll('.property-listing-grid .property-card');

    function filterListings() {
        const selectedStatus = document.getElementById('propertyStatusFilter').value.toLowerCase();

        listings.forEach(card => {
            const status = card.getAttribute('data-status');
            if (!selectedStatus || status === selectedStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        currentPage = 1;
        showPage(currentPage);
    }

    function filterListingsByPurpose() {
        const selectedPurpose = document.getElementById('propertyPurposeFilter').value.toLowerCase();

        listings.forEach(card => {
            const purpose = card.getAttribute('data-purpose');
            if (!selectedPurpose || purpose === selectedPurpose) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });

        currentPage = 1;
        showPage(currentPage);
    }
    document.getElementById('propertyPurposeFilter').addEventListener('change', filterListingsByPurpose);

    document.getElementById('propertyStatusFilter').addEventListener('change', filterListings);


    // Initial load
    filterListings();
    filterListingsByPurpose();
</script>



<?php require_once 'ownerFooter.view.php'; ?>