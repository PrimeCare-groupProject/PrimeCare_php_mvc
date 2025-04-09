<?php require_once 'ownerHeader.view.php'; ?>

<div class="user_view-menu-bar">
    <a href='<?= ROOT ?>/dashboard/propertylisting/propertyunitowner/<?= $property->property_id ?>'>
        <img src="<?= ROOT ?>/assets/images/backButton.png" alt="< back" class="navigate-icons">
    </a>
    <h2>Reviews on : <span style="color: var(--green-color)"><?= $property->name ?></span></h2>
</div>
<?php $images = explode(',', $property->property_images) ?>
<div class="RS__container">
    <div class="RS__image_section">
        <img src="<?= ROOT ?>/assets/images/uploads/property_images/<?= $images[0] ?>" alt="Property Image" class="RS__property_image">
        <div class="RS__review_stat">
            <h2>4.5</h2>
            <p>Good Ranking</p>
        </div>
    </div>
    <div class="RS__review_showing">

        <!-- <div class="RS__review_card">

        </div> -->
        <div class="RS__review-card">
            <div class="RS__stars">
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
                <i class="fa-solid fa-star"></i>
            </div>
            <p class="RS__review-text">
                "Their team took our wellness brand and elevated it to new heights with their thoughtful designs and strategic branding, they've helped us create a cohesive and compelling brand identity."
            </p>
            <div class="RS__reviewer">
                <div class="RS__reviewer-img">
                    <img src="/api/placeholder/50/50" alt="Mark Ramirez">
                </div>
                <div class="RS__reviewer-info">
                    <div class="RS__reviewer-name">Mark Ramirez</div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once 'ownerFooter.view.php'; ?>