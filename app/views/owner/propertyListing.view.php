<?php require_once 'ownerHeader.view.php'; ?>
<?php !empty($_SESSION['status']) ? $status = $_SESSION['status'] : "" ?>

<div class="user_view-menu-bar">
    <div class="gap"></div>
    <h2> my properties</h2>
    <div class="gap"></div>
    <div class="PL__filter_container">
        <select id="propertyStatusFilter" class="input_field_styled">
            <option value="">Status</option>
            <option value="pending">Pending</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="under Maintenance">Under Maintenance</option>
        </select>
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
                <div class="property-card" data-status="<?= strtolower($property->status) ?>">

                    <div class="property-image">
                        <a href="<?= ROOT ?>/dashboard/propertylisting/propertyunitowner/<?= $property->property_id ?>">
                            <?php $img_source = explode(',', $property->property_images)[0] ?? ''; ?>
                            <img src="<?= get_img($img_source, 'property') ?>" alt="Property Image">
                        </a>
                    </div>
                    <div class="property-details">
                        <div class="profile-details-items">
                            <div>
                                <h3><?= $property->name ?></h3>
                                <div class="property-info">
                                    <span><img src="<?= ROOT ?>/assets/images/building-plan.png" class="property-info-img" /><?= $property->units ?> Unit</span>
                                    <span><img src="<?= ROOT ?>/assets/images/double-bed.png" class="property-info-img" /><?= $property->bedrooms ?> Rooms</span>
                                </div>
                            </div>
                            <div>

                                <div class="property-status">
                                    <?php
                                    $color = 'orange';
                                    switch ($property->status):
                                        case 'Pending':
                                            $color = 'orange';
                                            break;
                                        case 'Active':
                                            $color = 'green';
                                            break;
                                        case 'Inactive':
                                            $color = 'red';
                                            break;
                                        case 'Under Maintenance':
                                            $color = 'blue';
                                            break;
                                    endswitch;
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

    <!-- Pagination Buttons -->
    <div class="pagination">
        <!-- <button class="prev-page"><img src="<?= ROOT ?>/assets/images/left-arrow.png" alt="Previous"></button>
        <span class="current-page">1</span>
        <button class="next-page"><img src="<?= ROOT ?>/assets/images/right-arrow.png" alt="Next"></button> -->
    </div>
</div>

<script>
    let currentPage = 1;
    const listingsPerPage = 9;
    const listings = document.querySelectorAll('.property-listing-grid .property-card');
    const totalPages = Math.ceil(listings.length / listingsPerPage);

    function showPage(page) {
        const visibleListings = Array.from(listings).filter(el => el.style.display !== 'none');
        visibleListings.forEach(el => el.style.display = 'none');

        const start = (page - 1) * listingsPerPage;
        const end = start + listingsPerPage;

        for (let i = start; i < end && i < visibleListings.length; i++) {
            visibleListings[i].style.display = 'block';
        }

        document.querySelector('.current-page').textContent = page;
    }

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

    document.getElementById('propertyStatusFilter').addEventListener('change', filterListings);

    document.querySelector('.next-page').addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            showPage(currentPage);
        }
    });

    document.querySelector('.prev-page').addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            showPage(currentPage);
        }
    });

    // Initial load
    filterListings();
</script>



<?php require_once 'ownerFooter.view.php'; ?>